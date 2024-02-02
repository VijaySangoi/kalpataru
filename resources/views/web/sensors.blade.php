@extends('layouts.app', ['pageSlug' => 'sensors'])

@section('content')
<div class="row">
    <div class="col-md-4 offset-md-8">
        <button type="button" class="btn btn-fill btn-primary" data-toggle="modal" data-target="#add">click me</button>
    </div>
    <div class="col-md-12">
        <div id="tmp" class="card flex">
        </div>
    </div>
    @include('modal.add',['heading'=>'Sensors Add'])
    @include('modal.edit',['heading'=>'Sensors Edit'])
</div>
@endsection

@push('js')
<script>
    apitoken = $('#api-token')[0].value;
    formify('modal_body', [
      ["text", "add_name", "name"],
      ["select", "add_sheet", "sheet", "", <?php echo ($sheet);?>],
      ["text", "add_value", "value","disabled"],
      ["text", "add_svg", "svg"],
      ["select", "add_device", "device", "", <?php echo ($cip);?>],
    ]);
    formify('modal_edit_body', [
      ["text", "id", "id", "disabled"],
      ["text", "edit_name", "name"],
      ["select", "edit_sheet", "sheet", "", <?php echo ($sheet);?>],
      ["text", "edit_value", "value","disabled"],
      ["text", "edit_svg", "svg"],
      ["select", "edit_device", "device", "", <?php echo ($cip);?>],
    ]);
    $(document).ready(function() {
        demo.initDashboardPageCharts();
    });
    var table = new Tabulator("#tmp", {
    ajaxConfig: {
      method: "GET",
      headers: {
        "Authorization": "Bearer " + apitoken
      }
    },
    pagination: true,
    paginationMode: "remote",
    ajaxURL: "/api/sensors",
    paginationSize: 10,
    autoColumns: true,
    layout: "fitColumns",
  });
  table.on("rowClick", (e, row) => {
    $("#edit").modal("show");
    data = row.getData();
    $("#id").val(data.id);
    $("#edit_name").val(data.name);
    $("#edit_sheet option[innerText="+data.sheet+"]").prop('selected',true);
    $("#edit_svg").val(data.svg);
    $("#edit_device option[innerText="+data.sheet+"]").prop('selected',true);
  });
  $("#add #save").click(() => {
    name = $("#add_name").val();
    sheet = $("#add_sheet").val();
    svg = $("#add_svg").val();
    node = $("#add_device").val();
    table.setData("/api/sensors",{"name":name,"sheet":sheet,"svg":svg,"node":node},"POST");
    $("#add").modal("hide");
  });
  $("#edit #save").click(() => {
    id = $("#id").val()
    name = $("#edit_name").val();
    sheet = $("#edit_sheet").val();
    svg = $("#edit_svg").val();
    node = $("#edit_device").val();
    table.setData("/api/sensors",{"id":id,"name":name,"sheet":sheet,"svg":svg,"node":node},"POST");
    $("#edit").modal("hide");
  });
  $("#delete").click(()=>{
    id = $("#id").val();
    table.setData("/api/sensors",{"id":id},"DELETE");
    $("#edit").modal("hide");
  });
</script>
@endpush