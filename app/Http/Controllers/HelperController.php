<?php

namespace App\Http\Controllers;

use App\Models\Camara;
use App\Models\Cargo;
use App\Models\Contrapartida;
use App\Models\Contrato;
use App\Models\Departamento;
use App\Models\Distrito;
use App\Models\Exercicio;
use App\Models\Funcionario;
use App\Models\Gaveta;
use App\Models\QuartoTarefario;
use App\Models\Quarto;
use App\Models\Municipio;
use App\Models\PacoteSalarial;
use App\Models\Periodo;
use App\Models\Produto;
use App\Models\Provincia;
use App\Models\TipoCredito;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HelperController extends Controller
{
    //

    public function gerar_factura($request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
    }

    public function getDetalheTarefario($id)
    {
        $quarto = Produto::findOrFail($id);

        return $quarto;
    }

    public function getFuncionarioContrato($id)
    {
        $contrato = Contrato::findOrFail($id);
        $funcionarios = Funcionario::where('id', $contrato->funcionario_id)->get();

        $option = "";
        foreach ($funcionarios as $item) {
            $option .= '<option value="' . $item->id . '">' . $item->nome . '<option>';
        }

        return $option;
    }

    public function getGavetaCamara($id)
    {
        $camara = Camara::findOrFail($id);
        $gavetas = Gaveta::where("ocupacao", 0)->where('camara_id', $camara->id)->get();

        $option = "<option value=''>Selecione Gavetas</option>";
        foreach ($gavetas as $item) {
            $option .= '<option value="' . $item->id . '">' . $item->nome . '<option>';
        }
        return $option;
    }

    public function getTarefariosQuarto($id)
    {
        $quarto = Quarto::findOrFail($id);
        $tarefarios = QuartoTarefario::with(['tarefario'])->where('quarto_id', $quarto->id)->get();

        $option = "<option value=''>Selecione Tarifários</option>";
        foreach ($tarefarios as $item) {
            $option .= '<option value="' . $item->tarefario->id . '">' . $item->tarefario->nome . '<option>';
        }
        
        return $option;
    }

    public function getTarifarios($id)
    {
        $quarto = Quarto::findOrFail($id);
        $tarefarios = QuartoTarefario::with(['tarefario'])->where('quarto_id', $quarto->id)->get();
        
    
        return response()->json($tarefarios);
    }

    public function getCategoriaCargos($id)
    {
        $cargo = Cargo::findOrFail($id);
        $categorias = PacoteSalarial::with(['categoria'])->where('cargo_id', $cargo->id)->get();

        $option = "<option value=''>Selecione Categeria</option>";
        foreach ($categorias as $item) {
            $option .= '<option value="' . $item->categoria->id . '">' . $item->categoria->nome . '<option>';
        }
        return $option;
    }

    public function getPeriodos($id)
    {
        $exercicio = Exercicio::findOrFail($id);
        $peridos = Periodo::where('exercicio_id', $exercicio->id)->get();

        $option = "<option value=''>Selecione o Período</option>";
        foreach ($peridos as $item) {
            $option .= '<option value="' . $item->id . '">' . $item->nome . '<option>';
        }
        return $option;
    }

    public function getCargos($id)
    {
        $departamento = Departamento::findOrFail($id);
        $cargos = Cargo::where('departamento_id', $departamento->id)->get();

        $option = "<option value=''>Selecione o Cargo</option>";
        foreach ($cargos as $item) {
            $option .= '<option value="' . $item->id . '">' . $item->nome . '<option>';
        }
        return $option;
    }

    public function getSalarioCargo($id)
    {
        $cargo = Cargo::findOrFail($id);

        return response()->json([
            "status" => 200,
            "cargo" => $cargo,
        ]);
    }

    public function getContrapartidas($id)
    {
        $tipo_credito = TipoCredito::findOrFail($id);
        $contrapartidas = Contrapartida::with(['subconta'])->where('tipo_credito_id', $tipo_credito->id)->get();

        $option = "<option value=''>Selecione a Conta</option>";
        foreach ($contrapartidas as $item) {
            $option .= '<option value="' . $item->subconta->id . '">' . $item->subconta->numero . ' - ' . $item->subconta->nome . '<option>';
        }
        return $option;
    }

    public function getMunicipio($id)
    {
        $states = Provincia::findOrFail($id);
        $municipios = Municipio::where('provincia_id', $states->id)->get();

        $option = "<option value=''>Selecione a Munícipios</option>";
        foreach ($municipios as $state) {
            $option .= '<option value="' . $state->id . '">' . $state->nome . '<option>';
        }
        return $option;
    }

    public function getDistritos($id)
    {
        $municipio = Municipio::findOrFail($id);
        $distritos = Distrito::where('municipio_id', $municipio->id)->get();

        $option = "<option value=''>Selecione Distritos</option>";
        foreach ($distritos as $distrito) {
            $option .= '<option value="' . $distrito->id . '">' . $distrito->nome . '<option>';
        }
        return $option;
    }
}
