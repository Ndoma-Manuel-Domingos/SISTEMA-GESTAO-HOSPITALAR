<?php

use App\Http\Controllers\AnatomiaController;
use App\Http\Controllers\app\AtendimentoController;
use App\Http\Controllers\app\CamaController;
use App\Http\Controllers\app\CamaraController;
use App\Http\Controllers\app\CatalogoExameController;
use App\Http\Controllers\app\CIDSController;
use App\Http\Controllers\app\EnfermeiroController;
use App\Http\Controllers\app\EspecialidadeController;
use App\Http\Controllers\app\GavetaController;
use App\Http\Controllers\app\GrupoController;
use App\Http\Controllers\app\ImpostoController;
use App\Http\Controllers\app\InternamentoController;
use App\Http\Controllers\app\MedicoController;
use App\Http\Controllers\app\MorgueController;
use App\Http\Controllers\app\ObitoController;
use App\Http\Controllers\app\ParamentroExameController;
use App\Http\Controllers\app\SubParamentroExameController;
use App\Http\Controllers\app\ParamentroConsultaController;
use App\Http\Controllers\app\PlanoTratamentoController;
use App\Http\Controllers\app\PrioridadeController;
use App\Http\Controllers\app\SeguradoraController;
use App\Http\Controllers\app\SeguradoraPlanoBeneficiadorController;
use App\Http\Controllers\app\SeguradoraPlanoCoberturaController;
use App\Http\Controllers\app\SeguradoraPlanoController;
use App\Http\Controllers\app\TecnicoController;
use App\Http\Controllers\app\TipoAtendimentoController;
use App\Http\Controllers\app\TipoOcorrenciaController;
use App\Http\Controllers\app\TipoPostoController;
use App\Http\Controllers\ConstruirEquipaController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\ConsultorioController;
use App\Http\Controllers\ContaHospitalarController;
use App\Http\Controllers\DisponibilidadeMedicaController;
use App\Http\Controllers\ExameController;
use App\Http\Controllers\FechoContaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaboratorioController;
use App\Http\Controllers\ResultadoExameController;
use App\Http\Controllers\SolicitacaoMedicaController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TriagemController;
use Illuminate\Support\Facades\Route;


Route::get('/dashboard-hospital', [HomeController::class, 'dashboardHospital'])->name('dashboard-hospital');

Route::prefix('enferms')->group(function () {
    Route::resource('/enfermeiros', EnfermeiroController::class);
});

Route::prefix('medics')->group(function () {
    Route::resource('/medicos', MedicoController::class);
});

Route::prefix('triangs')->group(function () {
    Route::resource('/triagens', TriagemController::class);
    Route::get('/triagens/{id}/imprimir', [TriagemController::class, 'imprimir_ficha'])->name('triangs.triagens-imprimir');
});

Route::get('/triagens-relatorios', [TriagemController::class, 'dashboard']);

Route::prefix('consults')->group(function () {
    Route::resource('/consultas', ConsultaController::class);
});

Route::prefix('exams')->group(function () {
    Route::resource('/exames', ExameController::class);
});

Route::prefix('disponibilidade')->group(function () {
    Route::resource('/disponibilidades-medica', DisponibilidadeMedicaController::class);
});
Route::get('/agendas-medicas', [DisponibilidadeMedicaController::class, 'agenda'])->name('agendas-medicas.index');
Route::get('/agendas-medicas-calendario', [DisponibilidadeMedicaController::class, 'calendario'])->name('agendas-medicas-calendario.index');
Route::get('/disponibilidades-eventos', [DisponibilidadeMedicaController::class, 'eventos'])->name('disponibilidades-eventos');
Route::put('/disponibilidades-drop/{id}', [DisponibilidadeMedicaController::class, 'drop'])->name('disponibilidades-drop');

