@extends('layouts.default')

@section('content')

    <div>
        <select class="form-control" id="vectors">
            <option value="">Select a vector</option>
        </select>

        <select class="form-control" id="companies">
            <option value="">Select a company</option>
        </select>

        <button class="btn btn-primary form-control" id="submit" onclick="getInstances();">Query</button>
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
        });


        function getInstances() {
            $.ajax({
                url: "instance",
                data: {
                    'companies.name': $("#companies").val()
                }
            }).done(function(data) {
                console.log(data);
            }).fail(function(data) {
                console.log(data);
            });
        }
    </script>
@endsection