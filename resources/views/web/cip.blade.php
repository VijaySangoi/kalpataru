@extends('layouts.app', ['pageSlug' => 'cip'])

@section('content')
<div class="row">
    <div class="col-md-12">
        <div id="tmp" class="card flex">
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
</script>
<script>
    apitoken = $('#api-token')[0].value;
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
        ajaxURL: "/api/cip",
        paginationSize: 10,
        autoColumns: true,
        layout: "fitColumns",
    });
</script>
@endpush