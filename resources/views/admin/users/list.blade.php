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
                <tr ng-repeat="singleUser in users">
                    <td>@{{ singleUser.name }}</td>
                    <td>@{{ singleUser.email }}</td>
                    <td>@{{ singleUser.role }}</td>
                    <td>
                        @{{ singleUser.phone_number }}
                        <span ng-if="singleUser.phone_number_extension">Ext: @{{ singleUser.phone_number_extension }}</span>
                    </td>
                    <td>
                        <div ng-show="user.id == singleUser.id">
                            Disabled for logged in user.
                        </div>
                        <button class="btn" ng-show="user.id != singleUser.id"
                                ng-class="{'btn-primary': singleUser.deleted_at, 'btn-danger': !singleUser.deleted_at}"
                                ng-click="toggle(singleUser.id)"
                        >
                            @{{ singleUser.deleted_at ? 'Enable' : 'Disable' }}
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
                    data: {
                        id: userId
                    }
                })
                .then(
                    function success(response) {
                        toastr.success('Successfully updated the user!');
                        $scope.users[response.data.data.id]['deleted_at'] = response.data.data.deleted_at;
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
        }]);
    </script>
@endsection