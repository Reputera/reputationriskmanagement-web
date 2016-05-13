@extends('layouts.auth')

@section('content')
    <h3 class="text-center">Reset Password <br /><br />{{ $email }}</h3>
    <div class="panel-body">
        <form class="form-horizontal center-block" style="width: 80%" role="form" method="POST"
              action="{{ route('password.reset.post') }}">
            {!! csrf_field() !!}
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span>
                    </div>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                </div>
                @if ($errors->has('password'))
                    <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                <div class="input-group">
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span>
                    </div>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
                </div>
                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                @endif
            </div>

            <div class="form-group">
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-btn fa-refresh"></i>&nbsp;&nbsp;&nbsp;Reset Password
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
