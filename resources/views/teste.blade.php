{{-- @extends('adminlte::page') --}}

@extends('layouts.app')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div class="d-flex align-items-center">

            <div class="logo-box mr-3">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>

            <div>
                <h4 class="mb-0 font-weight-bold">Nova Factura</h4>
                <small class="text-muted">Emita em segundos</small>
            </div>

        </div>

        <div class="w-25">
            <div class="d-flex justify-content-between mb-1">
                <small>Progresso</small>
                <small class="font-weight-bold">70%</small>
            </div>

            <div class="progress custom-progress">
                <div class="progress-bar progress-purple" style="width:70%"></div>
            </div>
        </div>

    </div>

    {{-- HERO --}}
    <div class="hero-card mb-4">

        <div>
            <span class="draft-badge">
                Rascunho · FT 2026/0001
            </span>

            <h1 class="hero-total mt-3">
                38 190,00 Kz
            </h1>

            <p class="hero-subtitle">
                2 itens · vence 24/06/2026
            </p>
        </div>

        <div class="hero-stats">

            <div class="hero-mini-card">
                <small>ITENS</small>
                <h5>2</h5>
            </div>

            <div class="hero-mini-card">
                <small>IVA</small>
                <h5>4 690,00 Kz</h5>
            </div>

            <div class="hero-mini-card">
                <small>PRAZO</small>
                <h5>29d</h5>
            </div>

        </div>

    </div>

    <div class="row">

        {{-- LEFT --}}
        <div class="col-lg-8">

            {{-- ITEMS --}}
            <div class="card invoice-card">

                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="font-weight-bold mb-1">
                            Itens
                        </h5>

                        <small class="text-muted">
                            Produtos e serviços
                        </small>
                    </div>

                    <button class="btn btn-gradient">
                        <i class="fas fa-plus mr-2"></i>
                        Adicionar item
                    </button>

                </div>

                <div class="card-body p-0">

                    {{-- TAGS --}}
                    <div class="px-4 pb-3">

                        <span class="service-tag">+ Consultoria Técnica (h)</span>
                        <span class="service-tag">+ Licença Software Mensal</span>
                        <span class="service-tag">+ Manutenção Preventiva</span>
                        <span class="service-tag">+ Hospedagem Cloud</span>

                    </div>

                    {{-- TABLE --}}
                    <div class="table-responsive">

                        <table class="table align-middle mb-0">

                            <thead>

                                <tr>
                                    <th>DESIGNAÇÃO</th>
                                    <th>QT</th>
                                    <th>P.UNIT</th>
                                    <th>IVA%</th>
                                    <th>DESC%</th>
                                    <th class="text-right">TOTAL</th>
                                    <th></th>
                                </tr>

                            </thead>

                            <tbody>

                                <tr>

                                    <td>

                                        <div class="d-flex align-items-center">

                                            <span class="dot-purple"></span>

                                            <strong>
                                                Licença Software Mensal
                                            </strong>

                                        </div>

                                    </td>

                                    <td width="80">
                                        <input type="number" class="form-control custom-input" value="1">
                                    </td>

                                    <td width="140">
                                        <input type="number" class="form-control custom-input" value="25000">
                                    </td>

                                    <td width="90">
                                        <input type="number" class="form-control custom-input" value="14">
                                    </td>

                                    <td width="90">
                                        <input type="number" class="form-control custom-input" value="0">
                                    </td>

                                    <td class="text-right font-weight-bold">
                                        28 500,00 Kz
                                    </td>

                                    <td width="40">

                                        <button class="btn btn-sm btn-light">
                                            <i class="far fa-trash-alt"></i>
                                        </button>

                                    </td>

                                </tr>

                                <tr>

                                    <td>

                                        <div class="d-flex align-items-center">

                                            <span class="dot-purple"></span>

                                            <strong>
                                                Manutenção Preventiva
                                            </strong>

                                        </div>

                                    </td>

                                    <td>
                                        <input type="number" class="form-control custom-input" value="1">
                                    </td>

                                    <td>
                                        <input type="number" class="form-control custom-input" value="8500">
                                    </td>

                                    <td>
                                        <input type="number" class="form-control custom-input" value="14">
                                    </td>

                                    <td>
                                        <input type="number" class="form-control custom-input" value="0">
                                    </td>

                                    <td class="text-right font-weight-bold">
                                        9 690,00 Kz
                                    </td>

                                    <td>

                                        <button class="btn btn-sm btn-light">
                                            <i class="far fa-trash-alt"></i>
                                        </button>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                    {{-- TOTALS --}}
                    <div class="totals-box">

                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <strong>33 500,00 Kz</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2 text-danger">
                            <span>Desconto (0%)</span>
                            <strong>-0,00 Kz</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>IVA</span>
                            <strong>4 690,00 Kz</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2 text-danger">
                            <span>Retenção</span>
                            <strong>-0,00 Kz</strong>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between total-highlight">

                            <span>Total</span>

                            <strong>
                                38 190,00 Kz
                            </strong>

                        </div>

                    </div>

                </div>

            </div>

            {{-- CLIENT --}}
            <div class="card invoice-card mt-4">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="font-weight-bold">
                                Cliente
                            </h5>

                            <small class="text-muted">
                                A quem se destina esta factura
                            </small>

                        </div>

                        <button class="btn btn-light rounded-pill px-4">

                            <i class="fas fa-search mr-2"></i>

                            Selecionar

                        </button>

                    </div>

                    <div class="client-placeholder mt-4">

                        Nenhum cliente selecionado — clique para escolher

                    </div>

                </div>

            </div>

            {{-- OBS --}}
            <div class="card invoice-card mt-4">

                <div class="card-body">

                    <h5 class="font-weight-bold">
                        Observação
                    </h5>

                    <small class="text-muted">
                        Notas internas e referência externa
                    </small>

                    <textarea class="form-control observation-area mt-3" rows="5" placeholder="Observação..."></textarea>

                </div>

            </div>

        </div>

        {{-- RIGHT --}}
        <div class="col-lg-4">

            <div class="card invoice-card sticky-sidebar">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <h4 class="font-weight-bold mb-0">
                            Definições
                        </h4>

                        <span class="badge badge-light">
                            FT
                        </span>

                    </div>

                    <small class="text-muted">
                        Factura · Data de emissão: hoje
                    </small>

                    {{-- CAIXA --}}
                    <div class="form-group mt-4">

                        <label>Caixa</label>

                        <select class="form-control custom-select-box">
                            <option>Caixa Principal (Loja Principal)</option>
                        </select>

                    </div>

                    <div class="row">

                        <div class="col-6">

                            <div class="form-group">

                                <label>Emissão</label>

                                <input type="date" class="form-control custom-input">

                            </div>

                        </div>

                        <div class="col-6">

                            <div class="form-group">

                                <label>Vencimento</label>

                                <input type="date" class="form-control custom-input">

                            </div>

                        </div>

                    </div>

                    <div class="form-group">

                        <label>Disponibilização</label>

                        <input type="date" class="form-control custom-input">

                    </div>

                    {{-- RANGE --}}
                    <div class="form-group mt-4">

                        <label>Desconto global (0%)</label>

                        <input type="range" class="custom-range">

                    </div>

                    {{-- PAGAMENTO --}}
                    <div class="form-group mt-4">

                        <label>Pagamento</label>

                        <select class="form-control custom-select-box">
                            <option>Numerário</option>
                            <option>Transferência</option>
                            <option>TPA</option>
                        </select>

                    </div>

                    {{-- PAY --}}
                    <div class="pay-box mt-4">

                        <small>TOTAL A PAGAR</small>

                        <h2>38 190,00 Kz</h2>

                    </div>

                    <button class="btn btn-gradient btn-lg btn-block mt-4">

                        <i class="far fa-paper-plane mr-2"></i>

                        Criar Factura

                    </button>

                    <p class="text-center text-muted small mt-4 mb-0">
                        Ao criar, a factura será emitida e bloqueada para edição.
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@section('styles')

