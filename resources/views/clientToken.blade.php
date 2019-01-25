@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">TOKEN INFO</div>

                <div class="card-body">
                    <div class="form-group row">
                        <label for="client_id" class="col-md-2 col-form-label text-md-right">{{ __('Token Type') }}</label>

                        <div class="col-md-10">
                            <input disabled id="client_id" type="client_id" class="form-control" name="client_id" value="{{ $response['token_type'] }}" required autofocus>
                        </div>
                    </div>                    
                    <div class="form-group row">
                        <label for="client_secret" class="col-md-2 col-form-label text-md-right">{{ __('Acess Token') }}</label>

                        <div class="col-md-10">
                            <textarea name="client_secret" id="client_secret" cols="30" rows="10" class="form-control">{{ $response['access_token'] }}</textarea>
                        </div>
                    </div>                    
                    <div class="form-group row">
                        <label for="auth_code" class="col-md-2 col-form-label text-md-right">{{ __('Refresh Token') }}</label>

                        <div class="col-md-10">
                            <textarea name="auth_code" id="auth_code" cols="30" rows="10" class="form-control">{{ $response['refresh_token'] }}</textarea>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
