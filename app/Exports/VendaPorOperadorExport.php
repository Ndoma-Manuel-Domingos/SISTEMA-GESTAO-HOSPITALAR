<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\Caixa;
use App\Models\Entidade;
use App\Models\ItemVenda;
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

class VendaPorOperadorExport implements FromView, WithTitle, WithCustomStartCell
{

    use TraitHelpers;
    
    private $data_inicio, $data_final, $caixa_id, $user_id;

    public function __construct($data_inicio, $data_final, $caixa_id, $user_id)
    {
        $this->data_inicio = $data_inicio;
        $this->data_final = $data_final;
        $this->caixa_id = $caixa_id;
        $this->user_id = $user_id;
    }


    public function title(): string
    {
        return "RELATÓRIO DE VENDAS POR OPERADORES";
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
        $data_final = $this->data_final;
        $caixa_id = $this->caixa_id;
        $user_id = $this->user_id;


        $vendas = ItemVenda::with(['produto', 'user'])
            ->select(
                'user_id',
                DB::raw("SUM(valor_pagar) as total_valor"),
            )
            ->where('entidade_id', $entidade->empresa->id)
            ->whereIn('status', ['realizado'])
            ->whereHas('factura', function ($query) {
                $query->whereIn('status_factura', ['pago']);
            })
            ->when($this->data_inicio, function ($query, $value) {
                $query->whereDate('created_at', '>=', Carbon::createFromDate($value));
            })
            ->when($this->data_final, function ($query, $value) {
                $query->whereDate('created_at', '<=', Carbon::createFromDate($value));
            })
            ->when($this->caixa_id, function ($query, $value) {
                $query->where('caixa_id', '=', $value);
            })
            ->when($this->user_id, function ($query, $value) {
                $query->where('user_id', '=', $value);
            })
            ->groupBy('user_id')
            ->get()
            ->sortBy(function ($prod) {
                return $prod->produto->nome ?? '';
            });


        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $caixa = Caixa::find($caixa_id);
        $user = User::find($user_id);


        return view('exports.vendas-por-operadores', [
            "titulo" => "RELATÓRIO DE VENDAS POR OPERADORES",
            "descricao" => env('APP_NAME'),
            'total_venda' => 0,
            'vendas' => $vendas,
            "caixa" => $caixa,
            "user" => $user,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ]);
    }
}
