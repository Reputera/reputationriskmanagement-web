<!DOCTYPE html>
<html ng-app="app">
@include('layouts.elements.head')
<body class="skin-blue">
<div class="wrapper" ng-controller="MainCtrl">
    @include('layouts.elements.header')
    @include('layouts.elements.side-bar')
    <div class="content-wrapper">
        <section class="content-header">
        </section>
        <section class="content">
            <div ui-view="main-content">
            </div>
            @yield('content')
        </section>
    </div>
    @include('layouts.elements.footer')
</div>
<script src="{{elixir('js/jquery.js')}}"></script>
<script src="{{elixir('js/adminlte.js')}}"></script>
<script src="{{elixir('assets/all.js')}}"></script>
<script>
    var user = {!! json_encode(Auth::user()) !!};
</script>
<script>
    app.controller('MainCtrl', function ($scope, toastr, currentUser) {
        $scope.user = currentUser;

        $scope.showErrorMessage = function (msg) {
            toastr.error(msg);
        };

        $scope.showSuccessMessage = function (msg) {
            toastr.success(msg);
        };
    });
</script>
@yield('scripts')
</body>
</html>