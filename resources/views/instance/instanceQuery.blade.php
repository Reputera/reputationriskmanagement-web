@extends('layouts.default')

@section('content')
    <div ng-controller="InstanceQueryController">
        <label>Company</label>
        <select class="form-control" ng-model="selectedCompany" ng-options="company.name for company in companies">
            <option value="">Select a Company</option>
        </select>

        <label>Vector</label>
        <select class="form-control" ng-model="selectedVector" ng-options="vector.name for vector in vectors">
            <option value="">All Vectors</option>
        </select>

        <label>Region</label>
        <select class="form-control" ng-model="selectedRegion" ng-options="region.name for region in regions">
            <option value="">All Regions</option>
        </select>

        <label>Fragment Search</label>
        <input type="text" class="form-control" ng-model="fragment" id="fragment">

        <label>Start date</label>
        <input type="text" class="form-control" id="start_datetime">

        <label>End date</label>
        <input type="text" class="form-control" id="end_datetime">

        <label>Hide flagged</label>
        <input type="checkbox" ng-model="hideFlagged" ng-false-value="0" ng-true-value="1">

        <button class="btn btn-primary form-control" id="submit" ng-click="reload()">Query</button>

        <div ng-if="riskScore">
            <p>Risk Score: <span ng-bind="riskScore"></span></p>
            <p>Instance count: <span ng-bind="resultCount"></span></p>
        </div>
        <div ng-if="riskScore">
            <a class="btn btn-primary" target="_blank" ng-href="@{{csvUrl}}">Download CSV</a>
            <table ng-cloak ng-table="instanceTable" class="table table-striped table-hover">
                <tr ng-repeat="instance in $data track by instance.id">
                    <td data-title="'Title'" sortable="'user_name'" ng-bind="instance.title"></td>
                    <td data-title="'Date'" sortable="'date'" ng-bind="instance.date"></td>
                    <td data-title="'Vector'" ng-bind="instance.vector"></td>
                    <td data-title="'Risk Score'" ng-bind="instance.risk_score"></td>
                    <td data-title="'Source'" ng-bind="instance.source"></td>
                    <td data-title="'Region'" ng-bind="instance.regions"></td>
                    <td data-title="'Fragment'" ng-bind="instance.fragment"></td>
                    <td data-title="'Flagged'">
                        <button class="btn btn-primary" ng-if="instance.flagged" ng-click="flag(instance.id, 0)">Unflag</button>
                        <button class="btn btn-danger" ng-if="!instance.flagged" ng-click="flag(instance.id, 1)">Flag</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            $(function() {
                $("#start_datetime").datepicker({dateFormat:'yy-mm-dd'});
                $("#end_datetime").datepicker({dateFormat:'yy-mm-dd'});
            });
        });
    </script>

@endsection