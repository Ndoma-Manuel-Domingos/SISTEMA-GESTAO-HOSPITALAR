<?php

namespace App\Http\Controllers;

use App\Models\BackupSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class DatabaseController extends Controller
{
    private $username;
    private $password;
    private $host;
    private $port;

    public function __construct()
    {
        $this->username = env('DB_USERNAME');
        $this->password = env('DB_PASSWORD');
        $this->host     = env('DB_HOST');
        $this->port     = env('DB_PORT', 3306);
    }

    // Listar bancos
    public function index()
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '1024M'); // ou mais se necessário

        $databases = DB::select("SHOW DATABASES WHERE `Database` NOT IN (
            'information_schema',
            'performance_schema',
            'mysql',
            'sys',
            'myapp2'
        )");

        $currentDb = env('DB_DATABASE');

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $head = [
            "titulo" => "Backup e Restauração do Banco",
            "descricao" => env('APP_NAME'),
            "empresa" => $entidade,
            "databases" => $databases,
            "currentDb" => $currentDb,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('databases.index', $head);
    }

    // Criar banco
    public function create(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '1024M'); // ou mais se necessário

        $request->validate([
            'name' => 'required|string'
        ]);

        DB::statement("CREATE DATABASE {$request->name}");
        return back()->with('success', "Banco {$request->name} criado!");
    }

    public function export(Request $request)
    {
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        ini_set('memory_limit', '2024M');

        $backupPath = storage_path("app/backups/{$request->name}_" . date('Y-m-d_H-i-s') . ".sql");

        // Garante que a pasta existe
        if (!is_dir(dirname($backupPath))) {
            mkdir(dirname($backupPath), 0777, true);
        }

        // Detecta o caminho do mysqldump automaticamente
        $mysqlDumpPath = trim(shell_exec('where mysqldump'));

        if (!$mysqlDumpPath || !file_exists($mysqlDumpPath)) {
            return back()->with('error', 'mysqldump não encontrado. Verifique se o MySQL está instalado e adicionado ao PATH.');
        }

        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST', '127.0.0.1');
        $port = env('DB_PORT', 3306);
        $database = $request->name;

        // Comando de exportação
        $command = "\"{$mysqlDumpPath}\" --user={$username} --password={$password} --host={$host} --port={$port} {$database} > \"{$backupPath}\"";

        exec($command, $output, $resultCode);

        if ($resultCode !== 0) {
            return back()->with('error', "Erro ao exportar banco de dados. Código de erro: {$resultCode}");
        }

        return response()->download($backupPath);
    }

    public function import(Request $request)
    {
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        ini_set('memory_limit', '2024M');

        $request->validate([
            'name' => 'required|string',
            'sql_file' => 'required|file|mimes:sql,txt'
        ]);

        $file = $request->file('sql_file');

        // Caminho de upload temporário
        $uploadPath = storage_path('app/sql_uploads');
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $filePath = $uploadPath . '/' . $file->getClientOriginalName();
        $file->move($uploadPath, $file->getClientOriginalName());

        // Detecta o caminho do mysql automaticamente
        $mysqlPath = trim(shell_exec('where mysql'));

        if (!$mysqlPath || !file_exists($mysqlPath)) {
            return back()->with('error', 'mysql não encontrado. Verifique se o MySQL está instalado e adicionado ao PATH.');
        }

        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST', '127.0.0.1');
        $port = env('DB_PORT', 3306);
        $database = $request->name;

        // Comando de importação
        $command = "\"{$mysqlPath}\" --user={$username} --password={$password} --host={$host} --port={$port} {$database} < \"{$filePath}\"";

        exec($command, $output, $resultCode);

        // Remove o arquivo após a importação
        unlink($filePath);
        rmdir($uploadPath);

        if ($resultCode !== 0) {
            return back()->with('error', "Erro ao importar banco de dados. Código de erro: {$resultCode}");
        }

        return back()->with('success', "Banco de dados '{$database}' importado com sucesso!");
    }

    // Ativar banco (alterar DB_DATABASE no .env)
    public function activate(Request $request)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '2024M'); // ou mais se necessário

        $request->validate([
            'name' => 'required|string',
            'password' => 'required',
        ]);

        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()->with('danger', 'Senha incorreta!');
        }

        $envPath = base_path('.env');
        $content = file_get_contents($envPath);
        $content = preg_replace("/DB_DATABASE=.*/", "DB_DATABASE={$request->name}", $content);
        file_put_contents($envPath, $content);

        return back()->with('success', "Banco {$request->name} ativado!");
    }

    // Deletar banco
    public function delete(Request $request, $name)
    {
        // Aumenta o tempo de execução
        ini_set('max_execution_time', 0); // 0 = infinito
        set_time_limit(0);

        // Aumenta o limite de memória
        ini_set('memory_limit', '2024M'); // ou mais se necessário

        $request->validate([
            'password' => 'required',
        ]);

        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()->with('danger', 'Senha incorreta!');
        }

        DB::statement("DROP DATABASE {$name}");
        return back()->with('success', "Banco {$name} deletado!");
    }

    // restrutar banco
    public function restrutar(Request $request)
    {
        if (!Schema::hasTable('backup_settings')) {
            Schema::create('backup_settings', function (Blueprint $table) {
                $table->id();
                $table->string('folder_path')->nullable(); // caminho absoluto onde salvar
                $table->boolean('enabled')->default(true);
                $table->integer('retain')->default(24); // manter 24 backups
                $table->integer('frequency_minutes')->default(60); // frequência em minutos
                $table->timestamp('last_run_at')->nullable();
                $table->integer('user_id');
                $table->integer('entidade_id');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasColumn("backup_settings", "tipo_mysql")) {
            Schema::table("backup_settings", function (Blueprint $table) {
                $table->enum("tipo_mysql", ["padrao", "definido", "outro"])->default("padrao");
            });
        }

        // if (!Schema::hasTable('clientes_contratos')) {
        //     Schema::create('clientes_contratos', function (Blueprint $table) {
        //         $table->id();
        //         $table->integer('cliente_id')->nullable();
        //         $table->string('codigo_contrato')->nullable();
        //         $table->text('descricao')->nullable();
        //         $table->date('data_inicio')->nullable();
        //         $table->date('data_final')->nullable();
        //         $table->double('valor_mensal', 2)->nullable();
        //         $table->integer('forma_pagamento_id')->nullable();
        //         $table->integer('user_id')->nullable();
        //         $table->integer('entidade_id')->nullable();
        //         $table->timestamps();
        //         $table->softDeletes();
        //     });
        // }

        // if (!Schema::hasColumn("contratos_postos", "representante_posto") && !Schema::hasColumn("contratos_postos", "contacto_posto") && !Schema::hasColumn("contratos_postos", "uso_armas")) {
        //     Schema::table("contratos_postos", function (Blueprint $table) {
        //         $table->string("representante_posto")->nullable();
        //         $table->string("contacto_posto")->nullable();
        //         $table->enum("uso_armas", ['Y', 'N'])->nullable('N');
        //     });
        // }

        if (Schema::hasColumn('equipamentos_activos', 'conta_id')) {
            DB::statement("ALTER TABLE equipamentos_activos CHANGE conta_id subconta_id BIGINT NULL");
        }

        DB::statement("ALTER TABLE produtos MODIFY preco_custo DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE produtos MODIFY preco_venda DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE produtos MODIFY preco DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE produtos MODIFY preco_venda_com_iva DECIMAL(20,4) NULL");


        // VENDAS
        DB::statement("ALTER TABLE vendas MODIFY valor_total DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE vendas MODIFY lucro_total DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE vendas MODIFY lucro_iva_total DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE vendas MODIFY valor_divida DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE vendas MODIFY valor_pago DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE vendas MODIFY valor_troco DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE vendas MODIFY desconto DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE vendas MODIFY desconto_percentagem DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE vendas MODIFY total_iva DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE vendas MODIFY valor_cash DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE vendas MODIFY valor_multicaixa DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE vendas MODIFY total_incidencia DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE vendas MODIFY total_retencao_fonte DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE vendas MODIFY custo_total DECIMAL(20,4) NULL");


        // VENDAS ITEMS
        DB::statement("ALTER TABLE itens_vendas MODIFY valor_pagar DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE itens_vendas MODIFY valor_base DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE itens_vendas MODIFY preco_unitario DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE itens_vendas MODIFY custo DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE itens_vendas MODIFY lucro DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE itens_vendas MODIFY lucro_iva DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE itens_vendas MODIFY total DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE itens_vendas MODIFY desconto_aplicado DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE itens_vendas MODIFY desconto_aplicado_valor DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE itens_vendas MODIFY retencao_fonte DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE itens_vendas MODIFY valor_iva DECIMAL(20,4) NULL");


        DB::statement("ALTER TABLE estoques MODIFY stock DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE registros MODIFY quantidade DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE itens_vendas MODIFY quantidade DECIMAL(20,4) NULL");
        DB::statement("ALTER TABLE itens_vendas MODIFY quantidade_devolvida DECIMAL(20,4) NULL");



        if (!Schema::hasColumn("descontos", "irt") && !Schema::hasColumn("descontos", "inss") && !Schema::hasColumn("descontos", "tipo_valor") && !Schema::hasColumn("descontos", "desconto")) {
            Schema::table("descontos", function (Blueprint $table) {
                $table->enum("irt", ["Y", "N"])->nullable();
                $table->enum("inss", ["Y", "N"])->nullable();
                $table->enum("tipo_valor", ["Y", "N"])->nullable();
                $table->double("desconto", 20, 4)->nullable();
            });
        }

        if (!Schema::hasColumn("subsidios", "irt") && !Schema::hasColumn("subsidios", "inss") && !Schema::hasColumn("subsidios", "limite_isencao")) {
            Schema::table("subsidios", function (Blueprint $table) {
                $table->enum("irt", ["Y", "N"])->nullable();
                $table->enum("inss", ["Y", "N"])->nullable();
                $table->double("limite_isencao", 20, 4)->nullable();
            });
        }

        if (!Schema::hasColumn("periodos_rendimentos", "sigla")) {
            Schema::table("periodos_rendimentos", function (Blueprint $table) {
                $table->string("sigla")->nullable();
            });
        }

        if (!Schema::hasColumn("registros_movimentos", "sigla")) {
            Schema::table("registros_movimentos", function (Blueprint $table) {
                $table->string("sigla")->nullable();
            });
        }

        if (!Schema::hasColumn("tipos_rendimentos", "sigla")) {
            Schema::table("tipos_rendimentos", function (Blueprint $table) {
                $table->string("sigla")->nullable();
            });
        }

        if (!Schema::hasColumn("departamentos", "sigla")) {
            Schema::table("departamentos", function (Blueprint $table) {
                $table->string("sigla")->nullable();
            });
        }

        if (!Schema::hasColumn("categorias_cargos", "sigla")) {
            Schema::table("categorias_cargos", function (Blueprint $table) {
                $table->string("sigla")->nullable();
            });
        }

        if (!Schema::hasColumn("cargos", "sigla")) {
            Schema::table("cargos", function (Blueprint $table) {
                $table->string("sigla")->nullable();
            });
        }

        if (!Schema::hasColumn("equipas", "area_atuacao")) {
            Schema::table("equipas", function (Blueprint $table) {
                $table->string("area_atuacao")->nullable();
            });
        }

        if (!Schema::hasColumn("equipas", "responsavel_id")) {
            Schema::table("equipas", function (Blueprint $table) {
                $table->string("responsavel_id")->nullable();
            });
        }

        if (!Schema::hasColumn("horarios_funcionarios", "posto_id")) {
            Schema::table("horarios_funcionarios", function (Blueprint $table) {
                $table->string("posto_id")->nullable();
            });
        }

        if (!Schema::hasColumn("tipos-processamentos", "numero")) {
            Schema::table("tipos-processamentos", function (Blueprint $table) {
                $table->string("numero")->nullable();
            });
        }

        if (!Schema::hasColumn("clientes", "responsavel_nome") && !Schema::hasColumn("clientes", "responsavel_contacto")) {
            Schema::table("clientes", function (Blueprint $table) {
                $table->integer("responsavel_nome")->nullable();
                $table->integer("responsavel_contacto")->nullable();
            });
        }

        if (!Schema::hasColumn("entidades", "numero_via_documento")) {
            Schema::table("entidades", function (Blueprint $table) {
                $table->integer("numero_via_documento")->nullable()->default(1);
            });
        }

        if (!Schema::hasColumn("registros_movimentos", "fornecedor_id")) {
            Schema::table("registros_movimentos", function (Blueprint $table) {
                $table->string("fornecedor_id")->nullable();
            });
        }
        if (!Schema::hasColumn("registros_movimentos", "total")) {
            Schema::table("registros_movimentos", function (Blueprint $table) {
                $table->decimal("total", 20, 2)->nullable();
            });
        }
        if (!Schema::hasColumn("registros_movimentos_item", "preco_custo")) {
            Schema::table("registros_movimentos_item", function (Blueprint $table) {
                $table->decimal("preco_custo", 20, 2)->nullable();
            });
        }
        if (!Schema::hasColumn("registros_movimentos_item", "preco_venda")) {
            Schema::table("registros_movimentos_item", function (Blueprint $table) {
                $table->decimal("preco_venda", 20, 2)->nullable();
            });
        }
        if (!Schema::hasColumn("registros_movimentos", "fornecedor_id")) {
            Schema::table("registros_movimentos", function (Blueprint $table) {
                $table->string("fornecedor_id")->nullable();
            });
        }
        if (!Schema::hasColumn("registros_movimentos", "cliente_id")) {
            Schema::table("registros_movimentos", function (Blueprint $table) {
                $table->string("cliente_id")->nullable();
            });
        }
        if (!Schema::hasColumn("registros", "documento")) {
            Schema::table("registros", function (Blueprint $table) {
                $table->string("documento")->nullable();
            });
        }
        if (!Schema::hasColumn("equipamentos_activos", "classificacao_id")) {
            Schema::table("equipamentos_activos", function (Blueprint $table) {
                $table->string("classificacao_id")->nullable();
            });
        }
        if (!Schema::hasColumn("registros_movimentos", "tipo_documento")) {
            Schema::table("registros_movimentos", function (Blueprint $table) {
                $table->enum("tipo_documento", ['CN', 'CF', 'IO', 'IP', 'D1', 'L1', 'L4',  'IN'])->nullable();
            });
        }
        if (!Schema::hasColumn("entidades", "valor_taxa_retencao_fonte")) {
            Schema::table("entidades", function (Blueprint $table) {
                $table->string("valor_taxa_retencao_fonte")->nullable();
            });
        }
        if (!Schema::hasColumn("funcionarios", "foto") && !Schema::hasColumn("funcionarios", "qr_code")) {
            Schema::table("funcionarios", function (Blueprint $table) {
                $table->string("foto")->nullable();
                $table->string("qr_code")->nullable();
            });
        }
        DB::statement("ALTER TABLE registros_movimentos MODIFY COLUMN tipo ENUM('CN', 'E','S','CF','SP', 'IO', 'IP', 'D1', 'L1', 'L4',  'IN') NOT NULL DEFAULT 'E'");

        return redirect()->back();
    }
}
