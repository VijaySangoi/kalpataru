@extends('layouts.app', ['pageSlug' => 'triggers'])

@section('content')
<div class="row">
    <div class="col-md-4 offset-md-8">
        <button type="button" class="btn btn-fill btn-primary" data-toggle="modal" data-target="#add">click me</button>
    </div>
    <div class="col-md-12">
        <div id="tmp" class="card flex">
        </div>
    </div>

    @include('modal.add',['heading'=>'Trigger Add'])
    @include('modal.edit',['heading'=>'Trigger Edit'])
</div>
@endsection

@push('js')
<script>
    apitoken = $('#api-token')[0].value;
    formify('modal_body', [
        ["text", "add_trigger", "trigger"]
    ]);
    formify('modal_edit_body', [
        ["text", "id", "id", "disabled"],
        ["text", "edit_trigger", "trigger", ""],
        ["text", "created_date", "created-date", "disabled"],
        ["text", "updated_date", "updated-date", "disabled"],
    ]);
    var table = new Tabulator("#tmp", {
    ajaxConfig: {
      method: "GET",
      headers: {
        "Authorization": "Bearer " + apitoken
      }
    },
    pagination: true,
    paginationMode: "remote",
    ajaxURL: "/api/trigger",
    paginationSize: 10,
    autoColumns: true,
    layout: "fitColumns",
  });
  table.on("rowClick", (e, row) => {
    $("#edit").modal("show");
    data = row.getData();
    $("#id").val(data.id);
    $("#edit #edit_trigger").val(data.name);
    $("#created_date").val(data.created_at);
    $("#updated_date").val(data.updated_at);
  });
</script>
@endpush