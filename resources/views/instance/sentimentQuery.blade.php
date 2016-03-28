@extends('layouts.default')

@section('content')
    <div>
        <label>Competitors</label>
        <div id="companies">
        </div>

        <label>Vector</label>
        <select class="form-control" id="vectors">
            <option value="">All vectors</option>
        </select>

        <label>Start date</label>
        <input type="text" class="form-control" id="start_datetime">

        <label>End date</label>
        <input type="text" class="form-control" id="end_datetime">

        <button class="btn btn-primary" id="submit" onclick="getSentiment();">Get Sentiment</button>

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

            $.each(competitors, function() {
                $("#companies").append('<div class="checkbox"> <label><input class="companies_name" type="checkbox" value="'+this.name+'">'+this.name+'</label> </div>');
            });

            $(function() {
                $("#start_datetime").datepicker({dateFormat:'yy-mm-dd'});
                $("#end_datetime").datepicker({dateFormat:'yy-mm-dd'});
            });
        });

        function getParameters() {
            var companies = [];
            $.each($('[class="companies_name"]'), function() {
                if(this.checked) {
                    companies.push(this.value);
                }
            });
            return {
                'companies_name': companies.join(),
                'vectors_name': $("#vectors").val(),
                'regions_name': $("#regions").val(),
                'start_datetime': $("#start_datetime").val(),
                'end_datetime': $("#end_datetime").val()
            }
        }

        function getSentiment() {
            $.ajax({
                url: "riskScore",
                data: getParameters()
            }).done(function(data) {
                $('#resultsDiv').html('Risk score: ' + data.data.risk_score);
            }).fail(function(data) {
                $('#resultsDiv').append('<p style="color:red;">' + data.responseText + '</p>');
            });
        }
    </script>
@endsection