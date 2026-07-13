<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">

        @if ($empresa_logada->empresa->tipo_entidade->sigla === 'HOSP')
        <img src="{{ asset('images/empresa/icone-hospital.png') }}" alt="{{ env('APP_NAME') }}" class="brand-image img-circle elevation-3" style="opacity: .8" />
        @endif

        @if ($empresa_logada->empresa->tipo_entidade->sigla === 'REST')
        <img src="{{ asset('images/empresa/icone-restaurante.png') }}" alt="{{ env('APP_NAME') }}" class="brand-image img-circle elevation-3" style="opacity: .8" />
        @endif

        @if ($empresa_logada->empresa->tipo_entidade->sigla === 'RH')
        <img src="{{ asset('images/empresa/icone-recurso-humano.png') }}" alt="{{ env('APP_NAME') }}" class="brand-image img-circle elevation-3" style="opacity: .8" />
        @endif

        @if ($empresa_logada->empresa->tipo_entidade->sigla === 'HOTL')
        <img src="{{ asset('images/empresa/icone-hotel.png') }}" alt="{{ env('APP_NAME') }}" class="brand-image img-circle elevation-3" style="opacity: .8" />
        @endif

        @if ($empresa_logada->empresa->tipo_entidade->sigla === 'CFAT')
        <img src="{{ asset('images/empresa/icone-facturacao.png') }}" alt="{{ env('APP_NAME') }}" class="brand-image img-circle elevation-3" style="opacity: .8" />
        @endif

        <span class="brand-text font-weight-light">{{ env('APP_NAME') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if (!$empresa_logada->empresa->logotipo == null)
                <img src="/images/empresa/{{ $empresa_logada->empresa->logotipo ?? "" }}" alt="Logotipo" class="img-circle elevation-2" style="text-align: center;" />
                @else

                @if ($empresa_logada->empresa->tipo_entidade->sigla === 'HOSP')
                <img src="{{ asset('images/empresa/icone-hospital.png') }}" alt="Logotipo" class="img-circle elevation-2" style="text-align: center;" />
                @endif

                @if ($empresa_logada->empresa->tipo_entidade->sigla === 'REST')
                <img src="{{ asset('images/empresa/icone-restaurante.png') }}" alt="Logotipo" class="img-circle elevation-2" style="text-align: center;" />
                @endif

                @if ($empresa_logada->empresa->tipo_entidade->sigla === 'RH')
                <img src="{{ asset('images/empresa/icone-recurso-humano.png') }}" alt="Logotipo" class="img-circle elevation-2" style="text-align: center;" />
                @endif

                @if ($empresa_logada->empresa->tipo_entidade->sigla === 'HOTL')
                <img src="{{ asset('images/empresa/icone-hotel.png') }}" alt="Logotipo" class="img-circle elevation-2" style="text-align: center;" />
                @endif

                @if ($empresa_logada->empresa->tipo_entidade->sigla === 'CFAT')
                <img src="{{ asset('images/empresa/icone-facturacao.png') }}" alt="Logotipo" class="img-circle elevation-2" style="text-align: center;" />
                @endif

                @endif
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- Sidebar Menu -->
        @include('includes.sidebar-menu')
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
