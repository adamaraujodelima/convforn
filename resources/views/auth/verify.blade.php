@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Confirmação de conta via e-mail') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Um link de verificação foi enviado para seu endereço de e-mail.') }}
                        </div>
                    @endif

                    {{ __('Antes de prosseguir, verifique seu e-mail para encontrar nosso link de verificação.') }}
                    {{ __('Se você não recebeu o email') }}, <a href="{{ route('verification.resend') }}">{{ __('clique aqui para receber um novo link') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