Route::resource('solicitacoes-medicas', SolicitacaoMedicaController::class);
Route::post('/adicionar-item-solicitacoes-medicas', [SolicitacaoMedicaController::class, 'adicionarItems'])->name('adicionar.item-solicitacoes-medicas');
Route::delete('/remover-item-solicitacoes-medicas/{id}', [SolicitacaoMedicaController::class, 'deleteItems'])->name('remover-items-solicitacoes-medicas');
Route::post('/confirmar-solicitacoes-medicas', [SolicitacaoMedicaController::class, 'confirmarItem'])->name('confirmar.items-solicitacoes-medicas');
Route::get('/pdf-solicitacoes-medicas', [SolicitacaoMedicaController::class, 'export'])->name('pdf.solicitacoes-medicas');

Route::get('/carregar/listar-itens', [SolicitacaoMedicaController::class, 'carregarItens']);

Route::prefix('consultor')->group(function () {
    Route::resource('/consultorio', ConsultorioController::class);
    Route::get('/consultorio/verificar', [ConsultorioController::class, 'verificarPaciente'])->name('consultorio-verificar');
});

Route::prefix('laborator')->group(function () {
    Route::get('/laboratorio/verificar', [LaboratorioController::class, 'verificarPaciente'])->name('laboratorio-verificar');
    Route::resource('/laboratorio', LaboratorioController::class);
});

Route::prefix('anatomia')->group(function () {
    Route::get('/', [AnatomiaController::class, 'index'])->name('anatomia.index');
    Route::get('/detalhes', [AnatomiaController::class, 'detalhes'])->name('anatomia.detalhes');
});

Route::resource('/atendimentos', AtendimentoController::class);
Route::get('/atendimento-verificar-solicitacoes', [AtendimentoController::class, 'verificarSolicitacoes'])->name('atendimentos-verificar');

Route::get('/atendimentos-relatorios', [AtendimentoController::class, 'dashboard']);

Route::get('/atendimentos-imprimir', [AtendimentoController::class, 'imprimir'])->name('atendimentos.imprimir');
Route::resource('/planos-tratamentos', PlanoTratamentoController::class);
Route::post('/planos-tratamentos/cancelar', [PlanoTratamentoController::class, 'cancelar'])->name('planos-tratamentos.cancelar');
Route::post('/planos-tratamentos/suspender', [PlanoTratamentoController::class, 'suspender'])->name('planos-tratamentos.suspender');
Route::post('/planos-tratamentos/finalizar', [PlanoTratamentoController::class, 'finalizar'])->name('planos-tratamentos.finalizar');
Route::post('/planos-tratamentos/{id}/resultado', [PlanoTratamentoController::class, 'lancarResultado'])->name('planos-tratamentos.lancar_resultado');
Route::get('/planos-tratamentos/{id}/imprimir', [PlanoTratamentoController::class, 'imprimir'])->name('planos-tratamentos.lancar_imprimir');
Route::get('/planos-tratamentos-imprimir', [PlanoTratamentoController::class, 'imprimir_all'])->name('planos-tratamentos.imprimir-all');

Route::resource('/resultados-exames', ResultadoExameController::class);
Route::get('/resultados-exames-imprimir', [ResultadoExameController::class, 'imprimir_all'])->name('resultados-exames.imprimir-all');


Route::resource('/obitos', ObitoController::class);
Route::get('/obitos/comunicar-familiares/{id}', [ObitoController::class, 'comunicar_familiares'])->name('obito.comunicar_familiares');
Route::get('/obitos/entreguar-a-morgue/{id}', [ObitoController::class, 'entregar_a_morte'])->name('obito.entreguar-a-morgue');
Route::get('/obitos/entreguar-a-familiares/{id}', [ObitoController::class, 'entreguar_a_familiares'])->name('obito.entreguar-a-familiares');


