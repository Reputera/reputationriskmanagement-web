@extends('layouts.default')

@section('content')
    <div>
        <label>Company</label>
        <select class="form-control" id="companies">
            <option value="">Select a company</option>
        </select>

        <label>Vector</label>
        <select class="form-control" id="vectors">
            <option value="">All vectors</option>
        </select>

        <label>Region</label>
        <select class="form-control" id="regions">
            <option value="">All regions</option>
        </select>

        <label>Start date</label>
        <input type="text" class="form-control" id="start_datetime">

        <label>End date</label>
        <input type="text" class="form-control" id="end_datetime">

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

            $(function() {
                $("#start_datetime").datepicker({dateFormat:'yy-mm-dd'});
                $("#end_datetime").datepicker({dateFormat:'yy-mm-dd'});
            });
        });

        function getParameters() {
            return {
                'companies_name': $("#companies").val(),
                'vectors_name': $("#vectors").val(),
                'regions_name': $("#regions").val(),
                'start_datetime': $("#start_datetime").val(),
                'end_datetime': $("#end_datetime").val()
            }
        }

        function getInstances() {
            $.ajax({
                url: "instance",
                data: getParameters()
            }).done(function(data) {
                if(data.data.instances.data.length == 0) {
                    $('#resultsDiv').html('<h2>No Results</h2>');
                }
                else {
                    $('#resultsDiv').html('<h2>Results</h2><p>Result count: ' + data.data.count + '</p>');
                    $('#resultsDiv').append('<p>Total sentiment score: ' + data.data.total_sentiment_score + '</p>');
                    $('#resultsDiv').append('<a class="btn btn-primary" target="_blank" href="/instanceCsv?'+$.param(getParameters())+'">Download CSV</a>');
                    var truncatedData = data.data.instances.data.slice(0,100);
                    $.each(truncatedData, function() {
                        var instanceContent = '<div class="well">';
                        for(var key in this) {
                            if(key == 'link') {
                                instanceContent += '<p>link: <a target="_blank" href="'+this[key]+'">'+ this[key] + '</a></p>';
                            } else {
                                instanceContent += '<p>' + key + ': ' + this[key] + '</p>';
                            }
                        }
                        instanceContent += '</div>';
                        $("#resultsDiv").append(instanceContent);
                    });
                    if(data.data.instances.data.length > 100) {
                        $("#resultsDiv").append('Data truncated, more results in CSV download');
                    }
                }
            }).fail(function(data) {
                $('#resultsDiv').append('<p style="color:red;">' + data.responseText + '</p>');
            });
        }
    </script>
@endsection