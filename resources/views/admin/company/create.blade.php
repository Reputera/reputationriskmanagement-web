@extends('layouts.default')

@section('content')
    <div ng-controller="CompanyCreateController">
        <h3>Company Creation</h3>
        <button type="button" class="btn btn-primary pull-right" ng-click="addIndustry()">Add New Industry</button>
        <br /><br />
        <table class="table">
            <tr>
                <th style="width: 35px;">&nbsp;</th>
                <th class="text-center">Company Name</th>
                <th>Entity ID (Recorded Future)</th>
                <th>Industry</th>
            </tr>
            <tr ng-repeat="(companyKey, company) in companiesToAdd">
                <td style="height: 90px;">
                    <button type="button" ng-if="hasMultipleCompanies()"
                            class="btn btn-danger" ng-click="removeCompany(company.id);"
                    >
                        x
                    </button>
                </td>
                <td style="height: 90px;">
                    <div class="form-group" ng-class="{ 'has-error' : companiesToAddErrors[companyKey]['name'] }">
                        <input type="text" class="form-control" ng-model="company.name"
                               required
                               name="company_name@{{company.id}}"
                        >
                        <div class="control-label" ng-if="companiesToAddErrors[companyKey]['name']">
                            <strong ng-bind='companiesToAddErrors[companyKey]["name"]'></strong>
                        </div>
                    </div>
                </td>
                <td style="height: 90px;">
                    <div class="form-group" ng-class="{ 'has-error' : companiesToAddErrors[companyKey]['entity_id'] }">
                        <input type="text" class="form-control" required
                               ng-model="company.entity_id"
                               placeholder="Recorded Future Entity Id"
                        >
                        <div class="control-label" ng-if="companiesToAddErrors[companyKey]['entity_id']">
                            <strong ng-bind='companiesToAddErrors[companyKey]["entity_id"]'></strong>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="form-group" ng-class="{ 'has-error' : companiesToAddErrors[companyKey]['industry_id'] }">
                        <select ng-model="company.industry_id" required>
                            <option value="" selected="selected">Select Industry</option>
                            <option ng-repeat="industry in industries"
                                    value="@{{ industry.id }}"
                            >
                                @{{ industry.name }}
                            </option>
                        </select>
                        <div class="control-label" ng-if="companiesToAddErrors[companyKey]['industry_id']">
                            <strong ng-bind='companiesToAddErrors[companyKey]["industry_id"]'></strong>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <button type="button" class="btn btn-primary" ng-click="addNewCompany()">Add Another Company</button>
        <br /><br />
        <button type="button" class="btn btn-success" ng-click="saveCompanies()" ng-click="">Save Companies</button>
    </div>
@endsection

@section('scripts')
<script type="text/ng-template" id="modal.html">
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" ng-click="close('Cancel')" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Add new industry</h4>
                </div>
                <div class="modal-body">

                    <input type="text" class="form-control" ng-model="newIndustryName">
                </div>
                <div class="modal-footer">
                    <button type="button" ng-click="save()" class="btn btn-default">Save</button>
                </div>
            </div>
        </div>
    </div>
</script>

<script>
    app.controller('CompanyCreateController',
            ['$scope', '$http', 'toastr', 'ModalService', 'ApplicationConfig', function($scope, $http, toastr, ModalService, ApplicationConfig)
    {
        ApplicationConfig.enableGlobalErrorHandling = false;

        $scope.companiesToAdd = [];
        $scope.companiesToAddErrors = [];
        $scope.industries = industries;
        $scope.newIndustry = [];

        $scope.addNewCompany = function() {
            var newCompanyTempId = $scope.companiesToAdd.length+1;
            $scope.companiesToAdd.push({'id': newCompanyTempId, 'name': '', 'entity_id': '', 'industry_id': ''});
        };

        $scope.removeCompany = function(companyId) {
            for(var i = 0; i < $scope.companiesToAdd.length; i++) {
                if($scope.companiesToAdd[i].id == companyId) {
                    $scope.companiesToAdd.splice(i, 1);
                    break;
                }
            }
        };

        $scope.addIndustry = function () {
            ModalService.showModal({
                templateUrl: 'modal.html',
                controller: "newIndustryModelController",
            }).then(function(modal) {
                modal.element.modal();
                modal.close.then(function(result) {
                    $scope.industries.push(result);
                });
            });
        };

        $scope.saveCompanies = function () {
            $scope.companiesToAddErrors = [];
            $http({
                method: 'POST',
                url: "{!! route('admin.company.create.store') !!}",
                data: {
                    companies: $scope.companiesToAdd
                }
            })
            .then(
                function success(response) {
                    toastr.success('Successfully added the companies!');
                    $scope.companiesToAdd = [];
                    $scope.addNewCompany();
                },
                function error(response) {
                    toastr.error('There were issues with saving the companies.');
                    if (response.data.hasOwnProperty('errors')) {
                        for (var error_field in response.data.errors) {
                            var field = error_field.split(".");
                            if(typeof $scope.companiesToAddErrors[field[1]] == "undefined"){
                                $scope.companiesToAddErrors[field[1]] = [];
                            }
                            $scope.companiesToAddErrors[field[1]][field[2]] = response.data.errors[error_field][0];
                        }
                    }
                }
            )
        };

        $scope.hasMultipleCompanies = function () {
            return $scope.companiesToAdd.length > 1;
        }

        $scope.addNewCompany();
    }]);

    app.controller('newIndustryModelController', function($scope, $element, $http, close, toastr) {
        $scope.newIndustryName = '';

        $scope.close = function(result) {
            close(result, 500); // close, but give 500ms for bootstrap to animate
        };

        $scope.save = function () {
            $http({
                method: 'POST',
                url: "{!! route('admin.industry.create.store') !!}",
                data: {
                    industry_name: $scope.newIndustryName
                }
            })
            .then(function success(response) {
                close(response.data.data, 500);
                $element.modal('hide');
                toastr.success('Successfully added ' + response.data.data.name + ' as an industry.');
                $scope.newIndustryName = '';
            });
        };
    });

</script>
@endsection