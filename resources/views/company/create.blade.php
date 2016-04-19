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
            <tr ng-repeat="company in companiesToAdd">
                <td>
                    <button type="button" ng-if="hasMultipleCompanies()"
                            class="btn btn-danger" ng-click="removeCompany(company.id);"
                    >
                        x
                    </button>
                </td>
                <td>
                    <input type="text" class="form-control" ng-model="company.name"
                           name="company_name@{{company.id}}"
                    >
                </td>
                <td>
                    <input type="text" class="form-control"
                           ng-model="company.entity_id" name="company_entity_id@{{company.id}}"
                           placeholder="Recorded Future Entity Id"
                    >
                </td>
                <td>
                    <select >
                        <option>Select Industry</option>
                        <option ng-repeat="industry in industries"
                                value="@{{ industry.id }}"
                        >
                            @{{ industry.name }}
                        </option>
                    </select>
                </td>
            </tr>
        </table>
        <button type="button" class="btn btn-primary" ng-click="addNewCompany()">Add Another Company</button>
        <br /><br />
        <button type="button" class="btn btn-success" ng-click="">Save Companies</button>
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
    app.controller('CompanyCreateController', ['$scope', '$http', 'toastr', 'ModalService', function($scope, $http, toastr, ModalService) {
        $scope.companiesToAdd = [];
        $scope.industries = industries;
        $scope.newIndustry = [];

        $scope.addNewCompany = function() {
            var newCompanyTempId = $scope.companiesToAdd.length+1;
            $scope.companiesToAdd.push({'id': newCompanyTempId, 'name': '', 'entity_id': ''});
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
                url: 'api/industry',
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