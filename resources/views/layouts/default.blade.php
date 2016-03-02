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
        </section>
    </div>
    @include('layouts.elements.footer')
</div>
<script src="{{elixir('js/jquery.js')}}"></script>
<script src="{{elixir('js/adminlte.js')}}"></script>
</body>
</html>