@extends('layouts.default')

@section('content')
    <div ng-controller="CompanyEditController">
        <h3>Edit Company</h3>
        <br /><br />

        <select ng-model="selectedCompanyId" required>
            <option value="" selected="selected">Select Company</option>
            <option ng-repeat="company in companies" value="@{{ company.id }}">
                @{{ company.name }}
            </option>
        </select>

        <h3>Vector colors</h3>
        <table>
            <tr ng-repeat="vector in vectors.data track by vector.id">
                <td><span>@{{vector.name}}</span></td>
                <td><spectrum-colorpicker format="'hex'" ng-model="vector.color"></spectrum-colorpicker></td>
                <td><button ng-click="saveColor(vector);" class="btn btn-default">Save</button></td>
                <td><button ng-click="resetColor(vector);" class="btn btn-default">Reset</button></td>
            </tr>
        </table>
    </div>
@endsection

@section('scripts')
<script>
    app.controller('CompanyEditController',
            ['$scope', '$http', 'toastr', 'ModalService', 'ApplicationConfig', function($scope, $http, toastr, ModalService, ApplicationConfig)
    {
        $scope.vectors = vectors;
        $scope.companies = companies.data;

        $scope.saveColor = function(vector) {
            console.log(vector.color);
            $http({
                method: 'POST',
                url: '/adminVectorColor',
                data: {
                    company_id: $scope.selectedCompanyId,
                    vector_id: vector.id,
                    color: vector.color
                }
            }).then(function(result) {
                $scope.loadVectors();
            });
        }

        $scope.loadVectors = function() {
            $http({
                method: 'GET',
                url: 'vectors'
            }).then(function(result) {
                $scope.vectors = result.data;
            })
        }

        $scope.resetColor = function(vector) {
            $scope.saveColor({id: vector.id, color:vector.default_color});
        }
    }]);

</script>
@endsection