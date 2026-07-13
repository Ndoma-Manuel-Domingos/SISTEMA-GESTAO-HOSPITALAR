<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Conta;
use App\Models\EquipamentoActivo;
use App\Models\Fornecedore;
use App\Models\Subconta;
use App\Models\TabelaTaxaReintegracaoAmortizacaoItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class EquipamentoActivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('activo contabilidade')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $equipamentos_activos = EquipamentoActivo::with(['user', 'classificacao', 'fornecedor', 'conta', 'entidade'])
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->get();

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "equipamentos_activos" => $equipamentos_activos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.equipamentos-activos.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('activo contabilidade')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $classe = Classe::where('conta', 'Classe 1')->pluck('id');
        $contaI = Conta::whereIn('classe_id', $classe)->pluck('id');

        $contas = Subconta::whereIn('tipo_conta', ['E', 'G'])->whereIn('conta_id', $contaI)->where('entidade_id', '=', $entidade->empresa->id)->orderBy('numero', 'asc')->get();

        $fornecedores = Fornecedore::where('entidade_id', '=', $entidade->empresa->id)->get();
        $classificacoes = TabelaTaxaReintegracaoAmortizacaoItem::get();

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "fornecedores" => $fornecedores,
            "contas" => $contas,
            "classificacoes" => $classificacoes,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.equipamentos-activos.create', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('activo contabilidade')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $request->validate([
            'nome' => 'required|string',
            'base_incidencia' => 'required|string',
            'conta_id' => 'required|string',
        ]);
     
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            if ($request->hasFile('anexo') && $request->file('anexo')->isValid()) {
                $requestImage = $request->anexo;
                $extension = $requestImage->extension();

                $imageName = md5($requestImage->getClientOriginalName() . strtotime("now") . "." . $extension);

                $request->anexo->move(public_path('images/imobilizados'), $imageName);
            } else {
                $imageName = NULL;
            }
        
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            if ($request->iva <= -1 and $request->iva >= 101) {
                return response()->json(['success' => false, 'message' => "Valor da Taxa do IVA Invalido!"], 404);
            }
            if ($request->iva_nd <= -1 and $request->iva_nd >= 101) {
                return response()->json(['success' => false, 'message' => "Valor da Taxa do IVA Não Dedutível Invalido!"], 404);
            }
            if ($request->iva_d <= -1 and $request->iva_d >= 101) {
                return response()->json(['success' => false, 'message' => "Valor da Taxa do IVA Dedutível Invalido!"], 404);
            }
       
            $iva_total = ($request->base_incidencia * $request->quantidade) * ($request->iva / 100);

            $iva_dedutivel =  $iva_total * ($request->iva_d / 100);
            $iva_n_dedutivel =  $iva_total * ($request->iva_nd / 100);

            $total = $iva_total + $request->base_incidencia;

            $valor_desconto = $total * ($request->desconto / 100);

            $custo_aquisicao = $total -  $valor_desconto;
     
            $subconta = Subconta::findOrFail($request->conta_id);

            $total_subconta = Subconta::where('numero', 'like', $subconta->numero . ".%")->where('entidade_id', $entidade->empresa->id)->count() + 1;
            $numero = $subconta->numero . ".{$total_subconta}";
            
            $code = uniqid(time());
            
            $subconta = Subconta::create([
                'numero' => $numero,
                'nome' => $request->nome,
                'tipo_conta' => 'M',
                'code' => $code,
                'status' => $request->status,
                'conta_id' => $subconta->conta_id,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);
            
            $equipamento_activo = EquipamentoActivo::create([
                'nome' => $request->nome,
                'numero_serie' => $request->numero_serie,
                'codigo_barra' => $request->codigo_barra,
                'quantidade' => $request->quantidade,
                'data_aquisicao' => $request->data_aquisicao,
                'data_utilizacao' => $request->data_utilizacao,
                'subconta_id' => $subconta->id,
                'classificacao_id' => $request->classificacao_id,
                'code' => $code,
                'status' => $request->status,
                'staus_financeiro' => $request->staus_financeiro,
                'base_incidencia' => $request->base_incidencia,
                'iva' => $request->iva,
                'iva_nd' => $request->iva_nd,
                'iva_d' => $request->iva_d,
                'desconto' => $request->desconto,
                'fornecedor_id' => $request->fornecedor_id,
                'numero_factura' => $request->numero_factura,
                'descricao' => $request->descricao,
                'anexo' => $imageName,

                'total' =>  $total,
                'iva_total' =>  $iva_total,
                'valor_desconto' => $valor_desconto,
                'iva_dedutivel' => $iva_dedutivel,
                'iva_n_dedutivel' => $iva_n_dedutivel,
                'custo_aquisicao' => $custo_aquisicao,
                'valor_contabilistico' => $custo_aquisicao,

                'entidade_id' => $entidade->empresa->id,
                'user_id' => Auth::user()->id,
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

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activar($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('activo contabilidade')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $equipamento_activo = EquipamentoActivo::findOrFail($id);
        $equipamento_activo->status = 'activo';
        $equipamento_activo->update();

        return redirect()->back()->with("success", "Exercício activado com sucesso!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function desactivar($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('activo contabilidade')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $equipamento_activo = EquipamentoActivo::findOrFail($id);
        $equipamento_activo->status = 'desactivo';
        $equipamento_activo->update();

        return redirect()->back()->with("success", "Exercício desactivado com sucesso!!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('activo contabilidade')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $equipamento_activo = EquipamentoActivo::with(['user', 'classificacao', 'fornecedor', 'conta', 'entidade'])->findOrFail($id);


        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "equipamento_activo" => $equipamento_activo,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.equipamentos-activos.show', $head);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('activo contabilidade')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $equipamento_activo = EquipamentoActivo::findOrFail($id);
        
        
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $classe = Classe::where('conta', 'Classe 1')->pluck('id');
        $contaI = Conta::whereIn('classe_id', $classe)->pluck('id');

        $contas = Subconta::whereIn('tipo_conta', ['E', 'G'])->whereIn('conta_id', $contaI)->where('entidade_id', '=', $entidade->empresa->id)->orderBy('numero', 'asc')->get();

        $fornecedores = Fornecedore::where('entidade_id', '=', $entidade->empresa->id)->get();
        $classificacoes = TabelaTaxaReintegracaoAmortizacaoItem::get();
        
        $head = [
            "titulo" => __('messages.editar'),
            "descricao" => env('APP_NAME'),
            "fornecedores" => $fornecedores,
            "contas" => $contas,
            "classificacoes" => $classificacoes,
            "equipamento_activo" => $equipamento_activo,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.equipamentos-activos.edit', $head);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('activo contabilidade')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $user = auth()->user();

            if (!$user->can('editar todos') && !$user->can('editar exercicio')) {
                return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            }

            $request->validate([
                'nome' => 'required|string',
                'conta_id' => 'required|string',
            ]);
                 
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
            
            $equipamento_activo = EquipamentoActivo::findOrFail($id);
            
            if ($request->hasFile('anexo') && $request->file('anexo')->isValid()) {
                $requestImage = $request->anexo;
                $extension = $requestImage->extension();

                $imageName = md5($requestImage->getClientOriginalName() . strtotime("now") . "." . $extension);

                $request->anexo->move(public_path('images/imobilizados'), $imageName);
            } else {
                $imageName = $equipamento_activo->anexo;
            }
            
            if ($request->iva <= -1 and $request->iva >= 101) {
                return response()->json(['success' => false, 'message' => "Valor da Taxa do IVA Invalido!"], 404);
            }
            if ($request->iva_nd <= -1 and $request->iva_nd >= 101) {
                return response()->json(['success' => false, 'message' => "Valor da Taxa do IVA Não Dedutível Invalido!"], 404);
            }
            if ($request->iva_d <= -1 and $request->iva_d >= 101) {
                return response()->json(['success' => false, 'message' => "Valor da Taxa do IVA Dedutível Invalido!"], 404);
            }
       
            $iva_total = ($request->base_incidencia * $request->quantidade) * ($request->iva / 100);

            $iva_dedutivel =  $iva_total * ($request->iva_d / 100);
            $iva_n_dedutivel =  $iva_total * ($request->iva_nd / 100);

            $total = $iva_total + $request->base_incidencia;

            $valor_desconto = $total * ($request->desconto / 100);

            $custo_aquisicao = $total -  $valor_desconto;
            
            $subconta = Subconta::findOrFail($equipamento_activo->subconta_id);
            
            if($equipamento_activo->subconta_id != $request->conta_id) {
                $subconta = Subconta::findOrFail($request->conta_id);
                $total_subconta = Subconta::where('numero', 'like', $subconta . ".%")->where('entidade_id', $entidade->empresa->id)->count() + 1;
                $numero = $subconta->numero . ".{$total_subconta}";
                $subconta->numero = $numero;
            }
            
            $subconta->nome = $request->nome;
            $subconta->update();
                
            $equipamento_activo->nome = $request->nome;
            $equipamento_activo->numero_serie = $request->numero_serie;
            $equipamento_activo->codigo_barra = $request->codigo_barra;
            $equipamento_activo->quantidade = $request->quantidade;
            $equipamento_activo->data_aquisicao = $request->data_aquisicao;
            $equipamento_activo->data_utilizacao = $request->data_utilizacao;
            $equipamento_activo->classificacao_id = $request->classificacao_id;
            $equipamento_activo->status = $request->status;
            $equipamento_activo->staus_financeiro = $request->staus_financeiro;
            $equipamento_activo->base_incidencia = $request->base_incidencia;
            $equipamento_activo->iva = $request->iva;
            $equipamento_activo->iva_nd = $request->iva_nd;
            $equipamento_activo->iva_d = $request->iva_d;
            $equipamento_activo->desconto = $request->desconto;
            $equipamento_activo->fornecedor_id = $request->fornecedor_id;
            $equipamento_activo->numero_factura = $request->numero_factura;
            $equipamento_activo->descricao = $request->descricao;
            $equipamento_activo->anexo = $imageName;

            $equipamento_activo->total =  $total;
            $equipamento_activo->iva_total =  $iva_total;
            $equipamento_activo->valor_desconto = $valor_desconto;
            $equipamento_activo->iva_dedutivel = $iva_dedutivel;
            $equipamento_activo->iva_n_dedutivel = $iva_n_dedutivel;
            $equipamento_activo->custo_aquisicao = $custo_aquisicao;
            $equipamento_activo->valor_contabilistico = $custo_aquisicao;
            $equipamento_activo->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('activo contabilidade')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $equipamento_activo = EquipamentoActivo::findOrFail($id);
            $equipamento_activo->delete();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
           
        return response()->json(['success' => true, 'message' => "Dados Excluídos com sucesso!"], 200);
    }
}
