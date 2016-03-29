app.service('Model', function(Restangular) {
    return {
        make:function(endpoint) {
            var point = Restangular.all(endpoint);
            return {
                service:point,
                methods:{
                    all:function()
                    {
                        return Restangular.all(endpoint).getList();
                    },
                    where: function(params){
                        return point.post(params);
                    },
                    get:function(id)
                    {
                        return point.one(id).get();
                    },
                    update:function(obj)
                    {
                        return Restangular.all(endpoint + '//update').post(obj);
                    },
                    delete: function(obj)
                    {
                        return point.one(obj.id).remove();
                    },
                    createNested: function(parent, obj, route) {
                        return parent.post(route, obj);
                    },
                    fromNested: function(parent, collection, route) {
                        return _.map(collection, function(item) {
                            return Restangular.restangularizeElement(parent, item, route);
                        });
                    }
                }
            }
        }
    };
})
.service('Instance', function(Model, Restangular) {
    var $service = Model.make('instanceQuery');
    $service.methods.flag = function(id) {
        return Restangular.service('instance/flag').post({id:id});
    };
    return $service.methods;
});