Route::resource('/internamentos', InternamentoController::class);
Route::post('/internamentos/actualizar-evolucao-media', [InternamentoController::class, 'actualizar_evolucao_media'])->name('internamentos.actualizar-evolucao-media');
Route::post('/internamentos/dar-alta', [InternamentoController::class, 'dar_alta'])->name('internamentos.dar-alta');
Route::post('/internamentos/transferir-paciente', [InternamentoController::class, 'transferencia_paciente'])->name('internamentos.transferir-paciente');
Route::post('/internamentos/definir-obito', [InternamentoController::class, 'obito_paciente'])->name('internamentos.definir-obito');

Route::get('/internamentos/{id}/imprimir-evolucao-medica', [InternamentoController::class, 'lista_evolucao_medica'])->name('internamentos.imprimir-evolucao-medica');
Route::get('/internamentos/{id}/imprimir-lista-exames', [InternamentoController::class, 'lista_exames'])->name('internamentos.imprimir-lista-exames');
Route::get('/internamentos/{id}/imprimir-lista-consultas', [InternamentoController::class, 'lista_consultas'])->name('internamentos.imprimir-lista-consultas');
Route::get('/internamentos/{id}/imprimir-lista-receitas', [InternamentoController::class, 'lista_receitas'])->name('internamentos.imprimir-lista-receitas');
Route::get('/internamentos/{id}/imprimir-plano-medico-internamento', [InternamentoController::class, 'plano_medico_internamento_export'])->name('internamentos.imprimir-plano-medico-internamento');
Route::get('/internamentos/{id}/imprimir', [InternamentoController::class, 'imprimir'])->name('internamentos.imprimir');


Route::get('/atendimentos/atender-paciente/{id}', [AtendimentoController::class, 'atender_paciente'])->name('atendimentos.atender-paciente');
Route::get('/atendimentos/definir-atendido-paciente/{id}', [AtendimentoController::class, 'definir_atendido_paciente'])->name('atendimentos.definir-atendido-paciente');

Route::post('/sub-parametros-exames/atualizar-descricao', [ResultadoExameController::class, 'atualizarDescricao']);
Route::post('/sub-parametros-exames/atualizar-valor', [ResultadoExameController::class, 'atualizarValor']);
Route::post('/sub-parametros-exames/upload-imagens', [ResultadoExameController::class, 'uploadImagens']);
Route::post('/sub-parametros-exames/remover-imagem', [ResultadoExameController::class, 'removerImagem']);

Route::resource('/tipos-atendimentos', TipoAtendimentoController::class);
Route::resource('/sub-parametros-exames', SubParamentroExameController::class);
Route::resource('/parametros-exames', ParamentroExameController::class);
Route::get('/parametros/exame/{id}', [ParamentroExameController::class, 'buscarPorExame']);
Route::get('/pdf-paramentros-exames', [SubParamentroExameController::class, 'export'])->name('pdf-paramentros-exames');
Route::resource('/cids', CIDSController::class);
Route::resource('/prioridades', PrioridadeController::class);
Route::resource('/tipos-postos', TipoPostoController::class);
Route::resource('/tipos-ocorrencias', TipoOcorrenciaController::class);
Route::resource('/impostos', ImpostoController::class);

Route::resource('/especialidades', EspecialidadeController::class);
Route::resource('/tecnicos', TecnicoController::class);
Route::resource('/grupos', GrupoController::class);
Route::post('/agrupar-mesas', [GrupoController::class, 'agrupar_mesas'])->name('agrupar_mesas');

Route::get('/tickets-gerar-senha', [TicketController::class, 'gerar_senha'])->name('tickets-gerar-senha');    // Geração/cliente
Route::get('/tickets-display', [TicketController::class, 'show_display'])->name('tickets-display');   // mostrador público

Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
Route::post('/tickets/call-next', [TicketController::class, 'callNext']);
Route::get('/tickets/latest-called', [TicketController::class, 'latestCalled']);
Route::get('/tickets/pending-count', [TicketController::class, 'pendingCount']);

// consultas
Route::resource('/paramentros-consultas', ParamentroConsultaController::class);
Route::get('/pdf-paramentros-consultas', [ParamentroConsultaController::class, 'export'])->name('pdf-paramentros-consultas');

