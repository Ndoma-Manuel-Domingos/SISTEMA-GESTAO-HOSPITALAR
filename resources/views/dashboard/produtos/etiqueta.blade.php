<!DOCTYPE html>
<html>
<head>
    <title>Etiqueta JS</title>
    <style>
        body{
            font-family: Arial;
        }

        .etiqueta{
            width: 250px;
            border: 1px solid #000;
            padding: 0 10px;
            text-align: center;
            margin-bottom: 15px;
        }

        .nome{
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }

        .preco{
            font-size: 20px;
            color: red;
            margin-top: 5px;
            text-align: center;
        }

        .barcode{
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="etiqueta">
    <h3 class="nome">{{ $produto->nome }}{{ $produto->unidade->sigla ?? "" }}</h3>
    <h2 class="preco">{{ number_format($produto->preco_venda, 2, ',', '.') }} Kz</h2>
    <svg id="barcode"></svg>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>

<script>

JsBarcode("#barcode", "{{ $produto->codigo_barra }}", {
    format: "CODE128",
    lineColor: "#000",
    width: 2,
    height: 60,
    displayValue: true
});

</script>

</body>
</html>