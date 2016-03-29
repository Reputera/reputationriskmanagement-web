app.controller('InstanceQueryController', ['$scope', '$http', 'toastr', 'helpers', 'Instance', 'NgTableParams', function($scope, $http, toastr, helpers, Instance, NgTableParams) {
    $scope.helpers = helpers;
    $scope.instances = [];
    $scope.selectedCompany = {};

    $scope.vectors = vectors;
    $scope.companies = companies;

    $scope.getParameters  = function() {
        return {
            'companies_name': $("#companies").val(),
            'vectors_name': $("#vectors").val(),
            'regions_name': $("#regions").val(),
            'start_datetime': $("#start_datetime").val(),
            'end_datetime': $("#end_datetime").val()
        }
    };

    $scope.instanceTable = new NgTableParams({page:1,total:2}, {
        getData: function($defer, params) {
            $http({
                method: 'GET',
                url: '/instance?' + $.param($scope.getParameters())
            }).then(function(result) {
                $scope.instances = result.data.data.instances;
                params.total($scope.instances.meta.pagination.total);
                params.page($scope.instances.meta.pagination.current_page);
                $defer.resolve($scope.instances.data);
                return $scope.instances.data;
            }).then(function(errorResult) {
                toastr.error('error');
            });
        }
    });

    $scope.reload = function() {
        console.log('adsf');
        $scope.instanceTable.reload();
    }
}]);