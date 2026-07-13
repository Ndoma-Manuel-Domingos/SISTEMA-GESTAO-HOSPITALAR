<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Cartões Funcionário</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px;
            gap: 4px;
        }

        .cartoes-container {
            display: flex;
            gap: 10px;
        }

        .card {
            width: 350px;
            height: 200px;
            border-radius: 0;
            box-shadow: 0 0 8px rgba(0,0,0,0.3);
            overflow: hidden;
            background: white;
            position: relative;
        }

        /* Frente */
        .frente {
            background: linear-gradient(120deg, #0056b3, #0099ff, #00ccff);
            color: white;
            padding: 15px;
            justify-content: flex-start;
        }
        .foto {
            width: 80px;
            height: 100px;
            border-radius: 6px;
            background: #fff;
            overflow: hidden;
            margin-right: 15px;
            flex-shrink: 0;
        }
        .foto img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .dados h2 { margin: 0; font-size: 18px; }
        .dados p { margin: 3px 0; font-size: 14px; }

        /* Verso */
        .verso {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }
        
        .horizontal {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
        }

        /* Vertical */
        .card.vertical {
            width: 200px;
            height: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px;
        }
        
        .card.vertical .foto {
            width: 100px;
            height: 120px;
            margin: 0 auto 10px auto;
        }
        .card.vertical .dados h2 { font-size: 16px; text-align: center; }
        .card.vertical .dados p { font-size: 13px; text-align: center; }
        
        
        .header-empresa {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            margin-bottom: 10px;
        }
        
        .header-empresa img {
            height: 40px;
            width: auto;
        }
        
        .header-empresa h3 {
            margin: 0;
            font-size: 14px;
            color: white; /* no verso pode mudar para preto */
        }
        
    </style>
</head>
<body>
    <div class="cartoes-container" id="vertical-duplo">
        <!-- Frente Vertical -->
        <div class="card vertical frente" id="frente-vertical">
            <div class="header-empresa">
                <img src="{{ asset('images/empresa/'.$empresa_logada->empresa->logotipo) }}" alt="Logo">
                <h3>{{ $LOJAACTIVAOPERADOR->nome }}</h3>
            </div>
            <div class="foto">
                <img src="{{ asset('images/funcionarios/'.$funcionario->foto) }}">
            </div>
            <div class="dados">
                <h2>{{ $funcionario->nome }}</h2>
                <p><b>Função:</b> {{ $funcionario->contrato->cargo->nome }}</p>
                <p><b>Nº Mec:</b> {{ $funcionario->numero_mecanografico }}</p>
                <p><b>Validade:</b> {{ \Carbon\Carbon::parse($funcionario->data_validade)->format('d/m/Y') }}</p>
            </div>
        </div>

        <!-- Verso Vertical -->
        <div class="card vertical verso" id="verso-vertical">
            {!! QrCode::size(180)->generate($funcionario->numero_mecanografico . ' - ' . $funcionario->nome) !!}
        </div>
    </div>

    <br>
    <button onclick="imprimirCartoes()">🖨️ Imprimir Tudo</button>
    <button onclick="downloadPNG('frente-vertical')">⬇️ Download Frente Vertical</button>
    <button onclick="downloadPNG('verso-vertical')">⬇️ Download Verso Vertical</button>
    
    <button onclick="imprimirContainer('vertical-duplo')">🖨️ Imprimir Cartão Vertical Duplo</button>
    <button onclick="downloadPNG('vertical-duplo')">⬇️ Download Vertical Duplo</button>

    <!-- JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        function imprimirCartoes() {
            window.print();
        }

        function imprimirContainer(id) {
            const container = document.getElementById(id).outerHTML;
            const win = window.open('', '_blank');
            win.document.write('<html><head><title>Impressão</title></head><body>' + container + '</body></html>');
            win.document.close();
            win.print();
        }

        function downloadPNG(id) {
            const card = document.getElementById(id);
            html2canvas(card).then(canvas => {
                const link = document.createElement("a");
                link.download = id + ".png";
                link.href = canvas.toDataURL("image/png");
                link.click();
            });
        }
    </script>
</body>
</html>
