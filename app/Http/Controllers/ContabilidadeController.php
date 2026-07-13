<?php

namespace App\Http\Controllers;

use App\Exports\InvantarioExport;
use App\Models\Categoria;
use App\Models\Entidade;
use App\Models\ItemVenda;
use App\Models\Marca;
use Illuminate\Support\Facades\DB;
use App\Models\Exercicio;
use App\Models\Periodo;
use App\Models\Movimento;
use App\Models\OperacaoFinanceiro;
use App\Models\Recibo;
use App\Models\Conta;
use App\Models\Loja;
use App\Models\LojaProduto;
use App\Models\Registro;
use App\Models\Subconta;
use App\Models\User;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use RealRashid\SweetAlert\Facades\Alert;

class ContabilidadeController extends Controller
{
    //
    use TraitHelpers;

    public function inventario(Request $request, string $tipo = 'produto')
    {
        ini_set('memory_limit', '1024M');  // Ajuste para 1024 MB ou outro valor
        ini_set('max_execution_time', 300); // 5 minutos

        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('inventario')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'categorias', 'marcas'])->findOrFail($entidade->empresa->id);
        
        $isMateriaPrima = $tipo === 'materias-primas';

        $produtos = LojaProduto::with(['loja'])
        ->with(['produto' => function ($query) use ($request, $isMateriaPrima) {
            $query->when($request->nome_referencia, function ($query, $value) {
                $query->where(function ($sub) use ($value) {
                    $sub->where('nome', 'LIKE', "%{$value}%")
                        ->orWhere('referencia', 'LIKE', "%{$value}%");
                });
            })
            ->when($request->categoria_id, function ($query, $value) {
                $query->where('categoria_id', $value);
            })
            ->when($request->marca_id, function ($query, $value) {
                $query->where('marca_id', $value);
            })
            ->when( $isMateriaPrima,
                fn($q) => $q->where('tipo_stock', 'P'),
                fn($q) => $q->where('tipo_stock', '!=', 'P')
            )
            ->withSum('quantidade', 'quantidade');
        }])
        ->where('entidade_id', $entidade->empresa->id)
        ->when($request->loja_id, function ($query, $value) {
            $query->where('loja_id', $value);
        })
        ->orderByDesc('loja_id') // ou pela ordem que preferir
        ->get();

        $lojas = Loja::where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => $isMateriaPrima ? 'Inventário de Matérias-primas' : 'Inventário de Produtos',
            "isMateriaPrima" => $isMateriaPrima,
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "produtos" => $produtos,
            "lojas" => $lojas,
            "requests" => $request->all('categoria_id', 'marca_id', 'nome_referencia', 'loja_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contabilidade.inventario', $head);
    }

    public function inventarioExportarPdf(Request $request, string $tipo = 'produto')
    {
        ini_set('memory_limit', '1024M');  // Ajuste para 1024 MB ou outro valor
        ini_set('max_execution_time', 300); // 5 minutos

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'categorias', 'marcas'])->findOrFail($entidade->empresa->id);
    
        $isMateriaPrima = $tipo === 'materias-primas';
        
        $produtos = LojaProduto::with(['loja'])->with(['produto' => function ($query) use ($request, $isMateriaPrima) {
            $query->when($request->nome_referencia, function ($query, $value) {
                $query->where(function ($sub) use ($value) {
                    $sub->where('nome', 'LIKE', "%{$value}%")
                        ->orWhere('referencia', 'LIKE', "%{$value}%");
                });
            })
            ->when($request->categoria_id, function ($query, $value) {
                $query->where('categoria_id', $value);
            })
            ->when($request->marca_id, function ($query, $value) {
                $query->where('marca_id', $value);
            })
            ->when( $isMateriaPrima,
                fn($q) => $q->where('tipo_stock', 'P'),
                fn($q) => $q->where('tipo_stock', '!=', 'P')
            )
            ->withSum('quantidade', 'quantidade');
        }])
        ->where('entidade_id', $entidade->empresa->id)
        ->when($request->loja_id, function ($query, $value) {
            $query->where('loja_id', $value);
        })
        ->orderByDesc('loja_id') // ou pela ordem que preferir
        ->get();

        $marca = Marca::find($request->marca_id);
        $categoria = Categoria::find($request->categoria_id);
        $loja = Loja::find($request->loja_id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => $isMateriaPrima ? 'Inventário de Matérias-primas' : 'Inventário de Produtos',
            "isMateriaPrima" => $isMateriaPrima,
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "produtos" => $produtos,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "marca" => $marca,
            "categoria" => $categoria,
            "loja" => $loja,
            "requests" => $request->all('categoria_id', 'marca_id', 'loja_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.produtos.inventario-pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

    public function inventarioExportarExcel(Request $request, string $tipo = 'produto')
    {
        ini_set('memory_limit', '1024M');  // Ajuste para 1024 MB ou outro valor
        ini_set('max_execution_time', 300); // 5 minutos

        $isMateriaPrima = $tipo === 'materias-primas';

        $codigo = date("Y-m-d");

        return Excel::download(new InvantarioExport($request, $isMateriaPrima), "inventario{$codigo}.xlsx");
    }

    public function diarios(Request $request)
    {
        $user = auth()->user();
        if (!$user->can('movimento no caixa')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);
        
        $data = date("Y-m-d");
        
        $total_arrecadado = ItemVenda::with(['factura'])->when($data, function ($query, $value) {
            $query->whereDate('created_at', '>=', Carbon::createFromDate($value));
        })
            ->when($data, function ($query, $value) {
                $query->whereDate('created_at', '<=', Carbon::createFromDate($value));
            })
            ->when($request->caixa_id, function ($query, $value) {
                $query->where('caixa_id', '=', $value);
            })
            ->when($request->user_id, function ($query, $value) {
                $query->where('user_id', '=', $value);
            })
            ->where('status', "!=", "anulada")
            ->where('entidade_id', $empresa->id)
            ->get();

        $total_vendido_valor = 0;
        $total_Custo_produto_vendido = 0;
        $total_ganho_vendas = 0;
        $total_arrecadado_cash = 0;
        $total_arrecadado_multicaixa = 0;
        $total_arrecadado_transferencias = 0;
        $total_arrecadado_depositos = 0;
        $total_duplo = 0;

        foreach ($total_arrecadado as $valores) {

            $total_vendido_valor += $valores->valor_pagar;
            $total_Custo_produto_vendido += $valores->custo;;
            $total_ganho_vendas += $valores->lucro;

            if ($valores->factura) {
                if ($valores->factura->pagamento == "NU") {
                    $total_arrecadado_cash += $valores->valor_pagar;
                }
                if ($valores->factura->pagamento == "MB") {
                    $total_arrecadado_multicaixa += $valores->valor_pagar;
                }
                if ($valores->factura->pagamento == "OU") {
                    $total_arrecadado_cash += $valores->valor_cash;
                    $total_arrecadado_multicaixa += $valores->valor_multicaixa;
                    $total_duplo += $valores->valor_pagar;
                }
                if ($valores->factura->pagamento == "TE") {
                    $total_arrecadado_transferencias += $valores->valor_pagar;
                }
                if ($valores->factura->pagamento == "DE") {
                    $total_arrecadado_depositos += $valores->valor_pagar;
                }
            }
        }

        $relatorios = Venda::with(['items', 'user', 'caixa', 'cliente'])
            ->where('entidade_id', $empresa->id)
            ->where('status_factura', ['pago'])
            ->where('anulado', ['N'])
            ->where('factura', ['FR'])
            ->when($data, function ($query, $value) {
                $query->whereDate('created_at', '=', Carbon::parse($value));
            })
            ->get();

        $facturas = Recibo::where('entidade_id', '=', $entidade->empresa->id)
            ->with(['cliente', 'facturas'])
            ->when($data, function ($query, $value) {
                $query->whereDate('created_at', '=', Carbon::parse($value));
            })
            ->get();

        // Unifica as coleções e adiciona um campo de identificação do tipo
        $resultadoUnificado = $relatorios->map(function ($item) {
            $item->tipo = 'relatorio'; // Identificador de tipo
            return $item;
        })->merge(
            $facturas->map(function ($item) {
                $item->tipo = 'factura'; // Identificador de tipo
                return $item;
            })
        );
      
 
        $head = [
            "titulo" => "Diários",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "relatorios" => $relatorios,
            "resultadoUnificado" => $resultadoUnificado,

            "total_vendido_valor" => $total_vendido_valor,
            "total_Custo_produto_vendido" => $total_Custo_produto_vendido,
            "total_ganho_vendas" => $total_ganho_vendas,
            "total_arrecadado_cash" => $total_arrecadado_cash,
            "total_arrecadado_multicaixa" => $total_arrecadado_multicaixa,
            "total_arrecadado_transferencias" => $total_arrecadado_transferencias,
            "total_arrecadado_depositos" => $total_arrecadado_depositos,
            "total_duplo" => $total_duplo,
            
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contabilidade.diario', $head);
    }

    public function diariosPDF(Request $request)
    {
        $user = auth()->user();

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);
        
        $data = date("Y-m-d");
        
        $total_arrecadado = ItemVenda::with(['factura'])->when($data, function ($query, $value) {
            $query->whereDate('created_at', '>=', Carbon::createFromDate($value));
        })
            ->when($data, function ($query, $value) {
                $query->whereDate('created_at', '<=', Carbon::createFromDate($value));
            })
            ->when($request->caixa_id, function ($query, $value) {
                $query->where('caixa_id', '=', $value);
            })
            ->when($request->user_id, function ($query, $value) {
                $query->where('user_id', '=', $value);
            })
            ->where('status', "!=", "anulada")
            ->where('entidade_id', $empresa->id)
            ->get();

        $total_vendido_valor = 0;
        $total_Custo_produto_vendido = 0;
        $total_ganho_vendas = 0;
        $total_arrecadado_cash = 0;
        $total_arrecadado_multicaixa = 0;
        $total_arrecadado_transferencias = 0;
        $total_arrecadado_depositos = 0;
        $total_duplo = 0;

        foreach ($total_arrecadado as $valores) {

            $total_vendido_valor += $valores->valor_pagar;
            $total_Custo_produto_vendido += $valores->custo;;
            $total_ganho_vendas += $valores->lucro;

            if ($valores->factura) {
                if ($valores->factura->pagamento == "NU") {
                    $total_arrecadado_cash += $valores->valor_pagar;
                }
                if ($valores->factura->pagamento == "MB") {
                    $total_arrecadado_multicaixa += $valores->valor_pagar;
                }
                if ($valores->factura->pagamento == "OU") {
                    $total_arrecadado_cash += $valores->valor_cash;
                    $total_arrecadado_multicaixa += $valores->valor_multicaixa;
                    $total_duplo += $valores->valor_pagar;
                }
                if ($valores->factura->pagamento == "TE") {
                    $total_arrecadado_transferencias += $valores->valor_pagar;
                }
                if ($valores->factura->pagamento == "DE") {
                    $total_arrecadado_depositos += $valores->valor_pagar;
                }
            }
        }
       
        $relatorios = Venda::with(['items', 'user', 'caixa', 'cliente'])
            ->where('entidade_id', $empresa->id)
            ->whereIn('status_factura', ['pago', 'anulada'])
            ->where('factura', ['FR'])
            ->when($data, function ($query, $value) {
                $query->whereDate('created_at', '=', Carbon::parse($value));
            })
            ->get();

        $facturas = Recibo::where('entidade_id', '=', $entidade->empresa->id)
            ->with(['cliente', 'facturas'])
            ->when($data, function ($query, $value) {
                $query->whereDate('created_at', '=', Carbon::parse($value));
            })
            ->get();

        $total_produtos_saida = Registro::where('entidade_id', $entidade->empresa->id)->whereIn('tipo', ['S'])->whereIn('status', ['E'])->whereDate('created_at', Carbon::parse($data))->sum('quantidade');

        $total_produtos_devolvidos = ItemVenda::where('entidade_id', $entidade->empresa->id)->where('status', 'anulada')->whereDate('created_at', Carbon::parse($data))->sum('quantidade');
        $total_produtos_vendidos = ItemVenda::where('entidade_id', $entidade->empresa->id)->where('status', '!=', 'anulada')->whereDate('created_at', Carbon::parse($data))->sum('quantidade');

        // Unifica as coleções e adiciona um campo de identificação do tipo
        $resultadoUnificado = $relatorios->map(function ($item) {
            $item->tipo = 'relatorio'; // Identificador de tipo
            return $item;
        })->merge(
            $facturas->map(function ($item) {
                $item->tipo = 'factura'; // Identificador de tipo
                return $item;
            })
        );

        $total_arrecadado = Venda::where('entidade_id', $empresa->id)
            // ->where('user_id', Auth::user()->id)
            ->where('status_factura', ['pago'])
            ->when($data, function ($query, $value) {
                $query->whereDate('created_at', '=', Carbon::parse($value));
            })
            ->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Fecho de Caixa",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "relatorios" => $relatorios,
            "total_arrecadado" => $total_arrecadado,
            "total_documentos_vendidos" => count($relatorios) + count($facturas),
            "total_produtos_devolvidos" => $total_produtos_devolvidos,
            "total_produtos_vendidos" => $total_produtos_vendidos,
            "total_produtos_saida" => $total_produtos_saida,
            "resultadoUnificado" => $resultadoUnificado,
            
            "total_vendido_valor" => $total_vendido_valor,
            "total_Custo_produto_vendido" => $total_Custo_produto_vendido,
            "total_ganho_vendas" => $total_ganho_vendas,
            "total_arrecadado_cash" => $total_arrecadado_cash,
            "total_arrecadado_multicaixa" => $total_arrecadado_multicaixa,
            "total_arrecadado_transferencias" => $total_arrecadado_transferencias,
            "total_arrecadado_depositos" => $total_arrecadado_depositos,
            "total_duplo" => $total_duplo,
    
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];
        
        if ($entidade->empresa->tipo_factura == 'Normal') {
            $pdf = PDF::loadView('dashboard.contabilidade.diarios-pdf', $head);
            $pdf->setPaper('A4', 'portrait');
        }
        if ($entidade->empresa->tipo_factura == 'Ticket') {
            $pdf = PDF::loadView('dashboard.contabilidade.diarios-pdf-ticket', $head);
            $pdf->setPaper([0, 0, 226.77, 1000], 'portrait'); 
        }

        return $pdf->stream();
    }

    public function diariosDetalhe(Request $request, $id)
    {

        $user = auth()->user();

        if (!$user->can('movimento no caixa')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $vendas = Venda::with(['items.produto', 'user', 'caixa', 'cliente'])->findOrFail($id);

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "relatorios" => $vendas,
            "empresa" => $empresa,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contabilidade.diario-detalhes', $head);
    }

    public function facturacao(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('movimento no caixa geral')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $empresa = Entidade::with(["caixas", "users", "variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);
        
        $data_inicio = $request->data_inicio ?? now()->startOfDay();
        $data_final  = $request->data_final ?? now()->endOfDay();

        $relatorios = Venda::with(['items', 'user', 'caixa', 'cliente'])
            ->with(['items' => function ($query) use ($request) {
                $query->where('status', '!=', 'anulada');
            }])
            ->when($data_inicio, function ($query, $value) {
                $query->whereDate('created_at', '>=', Carbon::createFromDate($value));
            })
            ->when($data_final, function ($query, $value) {
                $query->whereDate('created_at', '<=', Carbon::createFromDate($value));
            })
            ->when($request->caixa_id, function ($query, $value) {
                $query->where('caixa_id', '=', $value);
            })
            ->when($request->user_id, function ($query, $value) {
                $query->where('user_id', '=', $value);
            })
            ->where('entidade_id', $empresa->id)
            ->whereIn('status_factura', ['pago'])
            ->get();

        $total_arrecadado = ItemVenda::with(['factura'])->when($data_inicio, function ($query, $value) {
            $query->whereDate('created_at', '>=', Carbon::createFromDate($value));
        })
            ->when($data_final, function ($query, $value) {
                $query->whereDate('created_at', '<=', Carbon::createFromDate($value));
            })
            ->when($request->caixa_id, function ($query, $value) {
                $query->where('caixa_id', '=', $value);
            })
            ->when($request->user_id, function ($query, $value) {
                $query->where('user_id', '=', $value);
            })
            ->where('status', "!=", "anulada")
            ->where('entidade_id', $empresa->id)
            ->get();


        $total_vendido_valor = 0;
        $total_Custo_produto_vendido = 0;
        $total_ganho_vendas = 0;
        $total_arrecadado_cash = 0;
        $total_arrecadado_multicaixa = 0;
        $total_arrecadado_transferencias = 0;
        $total_arrecadado_depositos = 0;
        $total_duplo = 0;

        foreach ($total_arrecadado as $valores) {

            $total_vendido_valor += $valores->valor_pagar;
            $total_Custo_produto_vendido += $valores->custo;;
            $total_ganho_vendas += $valores->lucro;

            if ($valores->factura) {
                if ($valores->factura->pagamento == "NU") {
                    $total_arrecadado_cash += $valores->valor_pagar;
                }
                if ($valores->factura->pagamento == "MB") {
                    $total_arrecadado_multicaixa += $valores->valor_pagar;
                }
                if ($valores->factura->pagamento == "OU") {
                    $total_arrecadado_cash += $valores->valor_cash;
                    $total_arrecadado_multicaixa += $valores->valor_multicaixa;
                    $total_duplo += $valores->valor_pagar;
                }
                if ($valores->factura->pagamento == "TE") {
                    $total_arrecadado_transferencias += $valores->valor_pagar;
                }
                if ($valores->factura->pagamento == "DE") {
                    $total_arrecadado_depositos += $valores->valor_pagar;
                }
            }
        }

        $head = [
            "titulo" => "FACTURAÇÃO",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "total_vendido_valor" => $total_vendido_valor,
            "total_custo_produto_vendido" => $total_Custo_produto_vendido,
            "total_ganho_vendas" => $total_ganho_vendas,
            "relatorios" => $relatorios,
            "total_arrecadado" => $total_arrecadado,
            "total_arrecadado_cash" => $total_arrecadado_cash,
            "total_arrecadado_multicaixa" => $total_arrecadado_multicaixa,
            "total_arrecadado_transferencias" => $total_arrecadado_transferencias,
            "total_arrecadado_depositos" => $total_arrecadado_depositos,
            "total_duplo" => $total_duplo,
            "requests" => $request->all("data_inicio", "data_final", "caixa_id", "user_id"),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contabilidade.facturacao', $head);
    }

    public function calcularSaldos($activos)
    {
        $totais = ['credito' => 0, 'debito' => 0];
        foreach ($activos as $item) {
            foreach ($item->subcontas as $sub_) {
                foreach ($sub_->movimentos as $sl) {
                    $totais['credito'] += $sl->credito;
                    $totais['debito'] += $sl->debito;
                }
            }
        }
        return $totais;
    }

    public function getActivoNaoCorrente(array $contas, array $classeIds, string $subcont = null)
    {
        return Conta::whereIn('conta', $contas)
            ->with(['subcontas' => function ($query) use ($subcont) {
                if (!empty($subcont)) {
                    $query->where('numero', 'like', "{$subcont}%"); // Passa o array diretamente
                }
                $query->whereHas('movimentos', function ($query) {
                    $query->selectRaw('subconta_id, SUM(credito) as credito, SUM(debito) as debito')
                        ->groupBy('subconta_id');
                });
            }])
            ->whereHas('subcontas.movimentos') // Apenas subcontas com movimentos
            ->whereIn('classe_id', $classeIds)
            ->get();
    }

    public function balanco_inicial(Request $request)
    {
        ini_set('memory_limit', '1024M');  // Ajuste para 1024 MB ou outro valor
        ini_set('max_execution_time', 300); // 5 minutos
        
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('balanco')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // activos não correntes
        $meios_fixos_investimento_classe1 = $this->getActivoNaoCorrente(['11'], [1]);
        
        $meios_fixos_investimento_classe2 = $this->getActivoNaoCorrente(['12'], [1]);
        $meios_fixos_investimento_classe3 = $this->getActivoNaoCorrente(['13'], [1]);
        $meios_fixos_investimento_outros_activos_nao_correntes = $this->getActivoNaoCorrente(['14', '18'], [1]);
        $meios_fixos_investimento_financeiros = $this->getActivoNaoCorrente(['19'], [1]);

        $subtotal_activos_nao_correntes = $this->getActivoNaoCorrente(['11', '12', '13', '14', '15', '16', '17', '18', '19'], [1]);
        $saldos_activos_nao_correntes = $this->calcularSaldos($subtotal_activos_nao_correntes);
        // end activos não correntes

        // activos correntes
        $activo_corrente_existencias = $this->getActivoNaoCorrente(['21', '22', '23', '24', '25', '26', '27', '28'], [2]);
        $saldos_corrente_existencias = $this->calcularSaldos($activo_corrente_existencias);

        $activo_corrente_terceiros = $this->getActivoNaoCorrente(['31'], [3]);
        $activo_corrente_contas_receber = $this->getActivoNaoCorrente(['35'], [3], '35.1');
        $activo_corrente_contas_receber_2 = $this->getActivoNaoCorrente(['37'], [3], '37.2');

        $activo_corrente_disponibilidade = $this->getActivoNaoCorrente(['41', '42', '43', '44', '45', '48'], [4]);

        $subtotal_activos_correntes = $this->getActivoNaoCorrente(['21', '22', '23', '24', '25', '26', '27', '28', '31', '35', '37', '41', '42', '43', '44', '45', '48'], [1, 2, 3, 4]);
        $saldos_activos_correntes = $this->calcularSaldos($subtotal_activos_correntes);
        // end activos correntes


        // passivo não correntes
        $contas_passivo_nao_corrente = $this->getActivoNaoCorrente(['33'], [3], '33.1');
        $saldos_passivo_nao_corrente = $this->calcularSaldos($contas_passivo_nao_corrente);

        // passivo corrente
        $contas_passivo_corrente = $this->getActivoNaoCorrente(['32', '34', '36'], [3]);
        $saldo_passivo_corrente = $this->calcularSaldos($contas_passivo_corrente);

        // parte 1
        $outras_contas_passivos_correntes = $this->getActivoNaoCorrente(['37'], [3], '37.9');
        $saldo_outras_contas_passivos_correntes = $this->calcularSaldos($outras_contas_passivos_correntes);

        // parte 2
        $outras_contas_passivos_correntes1 = $this->getActivoNaoCorrente(['35'], [3], '35.2');
        $saldo_outras_contas_passivos_correntes1 = $this->calcularSaldos($outras_contas_passivos_correntes1);

        // passivo não correntes

        // capital Próprio
        $contas_resultado_transitados = $this->getActivoNaoCorrente(['81'], [8]);
        $saldo_resultado_transitados = $this->calcularSaldos($contas_resultado_transitados);

        $contas_resultado_liquido_exercicios = $this->getActivoNaoCorrente(['88'], [8]);
        $saldo_resultado_liquido_exercicios = $this->calcularSaldos($contas_resultado_liquido_exercicios);

        $contas_capital_social = $this->getActivoNaoCorrente(['51'], [5]);
        $saldo_capital_social = $this->calcularSaldos($contas_capital_social);

        $contas_reserva_legais = $this->getActivoNaoCorrente(['55'], [5]);
        $saldo_reserva_legais = $this->calcularSaldos($contas_reserva_legais);

        $head = [
            "titulo" => "Balanço Inicial",
            "descricao" => env('APP_NAME'),

            // activos
            // activos não correntes
            "meios_fixos_investimento_classe1" => $meios_fixos_investimento_classe1,
            "meios_fixos_investimento_classe2" => $meios_fixos_investimento_classe2,
            "meios_fixos_investimento_classe3" => $meios_fixos_investimento_classe3,
            "meios_fixos_investimento_financeiros" => $meios_fixos_investimento_financeiros,
            "meios_fixos_investimento_outros_activos_nao_correntes" => $meios_fixos_investimento_outros_activos_nao_correntes,
            "saldos_activos_nao_correntes" => $saldos_activos_nao_correntes,

            // activos correntes
            "activo_corrente_existencias" => $activo_corrente_existencias,
            "activo_corrente_disponibilidade" => $activo_corrente_disponibilidade,
            "activo_corrente_terceiros" => $activo_corrente_terceiros,
            "activo_corrente_contas_receber" => $activo_corrente_contas_receber,
            "activo_corrente_contas_receber_2" => $activo_corrente_contas_receber_2,
            "saldos_activos_correntes" => $saldos_activos_correntes,

            //passivos
            // passivos não correntes
            "contas_passivo_nao_corrente" => $contas_passivo_nao_corrente,
            "saldos_passivo_nao_corrente" => $saldos_passivo_nao_corrente,
            // passivo corrente
            "contas_passivo_corrente" => $contas_passivo_corrente,
            "saldo_passivo_corrente" => $saldo_passivo_corrente,

            "outras_contas_passivos_correntes" => $outras_contas_passivos_correntes,
            "saldo_outras_contas_passivos_correntes" => $saldo_outras_contas_passivos_correntes,

            "outras_contas_passivos_correntes1" => $outras_contas_passivos_correntes1,
            "saldo_outras_contas_passivos_correntes1" => $saldo_outras_contas_passivos_correntes1,
            // end passívos

            // capital social
            "contas_resultado_liquido_exercicios" => $contas_resultado_liquido_exercicios,
            "saldo_resultado_liquido_exercicios" => $saldo_resultado_liquido_exercicios,

            "contas_resultado_transitados" => $contas_resultado_transitados,
            "saldo_resultado_transitados" => $saldo_resultado_transitados,

            "contas_capital_social" => $contas_capital_social,
            "saldo_capital_social" => $saldo_capital_social,

            "contas_reserva_legais" => $contas_reserva_legais,
            "saldo_reserva_legais" => $saldo_reserva_legais,
            // capital social

            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contabilidade.balanco-inicial', $head);
    }

    public function balanco_inicial_create(Request $request)
    {

        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('balanco')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $subcontas = Subconta::with(['conta'])->where('entidade_id', $entidade->empresa->id)->orderBy('numero', 'asc')->get();

        $exercicio = Exercicio::findOrFail($this->exercicio());
        $periodos = Periodo::where('exercicio_id', $exercicio->id)->where('entidade_id', $entidade->empresa->id)->get();


        $movimentos = Movimento::with(['subconta' => function ($query) {
            $query->orderBy('numero', 'asc');
        }])
            ->whereIn('origem', ['BI'])
            ->where('exercicio_id', $exercicio->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "subcontas" => $subcontas,
            "exercicio" => $exercicio,
            "periodos" => $periodos,
            "movimentos" => $movimentos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contabilidade.novo-balanco-inicial', $head);
    }

    public function balanco_inicial_store(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('balanco')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'exercicio_id' => 'required|string',
            'periodo_id' => 'required|string',
            'subconta_id' => 'required|string',
            'saldo' => 'required|string',
        ]);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $code = uniqid(time());

            $subconta = Subconta::findOrFail($request->subconta_id);

            Movimento::create([
                'user_id' => Auth::user()->id,
                'subconta_id' => $subconta->id,
                'movimento' => 'E',
                'observacao' => 'Saldo Inicial',
                'origem' => 'BI',
                'numero' => $subconta->nome,
                'credito' => 0,
                'debito' => $request->saldo,
                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                'exercicio_id' => $request->exercicio_id,
                'periodo_id' => $request->periodo_id,
                'code' => $code,
                'data_at' => date("Y-m-d"),
                'entidade_id' => $entidade->empresa->id,
            ]);

            OperacaoFinanceiro::create([
                'nome' => "BALANÇO INICAL",
                'status' => "pago",
                'formas' => "O",
                'motante' => $request->saldo,
                'subconta_id' => $subconta->id,
                'cliente_id' => NULL,
                'model_id' => $this->receita_padrao(),
                'type' => 'R',
                'parcelado' => "N",
                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                'status_pagamento' => "pago",
                'code' => $code,
                'descricao' => "BALANÇO INICAL",
                'movimento' => "E",
                'date_at' => date("Y-m-d"),
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
                'exercicio_id' => $request->exercicio_id,
                'periodo_id' => $request->periodo_id,
            ]);

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }


        return redirect()->back()->with("success", "Dados Cadastrados com Sucesso!");
    }

    public function balancete(Request $request)
    {

        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('balacente')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $movimentos = Movimento::query()
            ->when($request->exercicio_id, fn($query, $value) => $query->where('exercicio_id', $value))
            ->when($request->periodo_id, fn($query, $value) => $query->where('periodo_id', $value))
            ->when($request->subconta_id, fn($query, $value) => $query->where('subconta_id', $value))
            ->when($request->data_inicio, fn($query, $value) => $query->whereDate('data_at', '>=', $value))
            ->when($request->data_final, fn($query, $value) => $query->whereDate('data_at', '<=', $value))
            ->with(['subconta', 'exercicio', 'periodo'])
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $subcontas = Subconta::with(['conta'])->where('entidade_id', $entidade->empresa->id)->get();
        $exercicios = Exercicio::where('id', $this->exercicio())->get();
        $periodos = Periodo::where('exercicio_id', '=', $this->exercicio())->where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => "Balancete",
            "descricao" => env('APP_NAME'),
            "movimentos" => $movimentos,
            "subcontas" => $subcontas,
            "exercicios" => $exercicios,
            "periodos" => $periodos,
            "requests" => $request->all('exercicio_id', 'periodo_id', 'subconta_id', 'data_inicio', 'data_final'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contabilidade.balancete', $head);
    }

    public function fecho_contas(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('fecho de contas')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $head = [
            "titulo" => "Fecho de contas",
            "descricao" => env('APP_NAME'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contabilidade.fecho-contas', $head);
    }

    public function fecho_contas_cmv(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('fecho de contas')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        /** START - CONTABILIDADE METODO INVENTARIO PERMANENTE */
        if ($entidade->empresa->tipo_inventario == "PERMANENTE") {
        }

        $head = [
            "titulo" => "Fecho de contas",
            "descricao" => env('APP_NAME'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.contabilidade.fecho-contas', $head);
    }
}
