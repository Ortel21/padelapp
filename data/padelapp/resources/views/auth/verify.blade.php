@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verifica tu correo electrónico') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Un nuevo link de verificación ha sido enviado al correo asociado.') }}
                        </div>
                    @endif

                    {{ __('Antes de seguir, por favor verifique el correo electrónico') }}
                    {{ __('Si no ha recibido el correo.') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('Haga "click" aquí para pedir otro.') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
