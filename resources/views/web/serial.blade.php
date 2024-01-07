@extends('layouts.app', ['pageSlug' => 'serial'])

@section('content')
<div class="row">
  <div class="col-md-4 offset-md-8">
    <button type="button" class="btn btn-fill btn-primary" data-toggle="modal" data-target="#coms">click me</button>
  </div>
  <div class="col-md-12">
    <div id="tmp" class="card flex">
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="coms" tabindex="-1" role="dialog" aria-labelledby="comslabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title p-2" id="comslabel">Coms Add</h5>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <i class="tim-icons icon-simple-remove"></i>
          </button>
        </div>
        <div class="modal-body" id="modal_body">

        </div>
        <div class="modal-footer">
          <button id="close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button id="save" type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="coms-edit" tabindex="-1" role="dialog" aria-labelledby="comseditlabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title p-2" id="comslabel">Coms Edit</h5>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <i class="tim-icons icon-simple-remove"></i>
          </button>
        </div>
        <div class="modal-body" id="modal_edit_body">

        </div>
        <div class="modal-footer">
          <div class="row col-md-12">
            <div class="col-md-4">
              <button id="close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            <div class="col-md-8 text-right">
              <button id="delete" type="button" class="btn btn-primary">Delete</button>
              <button id="save" type="button" class="btn btn-primary">Save changes</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script type="text/javascript" src="{{asset('web')}}/formaker.js"></script>
<script>
  apitoken = $('#api-token')[0].value;
  formify('modal_body', [
    ["text", "add_port", "port"]
  ]);
  formify('modal_edit_body', [
    ["text", "id", "id", "disabled"],
    ["text", "edit_port", "port", ""],
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
  $("#coms #save").click(() => {
    port = $("#add_port").val();
    table.setData("/api/devices/serial",{"port": port},"POST");
    $("#coms").modal("hide");
  });
  $("#coms-edit #save").click(() => {
    port = $("#edit_port").val();
    id = $("#id").val();
    table.setData("/api/devices/serial",{"port": port,"id":id},"POST");
    $("#coms-edit").modal("hide");
  });
  table.on("rowClick", (e, row) => {
    $("#coms-edit").modal("show");
    data = row.getData();
    $("#id").val(data.id);
    $("#coms-edit #edit_port").val(data.Port);
    $("#created_date").val(data.created_at);
    $("#updated_date").val(data.updated_at);
  });
</script>
@endpush