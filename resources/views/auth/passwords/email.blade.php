@extends('layouts.auth')

@section('content')
    <h2 class="text-center" align="center" style="margin-top: -10px; padding-bottom: 10px;">
        Reset Password
    </h2>
    <form action="{{ route('password.email.post') }}" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <div class="form-group has-feedback">
            <input type="email" name="email" required class="form-control" placeholder="Email"/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>

        <div class="form-group">
            <button type="submit" name="signin" class="btn btn-primary btn-block btn-flat">
                Reset Password
            </button>
        </div>

        <div class="form-group">
            <a class="text-red" href="{{ route('login.get') }}">Login</a>
        </div>
    </form>
@endsection