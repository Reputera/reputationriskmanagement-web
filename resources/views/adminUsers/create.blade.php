@extends('layouts.default')

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="col-sm-4">
        <h3>Create User</h3>
        <form method="post"  action="{{route('adminUser.store')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label for="companySelect">Company</label>
                <select class="form-control" id="company_id" name="company_id">
                    @foreach($companies as $company)
                        <option value="{{$company->id}}" "{{(old('company_id') != $company->id) ? '' : 'selected="selected"'}}">{{$company->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="roleSelect">Role</label>
                <select class="form-control" id="roleSelect" name="role">
                    @foreach($userRoles as $userRole)
                        <option value="{{$userRole}}">{{$userRole}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name='name' value="{{old('name')}}" class="form-control" id="name" placeholder="Full Name">
            </div>
            <div class="form-group">
                <label for="userEmail">Email</label>
                <input type="email" name='email' value="{{old('email')}}" class="form-control" id="userEmail" placeholder="example@domain.com">
            </div>
            <div class="form-group">
                <label for="userPassword">Password</label>
                <input type="password" name='password' class="form-control" id="userPassword">
            </div>
            <div class="form-group">
                <label for="userPasswordConfirm">Password Confirm</label>
                <input type="password" name='password_confirmation' class="form-control" id="userPasswordConfirm">
            </div>
            <div class="form-group">
                <div class="text-center">
                    <button type="submit" class="btn btn-default">Create</button>
                </div>
            </div>
        </form>
    </div>
@endsection