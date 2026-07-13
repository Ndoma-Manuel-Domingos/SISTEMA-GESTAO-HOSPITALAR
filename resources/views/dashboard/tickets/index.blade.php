@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Solicitar Senha</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.voltar') }}</a></li>
                        <li class="breadcrumb-item active">Historicos</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header text-center py-4">
                            <label for="service" class="form-label">Escolha o serviço:</label>
                        </div>

                        <div class="card-body text-center">
                            <select id="service" class="form-control">
                                <option value="">-- selecione --</option>
                                @foreach($servicos as $s)
                                <option value="{{ $s->id }}" data-code="{{ $s->codigo_barra }}">{{ $s->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="card-footer">
                            <button id="btn-get" class="btn btn-light-primary" disabled>Obter Senha</button>
                            <div id="result" class="result"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sel = document.getElementById('service');
        const btn = document.getElementById('btn-get');
        const res = document.getElementById('result');

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        sel.addEventListener('change', () => btn.disabled = !sel.value);

        btn.addEventListener('click', async () => {
            btn.disabled = true;
            res.textContent = 'Gerando...';
            try {
                const form = new FormData();
                form.append('service_id', sel.value);
                const r = await fetch('/tickets', {
                    method: 'POST'
                    , headers: {
                        'X-CSRF-TOKEN': token
                        , 'Accept': 'application/json'
                    }
                    , credentials: 'same-origin'
                    , body: form
                });
                const json = await r.json();
                if (r.ok) {
                    res.innerHTML = `<strong>Sua senha:</strong> ${json.ticket.display} <br><small>Serviço: ${json.ticket.service}</small>`;
                } else {
                    res.textContent = json.message || 'Erro ao gerar';
                }
            } catch (e) {
                res.textContent = 'Erro de rede';
            }
            btn.disabled = false;
        });
    });

</script>
@endsection
