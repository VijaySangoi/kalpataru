@extends('layouts.app', ['pageSlug' => 'transformer'])

@section('content')
<div class="row">
    <div class="col-md-4 offset-md-8">
      <button type="button" class="btn btn-fill btn-primary" data-toggle="modal" data-target="#add">click me</button>
    </div>
    <div class="col-md-12">
      <div id="tmp" class="card flex">
      </div>
    </div>
    @include('modal.add',['heading'=>'Transformer Add'])
    @include('modal.edit',['heading'=>'Transformer Edit'])
</div>
@endsection
@push('js')
<script>
    apitoken = $('#api-token')[0].value;
    formify('modal_body', [
    ["text", "add_name", "name"],
    ["text", "add_namespace", "namespace"],
    ["text", "add_type", "type"],
  ]);
  formify('modal_edit_body', [
    ["text", "id", "id", "disabled"],
    ["text", "edit_name", "name"],
    ["text", "edit_namespace", "namespace"],
    ["text", "edit_type", "type"],
  ]);    var table = new Tabulator("#tmp", {
    ajaxConfig: {
      method: "GET",
      headers: {
        "Authorization": "Bearer " + apitoken
      }
    },
    pagination: true,
    paginationMode: "remote",
    ajaxURL: "/api/transformer",
    paginationSize: 10,
    autoColumns: true,
    layout: "fitColumns",
  });
  table.on("rowClick", (e, row) => {
    $("#edit").modal("show");
    data = row.getData();
    $("#id").val(data.id);
    $("#edit_name").val(data.name);
    $("#edit_namespace").val(data.namespace);
    $("#edit_type").val(data.type);
  });
  $("#add #save").click(() => {
    name = $("#add_trigger").val();
    namespace = $("#add_namespace").val();
    type = $("#add_type").val();
    table.setData("/api/transformer",{"name":name,"namespace":namespace,"type":type},"POST");
    $("#add").modal("hide");
  });
  $("#edit #save").click(() => {
    id = $("#id").val();
    name = $("#edit_trigger").val();
    namespace = $("#edit_namespace").val();
    type = $("#edit_type").val();
    table.setData("/api/transformer",{"name":name,"namespace":namespace,"type":type},"POST");
    $("#edit").modal("hide");
  });
  $("#delete").click(()=>{
    id = $("#id").val();
    table.setData("/api/transformer",{"id":id},"DELETE");
    $("#edit").modal("hide");
  });
</script>
@endpush