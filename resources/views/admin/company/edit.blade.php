@extends('layouts.default')

@section('content')
    <div ng-controller="CompanyEditController">
        <h3>Edit Company</h3>
        <br /><br />

        <select ng-model="selectedCompanyId" ng-change="selectCompany()" required>
            <option value="" selected="selected">Select Company</option>
            <option ng-repeat="company in companies" value="@{{ company.id }}">
                @{{ company.name }}
            </option>
        </select>

        <div ng-show="selectedCompanyId">
            <h3>Alerts</h3>
            <label for="min_alert_threshold">Minimum alert</label>
            <input type="text" class="form-control" ng-model="company.min_alert_threshold" name="min_alert_threshold">
            <label for="max_alert_threshold">Maximum alert</label>
            <input type="text" class="form-control" ng-model="company.max_alert_threshold" name="max_alert_threshold">
            <button class="btn btn-primary" ng-click="updateCompany()">Update</button>

            <h3>Company Logo</h3>
            <p>Current logo:</p>
            <div id="logoDiv">
                <img name="logoImage" ng-src="@{{currentLogo}}">
            </div>
            <p>Upload new logo</p>
            <input type='file' file-model='file' />
            <button class="btn btn-primary" ng-click="uploadLogo()">Upload</button>

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
    </div>
@endsection

@section('scripts')
<script>
    app.controller('CompanyEditController',
            ['$scope', '$http', 'toastr', 'ApplicationConfig', function($scope, $http, toastr, ApplicationConfig)
    {
        $scope.companies = companies.data;

        $scope.selectCompany = function() {
            $scope.refreshLogo();
            $scope.loadVectors();
            $scope.refreshCompany();
        };

        $scope.refreshCompany = function() {
            $http.get('/company/get/' + $scope.selectedCompanyId).then(function(result) {
                $scope.company = result.data.data;
            });
        };

        $scope.refreshCompanyList = function() {
            $http.get('/company').then(function(result) {
                $scope.companies = result.data.data;
            });
        };

        $scope.updateCompany = function() {
            $http({
                method: 'POST',
                url: '/company/update/' + $scope.selectedCompanyId,
                data: {
                    min_alert_threshold: parseInt($scope.company.min_alert_threshold),
                    max_alert_threshold: parseInt($scope.company.max_alert_threshold)
                }
            }).then(function(result) {
                $scope.refreshCompanyList();
            });
        };

        $scope.refreshLogo = function() {
            $('#logoDiv').html('<img id="logoImage" ng-src="@{{currentLogo}}">');
            $('#logoImage').attr('src', '/company/logo?company_id=' + $scope.selectedCompanyId);
//            $scope.currentLogo = '';
//            console.log($scope.selectedCompanyId);
//            $scope.currentLogo =  '/company/logo?company_id=' + $scope.selectedCompanyId;

        };

        $scope.uploadLogo = function(){
            var fd = new FormData();
            console.log($scope.file);
            fd.append('logoImage', $scope.file);
            fd.append('company_id', $scope.selectedCompanyId);
            $http.post('/edit-company-logo', fd, {
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined}
            }).then(function(result) {
                $scope.refreshLogo();
            });
        };

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