@extends('layouts.form')

@section('content')
    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}
        @if(env('APP_DEBUG'))
            <small class="form-text text-muted">Random e-mail: <code>{{ \App\User::first()->email }}</code></small>
        @endif
        <div class="form-group">
            <label for="email">E-mail:</label>
            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
            @if ($errors->has('email'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </div>
            @endif
        </div>
        <div class="form-group">
            <label for="password">Heslo:</label>
            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' has-error' : '' }}" name="password" required>

            @if ($errors->has('password'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('password') }}</strong>
                </div>
            @endif
        </div>
        <div class="form-check mb-3">
            <label class="form-check-label">
                <input type="checkbox" name="remember" class="form-check-input"{{ old('remember') ? ' checked' : '' }}> Zapamatovat si mě
            </label>
        </div>
        <button type="submit" class="btn btn-primary">Přihlásit</button>
        <a class="btn btn-link" href="{{ route('password.request') }}">Zapomněli jste heslo?</a>
    </form>
@endsection
