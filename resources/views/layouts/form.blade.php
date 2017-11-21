@extends('core')

@section('layout')
    <div class="d-flex justify-content-center mt-5">
        <div class="card">
            <div class="card-body">
                @yield('content')
            </div>
        </div>
    </div>
@endsection