<!-- Sidebar Menu -->

<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-home"></i>
                <p>
                    Empresas
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">

                <li class="nav-item">
                    <a href="{{ route('inscricoes.create') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Nova Inscrição</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('empresas.create') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Nova Empresa</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('empresas.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.todos') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('membros.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Membros</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('nossos-utilizadores') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.utilizador') }}</p>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-money-bill"></i>
                <p>
                    Finanças
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('empresas-dashboard-financeiro.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Pagamentos</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('empresas-dashboard-financeiro-cotas.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Controle de cota</p>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a href="{{ route('operadores.index') }}" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>Operadores</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-th"></i>
                <p>
                    {{ __('messages.tabela_apoio') }}
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">

                <li class="nav-item">
                    <a href="{{ route('planos.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Planos</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('profissoes.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Profissões</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('funcoes.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Funções</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('tipos-entidade.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Tipo Entidades</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('modulos.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Modulos Entidades</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('tipo-pagamentos.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Tipo de Pagamentos</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{ route('provincias.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Províncias</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('municipios.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Municípios</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('distritos.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Distritos</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('configuracao-admin') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('messages.configuracoes') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('gerar-licenca-configuracao-admin') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Gerar Licença</p>
                    </a>
                </li>


            </ul>
        </li>

    </ul>
</nav>
<!-- /.sidebar-menu -->
