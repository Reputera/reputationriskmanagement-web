@extends('mail.baseMail')

@section('content')
    <div style="text-align: center">
        <h3 >Password Successfully Rest For {{ $email }}</h3>
        You can sign into the app with you new password now.
    </div>
@endsection
