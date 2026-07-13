<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\Caixa;
use App\Models\Entidade;
use App\Models\ItemVenda;
use App\Models\RegistroMovimento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class MovimentoEstoqueEntradaSaidaExport implements FromView, WithTitle, WithCustomStartCell
{
    
    use TraitHelpers;

    private $data_inicio, $data_final, $tipo, $produto_id, $fornecedor_id, $categoria_id;

    public function __construct($data_inicio, $data_final, $tipo, $produto_id, $categoria_id, $fornecedor_id)
    {
        $this->data_inicio = $data_inicio;
        $this->data_final = $data_final;
        $this->categoria_id = $categoria_id;
        $this->produto_id = $produto_id;
        $this->fornecedor_id = $fornecedor_id;
        $this->data_final = $data_final;
        $this->tipo = $tipo;
    }


    public function title(): string
    {
        return "RELATÓRIO MOVIMENTOS DO STOCK";
    }


    public function startCell(): string
    {
        return 'A11';
    }

    // public function drawings()
    // {
    //     $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
    //     // Caminho da imagem
    //     $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");

    //     $drawing = new Drawing();
    //     $drawing->setName('Logo');
    //     $drawing->setDescription('Este é o logotipo da Instituição');
    //     $drawing->setPath($logotipoPath);
    //     $drawing->setHeight(90);
    //     $drawing->setCoordinates('A11');

    //     return $drawing;
    // }


    public function view(): View
    {

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'categorias', 'marcas'])->findOrFail($entidade->empresa->id);

        $data_inicio = $this->data_inicio;
        $tipo = $this->tipo;
        $fornecedor_id = $this->fornecedor_id;
        $produto_id = $this->produto_id;
        $categoria_id = $this->categoria_id;
        $data_final = $this->data_final;

        $movimentos = RegistroMovimento::with(['items.produto', 'items.lote'])
            ->when($data_inicio, function ($query, $value) {
                $query->whereDate("created_at", ">=", Carbon::createFromDate($value));
            })
            ->when($data_final, function ($query, $value) {
                $query->whereDate("created_at", "<=", Carbon::createFromDate($value));
            })
            ->when($tipo, function ($query, $value) {
                $query->where("tipo_documento", $value);
            })
            ->whereHas('items', function ($query) use ($produto_id, $categoria_id) {
                $query->when($produto_id, function ($query, $value) {
                    $query->where('produto_id', $value);
                });
                   
                // 🔹 Filtro adicional por categoria (aninhado no produto)
                $query->when($categoria_id, function ($query, $value) {
                    $query->whereHas('produto.categoria', function ($subQuery) use ($value) {
                        $subQuery->where('id', $value);
                    });
                });
            })
            ->when($fornecedor_id, function ($query, $value) {
                $query->where("fornecedor_id", $value);
            })
            ->orderBy('data_at', 'asc')
            ->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        return view('exports.movimento-estoque-entrada-saida', [
            'titulo' => "RELATÓRIO DE MOVIMENTOS DO STOCK",
            "descricao" => env('APP_NAME'),
            "requests" => [
                'data_inicio' => $data_inicio,
                'data_final' => $data_final
            ],
            'movimentos' => $movimentos,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ]);
    }
}
