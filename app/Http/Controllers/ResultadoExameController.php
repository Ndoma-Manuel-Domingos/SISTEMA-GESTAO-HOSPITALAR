<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ResultadoExame;
use App\Models\ResultadoExameSubParamentro;
use App\Models\ResultadoExameSubParamentroImagem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;


use PDF;
use phpseclib\Crypt\RSA;

class ResultadoExameController extends Controller
{

    use TraitChavesSaft;
    use TraitHelpers;

    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('laboratorio') && !$user->can('consultorio') && !$user->can('monitoramento consultorio') && !$user->can('monitoramento laboratorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $resultados = ResultadoExame::query()
            ->when($request->paciente_id, function ($query, $value) {
                $query->whereHas('exame', function ($q) use ($value) {
                    $q->where('paciente_id', $value);
                });
            })
            ->when($request->status, function ($query, $value) {
                $query->where('status', $value);
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('data_realizacao', '>=', $value);
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('data_realizacao', '<=', $value);
            })
            ->with(['exame.paciente'])
            ->where('entidade_id', $entidade->entidade_id)
            ->orderByDesc('id')
            ->get();

        $pacientes = Cliente::where("entidade_id", $entidade->entidade_id)->get();

        $head = [
            "titulo" => "Resultados Exames",
            "descricao" => env("APP_NAME"),
            "resultados" => $resultados,
            "pacientes" => $pacientes,
            "requests" => $request->all("paciente_id", "status", "data_inicio", "data_final"),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.resultados-exames.index", $head);
    }

    public function atualizarValor(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('laboratorio') && !$user->can('consultorio') && !$user->can('monitoramento consultorio') && !$user->can('monitoramento laboratorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $param = ResultadoExameSubParamentro::findOrFail($request->id);
            $param->valor = $request->valor;
            $param->save();

            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => 'Dados actualizada com sucesso!', 'success' => true], 200);
    }

    public function atualizarDescricao(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('laboratorio') && !$user->can('consultorio') && !$user->can('monitoramento consultorio') && !$user->can('monitoramento laboratorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $param = ResultadoExameSubParamentroImagem::findOrFail($request->id);
            $param->descricao = $request->descricao;
            $param->save();

            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => 'Dados actualizada com sucesso!', 'success' => true], 200);
    }

    public function uploadImagens(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('laboratorio') && !$user->can('consultorio') && !$user->can('monitoramento consultorio') && !$user->can('monitoramento laboratorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $param = ResultadoExameSubParamentroImagem::findOrFail($request->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $paths = [];

            if ($request->hasFile('imagens')) {
                foreach ($request->file('imagens') as $file) {
                    $name = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $destinationPath = public_path('resultados/exames');
                    // cria pasta se não existir
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }
                    $file->move($destinationPath, $name);
                    $paths[] = 'resultados/exames/' . $name;
                }
            }

            // exemplo: guardar JSON no banco
            $param->ficheiro = json_encode($paths);
            $param->save();

            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => 'Dados actualizada com sucesso!', 'success' => true], 200);
    }

    public function removerImagem(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('laboratorio') && !$user->can('consultorio') && !$user->can('monitoramento consultorio') && !$user->can('monitoramento laboratorio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $param = ResultadoExameSubParamentroImagem::findOrFail($request->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $imagens = json_decode($param->ficheiro ?? '[]', true);

            $imagens = array_values(array_filter($imagens, function ($img) use ($request) {
                return $img !== $request->imagem;
            }));

            // opcional: apagar ficheiro físico
            $path = public_path($request->imagem);
            if (file_exists($path)) {
                unlink($path);
            }

            $param->ficheiro = json_encode($imagens);
            $param->save();

            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => 'Dados actualizada com sucesso!', 'success' => true], 200);
    }
}
