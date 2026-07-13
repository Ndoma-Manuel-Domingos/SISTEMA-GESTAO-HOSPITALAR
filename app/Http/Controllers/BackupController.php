<?php

namespace App\Http\Controllers;

use App\Models\BackupSetting;
use App\Models\Devolucao;
use App\Models\EncomendaFornecedore;
use App\Models\Estoque;
use App\Models\FacturaEncomendaFornecedor;
use App\Models\FacturaEncomendaFornecedorPagamento;
use App\Models\FacturaOriginal;
use App\Models\ItemDevolucao;
use App\Models\ItemFacturaOriginal;
use App\Models\ItemNotaCredito;
use App\Models\ItemRecibo;
use App\Models\ItemVenda;
use App\Models\ItensEncomenda;
use App\Models\LojaProduto;
use App\Models\Lote;
use App\Models\Movimento;
use App\Models\NotaCredito;
use App\Models\OperacaoFinanceiro;
use App\Models\Produto;
use App\Models\Recibo;
use App\Models\Registro;
use App\Models\RegistroMovimento;
use App\Models\RegistroMovimentoItem;
use App\Models\User;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Jobs\ExportDatabaseJob; // job que vamos criar
use App\Models\ItemReserva;
use App\Models\ItemReservaMesa;
use App\Models\Producao;
use App\Models\ProducaoItem;
use App\Models\ProdutoReceita;
use App\Models\ProdutoReceitaItem;
use App\Models\Reserva;
use App\Models\ReservaMesa;

class BackupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $head = [
            "titulo" => "Backup e Restauração do Banco",
            "descricao" => env('APP_NAME'),
            "dbName" => env('DB_DATABASE'),
            "empresa" => $entidade,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.backups.index', $head);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function configuracao()
    {
        // $head = [
        //     "titulo" => "Configuração do Sistema",
        //     "descricao" => env('APP_NAME'),
        //     "dbName" => env('DB_DATABASE'),
        //     "dbUsername" => env('DB_USERNAME'),
        //     "dbHost" => env('DB_HOST'),
        //     "dbPort" => env('DB_PORT'),
        //     "portaApache" => env('PORT_APACHE'),
        // ];

        // return view('dashboard.config', $head);

        $laragonPath = 'C:\laragon';

        // Lê porta do Apache
        $httpdConf = $laragonPath . '\etc\apache2\httpd.conf';

        $apacheConfig = file_get_contents($httpdConf);

        preg_match('/Listen\s+(\d+)/', $apacheConfig, $apacheMatch);
        $apachePort = $apacheMatch[1] ?? 80;

        // Lê porta do MySQL do laragon.ini
        $laragonIni = $laragonPath . '\usr\laragon.ini';
        $laragonConfig = file_get_contents($laragonIni);
        preg_match('/Port=(\d+)/', $laragonConfig, $mysqlMatch);
        $mysqlPort = $mysqlMatch[1] ?? 3306;

        return view('laragon.portas', [
            'apache' => $apachePort,
            'mysql' => $mysqlPort,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function configuracao_store(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '1024M'); // ou mais se necessário

        $request->validate([
            'apache_port' => 'required|numeric|min:80|max:65535',
            'mysql_port' => 'required|numeric|min:3306|max:65535',
        ]);


        $novaPortaApache = $request->apache_port;
        $novaPortaMysql = $request->mysql_port;

        $laragonPath = 'C:\laragon';
        $httpdConf = $laragonPath . '\etc\apache2\httpd.conf';
        $myIni = glob($laragonPath . '\bin\mysql\mysql-*\\my.ini')[0];
        $laragonIni = $laragonPath . '\usr\laragon.ini';

        // 1️⃣ Atualiza Apache (httpd.conf)
        $apacheConfig = file_get_contents($httpdConf);
        $apacheConfig = preg_replace('/Listen\s+\d+/', "Listen {$novaPortaApache}", $apacheConfig);
        $apacheConfig = preg_replace('/ServerName\s+localhost:\d+/', "ServerName localhost:{$novaPortaApache}", $apacheConfig);
        file_put_contents($httpdConf, $apacheConfig);


        // 2️⃣ Atualiza MySQL (my.ini)
        $mysqlConfig = file_get_contents($myIni);
        $mysqlConfig = preg_replace('/port=\d+/', "port={$novaPortaMysql}", $mysqlConfig);
        file_put_contents($myIni, $mysqlConfig);


        // 3️⃣ Atualiza laragon.ini (secção [mysql])
        $laragonConfig = file_get_contents($laragonIni);
        if (preg_match('/\[mysql\](.*?)\[/', $laragonConfig, $matches)) {
            // Substitui a porta apenas dentro da seção [mysql]
            $newSection = preg_replace('/Port=\d+/', "Port={$novaPortaMysql}", $matches[1]);
            $laragonConfig = str_replace($matches[1], $newSection, $laragonConfig);
        } else {
            // Se não existir, adiciona
            $laragonConfig .= "\n[mysql]\nPort={$novaPortaMysql}\n";
        }
        file_put_contents($laragonIni, $laragonConfig);

        //
        $envPath = base_path('.env');
        $content = file_get_contents($envPath);
        $content = preg_replace("/DB_PORT=.*/", "DB_PORT={$request->mysql_port}", $content);
        file_put_contents($envPath, $content);


        // 4️⃣ Reinicia Laragon
        exec('C:\laragon\laragon.exe reload');


        return response()->json(['success' => true, 'redirect' => route('login')]);
    }

    public function limparBancos(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // valida a senha do usuário
        if (!Hash::check($request->password, ENV('SEGURATIONS'))) {
            return response()->json(['ok' => false, 'message' => 'Senha incorreta'], 403);
        }

        try {
            // ⚠️ aqui tu defines a lógica de restauração
            // Exemplo: rodar migrations do zero

            Movimento::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            OperacaoFinanceiro::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            Venda::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            ItemVenda::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            Recibo::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            ItemRecibo::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            NotaCredito::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            ItemNotaCredito::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            Registro::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            RegistroMovimento::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            RegistroMovimentoItem::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            FacturaOriginal::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            ItemFacturaOriginal::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            EncomendaFornecedore::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            ItensEncomenda::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            FacturaEncomendaFornecedor::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            FacturaEncomendaFornecedorPagamento::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            Devolucao::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            ItemDevolucao::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            ProdutoReceitaItem::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            ProdutoReceita::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            Producao::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            ProducaoItem::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            Reserva::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            ItemReserva::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            ReservaMesa::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            ItemReservaMesa::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();

            $estoques = Estoque::where('entidade_id', $entidade->empresa->id)->get();

            foreach ($estoques as $item) {
                $item->stock = 0;
                $item->stock_minimo = 0;
                $item->stock_alerta = 0;
                $item->save();
            }

            return response()->json(['ok' => true]);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function geralBancos(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // valida a senha do usuário
        if (!Hash::check($request->password, ENV('SEGURATIONS'))) {
            return response()->json(['ok' => false, 'message' => 'Senha incorreta'], 403);
        }

        try {
            // ⚠️ aqui tu defines a lógica de restauração
            // Exemplo: rodar migrations do zero

            Movimento::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            OperacaoFinanceiro::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            Venda::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            ItemVenda::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            Recibo::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            ItemRecibo::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            NotaCredito::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            ItemNotaCredito::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            Registro::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            RegistroMovimento::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            RegistroMovimentoItem::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            FacturaOriginal::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            ItemFacturaOriginal::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            EncomendaFornecedore::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            ItensEncomenda::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            FacturaEncomendaFornecedor::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            FacturaEncomendaFornecedorPagamento::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            Devolucao::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            ItemDevolucao::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();

            Produto::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            LojaProduto::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            Lote::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
            Estoque::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();

            return response()->json(['ok' => true]);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Formulário (AdminLTE) que mostra as configurações
    public function settingsForm()
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $head = [
            "titulo" => "Backup e Restauração do Banco",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $userId = Auth::id();
        $setting = BackupSetting::firstOrCreate(
            ['user_id' => $userId],
            ['entidade_id' => $entidade->empresa->id],
            [
                'folder_path' => storage_path('app/backups'),
                'retain' => 24,
                'frequency_minutes' => 60
            ]
        );

        return view('dashboard.backups.settings', compact('setting'), $head);
    }

    // Salva as configurações do usuário
    public function saveSettings(Request $request)
    {
        $request->validate([
            'folder_path' => 'required|string',
            'retain' => 'nullable|integer|min:1',
            'frequency_minutes' => 'nullable|integer|min:1'
        ]);

        $userId = Auth::id();
        $data = $request->only(['folder_path', 'retain', 'frequency_minutes', 'tipo_mysql']);
        $data['enabled'] = $request->has('enabled') ? true : false;

        $setting = BackupSetting::updateOrCreate(
            ['user_id' => $userId],
            $data
        );

        return redirect()->back()->with('success', 'Configuração salva!');
    }

    // Endpoint AJAX para disparar backup - não executa o dump aqui diretamente;
    // apenas dispara um Job (recomendado) e retorna OK rapidamente.
    public function triggerBackup(Request $request)
    {
        $userId = Auth::id();
        $setting = BackupSetting::where('user_id', $userId)->first();

        if (!$setting || !$setting->enabled) {
            return response()->json(['success' => false, 'message' => 'Backup desabilitado'], 400);
        }

        // opcional: validar que o request->name (db) é permitido
        $dbName = config('database.connections.mysql.database');

        ExportDatabaseJob::dispatch($setting->id, $dbName);

        return response()->json(['success' => true, 'message' => 'Backup agendado']);
    }

    // status
    public function status()
    {
        $userId = Auth::id();
        $setting = BackupSetting::where('user_id', $userId)->first();
        return response()->json($setting);
    }
}
