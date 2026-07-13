<?php

namespace App\Http\Controllers;

use App\Models\Anatomia\Doenca;
use App\Models\Anatomia\ParteCorpo;
use App\Models\Entidade;
use App\Models\Exame;
use App\Models\Plano;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnatomiaController extends Controller
{
    public function index()
    {
        $head = [
            "titulo" => "Anatomia",
            "descricao" => env("APP_NAME"),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("anatomia.index", $head);
    }

    public function detalhes(Request $request)
    {
        $parte = ParteCorpo::where('codigo', $request->codigo)->first();

        if (!$parte) {
            return response()->json([
                'success' => false
            ]);
        }

        $doencas = DB::table('parte_corpo_doencas')->where('parte_corpo_id', $parte->id)->pluck('doenca_id');
        $exames = DB::table('parte_corpo_exames')->where('parte_corpo_id', $parte->id)->pluck('exame_id');

        return response()->json([
            'success' => true,
            'parte' => $parte,
            'doencas' => Doenca::whereIn('id', $doencas)->get(),
            'exames' => Exame::whereIn('id', $exames)->get()
        ]);
    }
}
