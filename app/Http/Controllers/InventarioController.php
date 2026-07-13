<?php

namespace App\Http\Controllers;

use App\Models\Entidade;
use App\Models\EquipamentoActivo;
use App\Models\Loja;
use App\Models\LojaProduto;
use App\Models\Produto;
use App\Models\User;
use App\Models\UserLoja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;
use RealRashid\SweetAlert\Facades\Alert;

class InventarioController extends Controller
{
    //
    use TraitHelpers;

    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('inventario')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'categorias', 'marcas'])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Inventário",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.inventarios.index', $head);
    }


    public function inicial(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('inventario')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'categorias', 'marcas'])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Inventário",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.inventarios.inicial', $head);
    }

    public function equipamentos(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('inventario')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'categorias', 'marcas'])->findOrFail($entidade->empresa->id);

        $equipamentos_activos = EquipamentoActivo::with(['user', 'classificacao', 'fornecedor', 'conta', 'entidade'])
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $head = [
            "titulo" => "Equipamentos / Activos",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "equipamentos_activos" => $equipamentos_activos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.inventarios.equipamentos', $head);
    }

    public function existencias(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('inventario')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(['variacoes', 'categorias', 'marcas'])->findOrFail($entidade->empresa->id);

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");

        $produtos = Produto::whereIn("id", $meus_produtos)
            ->with(['categoria', 'marca', 'taxa_imposto'])
            ->where('entidade_id', $entidade->empresa->id)
            ->where('tipo', 'P')
            ->orderBy('nome', 'asc')
            ->get();


        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();

        $head = [
            "titulo" => "Existências",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "produtos" => $produtos,
            "lojas" => $lojas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.inventarios.existencias', $head);
    }
}
