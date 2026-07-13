<?php

use App\Http\Controllers\app\ProdutoController;
use App\Http\Controllers\IngredienteController;
use App\Http\Controllers\ProducaoController;
use App\Http\Controllers\ProdutoReceitaController;
use App\Http\Controllers\UnidadeMedidaController;
use App\Models\Caixa;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::resource('/producao', ProducaoController::class);
Route::post('/producao-mudaStatus', [ProducaoController::class, 'mudaStatus']);

Route::get('/dashboard/producao-dia', [ProducaoController::class,'producaoDia']);

Route::get('/produto/{id}/receitas', [ProducaoController::class, 'receitas']);
Route::get('/receitas/{id}/items', [ProducaoController::class, 'receitaItems']);

Route::get('/materia-primas', [ProdutoController::class, 'materia_primas'])->name('produtos.materia_primas');

Route::resource('/unidades_medida', UnidadeMedidaController::class);
Route::resource('/ingredientes', IngredienteController::class);

Route::resource('/produtos-receitas', ProdutoReceitaController::class);

Route::get('/api/verificar-caixa', function(){

    $caixa = Caixa::where('user_open_id', auth()->id())->where('status_admin', 'liberado')->where('active', true)->first();
    
    return response()->json([
        'bloqueado' => $caixa ? true : false
    ]);
    
});
