<?php

namespace App\Imports;

use App\Models\Conta;
use App\Models\ContaFornecedore;
use App\Models\Fornecedore;
use App\Models\Subconta;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class FornecedorImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);
        
        $code = uniqid(time());
        $nova_conta = "";
        
        if(strtolower($row["tipo"]) == "corrente"){
            $conta = Conta::where("conta", "32")->where("entidade_id", $entidade->empresa->id)->first();
            $serie =  "32.1.2.1.";
        }
        if(strtolower($row["tipo"]) == "imobilizado"){
            $conta = Conta::where("conta", "37")->where("entidade_id", $entidade->empresa->id)->first();
            $serie =  "37.1.1.";
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
        
        $fornecedor = Fornecedore::create([
            "nif" => $row["nif"],
            "code" => $code,
            "tipo_fornecedor" => $row["tipo"],
            "tipo_pessoa" => "JURIDICA",
            "conta" => $nova_conta,
            "nome" => $row["nome"],
            "pais" => $row["pais"] ?? NULL,
            "status" => true,
            "codigo_postal" => $row["codigo_postal"] ?? NULL,
            "localidade" => $row["localidade"] ?? NULL,
            "telefone" => $row["telefone"] ?? NULL,
            "telemovel" => $row["telemovel"] ?? NULL,
            "email" => $row["email"] ?? NULL,
            "website" => $row["website"] ?? NULL,
            "observacao" => $row["observacao"] ?? NULL,         
            "user_id" => Auth::user()->id,          
            "entidade_id" => $entidade->empresa->id,  
            "subconta_id" => $subconta->id,  
        ]);
        
        $saldo = ContaFornecedore::create([
            "user_id" => Auth::user()->id,
            "divida_corrente" => 0,
            "divida_vencida" => 0,
            "saldo" => 0,
            "fornecedor_id" => $fornecedor->id,
            "entidade_id" => $entidade->empresa->id,  
        ]);
            
        return $fornecedor;
    }
}
