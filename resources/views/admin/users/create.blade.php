@extends('layouts.default')

@section('content')
    <div ng-controller="UserCreateController" class="col-sm-4">
        <h3>Create User</h3>
        <br /><br />

        <div class="form-group" ng-class="{ 'has-error' : addErrors['role'] }">
            <label for="roleSelect">Role</label>
            <select class="form-control" id="roleSelect" name="role" ng-model="user['role']">
                <option value="" selected="selected">Select Role</option>
                <option ng-repeat="role in roles"
                        value="@{{ role }}"
                >
                    @{{ role }}
                </option>
            </select>
        </div>

        <div class="form-group" ng-show="user['role'] != 'Admin'" ng-class="{ 'has-error' : addErrors['company_id'] }">
            <label for="companySelect">Company</label>
            <select class="form-control" id="company_id" name="company_id" ng-model="user['company_id']">
                <option value="" selected="selected">Select Company</option>
                <option ng-repeat="company in companies"
                        value="@{{ company.id }}"
                >
                    @{{ company.name }}
                </option>
            </select>
        </div>

        <div class="form-group" ng-class="{ 'has-error' : addErrors['name'] }">
            <label for="name">Name</label>
            <input type="text" name='name' class="form-control"
                   ng-model="user['name']" id="name" placeholder="Full Name">
        </div>

        <div class="form-inline" ng-show="user['role'] != 'Admin'">
            <div class="form-group" ng-class="{ 'has-error' : addErrors['phone_number'] }">
                <label>Phone Number</label>
                <input type="text" class="form-control" style="width: 120px;" ui-options="{clearOnBlur: false}"
                       ng-model="user['phone_number']" ui-mask="(999) 999 - 9999">
            </div>
            <div class="form-group">
                <label>Extension</label>
                <input type="text" class="form-control" style="width: 80px;" ui-options="{clearOnBlur: false}"
                       ng-model="user['phone_number_extension']" ui-mask="?9?9?9?9?9?9?9?9?9?9">
            </div>
        </div>

        <div class="form-group" ng-class="{ 'has-error' : addErrors['email'] }">
            <label for="userEmail">Email</label>
            <input type="email" name='email' class="form-control"
                   ng-model="user['email']" id="userEmail" placeholder="example@domain.com">
        </div>

        <div class="form-group" ng-class="{ 'has-error' : addErrors['password'] }">
            <label for="userPassword">Password</label> (Auto generated)
            <input type="text" name='password' class="form-control" id="userPassword"
                   ng-model="user['password']">
        </div>

        <div class="form-group">
            <div class="text-center">
                <button type="button" ng-click="saveUser()" class="btn btn-default">Create</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        app.controller('UserCreateController',
        ['$scope', '$http', 'toastr', function($scope, $http, toastr)
        {
            $scope.addErrors = [];
            $scope.roles = roles;
            $scope.companies = companies;
            initializeUser();

            $scope.saveUser = function () {
                $scope.addErrors = [];
                $http({
                    method: 'POST',
                    url: '{{route('admin.users.create.store')}}',
                    data: $scope.user
                })
                .then(
                    function success(response) {
                        toastr.success('Successfully added the companies!');
                        initializeUser();
                    },
                    function error(response) {
                        $scope.addErrors = [];
                        if (response.data.hasOwnProperty('errors')) {
                            for (var error_field in response.data.errors) {
                                var field = error_field.split(".");
                                if(typeof $scope.addErrors[field[0]] == "undefined"){
                                    $scope.addErrors[field[0]] = [];
                                }
                                $scope.addErrors[field[0]] = response.data.errors[error_field][0];
                            }
                        }
                    }
                )
            };

            $scope.hasMultipleCompanies = function () {
                return $scope.companiesToAdd.length > 1;
            }

            function generatePassword() {
                var length = 8;
                var text = "";
                var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                for(var i = 0; i < length; i++) {
                    text += possible.charAt(Math.floor(Math.random() * possible.length));
                }
                return text;
            }

            function initializeUser() {
                $scope.user = {password: generatePassword()};
            }
        }]);
    </script>
@endsection