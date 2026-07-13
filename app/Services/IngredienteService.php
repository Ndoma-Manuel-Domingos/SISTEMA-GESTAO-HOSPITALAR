<?php

namespace App\Services;

use App\Models\Ingrediente;
use App\Models\IngredienteMovimento;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class IngredienteService {
    
    public function addStock(string $ingredienteId, int $quantidade, $referencia = null)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        
        IngredienteMovimento::create([
            'ingrediente_id' => $ingredienteId,
            'tipo' => 'ENTRADA',
            'quantidade' => $quantidade,
            'referencia' => $referencia,
            'user_id' => Auth::user()->id,
            'entidade_id' => $entidade->empresa->id,
        ]);

        $this->recalculateStock($ingredienteId);
    }
    
    
    public function removeStock(string $ingredienteId, int $quantidade, $referencia = null)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        
        IngredienteMovimento::create([
            'ingrediente_id' => $ingredienteId,
            'tipo' => 'SAIDA',
            'quantidade' => $quantidade,
            'referencia' => $referencia,
            'user_id' => Auth::user()->id,
            'entidade_id' => $entidade->empresa->id,
        ]);

        $this->recalculateStock($ingredienteId);
    }


    private function recalculateStock(string $ingredienteId)
    {
        $entrada = IngredienteMovimento::where('ingrediente_id', $ingredienteId)
            ->where('tipo','ENTRADA')
            ->sum('quantidade');

        $saida = IngredienteMovimento::where('ingrediente_id', $ingredienteId)
            ->where('tipo','SAIDA')
            ->sum('quantidade');

        $stock = $entrada - $saida;

        Ingrediente::where('id',$ingredienteId)
            ->update(['quantidade_stock' => $stock]);
    }
    

}