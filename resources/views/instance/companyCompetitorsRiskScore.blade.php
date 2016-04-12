@extends('layouts.default')

@section('content')
    <div>
        <label>Companies</label>
        <select class="form-control" id="companies">
            <option value="">Select Company</option>
        </select>

        <label>Vector</label>
        <select class="form-control" id="vectors">
            <option value="">All vectors</option>
        </select>

        <label>Time Frame</label>
        <select class="form-control" id="lastDays">
            <option value="7">7 days</option>
            <option value="30">30 days</option>
            <option value="186">6 months</option>
            <option value="365">1 year</option>
        </select>

        <br />
        <button class="btn btn-primary" id="submit" onclick="getRiskScore();">Get Competitor Risk Score</button>

        <div id="resultsDiv">
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            $.each(vectors, function() {
                $("#vectors").append($("<option />").val(this.id).text(this.name));
            });

            $.each(companies, function() {
                $("#companies").append($("<option />").val(this.name).text(this.name));
            });
        });

        function getParameters() {
            var object = {
                'company_name': $("#companies").val(),
                'lastDays': $("#lastDays").val()
            }

            if (selectedVector = $("#vectors").val()) {
                object.vector = selectedVector;
            }

            return object;
        }

        function getRiskScore() {
            $('#resultsDiv').html('');

            $.ajax({
                url: "api/competitors-average-risk-score",
                data: getParameters()
            }).done(function(data) {
                $('#resultsDiv').html('<br /><br /><b>Company Risk score:</b> ' + data.data.company_risk_score + '<br />' +
                    '<b>Competitor Average Risk score:</b> ' + data.data.average_competitor_risk_score);
            }).fail(function(data) {
                $('#resultsDiv').append('<p style="color:red;">' + data.responseText + '</p>');
            });
        }
    </script>
@endsection