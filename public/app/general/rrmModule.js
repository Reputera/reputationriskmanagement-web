var app = angular
    .module('app', [
        'helpers',
        'restangular',
        'ngTable',
        'toastr',
        'easyTable',
        'currentUser',
        'singleClick',
        'angularModalService',
        'ui.mask',
        'angularSpectrumColorpicker'
    ])
    .factory('ApplicationConfig', function($log) {
        return {
            enableGlobalErrorHandling: true
        };
    })
    .factory('errorInterceptor', function($q, toastr, ApplicationConfig) {
        return {
            responseError: function (response) {
                if(ApplicationConfig.enableGlobalErrorHandling) {
                    if (response.status == 404) {
                        toastr.warning('Record not found');
                    } else if (response.status == 500) {
                        toastr.error('Unknown error occurred');
                    } else if (response.status == 400) {
                        toastr.error(response.data.message);
                    } else if (response.status == 401) {
                        toastr.error('Unauthorized: ' + response.data.message);
                    } else if (response.status == 422) {
                        if (response.data.hasOwnProperty('errors')) {
                            var errors = [];
                            for (var error_field in response.data.errors) {
                                if (Array.isArray(response.data.errors[error_field])) {
                                    errors = errors.concat(response.data.errors[error_field]);
                                } else {
                                    errors.push(response.data.errors[error_field]);
                                }
                            }
                            try {
                                toastr.warning(errors.join("<br />"));
                            } catch (e) {
                            }
                        } else {
                            try {
                                toastr.warning(JSON.stringify(response.data));
                            } catch (e) {
                            }
                        }
                    }
                }

                return $q.reject(response);
            }
        }
    });

app.config(function($httpProvider, toastrConfig) {
    $httpProvider.interceptors.push('errorInterceptor');

    angular.extend(toastrConfig, {
        allowHtml: true
    });
});