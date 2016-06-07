app.controller('InstanceQueryController', ['$scope', '$http', 'toastr', 'helpers', 'NgTableParams', function($scope, $http, toastr, helpers, NgTableParams) {
    $scope.helpers = helpers;
    $scope.instances = [];
    $scope.selectedCompany = {};
    $scope.selectedVector = {};
    $scope.selectedRegion = {};
    $scope.showDeleted = 0;
    $scope.csvUrl = '';

    $scope.vectors = vectors;
    $scope.companies = companies;
    $scope.regions = regions;

    $scope.getParameters  = function() {
        return {
            companies_name: $scope.selectedCompany ? $scope.selectedCompany.name : '',
            vectors_name: $scope.selectedVector ? $scope.selectedVector.name : '',
            regions_name: $scope.selectedRegion ? $scope.selectedRegion.name : '',
            showDeleted: $scope.showDeleted,
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
                $scope.instances = result.data.data;
                console.log($scope.instances);
                $scope.resultCount = result.data.meta.pagination.total;
                params.total(result.data.meta.pagination.total);
                params.page(result.data.meta.pagination.current_page);
                $defer.resolve($scope.instances);
                $scope.csvUrl = '/instanceCsv?'+$.param($scope.getParameters());
                return $scope.instances;
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

    $scope.flag = function(id) {
        $http({
            method: 'POST',
            url: '/toggleInstance',
            data: {id: id}
        }).then(function(result) {
            $scope.instanceTable.reload();
        });
    }
}]);