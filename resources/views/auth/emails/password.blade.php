@extends('mail.baseMail')

@section('content')
    Click here to reset your password: <a href="{{ url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}">Reset</a>
@endsection