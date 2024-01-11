@extends('layouts.app', ['pageSlug' => 'serial'])

@section('content')
<div class="row">
  <div class="col-md-4 offset-md-8">
    <button type="button" class="btn btn-fill btn-primary" data-toggle="modal" data-target="#add">click me</button>
  </div>
  <div class="col-md-12">
    <div id="tmp" class="card flex">
    </div>
  </div>

  @include('modal.add',['heading'=>'Coms Add'])
  @include('modal.edit',['heading'=>'Coms Edit'])
</div>
@endsection

@push('js')
<script>
  apitoken = $('#api-token')[0].value;
  formify('modal_body', [
    ["text", "add_name", "name"],
    ["text", "add_port", "port"],
    ["text", "add_baudrate", "baudrate"]
  ]);
  formify('modal_edit_body', [
    ["text", "id", "id", "disabled"],
    ["text", "edit_name", "name", ""],
    ["text", "edit_port", "port", ""],
    ["text", "edit_baudrate", "baudrate", ""],
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
    ajaxURL: "/api/devices/serial",
    paginationSize: 10,
    autoColumns: true,
    layout: "fitColumns",
  });
  $("#add #save").click(() => {
    port = $("#add_port").val();
    baud = $("#add_baudrate").val();
    name = $("#add_name").val();
    table.setData("/api/devices/serial",{"port": port,"baudrate":baud,"name":name},"POST");
    $("#add").modal("hide");
  });
  $("#edit #save").click(() => {
    port = $("#edit_port").val();
    baudrate = $("#edit_baudrate").val();
    id = $("#id").val();
    table.setData("/api/devices/serial",{"port": port,"id":id,"baudrate":baudrate},"POST");
    $("#edit").modal("hide");
  });
  table.on("rowClick", (e, row) => {
    $("#edit").modal("show");
    data = row.getData();
    $("#id").val(data.id);
    $("#edit #edit_port").val(data.port);
    $("#edit #edit_baudrate").val(data.baudrate);
    $("#edit #edit_name").val(data.name);
    $("#created_date").val(data.created_at);
    $("#updated_date").val(data.updated_at);
  });
</script>
@endpush