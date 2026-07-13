<?php

use App\Http\Controllers\AdminDashboardFinanceiroController;
use App\Http\Controllers\app\EmpresaController;
use App\Http\Controllers\FuncaoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InscricaoEmpresaController;
use App\Http\Controllers\MembroController;
use App\Http\Controllers\OperadorController;
use App\Http\Controllers\PlanoController;
use App\Http\Controllers\ProfissaoController;
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


Route::get('/configuracao-administrador', [HomeController::class, 'configuracao'])->name('configuracao-admin');
Route::post('/configuracao-administrador', [HomeController::class, 'configuracao_post'])->name('configuracao-admin-post');

Route::resource('/empresas', EmpresaController::class);
Route::get('/nossos-utilizadores', [EmpresaController::class, 'home'])->name('nossos-utilizadores');
Route::get('/nossos-utilizadores-pdf', [EmpresaController::class, 'nosso_utilizadores_pdf'])->name('nosso-utilizadores-pdf');
Route::get('/nossas-empresas-pdf', [EmpresaController::class, 'nosso_empresas_pdf'])->name('nossa-empresas-pdf');

Route::get('/empresa/create-controle', [EmpresaController::class, 'createControle'])->name('empresas.create-controle');
Route::post('/empresa/create-controle', [EmpresaController::class, 'storeControle'])->name('empresas.create-controle-store');

Route::get('/empresas/{id}/mudar-controlo', [EmpresaController::class, 'controlo'])->name('empresas.controlo');
Route::get('/empresas/{id}/desactivar', [EmpresaController::class, 'desactivar'])->name('empresas.desactivar');
Route::get('/empresas/{id}/actvar', [EmpresaController::class, 'actvar'])->name('empresas.actvar');
Route::get('/empresas/{id}/destroy', [EmpresaController::class, 'destroy'])->name('empresas.destroys');
Route::get('/empresas/{id}/actualizar-modulos', [EmpresaController::class, 'actualizar_modulos'])->name('empresas.actualizar-modulos');
Route::post('/empresas/actualizar-modulos', [EmpresaController::class, 'actualizar_modulos_post'])->name('empresas.actualizar-modulos-post');
Route::get('/empresas/exportar-fluxo-caixa/{id}', [EmpresaController::class, 'exportarFLuxoCaixa'])->name('empresas.exportar-fluxo-caixa');
Route::get('/empresas/exportar-fluxo-loja/{empresa}/{loja}', [EmpresaController::class, 'exportarFLuxoLoja'])->name('empresas.exportar-fluxo-loja');

Route::post('/empresas-caixa/store', [EmpresaController::class, 'storeCaixa']);
Route::get('/empresas/{empresa}/lojas/{loja}', [EmpresaController::class, 'showLoja'])->name('empresas.loja-detalhes');

Route::resource('inscricoes', InscricaoEmpresaController::class);
Route::resource('/operadores', OperadorController::class);

Route::resource('profissoes', ProfissaoController::class);
Route::resource('funcoes', FuncaoController::class);
Route::resource('membros', MembroController::class);
Route::get('/membro/buscar', [MembroController::class, 'buscarPorBilhete']);
Route::post('/caixa/toggle-status', [MembroController::class, 'toggleStatus']);
Route::post('/membro/remover-empresa', [MembroController::class, 'removerEmpresa']);
Route::post('/membro/add-empresa', [MembroController::class, 'addMembro']);

Route::resource('planos', PlanoController::class);
Route::post('/empresa/{id}/associar-plano', [PlanoController::class, 'associarPlano'])->name('empresa.associar.plano');
Route::post('/empresa/{id}/bloquear', [PlanoController::class, 'bloquearEmpresa'])->name('empresa.bloquear');
Route::post('/empresa/{id}/remover-plano', [PlanoController::class, 'removerPlano'])->name('empresa.remover.plano');

Route::get('/empresas-dashboard-financeiro-cotas', [AdminDashboardFinanceiroController::class, 'indexCota'])->name('empresas-dashboard-financeiro-cotas.index');
Route::get('/empresas-dashboard-financeiro-cotas-imprimir', [AdminDashboardFinanceiroController::class, 'indexCotaImprimir'])->name('empresas-dashboard-financeiro-cotas.imprimir');

Route::get('/empresas-dashboard-financeiro', [AdminDashboardFinanceiroController::class, 'index'])->name('empresas-dashboard-financeiro.index');
Route::get('/empresas-dashboard-financeiro/dados', [AdminDashboardFinanceiroController::class, 'dados']);
Route::get('/empresas-dashboard-financeiro/pdf', [AdminDashboardFinanceiroController::class, 'pdf'])->name('empresas-dashboard.financeiro.pdf');
Route::post('/empresas-mensalidade/pagar', [AdminDashboardFinanceiroController::class, 'pagar']);
Route::get('/mensalidade/{id}/pagar', [AdminDashboardFinanceiroController::class, 'createPagamento'])->name('mensalidade.pagar');
Route::post('/mensalidade/pagamento/store', [AdminDashboardFinanceiroController::class, 'storePagamento'])->name('mensalidade.pagamento.store');
Route::get('/mensalidade-cotas/{id}/pagar', [AdminDashboardFinanceiroController::class, 'createPagamentoCota'])->name('mensalidade-cotas.pagar');
Route::post('/mensalidade-cotas/pagamento/store', [AdminDashboardFinanceiroController::class, 'storePagamentoCota'])->name('mensalidade-cotas.pagamento.store');
Route::get('/mensalidade-cotas/comprovativo/{id}', [AdminDashboardFinanceiroController::class, 'gerarComprovativo'])->name('mensalidade-cotas.comprovativo');

Route::post('/financeiro/gerar-mensalidades', [AdminDashboardFinanceiroController::class, 'gerarMensalidades'])->name('financeiro.gerar');
Route::post('/financeiro/calcular-juros', [AdminDashboardFinanceiroController::class, 'calcularJuros'])->name('financeiro.juros');
