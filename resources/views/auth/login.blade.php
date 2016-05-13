@extends('layouts.auth')

@section('content')
    <form action="{{ route('login.post') }}" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <div class="form-group has-feedback">
            <input type="email" name="email" required class="form-control" placeholder="Email"/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" required name="password" class="form-control" placeholder="Password"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-8">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember" value="1"> Remember Me
                    </label>
                </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
                <button type="submit" name="signin" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div><!-- /.col -->
        </div>
    </form>
    <a class="text-red" href="{{ route('password.reset.get') }}">Forgot Password?</a>
@endsection