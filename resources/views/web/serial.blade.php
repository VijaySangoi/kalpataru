@extends('layouts.app', ['pageSlug' => 'serial'])

@section('content')
<div class="row">
  <div class="col-md-4 offset-md-8">
    <button type="button" class="btn btn-fill btn-primary" data-toggle="modal" data-target="#coms">click me</button>
  </div>
  <div class="col-md-12">
    <div id="tmp" class="card flex">
      list of connected devices
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="coms" tabindex="-1" role="dialog" aria-labelledby="comslabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <!-- <div class="modal-header">
        <h5 class="modal-title p-2" id="comslabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
          <i class="tim-icons icon-simple-remove"></i>
        </button>
      </div> -->
        <div class="modal-body" id="modal_body">
         
        </div>
        <div class="modal-footer">
          <button id="close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button id="save" type="button" class="btn btn-primary">Save changes</button>
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
    ["text", "port", "port"]
  ]);
  // $.ajax({
  //   url: "/api/devices/serial",
  //   type: "GET",
  //   headers: {
  //     "Authorization": "Bearer " + apitoken
  //   },
  //   success: (e) => {
  //     var table = new Tabulator("#tmp", {
  //       data: e, //assign data to table
  //       autoColumns: true, //create columns from data field names
  //     });
  //   }
  // })
    var table = new Tabulator("#tmp", {
      ajaxConfig:{
        method:"GET",
        headers:{"Authorization": "Bearer " + apitoken}
      },
      pagination:true,
      paginationMode:"remote",
      ajaxURL:"/api/devices/serial",
      paginationSize:10,
      paginationInitialPage:1,
      autoColumns:true,
      layout:"fitColumns",
      rowFormatter:(row,data)=>{
        console.log(row.getData());
      }
  });
  $("#save").click(() => {
    port = $("#port").val();
    $.ajax({
      url: "/api/devices/serial",
      type: "post",
      headers: {
        "Authorization": "Bearer " + apitoken
      },
      data: {
        "port": port
      },
      success: (e) => {
        // $("#tmp").html(e);
        var table = new Tabulator("#tmp", {
          data: e, //assign data to table
          autoColumns: true, //create columns from data field names
        });
      }
    })
  });
</script>
@endpush