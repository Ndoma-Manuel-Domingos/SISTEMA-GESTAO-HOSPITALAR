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
                <div class="card-header text-center">

                    @if ($getMacAddress)
                    <h4 class="text-center bg-light-warning p-2">{{ $getMacAddress }}</h4>
                    @endif

                    @if (Auth::user())
                    @if (Auth::user()->empresa->nif == "" || Auth::user()->empresa->nif == null)
                    <a href="{{ route('registrar.nif') }}" class="text-center btn btn-light-primary my-3">Precisas Informar o seu NIF, pela primeira Vez</a>
                    @else
                    <h4 class="text-center bg-light-warning p-2">{{ Auth::user()->empresa->nif }}</h4>
                    @endif
                    @else
                    <h4 class="text-center  bg-light-primary" p-2">Precisas primeiramente fazer o login</h4>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                </div>
                <div class="card-body">
                    <form action="{{ route('licenses.validate') }}" method="post" class="row" enctype="multipart/form-data">
                        @csrf

                        <div class="col-12 col-md-12 mb-2">
                            <label for="license_file">Ficheiro de licença (.txt) <span class="text-light-danger">*</span></label>
                            <div class="input-group">
                                <input type="file" class="form-control form-control-lg" id="license_file" required name="license_file" placeholder="Ficheiro de licença">
                            </div>
                        </div>

                        <div class="col-12 col-md-12 my-5">
                            <button type="submit" class="btn-lg btn-outline-dark btn-block">Activar Licença (Carregar ficheiro)</button>
                        </div>
                    </form>

                </div>

                <div class="card-footer">
                    <p class="mb-0 mt-4 text-center row">
                        <a href="{{ route('login') }}" class="text-center h4 col-12 col-md-12">Voltar Para Login</a> <br>
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

</body>

</html>
