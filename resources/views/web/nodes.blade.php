@extends('layouts.app', ['pageSlug' => 'nodes'])

@section('content')
<div class="row">
  <div class="col-md-4 offset-md-8">
    <button type="button" class="btn btn-fill btn-primary" data-toggle="modal" data-target="#add">click me</button>
  </div>
  <div class="col-md-12">
    <div id="tmp" class="card flex">
    </div>
  </div>
  @include('modal.add',['heading'=>'Nodes Add'])
  @include('modal.edit',['heading'=>'Nodes Edit'])
</div>
@endsection
@push('js')
<script>
  apitoken = $('#api-token')[0].value;
  formify('modal_body', [
    ["text", "add_name", "name"],
    ["text", "add_type", "type"],
    ["text", "add_address", "address"],
    ["text", "add_status", "status"]
  ]);
  formify('modal_edit_body', [
    ["text", "id", "id", "disabled"],
    ["text", "edit_name", "name", ""],
    ["text", "edit_type", "type", ""],
    ["text", "edit_address", "address", ""],
    ["text", "edit_status", "status", ""],
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
    ajaxURL: "/api/nodes",
    paginationSize: 10,
    autoColumns: true,
    layout: "fitColumns",
  });
  $("#add #save").click(() => {
    name = $("#add_name").val();
    type = $("#add_type").val();
    address = $("#add_address").val();
    status = $("#add_status").val();
    table.setData("/api/nodes",{"name":name,"type":type,"address":address,"status":status},"POST");
    $("#add").modal("hide");
  });
  table.on("rowClick", (e, row) => {
    $("#edit").modal("show");
    data = row.getData();
    $("#id").val(data.id);
    $("#edit #edit_name").val(data.name);
    $("#edit #edit_type").val(data.type);
    $("#edit #edit_address").val(data.address);
    $("#edit #edit_status").val(data.status);
  });
  $("#edit #save").click(() => {
    id = $("#id").val();
    name = $("#edit_name").val();
    type = $("#edit_type").val();
    address = $("#edit_address").val();
    status = $("#edit_status").val();
    table.setData("/api/nodes",{"id":id,"name":name,"type":type,"address":address,"status":status},"POST");
    $("#edit").modal("hide");
  });
  $("#delete").click(()=>{
    id = $("#id").val();
    table.setData("/api/nodes",{"id":id},"DELETE");
    $("#edit").modal("hide");
  });
</script>
@endpush