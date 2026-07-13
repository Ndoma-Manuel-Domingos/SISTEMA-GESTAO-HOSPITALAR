<?php

namespace App\Imports;

use App\Http\Controllers\TraitHelpers;
use App\Models\Conta;
use App\Models\Funcionario;
use App\Models\Subconta;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Spatie\Permission\Models\Role;

class FuncionarioImport implements ToModel, WithHeadingRow
{

    use TraitHelpers;
    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);
        
        // Verificar se os campos esperados estão presentes
        if (!isset($row["nif"]) || !isset($row["nome"])) {
            // Se os campos não estiverem presentes, lançar uma exceção ou logar o erro
            throw new \Exception("Os campos necessários não estão presentes na linha.");
        }
        
        $code = uniqid(time());
        $nova_conta = "";
         
        $conta = Conta::where("conta", "36")->where("entidade_id", $entidade->empresa->id)->first();
       
        if($row["categoria"] == "Orgão Sociais"){
            $serie =  "36.1.1.";
        }
        if($row["categoria"] == "Empregados"){
            $serie =  "36.1.2.";
        }
        if($row["categoria"] == "Pessoal"){
            $serie =  "36.1.2.";
        }
        
        $subc_ = Subconta::where("numero", "like", "{$serie}%")->where("entidade_id", $entidade->empresa->id)->count() + 1;
        $nova_conta =  $serie . "{$subc_}";
        
        $subconta = Subconta::create([
            "entidade_id" => $entidade->empresa->id, 
            "numero" => $nova_conta,
            "nome" => $row["nome"],
            "tipo_conta" => "M",
            "code" => $code,
            "status" => $conta->status,
            "conta_id" => $conta->id,
            "user_id" => Auth::user()->id,
        ]);
        
        $role = Role::where("name", "{$entidade->empresa->sigla} - Padrao")->first();
        $level = 1;
        
        $email = $row["email"] ?? $this->gerarEmailCliente($row["nome"]);
        
        $user = User::create([
            "name" => $row["nome"],
            "email" => $email,
            "is_admin" => false,
            "type_user" => "Funcionario",
            "status" => true,
            "level" => $level,
            "login_access" => false,
            "password" => Hash::make($row["nif"]),
            "entidade_id" => $entidade->empresa->id,
        ]);
    
        $user->assignRole($role);
        
        $funcionario = Funcionario::create([
            "numero_mecanografico" => $row["numero_mecanografico"],
            "nif" => $row["nif"],
            "nome" => $row["nome"],
            "code" => $code,
            "conta" => $nova_conta,
            "pais" => "AO",
            "nome_do_pai" => NULL,
            "nome_da_mae" => NULL,
            "data_nascimento" => NULL,
            "genero" => $row["genero"] ?? NULL,
            "estado_civil_id" => NULL,
            "gestor_conta" => $user->id,
            "seguradora_id" => NULL,
            "provincia_id" => NULL,
            "municipio_id" => NULL,
            "distrito_id" => NULL,
            "subconta_id" => $subconta->id,   
            "status" => true,
            "vencimento" => NULL,
            "morada" => NULL,
            "codigo_postal" => $row["codigo_postal"] ?? NULL,
            "localidade" => $row["localidade"] ?? NULL,
            "telefone" => $row["telefone"] ?? NULL,
            "telemovel" => $row["telemovel"] ?? NULL,
            "email" => $email,
            "website" => NULL,
            "referencia_externa" => NULL,
            "observacao" => NULL,
            "user_id" => Auth::user()->id,
            "entidade_id" => $entidade->empresa->id,
        ]);
        
        return $funcionario; 
    }
}
