@extends('layouts.app', ['pageSlug' => 'workers'])

@section('content')
<div class="row">
  <div class="col-md-4 offset-md-8">
    <button type="button" class="btn btn-fill btn-primary" data-toggle="modal" data-target="#add">click me</button>
  </div>
  <div class="col-md-12">
    <div id="tmp" class="card flex">
    </div>
  </div>

  @include('modal.add',['heading'=>'Worker Add'])
  @include('modal.edit',['heading'=>'Worker Edit'])
</div>
@endsection

@push('js')
<script>
  apitoken = $('#api-token')[0].value;
  formify('modal_body', [
    ["text", "add_worker", "worker"],
    ["text", "add_tries", "tries"],
    ["text", "add_timeout", "timeout"],
    ["text", "add_memory", "memory"]
  ]);
  formify('modal_edit_body', [
    ["text", "id", "id", "disabled"],
    ["text", "edit_worker", "worker", ""],
    ["text", "edit_tries", "tries", ""],
    ["text", "edit_timeout", "timeout", ""],
    ["text", "edit_memory", "memory", ""],
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
    ajaxURL: "/api/workers",
    paginationSize: 10,
    autoColumns: true,
    layout: "fitColumns",
  });
  $("#add #save").click(() => {
    worker = $("#add_worker").val();
    tries = $("#add_tries").val();
    timeout = $("#add_timeout").val();
    memory = $("#add_memory").val();
    table.setData("/api/workers",{"worker":worker,"tries":tries,"timeout":timeout,"memory":memory},"POST");
    $("#add").modal("hide");
  });
  table.on("rowClick", (e, row) => {
    $("#edit").modal("show");
    data = row.getData();
    $("#id").val(data.id);
    $("#edit #edit_worker").val(data.name);
    $("#edit #edit_tries").val(data.tries);
    $("#edit #edit_timeout").val(data.timeout);
    $("#edit #edit_memory").val(data.memory);
    $("#created_date").val(data.created_at);
    $("#updated_date").val(data.updated_at);
  });
  $("#edit #save").click(() => {
    id = $("#id").val();
    worker = $("#edit_worker").val();
    tries = $("#edit_tries").val();
    timeout = $("#edit_timeout").val();
    memory = $("#edit_memory").val();
    table.setData("/api/workers",{"id":id,"worker":worker,"tries":tries,"timeout":timeout,"memory":memory},"POST");
    $("#edit").modal("hide");
  });
</script>
@endpush