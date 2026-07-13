<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $titulo ?? '' }} | {{ $descricao ?? '' }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">

    <link rel="stylesheet" href="{{ asset('dist/css/sweetalert2.min.css') }}">

    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    <style>
        p{
            font-size: 15pt;
        }    
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
            /* background-image: url('/dist/img/focused-young-man-paying-bill-store.jpg'); */
            background-image: url('/dist/img/5433276-um-empresario-trabalha-em-uma-rede-publica-com-informacoes-protegidas-seguras-gratis-foto.jpg');
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
            margin: 20px auto;
            width: 40px;
            height: 40px;
            border: 4px solid #ccc;
            border-top-color: #007bff;
            border-radius: 50%;
            animation: spin 1s infinite linear;
        }

        @keyframes spin {
           to { transform: rotate(360deg); }
        }
    </style>

</head>

<body class="hold-transition  login-page container-bg text-white">
{{-- <body class="hold-transition  login-page container-bg"> --}}

    <h1>Verifique seu E-mail 📩</h1>
    <p>Sua conta foi criada com sucesso!</p>
    <p>Enviamos um link de verificação para o seu e-mail.</p>
    <p>Por favor, confirme sua conta para continuar.</p>

    <div class="spinner"></div>
    <p>Estamos aguardando a confirmação...</p>

    <script>
        setInterval(() => {
            fetch('/check-verification')
                .then(response => response.json())
                .then(data => {
                    if (data.verified) {
                        window.location.href = '/dashboard';
                    }
                });
        }, 10000); // verifica a cada 10 segundos
    </script>
    
</body>

</html>
