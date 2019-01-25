@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">GERAR TOKEN</div>

                <div class="card-body">                    
                    <form method="POST" action="{{ route('getToken') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="client_id" class="col-md-4 col-form-label text-md-right">{{ __('Client ID') }}</label>

                            <div class="col-md-6">
                                <input id="client_id" type="client_id" class="form-control" name="client_id" value="{{ old('client_id') }}" required autofocus>
                            </div>
                        </div>                    
                        <div class="form-group row">
                            <label for="client_secret" class="col-md-4 col-form-label text-md-right">{{ __('Client Secret') }}</label>

                            <div class="col-md-6">
                                <input id="client_secret" type="client_secret" class="form-control" name="client_secret" value="{{ old('client_secret') }}" required autofocus>
                            </div>
                        </div>                    
                        <div class="form-group row">
                            <label for="auth_code" class="col-md-4 col-form-label text-md-right">{{ __('Authorization Code') }}</label>

                            <div class="col-md-6">
                                <input readonly="true" id="auth_code" type="auth_code" class="form-control" name="auth_code" value="{{ $authCode }}" required autofocus>
                            </div>
                        </div> 
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Generate') }}
                                </button>
                            </div>
                        </div>
                    </form>                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
