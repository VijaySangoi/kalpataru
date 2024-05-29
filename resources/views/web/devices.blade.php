@extends('layouts.app', ['pageSlug' => 'devices'])

@section('content')
<div class="row">
  <div class="col-md-4 offset-md-8">
    <button type="button" class="btn btn-fill btn-primary" data-toggle="modal" data-target="#add">click me</button>
  </div>
  <div class="col-md-12">
    <div id="tmp" class="card flex">
    </div>
  </div>
  @include('modal.add',['heading'=>'Devices Add'])
  @include('modal.edit',['heading'=>'Devices Edit'])
</div>
@endsection
@push('js')
<script>
  apitoken = $('#api-token')[0].value;
  formify('modal_body', [
    ["text", "add_name", "name"],
    ["select", "add_sheet", "sheet", "", <?php echo ($sheet);?>],
    ["select", "add_node", "node", "", <?php echo ($node);?>],
    ["text", "add_value", "value","disabled"],
    ["text", "add_svg", "svg"],
  ]);
  formify('modal_edit_body', [
    ["text", "id", "id", "disabled"],
    ["text", "edit_name", "name", ""],
    ["select", "edit_sheet", "sheet", "", <?php echo ($sheet);?>],
    ["select", "edit_node", "node", "", <?php echo ($node);?>],
    ["text", "edit_value", "value","disabled"],
    ["text", "edit_svg", "svg"],
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
    ajaxURL: "/api/devices",
    paginationSize: 10,
    autoColumns: true,
    layout: "fitColumns",
  });
  $("#add #save").click(() => {
    name = $("#add_name").val();
    sheet = $("#add_sheet").val();
    node = $("#add_node").val();
    svg = $("#add_svg").val();
    table.setData("/api/devices",{"name":name,"sheet":sheet,"node":node,"svg":svg},"POST");
    $("#add").modal("hide");
  });
  table.on("rowClick", (e, row) => {
    $("#edit").modal("show");
    data = row.getData();
    $("#id").val(data.id);
    $("#edit #edit_name").val(data.name);
    $("#edit_sheet option:contains("+data.sheet+")").prop('selected',true);
    $("#edit #edit_node").val(data.node);
    $("#edit_svg").val(data.svg);
  });
  $("#edit #save").click(() => {
    id = $("#id").val();
    name = $("#edit_name").val();
    sheet = $("#edit_sheet").val();
    node = $("#edit_node").val();
    svg = $("#edit_svg").val();
    table.setData("/api/devices",{"id":id,"name":name,"sheet":sheet,"node":node,"svg":svg},"POST");
    $("#edit").modal("hide");
  });
  $("#delete").click(()=>{
    id = $("#id").val();
    table.setData("/api/devices",{"id":id},"DELETE");
    $("#edit").modal("hide");
  });
</script>
@endpush