@extends('layouts.pim')

@section('content')
<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="{{ route('dashboard') }}" class="h1">{{ env('APP_NAME') }}</a>
        </div>
        <div class="card-body pb-5">
            <p class="login-box-msg text-uppercase">Por favor informe o Código de activação da Licença.</p>
            <form action="{{ route('licenca-activa-post') }}" method="post">
                @csrf
                <div class="my-3">
                    <label for="codigo" class="form-label">Código</label>
                    <input type="text" id="codigo" value="{{ old('codigo') }}" name="codigo" class="form-control form-control-lg mb-2" placeholder="Codigo">
                    @error('codigo')
                    <p class="text-light-danger text-center text-uppercase">{{ $message }}</p>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn-lg btn-light-primary btn-block">{{ __('messages.salvar') }}</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer text-center">
            <h4>Olá Sr(a) {{ Auth::user()->name }}, infelizmente a sua licença expirou. Por favor, entre em contato com o administrador
                para receber um código de ativação e reativar o acesso ao sistema.</h4>
        </div>
    </div>
</div>

@endsection
