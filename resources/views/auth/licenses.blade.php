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
            background-image: url('/dist/img/focused-young-man-paying-bill-store.jpg');
            /* background-image: url('/dist/img/5433276-um-empresario-trabalha-em-uma-rede-publica-com-informacoes-protegidas-seguras-gratis-foto.jpg'); */
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

        .scroll-container {
            max-height: 80vh;
            /* Ajuste conforme necessário */
            overflow-y: auto;
            /* Ativa o scroll vertical */
        }

    </style>

</head>

<body class="hold-transition login-page container-bg">
    <div class="login-box">
        <div class="card card-outline card-dark">
            <div class="scroll-container mt-5">
                <div class="card-body">
                    <form action="{{ route('licenses.generate') }}" method="post" class="row">
                        @csrf
                        <div class="col-12 col-md-12 mb-2">
                            <label for="nif">NIF <span class="text-light-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-lg" name="nif" id="nif" value="{{ old('nif') ?? '' }}" placeholder="Nome da Empresa">
                            </div>
                        </div>

                        <div class="col-12 col-md-12 mb-2">
                            <label for="mac">MAC <span class="text-light-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-lg" name="mac" id="mac" value="{{ old('mac') ?? '' }}" placeholder="Endereco da Maquina">
                            </div>
                        </div>

                        <div class="col-12 col-md-12 mb-2">
                            <label for="start_date">Data Início <span class="text-light-danger">*</span></label>
                            <div class="input-group">
                                <input type="date" class="form-control form-control-lg" id="start_date" name="start_date" placeholder="Data Inicio">
                            </div>
                        </div>

                        <div class="col-12 col-md-12 mb-2">
                            <label for="end_date">Data Fim <span class="text-light-danger">*</span></label>
                            <div class="input-group">
                                <input type="date" class="form-control form-control-lg" id="end_date" name="end_date" placeholder="Data Final">
                            </div>
                        </div>

                        <div class="col-12 col-md-12 my-5">
                            <button type="submit" class="btn-lg btn-outline-dark btn-block">Gerar e Descarregar Licença</button>
                        </div>
                    </form>

                </div>

                <div class="card-footer">
                    <p class="mb-0 mt-4 text-right row">
                        <a href="{{ route('login') }}" class="text-right h4 col-12 col-md-6">Voltar Para Login</a> <br>
                        <a href="{{ route('licenses.upload') }}" class="text-right h4 col-12 col-md-6">Activar Licença</a> <br>
                    </p>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    {{-- sweetalert2 --}}
    <script src="{{ asset('dist/js/sweetalert2@11.js') }}"></script>

    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

    <script>
        // $(document).ready(function() {
        //     $('form').on('submit', function(e) {
        //         e.preventDefault(); // Impede o envio tradicional do formulário

        //         let form = $(this);
        //         let formData = form.serialize(); // Serializa os dados do formulário

        //         $.ajax({
        //             url: form.attr('action'), // URL do endpoint no backend
        //             method: form.attr('method'), // Método HTTP definido no formulário
        //             data: formData, // Dados do formulário
        //             headers: {
        //                 "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        //             },
        //             beforeSend: function() {
        //                 // Você pode adicionar um loader aqui, se necessário
        //                 progressBeforeSend();
        //             },
        //             success: function(response) {
        //                 // Feche o alerta de carregamento
        //                 Swal.close();

        //                 showMessage('Sucesso!', 'Seja bem vindo ao sistema!', 'success');

        //                 window.location.href = response.redirect;

        //             },
        //             error: function(xhr) {
        //                 // Feche o alerta de carregamento
        //                 Swal.close();
        //                 // Trata erros e exibe mensagens para o usuário
        //                 if (xhr.status === 422) {
        //                     let errors = xhr.responseJSON.errors;
        //                     let messages = '';
        //                     $.each(errors, function(key, value) {
        //                         messages += `${value}\n`; // Exibe os erros
        //                     });
        //                     showMessage('Erro de Validação!', messages, 'error');
        //                 } else {
        //                     showMessage('Erro!', xhr.responseJSON.message, 'error');
        //                 }
        //             },
        //         });
        //     });
        // });

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

        function showProgressModal() {
            const modal = document.getElementById("loading-modal");
            modal.style.display = "flex";
            modal.style.zIndex = "999999"; // Corrigido: zIndex ao invés de z-index
        }

        function hideProgressModal() {
            const modal = document.getElementById("loading-modal");
            modal.style.display = "none";
            modal.style.zIndex = "-2"; // Corrigido: zIndex ao invés de z-index
        }

    </script>
</body>

</html>
