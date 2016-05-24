@extends('layouts.default')

@section('content')
    <div ng-controller="CompanyEditController">
        <h3>Edit Company</h3>
        <br /><br />

        <select ng-model="selectedCompanyId" ng-change="loadVectors()" required>
            <option value="" selected="selected">Select Company</option>
            <option ng-repeat="company in companies" value="@{{ company.id }}">
                @{{ company.name }}
            </option>
        </select>

        <h3>Vector colors</h3>
        <table>
            <tr ng-repeat="vectorColor in vectorColors track by vectorColor.vector_id">
                <td><span>@{{vectorColor.name}}</span></td>
                <td><spectrum-colorpicker format="'hex'" ng-model="vectorColor.color"></spectrum-colorpicker></td>
                <td><button ng-click="saveColor(vectorColor);" class="btn btn-default">Save</button></td>
                <td><button ng-click="resetColor(vectorColor);" class="btn btn-default">Reset</button></td>
            </tr>
        </table>
    </div>
@endsection

@section('scripts')
<script>
    app.controller('CompanyEditController',
            ['$scope', '$http', 'toastr', 'ModalService', 'ApplicationConfig', function($scope, $http, toastr)
    {
        $scope.companies = companies.data;

        $scope.loadVectors = function() {
            $http({
                method: 'GET',
                url: '/vectorColors?company_id=' + $scope.selectedCompanyId
            }).then(function(result) {
                $scope.vectorColors = result.data.data;
            });
        };

        $scope.saveColor = function(vectorColor) {
            $http({
                method: 'POST',
                url: '/adminVectorColor',
                data: {
                    company_id: $scope.selectedCompanyId,
                    vector_id: vectorColor.vector_id,
                    color: vectorColor.color
                }
            }).then(function(result) {
                $scope.loadVectors();
            });
        };

        $scope.resetColor = function(vectorColor) {
            $scope.saveColor({vector_id: vectorColor.vector_id, color:vectorColor.default_color});
        }
    }]);

</script>
@endsection