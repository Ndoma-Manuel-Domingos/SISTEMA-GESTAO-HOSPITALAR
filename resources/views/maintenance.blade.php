<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sistema Bloqueado</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 10%;
        }

        h1 {
            color: #c0392b;
        }

    </style>
</head>
<body>
    <h1>🚨 Sistema Bloqueado</h1>
    <p style="text-align: center; font-size: 15pt">Pedimos desculpa pela interrupção nos seus serviços. No entanto, é necessário confirmar a sua licença. <br>Por favor, entre em contacto com o administrador para proceder ao desbloqueio.</p>
    <p style="text-align: center; font-size: 30pt">AngoEngenharia e sistemas informáticos</p>
    <p>(+244 942-39-35-08) (+244 974-70-50-54)</p>


    <!-- Modal oculto -->
    <div id="unlockModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
        background:rgba(0,0,0,0.8); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:#fff; padding:20px; border-radius:10px; width:300px; text-align:center;">
            <h3>Sistema Bloqueado 🔒</h3>
            <p>Digite a senha master para desbloquear</p>
            <input type="password" id="masterPassword" class="form-control" placeholder="Senha Master"><br>
            <button id="btnUnlock" class="btn btn-light-success">Desbloquear</button>
        </div>
    </div>


</body>
</html>


<script>
    let tentativas = 0;
    let bloqueado = false;

    // Mostrar modal ao pressionar ALT+F2
    document.addEventListener("keydown", function(event) {
        if (event.altKey && event.key === "F2") {
            document.getElementById("unlockModal").style.display = "flex";
        }
    });

    // A cada 2 minutos, pedir senha (se não estiver bloqueado)
    setInterval(() => {
        if (!bloqueado) {
            document.getElementById("unlockModal").style.display = "flex";
        }
    }, 120000); // 120000ms = 2min

    // Clique no botão desbloquear
    document.getElementById("btnUnlock").addEventListener("click", function() {
        let senha = document.getElementById("masterPassword").value;

        fetch("/verificar-senha", {
                method: "POST"
                , headers: {
                    "Content-Type": "application/json"
                    , "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
                , body: JSON.stringify({
                    senha
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.sucesso) {
                    alert("✅ Sistema desbloqueado!");
                    tentativas = 0;
                    bloqueado = false;
                    document.getElementById("unlockModal").style.display = "none";
                } else {
                    tentativas++;
                    alert("❌ Senha incorreta (" + tentativas + "/3)");

                    if (tentativas >= 3) {
                        bloqueado = true;
                        alert("🚫 Sistema bloqueado permanentemente!");
                        // Aqui você pode chamar rota para renomear pastas no backend
                        fetch("/bloquear-sistema", {
                            method: "POST"
                            , headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            }
                        });
                    }
                }
            });
    });

</script>
