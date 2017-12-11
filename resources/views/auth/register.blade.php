@extends('layouts.form')

@section('content')
    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
        <ul>
        @foreach($errors as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
        {{ csrf_field() }}
        <div class="form-group">
            <label for="name">Jméno:</label>
            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
            @if ($errors->has('name'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('name') }}</strong>
                </div>
            @endif
        </div>
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
        <div class="form-group">
            <label for="password_confirmation">Heslo znovu:</label>
            <input id="password_confirmation" type="password" class="form-control{{ $errors->has('password_confirmation') ? ' has-error' : '' }}" name="password_confirmation" required>
            @if ($errors->has('password_confirmation'))
                <div class="invalid-feedback">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </div>
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Registrovat</button>
        <a class="btn btn-link" href="{{ route('login') }}">Již jste registrováni?</a>
    </form>
@endsection