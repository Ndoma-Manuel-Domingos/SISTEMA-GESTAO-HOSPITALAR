<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $titulo ?? '' }} | {{ $descricao ?? '' }}</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/sweetalert2.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}">

    <style>
        .container-bg {
            position: relative;
            overflow: hidden;
            height: 100vh;
            /* Ajuste conforme necessário */
        }

        .container-bg::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* background-image: url('/dist/img/5433276-um-empresario-trabalha-em-uma-rede-publica-com-informacoes-protegidas-seguras-gratis-foto.jpg'); */
            background-image: url('/dist/img/focused-young-man-paying-bill-store.jpg');
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-size: cover;
            opacity: 0.9;
            filter: blur(5px);
            /* Ajuste o nível de desfoque aqui */
            z-index: -1;
            /* Certifique-se de que o fundo fique atrás do conteúdo */
        }

        .loading-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            z-index: -2;
        }

        .spinner {
            width: 100px;
            height: 100px;
            border: 10px solid #f3f3f3;
            border-top: 10px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

    </style>
</head>

<body class="hold-transition login-page container-bg">

    <div class="loading-modal d-flex" id="loading-modal">
        <div class="spinner"></div>
    </div>

    <div class="login-box">
        <div class="card card-outline card-dark">
            <div class="card-body py-5">
                <form action="{{ route('config.configuracao-store') }}" method="post" class="pt-3">
                    @csrf
                    {{-- <div class="col-12 col-md-12 mb-3">
                        <label for="host" class="form-label">Host</label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg" disabled id="host" name="host" value="{{ $dbHost }}" placeholder="Localhost de acesso ao sistema">
            </div>
            <p class="text-light-danger"> @error('host') {{ $message }} @enderror </p>
        </div> --}}

        <div class="col-12 col-md-12 mb-3">
            <label for="apache" class="form-label">Porta Apache <span class="text-light-danger">*</span></label>
            <div class="input-group">
                <input type="number" class="form-control form-control-lg" id="apache" name="apache" value="{{ $apache }}" placeholder="Apache Porta">
            </div>
            <p class="text-light-danger"> @error('apache') {{ $message }} @enderror </p>
        </div>

        <div class="col-12 col-md-12 mb-3">
            <label for="mysql" class="form-label">Porta MySQL <span class="text-light-danger">*</span></label>
            <div class="input-group">
                <input type="number" class="form-control form-control-lg" id="mysql" name="mysql" value="{{ $mysql }}" placeholder="Mysql Porta">
            </div>
            <p class="text-light-danger"> @error('mysql') {{ $message }} @enderror </p>
        </div>

        <div class="row">
            <div class="col-12 mt-2">
                <button type="submit" class="btn-lg btn-outline-dark btn-block">{{ __('messages.salvar') }}</button>
            </div>
        </div>
        </form>

    </div>
    <div class="card-footer">
        <p class="mb-0 mt-4 text-right row">
            <a href="{{ route('login') }}" class="text-right h4 col-12 col-md-6">Usar a minha conta</a> <br>
        </p>
    </div>
    <!-- /.card-body -->
    </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-12 text-center text-light-dark mt-3">
            {{-- <div class="col-12 col-md-12 text-center text-white mt-3"> --}}
            <h3>"Bem-vindo de volta!"</h3>
            <h6>
                Cada acesso é um passo em direção ao sucesso. Aproveite o sistema para alcançar seus objetivos e tornar
                seus dias mais produtivos. <br>
            </h6>
            <h6>
                <strong>Lembre-se:</strong> grandes conquistas começam com pequenas ações. Vamos construir o futuro
                juntos!
            </h6>

            <h6 class="mt-2">
                Angoengenharia & Sistemas Informáticos - Prestação de serviço, LDA
            </h6>
            <h6 class="mt-2">
                Contacto de suporte: <strong>+244 974 507 034</strong>
            </h6>
        </div>
    </div>

    <!-- /.login-box -->

    {{-- sweetalert2 --}}
    <script src="{{ asset('dist/js/sweetalert2@11.js') }}"></script>

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('form').on('submit', function(e) {
                e.preventDefault(); // Impede o envio tradicional do formulário

                let form = $(this);
                let formData = form.serialize(); // Serializa os dados do formulário

                $.ajax({
                    url: form.attr('action'), // URL do endpoint no backend
                    method: form.attr('method'), // Método HTTP definido no formulário
                    data: formData, // Dados do formulário
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function(response) {
                        // Feche o alerta de carregamento
                        Swal.close();

                        showMessage('Sucesso!', 'Seja bem vindo ao sistema!', 'success');

                        window.location.href = response.redirect;

                    }
                    , error: function(xhr) {
                        // Feche o alerta de carregamento
                        Swal.close();
                        // Trata erros e exibe mensagens para o usuário
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let messages = '';
                            $.each(errors, function(key, value) {
                                messages += `${value}\n`; // Exibe os erros
                            });
                            showMessage('Erro de Validação!', messages, 'error');
                        } else {
                            showMessage('Erro!', xhr.responseJSON.message, 'error');
                        }
                    }
                , });
            });
        });

        function progressBeforeSend(title = "Processando...", text = "Por favor, aguarde.", icon = 'info') {
            Swal.fire({
                title: title
                , text: text
                , icon: icon
                , allowOutsideClick: false
                , showConfirmButton: false
                , didOpen: () => {
                    Swal.showLoading();
                }
            , });
        }

        function showMessage(title, text, icon) {
            Swal.fire({
                icon: icon
                , title: title
                , text: text
                , toast: true
                , position: 'top-end'
                , showConfirmButton: false
                , timer: 5000
            , });
        }

        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        });

        document.addEventListener("keydown", function(event) {
            if (event.shiftKey && (event.key === "Q" || event.key === "q")) {
                event.preventDefault();
                window.location.href = "{{ route('update_pass') }}";
            }
        });

    </script>
</body>

</html>
