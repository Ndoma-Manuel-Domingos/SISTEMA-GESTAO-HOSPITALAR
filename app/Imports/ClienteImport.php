<?php

namespace App\Imports;

use App\Http\Controllers\TraitHelpers;
use App\Models\Cliente;
use App\Models\Conta;
use App\Models\ContaCliente;
use App\Models\Subconta;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Spatie\Permission\Models\Role;

class ClienteImport implements ToModel, WithHeadingRow
{

    use TraitHelpers;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // Verificar se os campos esperados estão presentes
        if (!isset($row['nif']) || !isset($row['nome'])) {
            // Se os campos não estiverem presentes, lançar uma exceção ou logar o erro
            throw new \Exception('Os campos necessários não estão presentes na linha.');
        }

        $code = uniqid(time());
        $nova_conta = "";

        $conta = Conta::where('conta', '31')->where('entidade_id', $entidade->empresa->id)->first();

        $serie = "31.1.2.1.";

        $subc_ = Subconta::where('numero', 'like', "{$serie}%")->where('entidade_id', $entidade->empresa->id)->count() + 1;
        $nova_conta =  $serie . "{$subc_}";

        $subconta = Subconta::create([
            "entidade_id" => $entidade->empresa->id,
            "numero" => $nova_conta,
            "nome" => $row['nome'],
            "tipo_conta" => "M",
            "code" => $code,
            "status" => $conta->status,
            "conta_id" => $conta->id,
            "user_id" => Auth::user()->id,
        ]);

        $cliente = Cliente::create([
            "nif" => $row["nif"],
            "nome" => $row["nome"],
            "pais" => "AO",
            "code" => $code,
            "tipo_cliente" => "C",
            "gestor_conta" => NULL,
            "conta" => $nova_conta,
            "nome_do_pai" => NULL,
            "nome_da_mae" => NULL,
            "data_nascimento" => NULL,
            "genero" => $row["genero"],
            "estado_civil_id" => NULL,
            "seguradora_id" => NULL,
            "provincia_id" => NULL,
            "municipio_id" => NULL,
            "distrito_id" => NULL,
            "status" => true,
            "vencimento" => NULL,
            "gestor_conta" => NULL,
            "morada" => NULL,
            "codigo_postal" => $row["codigo_postal"] ?? NULL,
            "localidade" => $row["localidade"] ?? NULL,
            "telefone" => $row["telefone"] ?? NULL,
            "telemovel" => $row["telemovel"] ?? NULL,
            "email" => $email,
            "website" => NULL,
            "referencia_externa" => NULL,
            "observacao" => NULL,
            "subconta_id" => $subconta->id,
            "user_id" => Auth::user()->id,
            "entidade_id" => $entidade->empresa->id,
        ]);

        $saldo = ContaCliente::create([
            "user_id" => Auth::user()->id,
            "divida_corrente" => 0,
            "divida_vencida" => 0,
            "saldo" => 0,
            "cliente_id" => $cliente->id,
            "entidade_id" => $entidade->empresa->id,
        ]);

        return $cliente;
    }
}
