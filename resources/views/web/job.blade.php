@extends('layouts.app', ['pageSlug' => 'job'])

@section('content')
<div>
    <div class="row py-2">
        <div class="col-md-6">
            <select class="form-control" name="filename" id="jobs">
            </select>
        </div>
        <div class="col">
            <button class="btn btn-info" id="addx" name="addx" data-toggle="modal" data-target="#add"><i class="fa-solid fa-add"></i></button>
            <button class="btn btn-info" id="savex" name="savex"><i class="fa-solid fa-save"></i></button>
        </div>
    </div>
    <div id="editor" class="editor" spellcheck="false">
</div>
    @include('modal.add',['heading'=>'Create Job'])
</div>
@endsection

@push('js')
<script src="{{ asset('js') }}/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
    var editor = ace.edit("editor");
    editor.setShowPrintMargin(false);
    editor.setTheme("ace/theme/monokai");
    editor.session.setMode("ace/mode/php");
    apitoken = $('#api-token')[0].value;
    formify('modal_body', [
        ["text", "add_name", "name"],
    ]);
    load_option();
    function load_option(){
        $.ajax({
            url: "/api/option",
            type: "GET",
            headers: {
                "Authorization": "Bearer " + apitoken
            },
            success: (e) => {
                html = "";
                e.forEach((item, index, arr) => {
                    if (index == 0) {
                        dummy(item[1]);
                    }
                    html += "<option val=" + item[1] + ">" + item[0] + "</option>";
                });
                $("#jobs").html(html);
            }
        });
    }
    $(document).ready(function() {
        demo.initDashboardPageCharts();
    });
    $("#jobs").change((e) => {
        dummy($("#jobs").val());
    })

    function dummy(file) {
        $.ajax({
            url: "/api/file/" + file,
            type: "GET",
            headers: {
                "Authorization": "Bearer " + apitoken
            },
            success: (e) => {
                editor.setValue(e);
            }
        });
    }
    $("#save").click(() => {
        name = $("#add_name").val();
        $.ajax({
            url: "/api/job",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + apitoken
            },
            data: {
                "name":name,
            },
            success: (e) => {
                $("#add").modal('hide');
                load_option();
            }
        });
    });
    $("#savex").click(() => {
        file = $("#jobs").val();
        data = editor.getValue();
        $.ajax({
            url: "/api/job",
            type: "POST",
            headers: {
                "Authorization": "Bearer " + apitoken
            },
            data: {
                "file":file,
                "data":data,
            },
            success: (e) => {

            }
        });
    });
</script>
@endpush