Route::get('/consultas/medicos-disponiveis', [ConsultaController::class, 'medicosDisponiveis']);
Route::get('/consultas/horarios-disponiveis', [ConsultaController::class, 'horariosDisponiveis']);

Route::post('/adicionar-items-consultas', [ConsultaController::class, 'adicionarItems'])->name('adicionar_items_consultas');
Route::delete('/adicionar-items-consultas/{id}', [ConsultaController::class, 'deleteItems'])->name('adicionar-items-consultas');
Route::post('/actualizar-items-consultas', [ConsultaController::class, 'actualizarItems'])->name('actualizar-items-consultas');
Route::delete('/actualizar-items-consultas/{id}/{consulta_id}', [ConsultaController::class, 'deleteActualizarItems'])->name('actualizar-items-consultas-delete');
Route::get('/consultas/{id}/imprimir', [ConsultaController::class, 'imprimir'])->name('consultas-imprimir');
Route::get('/consultas/{id}/imprimir-individual', [ConsultaController::class, 'imprimir_individual'])->name('consultas-imprimir-individual');
Route::get('/consultas-imprimir', [ConsultaController::class, 'imprimir_all'])->name('consultas-imprimir-all');

Route::get('/consultas/cancelar/{id}', [ConsultaController::class, 'cancelar_consulta'])->name('cancelar_consulta');
Route::get('/consultas/dar-tratamento/{id}', [ConsultaController::class, 'dar_tratamento_consulta'])->name('consulta-dar-tratamento');
Route::get('/consultas/atestado-medico/{id}', [ConsultaController::class, 'atestado_medico'])->name('consulta-atestado-medico');
Route::get('/consultas/receita-medica/{id}', [ConsultaController::class, 'receita_medico'])->name('consulta-receita-medica');
Route::post('/consultas/receita-medica', [ConsultaController::class, 'receita_medico_post'])->name('consulta-receita-medica-post');
Route::get('/consultas/receitas-medica/{id}/imprimir', [ConsultaController::class, 'receitas_imprimir'])->name('consulta-receitas-medico-imprimir');

// exames

Route::get('/exames/{id}/imprimir', [ExameController::class, 'imprimir'])->name('exames-imprimir');
Route::get('/exames/{id}/imprimir-individual', [ExameController::class, 'imprimir_individual'])->name('exames-imprimir-individual');
Route::get('/exames-atendimentos/{id}/imprimir', [ExameController::class, 'imprimir_exames_atendimentos'])->name('exames-atendimento-imprimir');
Route::get('/exames-mprimir', [ExameController::class, 'imprimir_all'])->name('exames-imprimir-all');

Route::get('/exames/cancelar/{id}', [ExameController::class, 'cancelar_exame'])->name('cancelar_exames');
Route::post('/exames/envair-resultados/{id}', [LaboratorioController::class, 'enviar_resultados'])->name('exames.enviar-resultados');

Route::post('/adicionar-items-exames-post', [ExameController::class, 'adicionarItems'])->name('adicionar_items_exames-post');
Route::delete('/adicionar-items-exames/{id}', [ExameController::class, 'deleteItems'])->name('adicionar-items-exames-delete');
Route::post('/actualizar-items-exames', [ExameController::class, 'actualizarItems'])->name('actualizar-items-exames');
Route::delete('/actualizar-items-exames/{id}/{exame_id}', [ExameController::class, 'deleteActualizarItems'])->name('actualizar-items-exames-delete');

