var app = angular
    .module('app', [
        'helpers',
        //'ui.bootstrap',
        'restangular',
        'ngTable',
        'toastr',
        'easyTable',
        'currentUser',
        'singleClick'
    ])
    .factory('errorInterceptor', function($q, toastr) {
        return {
            responseError: function (response) {
                if (response.status == 404) {
                    toastr.warning('Record not found');
                } else if (response.status == 500) {
                    toastr.error('Unknown error occurred');
                } else if(response.status == 400) {
                    toastr.error(response.data.message);
                } else if(response.status == 401) {
                    toastr.error('Unauthorized: ' + response.data.message);
                } else if (response.status == 422) {
                    try {
                        if(response.data.message && response.data.message != 'Unprocessable Entity'){
                            toastr.warning(response.data.message);
                        }
                    } catch(e) {}
                }
                return $q.reject(response);
            }
        }
    });