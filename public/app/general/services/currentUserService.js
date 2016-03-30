angular.module('currentUser', [])
    .factory('currentUser', function() {
        user = (typeof user == 'undefined') ? {} : angular.copy(user);
        return user;
    });