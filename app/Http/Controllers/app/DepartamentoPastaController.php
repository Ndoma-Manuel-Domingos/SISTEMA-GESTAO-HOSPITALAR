<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\ArquivoPasta;
use App\Models\Departamento;
use App\Models\DepartamentoPasta;
use App\Models\Pasta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

use Ramsey\Uuid\Uuid;

class DepartamentoPastaController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar departamento')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            DepartamentoPasta::create([
                'entidade_id' => $entidade->empresa->id,
                'nome' => $request->nome,
                'code' => Uuid::uuid4(),
                'type' => $request->type,
                'status' => $request->status,
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
    public function show($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar departamento')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $departamento = DepartamentoPasta::with(["pastas" => function ($query) {
            $query->whereNull("parent_id");
        }])
            ->with(["files" => function ($query) {
                $query->whereNull("parent_id");
            }])
            ->where("code", $id)
            ->where("entidade_id", $entidade->empresa->id)
            ->first();

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "departamento" => $departamento,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.departamentos-pastas.show', $head);
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

        if (!$user->can('editar todos') && !$user->can('editar departamento')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $departamento = DepartamentoPasta::findOrFail($id);

        return response()->json(['success' => true, 'data' => $departamento], 200);
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
        //
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar departamento')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $departamento = DepartamentoPasta::findOrFail($id);
            $departamento->update($request->all());

            $departamento->update();

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

        if (!$user->can('eliminar todos') && !$user->can('eliminar departamento')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $departamento = DepartamentoPasta::findOrFail($id);

            $pastas = Pasta::where('departamento_id', $departamento->id)->where('entidade_id', $user->entidade_id)->get();

            foreach ($pastas as $item) {
                Pasta::findOrFail($item->id)->delete();
            }

            $departamento->delete();

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

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function upload(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|max:10240', // max 10MB por arquivo
            'departamento_id' => 'required'
        ]);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        foreach ($request->file('files') as $file) {
            $requestImage = $file;
            // nome
            $imageName = $requestImage->getClientOriginalName();
            // extesion
            $extension = $requestImage->extension();
            // Tamanho em bytes
            $sizeInBytes = $requestImage->getSize();
            // Tamanho formatado (opcional)
            $sizeFormatted = $this->formatBytes($sizeInBytes);

            $destinationPath = public_path('images/documentos');

            $fullPath = $destinationPath . '/' . $imageName;

            // Verifica se o arquivo já existe
            if (!file_exists($fullPath)) {
                $requestImage->move(public_path('images/documentos'), $imageName);
            }

            ArquivoPasta::create([
                'nome' => $imageName,
                'size_bytes' => $sizeInBytes,
                'size_formatted' => $sizeFormatted,
                'extension' => $extension,
                'code' => Uuid::uuid4(),
                'parent_id' => NULL,
                'departamento_id' => $request->departamento_id,
                'entidade_id' => $entidade->empresa->id,
                'user_id' => Auth::user()->id,
            ]);
        }

        return response()->json([
            'message' => 'Arquivos enviados com sucesso!',
        ], 200);
    }

    public function upload_pastas(Request $request)
    {

        $request->validate([
            'files.*' => 'required|file|max:10240', // max 10MB por arquivo
            'pasta_id' => 'required'
        ]);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $pasta = Pasta::findOrFail($request->pasta_id);

        foreach ($request->file('files') as $file) {
            $requestImage = $file;
            // nome
            $imageName = $requestImage->getClientOriginalName();
            // extesion
            $extension = $requestImage->extension();
            // Tamanho em bytes
            $sizeInBytes = $requestImage->getSize();
            // Tamanho formatado (opcional)
            $sizeFormatted = $this->formatBytes($sizeInBytes);

            $destinationPath = public_path('images/documentos');

            $fullPath = $destinationPath . '/' . $imageName;

            // Verifica se o arquivo já existe
            if (!file_exists($fullPath)) {
                $requestImage->move(public_path('images/documentos'), $imageName);
            }

            ArquivoPasta::create([
                'nome' => $imageName,
                'size_bytes' => $sizeInBytes,
                'size_formatted' => $sizeFormatted,
                'extension' => $extension,
                'code' => Uuid::uuid4(),
                'parent_id' => $pasta->id,
                'departamento_id' => $pasta->departamento_id,
                'entidade_id' => $entidade->empresa->id,
                'user_id' => Auth::user()->id,
            ]);
        }

        return response()->json([
            'message' => 'Arquivos enviados com sucesso!',
        ], 200);
    }
}
