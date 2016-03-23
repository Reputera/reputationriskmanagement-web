@extends('layouts.default')

@section('content')
    <div>
        <label>Company</label>
        <select class="form-control" id="companies">
            <option value="">Select a company</option>
        </select>

        <label>Vector</label>
        <select class="form-control" id="vectors">
            <option value="">Select a vector</option>
        </select>

        <label>Region</label>
        <select class="form-control" id="regions">
            <option value="">Select a region</option>
        </select>

        <button class="btn btn-primary form-control" id="submit" onclick="getInstances();">Query</button>

        <div id="resultsDiv">

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            $.each(vectors, function() {
                $("#vectors").append($("<option />").val(this.name).text(this.name));
            });
            $.each(companies, function() {
                $("#companies").append($("<option />").val(this.name).text(this.name));
            });
            $.each(regions, function() {
                $("#regions").append($("<option />").val(this.name).text(this.name));
            });
        });

        function getInstances() {
            $.ajax({
                url: "instance",
                data: {
                    'companies_name': $("#companies").val(),
                    'vectors_name': $("#vectors").val(),
                    'regions_name': $("#regions").val()
                }
            }).done(function(data) {
                $('#resultsDiv').html('<h2>Results</h2><p>Total risk score: ' + data.data.total_risk_score + '</p>');
                $.each(data.data.instances.data, function() {
                    var instanceContent = '<div class="well"';
                    for(var key in this) {
                        instanceContent += '<p>' + key + ': ' + this[key] + '</p>';
                    }
                    instanceContent += '</div>';
                    $("#resultsDiv").append(instanceContent);
                });
            }).fail(function(data) {
//                console.log(data);
            });
        }
    </script>
@endsection