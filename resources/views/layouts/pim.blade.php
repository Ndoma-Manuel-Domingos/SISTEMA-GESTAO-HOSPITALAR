<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestão de Licença e Segurança</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">


    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">


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
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-size: cover;
            opacity: 0.9;
            filter: blur(5px);
            /* Ajuste o nível de desfoque aqui */
            z-index: -1;
            /* Certifique-se de que o fundo fique atrás do conteúdo */
        }
    </style>
    {{-- <link rel="stylesheet" href="../../dist/css/adminlte.min.css?v=3.2.0"> --}}
</head>

<body class="hold-transition login-page container-bg">

    <!-- Login -->
    @yield('content')
    <!-- /.Login -->
    {{-- <script src="../../dist/js/adminlte.min.js?v=3.2.0"></script> --}}
    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

</body>

</html>
