angular.module('easyTable', [])
    .factory('easyTable', easyTable);

easyTable.$inject = ['NgTableParams', 'helpers'];


function easyTable(NgTableParams, helpers) {
    return {
        create: function(Model, easyParams) {
            return new EasyTable(NgTableParams, Model, easyParams, helpers);
        }
    }
}

/**
 *
 * @param NgTableParams - injected ngtable object
 * @param Model - Restangular model to query on
 * @param easyParams - closure to define data that will be passed when table is reloaded
 * easyParams.data - closure with data that will be used when posting.
 * easyParams.defaultSort - determines default sorting eg. {name: 'desc'}
 * @constructor
 */
function EasyTable(NgTableParams, Model, easyParams, helpers) {
    var easyTable = this;
    easyTable.models = {};
    
    easyTable.table = new NgTableParams({
        page: easyTable.models.meta ? easyTable.models.meta.pagination.current_page : 1,
        total: easyTable.models.meta ? easyTable.models.meta.pagination.total : 2,
        count: 10,
        sorting: easyParams.defaultSort
    }, {
        getData: function($defer, params) {
            return Model.where({
                meta: {
                    pagination: {
                        page: params.page(),
                        count: params.count(),
                        sort: helpers.getSortingParams(params.sorting())
                    }
                },
                data: easyParams.data()
            }).then(function(result) {
                if (typeof result.data.data === 'undefined') {
                    easyTable.models = result;
                    params.total(easyTable.models.meta.pagination.total);
                }
                else {
                    easyTable.models = result.data;
                    params.total(easyTable.models.meta.pagination.total);
                }
                params.page(easyTable.models.meta.pagination.current_page);
                $defer.resolve(easyTable.models.data);
                return easyTable.models.data;
            });
        }
    });
}