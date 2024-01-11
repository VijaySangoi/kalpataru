@extends('layouts.app', ['pageSlug' => 'logs'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <form id="read">
            <div class="card ">
                <div class="card-header">
                    <h5 class="title">File</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 pt-1">
                            <select id="inp_file" name="inp_file" class="form-control"></select>
                        </div>
                        <div class="col-md-6">
                            <button id="view" type="submit" class="btn btn-fill btn-primary">View</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <pre class="container col-md-12" id="data" style="min-height:71vh;max-height:71vh">

    </pre>
</div>
<div class="row">
    <div class="col-md-12">
        <form id="write">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 pt-1">
                            <input class="form-control" type="text" name="message" id="message"></input>
                        </div>
                        <div class="col-md-6">
                            <button id="send" type="submit" class="btn btn-fill btn-primary">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    apitoken = $('#api-token')[0].value;
    $.ajax({
        url: "/api/list-log-file",
        type: "GET",
        headers: {
            "Authorization": "Bearer " + apitoken
        },
        contentType: "application/json",
        success: (e) => {
            e.forEach((item, index, array) => {
                $("#inp_file").append("<option id=" + item + ">" + item + "</option>")
            });
        }
    })
    $("#view").click((e) => {
        e.preventDefault();
        fl_name = $("#inp_file").val();
        $.ajax({
            url: "/api/log-file",
            type: "GET",
            headers: {
                "Authorization": "Bearer " + apitoken
            },
            data: {
                "fl_name": fl_name
            },
            success: (e) => {
                $("#data").html(e);
            }
        })
    });
    $("#send").click((e) => {
        e.preventDefault();
        fl_name = $("#inp_file").val();
        message = $("#message").val();
        $.ajax({
            url: "/api/log-file",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + apitoken
            },
            data: {
                "fl_name": fl_name,
                "message": message
            },
            success: (e) => {
                $("#data").html(e);
            }
        })
    });
</script>
@endpush