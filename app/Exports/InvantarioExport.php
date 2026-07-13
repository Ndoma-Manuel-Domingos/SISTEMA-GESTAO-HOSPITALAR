<?php

namespace App\Exports;

use App\Models\Categoria;
use App\Models\Entidade;
use App\Models\Loja;
use App\Models\LojaProduto;
use App\Models\Marca;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class InvantarioExport implements FromView, WithTitle, WithCustomStartCell
{
    private $marca_id;
    private $categoria_id;
    private $loja_id;
    private bool $isMateriaPrima;

    public function __construct(Request $request, bool $isMateriaPrima)
    {
        $this->marca_id = $request->marca_id;
        $this->categoria_id = $request->categoria_id;
        $this->loja_id = $request->loja_id;
        $this->isMateriaPrima = $isMateriaPrima;
    }


    public function title(): string
    {
        return "INVENTÁRIO DAS VENDAS";
    }


    public function startCell(): string
    {
        return 'A11';
    }


    public function view(): View
    {

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'categorias', 'marcas'])->findOrFail($entidade->empresa->id);

        $categoria_id = $this->categoria_id;
        $marca_id = $this->marca_id;
        $loja_id = $this->loja_id;
        $isMateriaPrima = $this->isMateriaPrima;

        $produtos = LojaProduto::with(['loja'])->with(['produto' => function ($query) use ($categoria_id, $marca_id, $loja_id, $isMateriaPrima) {
            $query->when($categoria_id, function ($query, $value) {
                $query->where('categoria_id', $value);
            })
                ->when($marca_id, function ($query, $value) {
                    $query->where('marca_id', $value);
                })
                ->when( $isMateriaPrima,
                    fn($q) => $q->where('tipo_stock', 'P'),
                    fn($q) => $q->where('tipo_stock', '!=', 'P')
                )
                ->withSum('quantidade', 'quantidade');
        }])
        ->where('entidade_id', $entidade->empresa->id)
        ->when($loja_id, function ($query, $value) {
            $query->where('loja_id', $value);
        })
        ->orderByDesc('loja_id') // ou pela ordem que preferir
        ->get();

        $marca = Marca::find($marca_id);
        $categoria = Categoria::find($categoria_id);
        $loja = Loja::find($loja_id);

        return view('exports.inventario', [
            "titulo" => $isMateriaPrima ? 'Inventário de Matérias-primas' : 'Inventário de Produtos',
            "isMateriaPrima" => $isMateriaPrima,
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "produtos" => $produtos,
            "marca" => $marca,
            "categoria" => $categoria,
            "loja" => $loja,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ]);
    }
}
