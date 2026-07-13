<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ Route::currentRouteNamed('dashboard') ? 'active' : '' }}">
                <i class="nav-icon fas fa-door-open"></i>
                <p>{{ __('messages.bem_vindo') }}</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('dashboard-principal') }}" class="nav-link {{ Route::currentRouteNamed('dashboard-principal') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chart-line"></i>
                <p>{{ __('messages.dashboard') }}</p>
            </a>
        </li>

        @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOSP')
        <li class="nav-item">
            <a href="{{ route('dashboard-hospital') }}" class="nav-link {{ Route::currentRouteNamed('dashboard-hospital') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chart-line"></i>
                <p>Central atendimento</p>
            </a>
        </li>
        @endif

        @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOSP')
        <li class="nav-item {{ Request::is('consults*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('consults*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-stethoscope"></i>
                <p>
                    Consultas
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                @if (Auth::user()->can('listar todos') || Auth::user()->can('criar consulta') || Auth::user()->can('monitoramento central atendimento'))
                <li class="nav-item">
                    <a href="{{ route('consultas.create', ['origem' => 'padrao']) }}" class="nav-link {{ Route::currentRouteNamed('consultas.create') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.agendar_consultas') }}</p>
                    </a>
                </li>
                @endif
                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar consulta') || Auth::user()->can('monitoramento central atendimento'))
                <li class="nav-item">
                    <a href="{{ route('consultas.index') }}" class="nav-link {{ Route::currentRouteNamed('consultas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Listas Consultas</p>
                    </a>
                </li>
                @endif
            </ul>
        </li>

        <li class="nav-item {{ Request::is('exams*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('exams*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-microscope"></i>
                <p>
                    Exames
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                @if (Auth::user()->can('listar todos') || Auth::user()->can('criar exame') || Auth::user()->can('monitoramento central atendimento'))
                <li class="nav-item">
                    <a href="{{ route('exames.create', ['origem' => 'padrao']) }}" class="nav-link {{ Route::currentRouteNamed('exames.create') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.agendar_exames') }}</p>
                    </a>
                </li>
                @endif
                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar exame') || Auth::user()->can('monitoramento central atendimento'))
                <li class="nav-item">
                    <a href="{{ route('exames.index') }}" class="nav-link {{ Route::currentRouteNamed('exames.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Listas de Exames</p>
                    </a>
                </li>
                @endif
            </ul>
        </li>

        <li class="nav-item {{ Request::is('disponibilidade*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('disponibilidade*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-calendar-alt"></i>
                <p>
                    Disponíbilidade Médica
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                @if (Auth::user()->can('listar todos'))
                <li class="nav-item">
                    <a href="{{ route('disponibilidades-medica.index') }}" class="nav-link {{ Route::currentRouteNamed('disponibilidades-medica.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Todas</p>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        {{-- GESTÃO DE FACTURACAO - PRODUTOS --}}
        @if ($empresa_logada->empresa->tem_perfil('Gestão Facturação'))
        <li class="nav-item {{ Request::is('prds*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('prds*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-cog"></i>
                <p>
                    {{ __('messages.produtos') }}
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar produtos'))
                <li class="nav-item">
                    <a href="{{ route('produtos.index') }}" class="nav-link {{ Route::currentRouteNamed('produtos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.produtos') }}</p>
                    </a>
                </li>
                @endif


                @if ($empresa_logada->empresa->tipo_entidade->sigla != 'SEGPRIVADA')

                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar stock'))
                <li class="nav-item">
                    <a href="{{ route('estoques.create') }}" class="nav-link {{ Route::currentRouteNamed('estoques.create') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.actualizar_stock') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar stock'))
                <li class="nav-item">
                    <a href="{{ route('movimento-estoques.index') }}" class="nav-link {{ Route::currentRouteNamed('movimento-estoques.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.movimentos_stock') }}</p>
                    </a>
                </li>
                @endif

                @endif


                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar categoria'))
                <li class="nav-item">
                    <a href="{{ route('categorias.index') }}" class="nav-link {{ Route::currentRouteNamed('categorias.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.categoria') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar marca'))
                <li class="nav-item">
                    <a href="{{ route('marcas.index') }}" class="nav-link {{ Route::currentRouteNamed('marcas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.marcas') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar variacoes'))
                <li class="nav-item">
                    <a href="{{ route('variacoes.index') }}" class="nav-link {{ Route::currentRouteNamed('variacoes.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.variacoes') }}</p>
                    </a>
                </li>
                @endif

            </ul>
        </li>
        @endif

        {{-- GESTÃO DE CLIENTES --}}
        @if ($empresa_logada->empresa->tem_perfil('Gestão Facturação'))
        <li class="nav-item {{ Request::is('clients*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('clients*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <p>
                    {{ $empresa_logada->empresa->tipo_entidade->sigla == 'HOSP' ? __('messages.paciente') : __('messages.clientes') }}
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar cliente'))
                <li class="nav-item">
                    <a href="{{ route('clientes.index') }}" class="nav-link {{ Route::currentRouteNamed('clientes.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.todos') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('conta-clientes.index') }}" class="nav-link {{ Route::currentRouteNamed('conta-clientes.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.conta_corrente') }}</p>
                    </a>
                </li>

                @endif


                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar triagem'))
                @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOSP')
                <li class="nav-item">
                    <a href="{{ route('triagens.index') }}" class="nav-link {{ Route::currentRouteNamed('triagens.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.triagem') }}</p>
                    </a>
                </li>
                @endif
                @endif


            </ul>
        </li>
        @endif

        {{-- OPERACÕES --}}

        @if ($empresa_logada->empresa->tipo_entidade->sigla !== 'HOSP')
        <li class="nav-item {{ Request::is('flcai*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('flcai*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-cogs"></i>
                <p>
                    {{ __('messages.operacoes') }}
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">

                @if ($empresa_logada->empresa->tipo_entidade->sigla == 'SEGPRIVADA')
                <li class="nav-item">
                    <a href="{{ route('clientes.index') }}" class="nav-link {{ Route::currentRouteNamed('clientes.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.clientes') }}</p>
                    </a>
                </li>
                @endif

                @if ($empresa_logada->empresa->tipo_entidade->sigla == 'CFOR')
                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar cliente'))
                <li class="nav-item">
                    <a href="{{ route('clientes.index') }}" class="nav-link {{ Route::currentRouteNamed('clientes.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.aluno') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar matricula'))
                <li class="nav-item">
                    <a href="{{ route('alunos-matriculas') }}" class="nav-link {{ Route::currentRouteNamed('alunos-matriculas') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.matricula') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar formador'))
                <li class="nav-item">
                    <a href="{{ route('funcionarios.index') }}" class="nav-link {{ Route::currentRouteNamed('funcionarios.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.formadores') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar video'))
                <li class="nav-item">
                    <a href="{{ route('videos.home') }}" class="nav-link {{ Route::currentRouteNamed('videos.home') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.video') }}/{{ __('messages.conteudos') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar prova'))
                <li class="nav-item">
                    <a href="{{ route('provas.index') }}" class="nav-link {{ Route::currentRouteNamed('provas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.provas') }}</p>
                    </a>
                </li>
                @endif
                @endif

                @if ($empresa_logada->empresa->tipo_entidade->sigla == 'CONS')
                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar agendamento'))
                <li class="nav-item">
                    <a href="{{ route('agendamentos.index') }}" class="nav-link {{ Route::currentRouteNamed('agendamentos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.agendamento') }}</p>
                    </a>
                </li>
                @endif
                @endif

                @if ( $empresa_logada->empresa->tipo_entidade->sigla == 'GEST_EMPRE' || $empresa_logada->empresa->tipo_entidade->sigla == 'CFAT' || $empresa_logada->empresa->tipo_entidade->sigla == 'REST')

                @if (Auth::user()->can('movimento no caixa'))
                <li class="nav-item">
                    <a href="{{ route('contabilidade-diarios') }}" class="nav-link {{ Route::currentRouteNamed('contabilidade-diarios') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.registro_movimentos') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('movimento no caixa geral'))
                <li class="nav-item">
                    <a href="{{ route('contabilidade-facturacao') }}" class="nav-link {{ Route::currentRouteNamed('contabilidade-facturacao') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.facturacao') }}</p>
                    </a>
                </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('contabilidade-inventario', ['tipo' => 'produto']) }}" class="nav-link {{ Route::currentRouteNamed('contabilidade-inventario') && request()->tipo == 'produto' ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.inventario_inicial') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('contabilidade-inventario', ['tipo' => 'materias-primas']) }}" class="nav-link {{ Route::currentRouteNamed('contabilidade-inventario') && request()->tipo == 'materias-primas' ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Inventário Mater-primas</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('lojas.index') }}" class="nav-link {{ Route::currentRouteNamed('lojas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.lojas') }}</p>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif


        {{-- GESTÃO FINANCEIRA --}}
        @if ($empresa_logada->empresa->tem_perfil('Gestão Financeira'))
        <li class="nav-item {{ Request::is('financ*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('financ*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-boxes"></i>
                <p>
                    {{ __('messages.financeiro') }}
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">

                @if (Auth::user()->can('painel financeiro'))
                <li class="nav-item">
                    <a href="{{ route('dashboard-financeiro') }}" class="nav-link {{ Route::currentRouteNamed('dashboard-financeiro') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.controle') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar receita'))
                <li class="nav-item">
                    <a href="{{ route('receitas.index') }}" class="nav-link {{ Route::currentRouteNamed('receitas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.tipos_receitas') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar dispesa'))
                <li class="nav-item">
                    <a href="{{ route('dispesas.index') }}" class="nav-link {{ Route::currentRouteNamed('dispesas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.tipos_despesas') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('operacao financeira'))
                <li class="nav-item">
                    <a href="{{ route('operacaoes-financeiras.index') }}" class="nav-link {{ Route::currentRouteNamed('operacaoes-financeiras.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.transacoes') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('operacao financeira'))
                <li class="nav-item">
                    <a href="{{ route('orcamentos.index') }}" class="nav-link {{ Route::currentRouteNamed('orcamentos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.orcamentos') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('operacao financeira'))
                <li class="nav-item">
                    <a href="{{ route('centros-custos.index') }}" class="nav-link {{ Route::currentRouteNamed('centros-custos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.centro_custos') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('painel financeiro'))
                <li class="nav-item">
                    <a href="{{ route('operacaoes-financeiras.lixeira') }}" class="nav-link {{ Route::currentRouteNamed('operacaoes-financeiras.lixeira') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.lixeira') }}</p>
                    </a>
                </li>
                @endif

            </ul>
        </li>
        @endif

        {{-- GESTÃO FACTURAÇÃO --}}
        @if ($empresa_logada->empresa->tem_perfil('Gestão Facturação'))
        <li class="nav-item {{ Request::is('facturs*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('facturs*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-folder-open"></i>
                <p>
                    {{ __('messages.facturacao') }}
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">

                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar facturas'))
                <li class="nav-item">
                    <a href="{{ route('contas-hospitalares.index') }}" class="nav-link {{ Route::currentRouteNamed('contas-hospitalares.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Conta Hospitalar</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('fechos-contas.index') }}" class="nav-link {{ Route::currentRouteNamed('fechos-contas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Cobrança Seguradoras</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('criar todos') || Auth::user()->can('criar facturas'))
                <li class="nav-item">
                    <a href="{{ route('facturas.create') }}" class="nav-link {{ Route::currentRouteNamed('facturas.create') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.criar_documentos') }}</p>
                    </a>
                </li>
                @endif


                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar facturas'))
                <li class="nav-item">
                    <a href="{{ route('facturas.index') }}" class="nav-link {{ Route::currentRouteNamed('facturas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.todos') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('facturas-facturacao') }}" class="nav-link {{ Route::currentRouteNamed('facturas-facturacao') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.facturacao') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('facturas-informativo') }}" class="nav-link {{ Route::currentRouteNamed('facturas-informativo') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.informativos') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('facturas-sem-pagamento') }}" class="nav-link {{ Route::currentRouteNamed('facturas-sem-pagamento') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.facturas_sem_pagamentos') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('recibos') }}" class="nav-link {{ Route::currentRouteNamed('recibos') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.recibos') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('notas-creditos') }}" class="nav-link {{ Route::currentRouteNamed('notas-creditos') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.nota_creditos') }}</p>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        {{-- OPERACOES 1 --}}
        @if (Auth::user()->can('gerar saft'))
        <li class="nav-item {{ Request::is('agts*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('agts*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-pdf"></i>
                <p>
                    AGT
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('agt.index') }}" class="nav-link {{ Route::currentRouteNamed('agt.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.gerar_ficheiro_saft') }}</p>
                    </a>
                </li>
                @if ($empresa_logada->empresa->tipo_facturacao != "saft")
                <li class="nav-item">
                    <a href="{{ route('series.home') }}" class="nav-link {{ Route::currentRouteNamed('series.home') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Series</p>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        {{-- OPERACOES 1 --}}
        @if (Auth::user()->can('gerar saft'))
        <li class="nav-item {{ Request::is('backup*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('backup*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-database"></i>
                <p>
                    {{ __('messages.backup') }}
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('databases.index') }}" class="nav-link {{ Route::currentRouteNamed('databases.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.backup') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backups.index') }}" class="nav-link {{ Route::currentRouteNamed('backups.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Historicos</p>
                    </a>
                </li>
            </ul>
        </li>
        @endif

        {{-- GESTÃO DE RECURSOS HUMANOS --}}
        @if ($empresa_logada->empresa->tem_perfil('Gestão Recurso Humano'))
        <li class="nav-item {{ Request::is('reshums*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('reshums*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <p>
                    {{ __('messages.recursos_humanos') }}
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">

                @if (Auth::user()->can('painel recursos humano'))
                <li class="nav-item">
                    <a href="{{ route('dashboard-recurso-humanos') }}" class="nav-link {{ Route::currentRouteNamed('dashboard-recurso-humanos') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.controle') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar categoria'))
                <li class="nav-item">
                    <a href="{{ route('categorias-cargos.index') }}" class="nav-link {{ Route::currentRouteNamed('categorias-cargos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.categoria') }}</p>
                    </a>
                </li>
                @endif


                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar departamento'))
                <li class="nav-item">
                    <a href="{{ route('departamentos.index') }}" class="nav-link {{ Route::currentRouteNamed('departamentos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.departamentos') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar cargo'))
                <li class="nav-item">
                    <a href="{{ route('cargos.index') }}" class="nav-link {{ Route::currentRouteNamed('cargos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.cargos') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar rendimento'))
                <li class="nav-item">
                    <a href="{{ route('tipos-rendimentos.index') }}" class="nav-link {{ Route::currentRouteNamed('tipos-rendimentos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.tipo_rendimento') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar periodo'))
                <li class="nav-item">
                    <a href="{{ route('periodos-rendimentos.index') }}" class="nav-link {{ Route::currentRouteNamed('periodos-rendimentos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.periodo_rendimento') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar tipo contrato'))
                <li class="nav-item">
                    <a href="{{ route('tipos-contratos.index') }}" class="nav-link {{ Route::currentRouteNamed('tipos-contratos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.tipo_contrato') }}</p>
                    </a>
                </li>
                @endif


                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar processamento'))
                <li class="nav-item">
                    <a href="{{ route('tipos-processamentos.index') }}" class="nav-link {{ Route::currentRouteNamed('tipos-processamentos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.tipo_processamento') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar subsidio'))
                <li class="nav-item">
                    <a href="{{ route('subsidios.index') }}" class="nav-link {{ Route::currentRouteNamed('subsidios.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.subsidio') }}</p>
                    </a>
                </li>
                @endif


                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar desconto'))
                <li class="nav-item">
                    <a href="{{ route('descontos.index') }}" class="nav-link {{ Route::currentRouteNamed('descontos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.desconto') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar funcionario'))
                <li class="nav-item">
                    <a href="{{ route('funcionarios.index') }}" class="nav-link {{ Route::currentRouteNamed('funcionarios.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.funcionario') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar funcionario'))
                <li class="nav-item">
                    <a href="{{ route('tipos-funcionarios.index') }}" class="nav-link {{ Route::currentRouteNamed('tipos-funcionarios.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.tipo_funcionario') }}</p>
                    </a>
                </li>
                @endif

            </ul>
        </li>
        @endif

        {{-- GESTÃO DE CONTABILIDADE --}}
        @if ($empresa_logada->empresa->tem_perfil('Gestão Contabilidade'))
        <li class="nav-item {{ Request::is('contabils*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('contabils*') ? 'active' : '' }}">
                <i class="nav-icon far fa-plus-square"></i>
                <p>
                    {{ __('messages.contabilidade') }}
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>

            <ul class="nav nav-treeview">

                @if ($empresa_logada->empresa->tipo_entidade->sigla != 'SEGPRIVADA')
                <li class="nav-item {{ Request::is('contabils*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                            {{ __('messages.plano_conta') }}
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (Auth::user()->can('listar todos') || Auth::user()->can('plano de conta'))
                        <li class="nav-item">
                            <a href="{{ route('plano-geral-contas.index') }}" class="nav-link {{ Route::currentRouteNamed('plano-geral-contas.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>P.G.C</p>
                            </a>
                        </li>
                        @endif

                        @if (Auth::user()->can('listar todos') || Auth::user()->can('listar classe'))
                        <li class="nav-item">
                            <a href="{{ route('classes.index') }}" class="nav-link {{ Route::currentRouteNamed('classes.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('messages.classe') }}</p>
                            </a>
                        </li>
                        @endif

                        @if (Auth::user()->can('listar todos') || Auth::user()->can('listar conta'))
                        <li class="nav-item">
                            <a href="{{ route('contas.index') }}" class="nav-link {{ Route::currentRouteNamed('contas.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('messages.conta') }}</p>
                            </a>
                        </li>
                        @endif

                        @if (Auth::user()->can('listar todos') || Auth::user()->can('listar subconta'))
                        <li class="nav-item">
                            <a href="{{ route('subcontas.index') }}" class="nav-link {{ Route::currentRouteNamed('subcontas.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('messages.subconta') }}</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if ($empresa_logada->empresa->tipo_entidade->sigla != 'SEGPRIVADA')
                <li class="nav-item {{ Request::is('contabils*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                            {{ __('messages.operacoes') }}
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (Auth::user()->can('listar todos') || Auth::user()->can('balanco'))
                        <li class="nav-item">
                            <a href="{{ route('contabilidade-balanco-inicial') }}" class="nav-link {{ Route::currentRouteNamed('contabilidade-balanco-inicial') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('messages.balanco_inicial') }}</p>
                            </a>
                        </li>
                        @endif
                        @if (Auth::user()->can('listar todos') || Auth::user()->can('balacente'))
                        <li class="nav-item">
                            <a href="{{ route('contabilidade-balancete') }}" class="nav-link {{ Route::currentRouteNamed('contabilidade-balancete') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('messages.balacente') }}</p>
                            </a>
                        </li>
                        @endif
                        @if (Auth::user()->can('listar todos') || Auth::user()->can('fecho de contas'))
                        <li class="nav-item">
                            <a href="{{ route('contabilidade-fecho-contas') }}" class="nav-link {{ Route::currentRouteNamed('contabilidade-fecho-contas') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Fecho de contas</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if ($empresa_logada->empresa->tipo_entidade->sigla != 'SEGPRIVADA')
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                            {{ __('messages.inventario') }}
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (Auth::user()->can('listar todos') || Auth::user()->can('inventario'))
                        <li class="nav-item">
                            <a href="{{ route('inventarios.index') }}" class="nav-link {{ Route::currentRouteNamed('inventarios.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('messages.todos') }}</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                <li class="nav-item {{ Request::is('contabils*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                            {{ __('messages.equipamento_activos') }}
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('equipamentos-activos.index') }}" class="nav-link {{ Route::currentRouteNamed('equipamentos-activos.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('messages.todos') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                @if ($empresa_logada->empresa->tipo_entidade->sigla != 'SEGPRIVADA')
                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar exercicio'))
                <li class="nav-item">
                    <a href="{{ route('exercicios.index') }}" class="nav-link {{ Route::currentRouteNamed('exercicios.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.exercicio') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar periodo'))
                <li class="nav-item">
                    <a href="{{ route('periodos.index') }}" class="nav-link {{ Route::currentRouteNamed('periodos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.periodo') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar banco'))
                <li class="nav-item">
                    <a href="{{ route('tipos-creditos.index') }}" class="nav-link {{ Route::currentRouteNamed('tipos-creditos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.tipos_credito') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar banco'))
                <li class="nav-item">
                    <a href="{{ route('contrapartidas.index') }}" class="nav-link {{ Route::currentRouteNamed('contrapartidas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.contrapartida') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar banco'))
                <li class="nav-item">
                    <a href="{{ route('taxas-reintegracao-amortizacoes.index') }}" class="nav-link {{ Route::currentRouteNamed('taxas-reintegracao-amortizacoes.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.taxa_reintegracao_amortizacoes') }}</p>
                    </a>
                </li>
                @endif
                @endif


            </ul>
        </li>
        @endif

        {{-- GESTÃO DE LOGISTICAS  --}}
        @if ($empresa_logada->empresa->tem_perfil('Gestão Logistica'))
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-truck"></i>
                <p>
                    {{ __('messages.compras') }}
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">

                {{-- @if (Auth::user()->can('Gestão Logistica')) --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard-logistica') }}" class="nav-link {{ Route::currentRouteNamed('dashboard-logistica') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.controle') }}</p>
                    </a>
                </li>
                {{-- @endif --}}


                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar fornecedores'))
                <li class="nav-item">
                    <a href="{{ route('fornecedores.index') }}" class="nav-link {{ Route::currentRouteNamed('fornecedores.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.fornecedores') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar encomendas'))
                <li class="nav-item">
                    <a href="{{ route('fornecedores-encomendas.index') }}" class="nav-link {{ Route::currentRouteNamed('fornecedores-encomendas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.encomendas') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar requisicao'))
                <li class="nav-item">
                    <a href="{{ route('requisacoes.index') }}" class="nav-link {{ Route::currentRouteNamed('requisacoes.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.requisicoes') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar facturas'))
                <li class="nav-item">
                    <a href="{{ route('fornecedores-facturas-encomendas.index') }}" class="nav-link {{ Route::currentRouteNamed('fornecedores-facturas-encomendas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.factura') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar loja/armazem'))
                <li class="nav-item">
                    <a href="{{ route('lojas.index') }}" class="nav-link {{ Route::currentRouteNamed('lojas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.loja_armazem') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('gestao loja/armazem'))
                <li class="nav-item" title="Gestão de Lojas/Armazém">
                    <a href="{{ route('gestao-lojas-armazem') }}" class="nav-link {{ Route::currentRouteNamed('gestao-lojas-armazem') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.gestao_lojas_armazem') }}</p>
                    </a>
                </li>

                <li class="nav-item" title="Transferência de Loja/Armazém">
                    <a href="{{ route('transferencia-lojas-armazem') }}" class="nav-link {{ Route::currentRouteNamed('transferencia-lojas-armazem') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.transferencia') }}</p>
                    </a>
                </li>
                @endif

            </ul>
        </li>
        @endif


        {{-- RELATORIO --}}
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-file-excel"></i>
                <p>
                    {{ __('messages.relatorios') }}
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">

                @if ($empresa_logada->empresa->tipo_entidade->sigla == 'CFOR')

                @if (Auth::user()->can('listar cliente'))
                <li class="nav-item">
                    <a href="{{ route('relatorio-cliente-pdf') }}" class="nav-link {{ Route::currentRouteNamed('relatorio-cliente-pdf') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.aluno') }}</p>
                    </a>
                </li>
                @endif

                @endif

                @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOTL')

                @if (Auth::user()->can('listar cliente'))
                <li class="nav-item">
                    <a href="{{ route('relatorio-cliente-pdf') }}" class="nav-link {{ Route::currentRouteNamed('relatorio-cliente-pdf') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.hospede') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar reserva'))
                <li class="nav-item">
                    <a href="{{ route('reservas.index') }}" class="nav-link {{ Route::currentRouteNamed('reservas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.reserva') }}</p>
                    </a>
                </li>
                @endif

                @endif

                @if ($empresa_logada->empresa->tipo_entidade->sigla == 'CONS')
                @if (Auth::user()->can('listar cliente'))
                <li class="nav-item">
                    <a href="{{ route('relatorio-cliente-pdf') }}" class="nav-link {{ Route::currentRouteNamed('relatorio-cliente-pdf') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.cliente') }}</p>
                    </a>
                </li>
                @endif
                @endif

                @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOSP')
                @if (Auth::user()->can('listar cliente'))
                <li class="nav-item">
                    <a href="{{ route('clientes.index') }}" class="nav-link {{ Route::currentRouteNamed('relatorio-cliente-pdf') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.paciente') }}</p>
                    </a>
                </li>
                @endif
                @endif


                @if (Auth::user()->can('movimento no caixa'))
                <li class="nav-item">
                    <a href="{{ route('contabilidade-diarios') }}" class="nav-link {{ Route::currentRouteNamed('contabilidade-diarios') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.diario') }}</p>
                    </a>
                </li>
                @endif

                @if ($empresa_logada->empresa->tipo_entidade->sigla != 'HOSP')

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar vendas'))
                <li class="nav-item">
                    <a title="Mapa de Retenção na fonte" href="{{ route('mapa_retencao_fonte') }}" class="nav-link {{ Route::currentRouteNamed('mapa_retencao_fonte') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Mapa Retenção fonte</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar vendas'))
                <li class="nav-item">
                    <a href="{{ route('vendas_por_produtos') }}" class="nav-link {{ Route::currentRouteNamed('vendas_por_produtos') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.venda') }} por Produto</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar vendas'))
                <li class="nav-item">
                    <a href="{{ route('vendas_por_operadores') }}" class="nav-link {{ Route::currentRouteNamed('vendas_por_operadores') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.venda') }} por Operador</p>
                    </a>
                </li>
                @endif


                @if (Auth::user()->can('movimento no caixa geral'))
                <li class="nav-item">
                    <a href="{{ route('caixa.movimentos_caixa') }}" class="nav-link {{ Route::currentRouteNamed('caixa.movimentos_caixa') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.movimento_caixa') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar stock'))
                <li class="nav-item">
                    <a href="{{ route('vendas_por_artigo', ['tipo' => 'produto']) }}" class="nav-link {{ Route::currentRouteNamed('vendas_por_artigo') && request()->tipo == 'produto' ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Stock de Produtos</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('vendas_por_artigo', ['tipo' => 'materias-primas']) }}" class="nav-link {{ Route::currentRouteNamed('vendas_por_artigo') && request()->tipo == 'materias-primas' ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Stock de Matérias-primas</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('vendas_por_artigo_anterior', ['tipo' => 'produto']) }}" class="nav-link {{ Route::currentRouteNamed('vendas_por_artigo_anterior') && request()->tipo == 'produto' ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.stock_por_artigo_anterior') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar vendas'))
                <li class="nav-item">
                    <a href="{{ route('vendas_movimentos_estoques') }}" class="nav-link {{ Route::currentRouteNamed('vendas_movimentos_estoques') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Movimentos de Stock</p>
                    </a>
                </li>
                @endif
                @endif

            </ul>
        </li>

        {{-- GESTÃO DE DOCUMENTOS --}}
        @if (Auth::user()->can('controle documentos'))
        <li class="nav-item  {{ Request::is('gestao-documentos*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('gestao-documentos*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-th"></i>
                <p>
                    {{ __('messages.gestao_documentos') }}
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">

                <li class="nav-item">
                    <a href="{{ route('documentos.index') }}" class="nav-link {{ Route::currentRouteNamed('documentos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.controle') }}</p>
                    </a>
                </li>

            </ul>
        </li>
        @endif

        {{-- GESTÃO DE DOCUMENTOS --}}
        @if (Auth::user()->can('controle auditoria'))
        <li class="nav-item  {{ Request::is('auditoria*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('auditoria*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-th"></i>
                <p>
                    {{ __('messages.auditoria') }}
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('auditorias.index') }}" class="nav-link {{ Route::currentRouteNamed('auditorias.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.controle') }}</p>
                    </a>
                </li>
            </ul>
        </li>
        @endif


        {{-- GESTÃO DE PERMISSÕES --}}
        @if (Auth::user()->can('controle permissoes'))
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-th"></i>
                <p>
                    {{ __('messages.gestao_permissoes') }}
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('roles.index') }}" class="nav-link {{ Route::currentRouteNamed('roles.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.perfil') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('permissoes.index') }}" class="nav-link {{ Route::currentRouteNamed('permissoes.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.permissoes') }}</p>
                    </a>
                </li>
            </ul>
        </li>
        @endif


        @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOSP')
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-th"></i>
                <p>
                    Seguradoras
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar seguradora'))
                <li class="nav-item">
                    <a href="{{ route('seguradoras.index') }}" class="nav-link {{ Route::currentRouteNamed('seguradoras.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Todas</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('planos-seguradora.index') }}" class="nav-link {{ Route::currentRouteNamed('planos-seguradora.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Planos</p>
                    </a>
                </li>
                @endif
            </ul>
        </li>

        @endif

        {{-- TABELA DE APOIO --}}
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-th"></i>
                <p>
                    {{ __('messages.tabela_apoio') }}
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>

            @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOTL')
            <ul class="nav nav-treeview">

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar tarefario'))
                <li class="nav-item">
                    <a href="{{ route('tarefarios.index') }}" class="nav-link {{ Route::currentRouteNamed('tarefarios.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.tarefarios') }}</p>
                    </a>
                </li>
                @endif


                @if (Auth::user()->can('listar todos'))
                <li class="nav-item">
                    <a href="{{ route('tipos-reservas.index') }}" class="nav-link {{ Route::currentRouteNamed('tipos-reservas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Tipo Reservas</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar quarto'))
                <li class="nav-item">
                    <a href="{{ route('tipo-quartos.index') }}" class="nav-link {{ Route::currentRouteNamed('tipo-quartos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.tipo_quarto') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar andar'))
                <li class="nav-item">
                    <a href="{{ route('andares.index') }}" class="nav-link {{ Route::currentRouteNamed('andares.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.andares') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos'))
                <li class="nav-item">
                    <a href="{{ route('salas.index') }}" class="nav-link {{ Route::currentRouteNamed('salas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.sala_mesas') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos'))
                <li class="nav-item">
                    <a href="{{ route('motivos-reservas.index') }}" class="nav-link {{ Route::currentRouteNamed('motivos-reservas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.motivos') }}</p>
                    </a>
                </li>
                @endif

            </ul>
            @endif

            @if ($empresa_logada->empresa->tipo_entidade->sigla == 'REST')
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('salas.index') }}" class="nav-link {{ Route::currentRouteNamed('salas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.mesa') }}</p>
                    </a>
                </li>
                @if (Auth::user()->can('listar todos'))
                <li class="nav-item">
                    <a href="{{ route('grupos.index') }}" class="nav-link {{ Route::currentRouteNamed('grupos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.grupo') }}</p>
                    </a>
                </li>
                @endif

            </ul>
            @endif

            @if ($empresa_logada->empresa->tipo_entidade->sigla == 'CFOR')
            <ul class="nav nav-treeview">

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar ano lectivo'))
                <li class="nav-item">
                    <a href="{{ route('anos-lectivos.index') }}" class="nav-link {{ Route::currentRouteNamed('anos-lectivos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.ano_lectivo') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos'))
                <li class="nav-item">
                    <a href="{{ route('anuncios.index') }}" class="nav-link {{ Route::currentRouteNamed('anuncios.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.anuncio') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar curso'))
                <li class="nav-item">
                    <a href="{{ route('cursos.index') }}" class="nav-link {{ Route::currentRouteNamed('cursos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.curso') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar turno'))
                <li class="nav-item">
                    <a href="{{ route('turnos.index') }}" class="nav-link {{ Route::currentRouteNamed('turnos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.turno') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar sala'))
                <li class="nav-item">
                    <a href="{{ route('salas.index') }}" class="nav-link {{ Route::currentRouteNamed('salas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.sala') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar turma'))
                <li class="nav-item">
                    <a href="{{ route('turmas.index') }}" class="nav-link {{ Route::currentRouteNamed('turmas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.turma') }}</p>
                    </a>
                </li>
                @endif

            </ul>
            @endif

            @if ($empresa_logada->empresa->tipo_entidade->sigla == 'HOSP')
            <ul class="nav nav-treeview">


                <li class="nav-item">
                    <a href="{{ route('catalogo-exames.index') }}" class="nav-link {{ Route::currentRouteNamed('catalogo-exames.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Catalogo Exames</p>
                    </a>
                </li>

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar camara'))
                <li class="nav-item">
                    <a href="{{ route('gavetas.index') }}" class="nav-link {{ Route::currentRouteNamed('gavetas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.gaveta') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('camaras.index') }}" class="nav-link {{ Route::currentRouteNamed('camaras.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.camaras') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar quarto') || Auth::user()->can('listar tipo quarto'))

                <li class="nav-item">
                    <a href="{{ route('quartos.index') }}" class="nav-link {{ Route::currentRouteNamed('quartos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.quarto') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('tipo-quartos.index') }}" class="nav-link {{ Route::currentRouteNamed('tipo-quartos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.tipo_quarto') }}</p>
                    </a>
                </li>

                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar andar'))
                <li class="nav-item">
                    <a href="{{ route('andares.index') }}" class="nav-link {{ Route::currentRouteNamed('andares.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.andares') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar consulta'))
                <li class="nav-item">
                    <a href="{{ route('paramentros-consultas.index') }}" class="nav-link {{ Route::currentRouteNamed('paramentros-consultas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Paramentro Consultas</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar exame'))
                <li class="nav-item">
                    <a href="{{ route('sub-parametros-exames.index') }}" class="nav-link {{ Route::currentRouteNamed('sub-parametros-exames.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.tipo_resultado_exames') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar atendimento'))
                <li class="nav-item">
                    <a href="{{ route('tipos-atendimentos.index') }}" class="nav-link {{ Route::currentRouteNamed('tipos-atendimentos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.tipo_atendimento') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar atendimento'))
                <li class="nav-item">
                    <a href="{{ route('cids.index') }}" class="nav-link {{ Route::currentRouteNamed('cids.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>CIDs</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar prioridade'))
                <li class="nav-item">
                    <a href="{{ route('prioridades.index') }}" class="nav-link {{ Route::currentRouteNamed('prioridades.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.prioridade') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar especialidade'))
                <li class="nav-item">
                    <a href="{{ route('especialidades.index') }}" class="nav-link {{ Route::currentRouteNamed('especialidades.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.especialidade') }}</p>
                    </a>
                </li>
                @endif
            </ul>
            @endif

            {{-- MENUS GERAIS --}}
            <ul class="nav nav-treeview">

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar especialidade'))
                <li class="nav-item">
                    <a href="{{ route('impostos.index') }}" class="nav-link {{ Route::currentRouteNamed('impostos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.imposto') }}s</p>
                    </a>
                </li>
                @endif

                @if ($empresa_logada->empresa->tipo_entidade->sigla == 'PADARIA')
                @if (Auth::user()->can('listar todos'))
                <li class="nav-item">
                    <a href="{{ route('unidades_medida.index') }}" class="nav-link {{ Route::currentRouteNamed('unidades_medida.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Unidade Médidas</p>
                    </a>
                </li>
                @endif
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar utilizadores'))
                <li class="nav-item">
                    <a href="{{ route('utilizadores.index') }}" class="nav-link {{ Route::currentRouteNamed('utilizadores.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.utilizador') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar banco'))
                <li class="nav-item">
                    <a href="{{ route('contas-bancarias.index') }}" class="nav-link {{ Route::currentRouteNamed('contas-bancarias.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.conta_bancaria') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar caixa'))
                <li class="nav-item">
                    <a href="{{ route('caixas.index') }}" class="nav-link {{ Route::currentRouteNamed('caixas.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.caixa') }}</p>
                    </a>
                </li>
                @endif


                @if ($empresa_logada->empresa->tipo_entidade->sigla == 'CFAT')
                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar pin'))
                <li class="nav-item">
                    <a href="{{ route('pins.index') }}" class="nav-link {{ Route::currentRouteNamed('pins.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.pin') }}</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos') || Auth::user()->can('listar lote'))
                <li class="nav-item">
                    <a href="{{ route('lotes.index') }}" class="nav-link {{ Route::currentRouteNamed('lotes.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.lotes') }}</p>
                    </a>
                </li>
                @endif
                @endif

                @if ($empresa_logada->empresa->tipo_entidade->sigla == 'SEGPRIVADA' )
                @if (Auth::user()->can('configuracoes'))

                @if (Auth::user()->can('listar todos'))
                <li class="nav-item">
                    <a href="{{ route('tipos-postos.index') }}" class="nav-link {{ Route::currentRouteNamed('tipos-postos.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Tipos de Postos</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('listar todos'))
                <li class="nav-item">
                    <a href="{{ route('tipos-ocorrencias.index') }}" class="nav-link {{ Route::currentRouteNamed('tipos-ocorrencias.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Tipos de Ocorrências</p>
                    </a>
                </li>
                @endif
                @endif
                @endif
            </ul>
        </li>


        @if ($empresa_logada->empresa->tipo_entidade->sigla == 'REST')
        @if (Auth::user()->can('criar todos') || Auth::user()->can('criar vendas'))
        <li class="nav-item">
            <a href="{{ route('pronto-venda-mesas') }}" class="nav-link {{ Route::currentRouteNamed('pronto-venda-mesas') ? 'active' : '' }}">
                <i class="nav-icon fas fa-desktop"></i>
                <p>{{ __('messages.inicio_vendas') }} </p>
            </a>
        </li>
        @endif
        @endif

        @if ($empresa_logada->empresa->tipo_entidade->sigla == 'CFAT')
        @if ($empresa_logada->empresa->tipo_pronto_venda == 'Grelha')
        <li class="nav-item">
            <a href="{{ route('pronto-venda') }}" class="nav-link {{ Route::currentRouteNamed('pronto-venda') ? 'active' : '' }}">
                <i class="nav-icon fas fa-desktop"></i>
                <p>{{ __('messages.pronto_venda') }}</p>
            </a>
        </li>
        @endif

        @if ($empresa_logada->empresa->tipo_pronto_venda == 'Lista')
        <li class="nav-item">
            <a href="{{ route('pos.index') }}" class="nav-link {{ Route::currentRouteNamed('pos.index') ? 'active' : '' }}">
                <i class="nav-icon fas fa-desktop"></i>
                <p>{{ __('messages.pronto_venda') }}</p>
            </a>
        </li>
        @endif
        @endif

    </ul>
</nav>
<!-- /.sidebar-menu -->
