<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\Caixa;
use App\Models\Entidade;
use App\Models\ItemVenda;
use App\Models\LojaProduto;
use App\Models\Produto;
use App\Models\User;
use App\Models\UserLoja;
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

class ListagemProdutoExport implements FromView, WithTitle, WithCustomStartCell
{
    
    use TraitHelpers;
    
    private $categoria_id, $tipo, $marca_id;
    
    public function __construct($categoria_id, $tipo, $marca_id) {
        $this->categoria_id = $categoria_id;
        $this->tipo = $tipo;
        $this->marca_id = $marca_id;
    }
    
        
    public function title(): string
    {
        return "LISTAGEM DE PRODUTOS";
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
        
        $categoria_id = $this->categoria_id;
        $tipo = $this->tipo;
        $marca_id = $this->marca_id;
        
        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");

        $produtos = Produto::whereIn("id", $meus_produtos)->where([
            ['user_id', '=', Auth::user()->id]
        ])
            ->when($categoria_id, function ($query, $value) {
                $query->where('categoria_id', '=', $value);
            })
            ->when($tipo, function ($query, $value) {
                $query->where('tipo', '=', $value);
            })
            ->when($marca_id, function ($query, $value) {
                $query->where('marca_id', '=', $value);
            })
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();
    
        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        
        return view('exports.listagem-produtos', [
            "titulo" => "LISTAGEM DE PRODUTOS",
            "descricao" => env('APP_NAME'),
            'total_venda' => 0,
            'produtos' => $produtos,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ]);
    
    }

}
