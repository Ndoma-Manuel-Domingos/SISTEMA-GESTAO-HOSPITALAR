<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\Categoria;
use App\Models\Entidade;
use App\Models\Loja;
use App\Models\LojaProduto;
use App\Models\Marca;
use App\Models\Produto;
use App\Models\Registro;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class MovimentoEstoqueExport implements FromView, WithTitle, WithCustomStartCell
{
    
    use TraitHelpers;
    
    private $data_inicio, $data_final, $loja_id, $produto_id, $status, $tipo;
    
    public function __construct($data_inicio, $data_final, $loja_id, $produto_id, $status, $tipo) {
        $this->data_inicio = $data_inicio;
        $this->data_final = $data_final;
        $this->loja_id = $loja_id;
        $this->status = $status;
        $this->tipo = $tipo;
        $this->produto_id = $produto_id;
    }
        
    public function title(): string
    {
        return "RELATÓRIO DE PRODUTOS NO STOCK";
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
        $loja_id = $this->loja_id;
        $produto_id = $this->produto_id;
        $tipo = $this->tipo;
        $status = $this->status;
        
        $movimentos = Registro::when($loja_id, function ($query, $value) {
            $query->where('loja_id', $value);
        })
            ->when($produto_id, function ($query, $value) {
                $query->where('produto_id', $value);
            })
            ->when($tipo, function ($query, $value) {
                $query->where('tipo', $value);
            })
            ->when($status, function ($query, $value) {
                $query->where('status', $value);
            })
            ->when($data_inicio, function ($query, $value) {
                $query->whereDate('created_at', '>=', Carbon::parse($value));
            })
            ->when($data_final, function ($query, $value) {
                $query->whereDate('created_at', '<=', Carbon::parse($value));
            })
            ->with('produto.unidade', 'user', 'loja')
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
        ->get();

        $produto = Produto::find($produto_id);
        $loja = Loja::find($loja_id);
        
        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $loja = Loja::find($loja_id);
        
        return view('exports.movimentos-stock', [
            "titulo" => "RELATÓRIO DOS MOVIMENTOS DO ESTOQUE STOCK",
            "descricao" => env('APP_NAME'),
            'movimentos' => $movimentos,
            "produto" => $produto,
            "loja" => $loja,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
            "requests" => [
                'status' => $this->status, 
                'tipo' => $this->tipo, 
                'produto_id' => $this->produto_id, 
                'data_inicio' => $this->data_inicio, 
                'data_final' => $this->data_final
            ]
        ]);
    
    }

}
