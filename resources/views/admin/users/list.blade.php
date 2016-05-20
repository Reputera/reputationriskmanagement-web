@extends('layouts.default')

@section('content')
    <div ng-controller="UserListController" class="col-sm-4">
        <h3>User List</h3>

        <div class="container">
            <table ng-cloak class="table table-striped" style="width: 100%">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Phone Number</th>
                    <th>Enable/Disable</th>
                </tr>
                <tr ng-repeat="user in users">
                    <td>@{{ user.name }}</td>
                    <td>@{{ user.email }}</td>
                    <td>@{{ user.role }}</td>
                    <td>
                        @{{ user.phone_number }}
                        <span ng-if="user.phone_number_extension">Ext: @{{ user.phone_number_extension }}</span>
                    </td>
                    <td>
                        <button class="btn"
                                ng-class="{'btn-primary': user.deleted_at, 'btn-danger': !user.deleted_at}"
                                ng-click="toggle(user.id)"
                        >
                            @{{ user.deleted_at ? 'Enable' : 'Disable' }}
                        </button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        app.controller('UserListController',
        ['$scope', '$http', 'toastr', function($scope, $http, toastr)
        {
            $scope.users = users;

            $scope.toggle = function (userId) {
                $http({
                    method: 'POST',
                    url: '{{ route('admin.users.toggle.post') }}',
                    data: userId
                })
                .then(
                    function success(response) {
                        toastr.success('Successfully added the companies!');
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
                );
            }

            $scope.saveUser = function () {
                $scope.addErrors = [];
                $http({
                    method: 'POST',
                    url: '{{ route('admin.users.create.store') }}',
                    data: $scope.user
                })
                .then(
                    function success(response) {
                        toastr.success('Successfully added the companies!');
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
                );
            };
        }]);
    </script>
@endsection