<style>
    body {
        background: #f4f5fb !important;
    }

    .invoice-card {
        border: none;
        border-radius: 28px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .04);
    }

    .logo-box {
        width: 55px;
        height: 55px;
        border-radius: 18px;
        background: linear-gradient(135deg, #6c4dff, #b56dff);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 22px;
    }

    .custom-progress {
        height: 8px;
        border-radius: 20px;
        overflow: hidden;
        background: #ececf4;
    }

    .progress-purple {
        background: linear-gradient(90deg, #6b4cff, #b86eff);
    }

    .hero-card {
        border-radius: 32px;
        padding: 40px;
        background: linear-gradient(135deg, #5c4cff, #c06cff);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 20px 50px rgba(107, 76, 255, .25);
    }

    .hero-total {
        font-size: 60px;
        font-weight: 800;
        line-height: 1;
    }

    .hero-subtitle {
        opacity: .9;
        font-size: 17px;
    }

    .draft-badge {
        background: rgba(255, 255, 255, .15);
        border: 1px solid rgba(255, 255, 255, .2);
        padding: 8px 16px;
        border-radius: 40px;
        font-size: 13px;
        backdrop-filter: blur(10px);
    }

    .hero-stats {
        display: flex;
        gap: 15px;
    }

    .hero-mini-card {
        background: rgba(255, 255, 255, .12);
        border: 1px solid rgba(255, 255, 255, .15);
        padding: 20px;
        min-width: 130px;
        border-radius: 22px;
        backdrop-filter: blur(15px);
    }

    .hero-mini-card h5 {
        margin-top: 8px;
        font-weight: 700;
    }

    .btn-gradient {
        border: none;
        border-radius: 14px;
        padding: 12px 24px;
        background: linear-gradient(135deg, #6c4dff, #b56dff);
        color: white;
        font-weight: 700;
    }

    .service-tag {
        display: inline-block;
        background: #f5f5fb;
        border: 1px solid #ececf6;
        border-radius: 40px;
        padding: 10px 18px;
        margin-right: 10px;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .table th {
        border-top: none !important;
        font-size: 12px;
        color: #999;
        padding: 20px;
    }

    .table td {
        padding: 18px 20px;
    }

    .custom-input {
        border-radius: 14px;
        border: 1px solid #ececf4;
        height: 48px;
        background: #fafaff;
    }

    .custom-select-box {
        border-radius: 14px;
        border: 1px solid #ececf4;
        height: 48px !important;
        background: #fafaff;
    }

    .dot-purple {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #6b4cff;
        margin-right: 12px;
    }

    .totals-box {
        padding: 35px;
        background: #fcfcff;
        border-top: 1px solid #f1f1f7;
    }

    .total-highlight {
        font-size: 32px;
        color: #7a5cff;
        font-weight: 800;
    }

    .client-placeholder {
        border: 2px dashed #e5e5f1;
        border-radius: 22px;
        padding: 30px;
        text-align: center;
        color: #888;
    }

    .observation-area {
        border-radius: 20px;
        border: 1px solid #ececf4;
        resize: none;
    }

    .pay-box {
        background: #f5f3ff;
        border-radius: 24px;
        text-align: center;
        padding: 30px;
    }

    .pay-box h2 {
        margin-top: 10px;
        color: #6b4cff;
        font-size: 40px;
        font-weight: 800;
    }

    .sticky-sidebar {
        position: sticky;
        top: 20px;
    }

    .custom-range::-webkit-slider-thumb {
        background: #6b4cff;
    }

    @media(max-width:991px) {

        .hero-card {
            flex-direction: column;
            gap: 30px;
            text-align: center;
        }

        .hero-stats {
            width: 100%;
            justify-content: center;
            flex-wrap: wrap;
        }

    }

</style>

@endsection

@section('scripts')

<script>
    $(function() {
        console.log('Factura UI carregada com sucesso.');
    });

</script>

@endsection
