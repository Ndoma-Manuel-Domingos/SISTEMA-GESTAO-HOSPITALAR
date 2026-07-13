<?php

use App\Http\Controllers\app\AppController;
use App\Http\Controllers\app\ReceitaController;
use App\Http\Controllers\app\TipoPagamentoController;
use App\Http\Controllers\FacturaElectronicaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// routes/api.php

Route::post('/solicitarSerie', [FacturaElectronicaController::class, 'solicitarSerie']);
Route::post('/listarSeries', [FacturaElectronicaController::class, 'listarSeries']);
Route::post('/registrarFactura', [FacturaElectronicaController::class, 'registrarFactura']);
Route::post('/obterEstado', [FacturaElectronicaController::class, 'obterEstado']);
Route::post('/consultarFatura', [FacturaElectronicaController::class, 'consultarFatura']);
Route::post('/listarFacturas', [FacturaElectronicaController::class, 'listarFacturas']);
Route::post('/payload', [FacturaElectronicaController::class, 'payload']);
Route::post('/ConsultarNif', [FacturaElectronicaController::class, 'ConsultarNif']);

Route::post('/__invoke', [FacturaElectronicaController::class, '__invoke']);
Route::post('/__invoke_list', [FacturaElectronicaController::class, '__invoke_list']);
Route::post('/__invoke_status', [FacturaElectronicaController::class, '__invoke_status']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