Route::resource('/equipas', ConstruirEquipaController::class);
Route::post('/equipes/{id}/definir-horario', [ConstruirEquipaController::class, 'definirHorario']);
Route::delete('/medico-equipa/{id}', [ConstruirEquipaController::class, 'destroy_medico_equipa'])->name('medico-equipa.destroy');
Route::post('/adicionar-membros-equipas', [ConstruirEquipaController::class, 'adicionarMembros'])->name('adicionar_membros_equipas');
Route::get('/escalas', [ConstruirEquipaController::class, 'buscarEscala']);
Route::post('/escalas', [ConstruirEquipaController::class, 'create_horario'])->name('escalas.create_horario');
Route::post('/escalas-recursos', [ConstruirEquipaController::class, 'create_recursos'])->name('escalas.create_recursos');
Route::put('/escalas/{id}', [ConstruirEquipaController::class, 'update_horario']);
Route::delete('/escalas/{id}', [ConstruirEquipaController::class, 'destroy_horario'])->name('escalas.destroy');
Route::delete('/escalas-recursos/{id}', [ConstruirEquipaController::class, 'destroy_recursos'])->name('escalas.destroy-recursos');

Route::delete('/adicionar-membros-equipas/{id}', [ConstruirEquipaController::class, 'deleteMembros'])->name('adicionar-membros-equipas');
Route::post('/actualizar-membros-equipas', [ConstruirEquipaController::class, 'actualizarMembros'])->name('actualizar-membros-equipas');
Route::delete('/actualizar-membros-equipas/{id}/{equipa_id}', [ConstruirEquipaController::class, 'deleteActualizarMembros'])->name('actualizar-membros-equipas-delete');


Route::resource('/catalogo-exames', CatalogoExameController::class);
Route::resource('/gavetas', GavetaController::class);
Route::resource('/camaras', CamaraController::class);
Route::get('/visualizacao-gavetas-camaras', [CamaraController::class, "visualizacao_gavetas_camaras"])->name("camaras.visualizacao-gavetas-camaras");

Route::resource('/morgues', MorgueController::class);
Route::get('/morgues/liberacao/{id}', [MorgueController::class, "liberacao_morgue"])->name("morgues.liberacao");
Route::post('/morgues/liberacao', [MorgueController::class, "liberacao_morgue_store"])->name("morgues.liberacao-store");
Route::get('/morgues/entregar-funeraria/{id}', [MorgueController::class, "entregar_funeraria"])->name("morgues.entregar-funeraria");

Route::get('/morgues/liberacao/{id}/imprimir', [MorgueController::class, "morgue_liberacao_imprimir"])->name("morgues.liberacao-imprimir");
Route::get('/morgues/{id}/imprimir', [MorgueController::class, "morgue_imprimir"])->name("morgues.imprimir");

Route::resource('/camas', CamaController::class);

Route::get('assign/{seguradora}/{mes}/{ano}', [FechoContaController::class, 'mais_detalhe'])->name('fechos-contas-seguradora.show');
Route::get('imprimir/{seguradora}/fecho-contas', [FechoContaController::class, 'export'])->name('fechos-contas-seguradora.imprimir');
Route::resource('/fechos-contas', FechoContaController::class);

Route::resource('/contas-hospitalares', ContaHospitalarController::class);
Route::post('/{id}/item', [ContaHospitalarController::class, 'adicionarItem'])->name('item.add');
Route::delete('/item/{id}', [ContaHospitalarController::class, 'removerItem'])->name('item.remove');
Route::post('/{id}/fechar', [ContaHospitalarController::class, 'fecharConta'])->name('fechar.conta');
Route::post('/{id}/reabrir', [ContaHospitalarController::class, 'reabrirConta'])->name('reabrir.conta');
Route::post('/{id}/cancelar', [ContaHospitalarController::class, 'cancelarConta'])->name('cancelar.conta');
Route::post('/{id}/pagamento', [ContaHospitalarController::class, 'pagar'])->name('pagamento.conta.store');
// Route::post('/{id}/pagamento', [ContaHospitalarController::class, 'receberPagamento'])->name('pagamento.conta.store');

Route::resource('/seguradoras', SeguradoraController::class);
Route::resource('/planos-seguradora', SeguradoraPlanoController::class);
Route::resource('/plano-seguradora-coberturas', SeguradoraPlanoCoberturaController::class);
Route::resource('/plano-seguradora-beneficiadores', SeguradoraPlanoBeneficiadorController::class);
