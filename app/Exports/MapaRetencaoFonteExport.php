<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\Caixa;
use App\Models\Categoria;
use App\Models\Entidade;
use App\Models\ItemVenda;
use App\Models\Loja;
use App\Models\LojaProduto;
use App\Models\Marca;
use App\Models\Produto;
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

class MapaRetencaoFonteExport implements FromView, WithTitle, WithCustomStartCell
{
    use TraitHelpers;
    private $data_inicio, $data_final, $status;

    public function __construct($data_inicio, $data_final, $status)
    {
        $this->data_inicio = $data_inicio;
        $this->data_final = $data_final;
        $this->status = $status;
    }


    public function title(): string
    {
        return "MAPA DE RETENÇÃO NA FONTE";
    }


    public function startCell(): string
    {
        return 'A11';
    }

    public function view(): View
    {

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'categorias', 'marcas'])->findOrFail($entidade->empresa->id);

        $data_inicio = $this->data_inicio;
        $data_final = $this->data_final;
        $status = $this->status;

        $vendas = ItemVenda::with(["produto.categoria", "user", "factura.cliente"])
            ->select(
                "produto_id",
                DB::raw("SUM(retencao_fonte) as total_retencao_fonte"),
                DB::raw("SUM(valor_pagar) as total_valor_pagar"),
            )
            ->where("entidade_id", $entidade->empresa->id)
            ->where("status", "realizado")
            ->whereHas("factura", function ($query) use ($status) {
                $query->when($status, function ($query, $value) {
                    $query->where("status_factura", $value);
                });
            })
            ->when($data_inicio, function ($query, $value) {
                $query->whereDate('created_at', '>=', Carbon::createFromDate($value));
            })
            ->when($data_final, function ($query, $value) {
                $query->whereDate('created_at', '<=', Carbon::createFromDate($value));
            })
            ->groupBy('produto_id')
            ->get()
            ->sortBy(function ($prod) {
                return $prod->produto->nome ?? '';
            });


        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        return view('exports.mapa-retencao-fonte', [
            "titulo" => "MAPA DE RETENÇÃO NA FONTE",
            "descricao" => env('APP_NAME'),
            'total_venda' => 0,
            'vendas' => $vendas,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ]);
    }
}
