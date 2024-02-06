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
    var html = horiform([
      [["col-md-8",["select","create_job1","create_job1","",<?php echo $ff;?>]],["col-md-4",["select","create_worker1","create_worker1","",<?php echo $wo; ?>]]],
      [["col-md-8",["select","create_job2","create_job2","",<?php echo $ff;?>]],["col-md-4",["select","create_worker2","create_worker2","",<?php echo $wo; ?>]]],
      [["col-md-8",["select","create_job3","create_job3","",<?php echo $ff;?>]],["col-md-4",["select","create_worker3","create_worker3","",<?php echo $wo; ?>]]]
    ]);
    formify('modal_body', [
      ["text", "add_trigger", "trigger"],
      ["html", html],
    ]);
    var html = horiform([
      [["col-md-8",["select","edit_job1","edit_job1","",<?php echo $ff;?>]],["col-md-4",["select","edit_worker1","edit_worker1","",<?php echo $wo; ?>]]],
      [["col-md-8",["select","edit_job2","edit_job2","",<?php echo $ff;?>]],["col-md-4",["select","edit_worker2","edit_worker2","",<?php echo $wo; ?>]]],
      [["col-md-8",["select","edit_job3","edit_job3","",<?php echo $ff;?>]],["col-md-4",["select","edit_worker3","edit_worker3","",<?php echo $wo; ?>]]]
    ]);
    formify('modal_edit_body', [
      ["text", "id", "id", "disabled"],
      ["text", "edit_trigger", "trigger", ""],
      ["html", html],
      ["text", "edit_url", "url", "disabled"],
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
    $("#edit_trigger").val(data.name);
    $("#edit_url").val(data.url);
    $("#created_date").val(data.created_at);
    $("#updated_date").val(data.updated_at);
  });
  $("#add #save").click(() => {
    name = $("#add_trigger").val();
    job1 = $("#create_job1").val();
    job2 = $("#create_job2").val();
    job3 = $("#create_job3").val();
    worker1 = $("#create_worker1").val();
    worker2 = $("#create_worker2").val();
    worker3 = $("#create_worker3").val();
    table.setData("/api/trigger",{"name":name,"job1":job1,"job2":job2,"job3":job3,"worker1":worker1,"worker2":worker2,"worker3":worker3},"POST");
    $("#add").modal("hide");
  });
  $("#edit #save").click(() => {
    id = $("#id").val();
    name = $("#edit_trigger").val();
    job1 = $("#edit_job1").val();
    job2 = $("#edit_job2").val();
    job3 = $("#edit_job3").val();
    worker1 = $("#edit_worker1").val();
    worker2 = $("#edit_worker2").val();
    worker3 = $("#edit_worker3").val();
    table.setData("/api/trigger",{"id":id,"name":name,"job1":job1,"job2":job2,"job3":job3,"worker1":worker1,"worker2":worker2,"worker3":worker3},"POST");
    $("#edit").modal("hide");
  });
  $("#delete").click(()=>{
    id = $("#id").val();
    table.setData("/api/trigger",{"id":id},"DELETE");
    $("#edit").modal("hide");
  });
</script>
@endpush