app.controller('InstanceQueryController', ['$scope', '$http', 'toastr', 'helpers', 'NgTableParams', function($scope, $http, toastr, helpers, NgTableParams) {
    $scope.helpers = helpers;
    $scope.instances = [];
    $scope.selectedCompany = {};
    $scope.selectedVector = {};
    $scope.selectedRegion = {};
    $scope.hideFlagged = false;

    $scope.vectors = vectors;
    $scope.companies = companies;

    $scope.getParameters  = function() {
        return {
            companies_name: $scope.selectedCompany.name,
            vectors_name: $scope.selectedVector.name,
            regions_name: $scope.selectedRegion.name,
            hideFlagged: $scope.hideFlagged,
            fragment: $scope.fragment,
            start_datetime: $("#start_datetime").val(),
            end_datetime: $("#end_datetime").val(),
            page: $scope.instanceTable.page(),
            count: $scope.instanceTable.count()
        }
    };

    $scope.instanceTable = new NgTableParams({count:10}, {
        getData: function($defer, params) {
            $http({
                method: 'GET',
                url: '/instance?' + $.param($scope.getParameters())
            }).then(function(result) {
                $scope.instances = result.data.data.instances;
                $scope.resultCount = $scope.instances.meta.pagination.total;
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
        $scope.instanceTable.reload();
        $http({
            method: 'GET',
            url: '/riskScore?' + $.param($scope.getParameters())
        }).then(function(result) {
            $scope.riskScore = result.data.data.risk_score;
        });
    };

    $scope.flag = function(id, flagged) {
        $http({
            method: 'POST',
            url: '/flagInstance',
            data: {id: id, flagged: flagged}
        }).then(function(result) {
            $scope.instanceTable.reload();
        });
    }
}]);