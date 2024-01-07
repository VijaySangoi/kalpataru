@extends('layouts.app', ['pageSlug' => 'job'])

@section('content')
    <div class="row">
        <div class="p-2">
            <textarea id="editor" class="editor" spellcheck="false">
            </textarea>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('black') }}/js/plugins/chartjs.min.js"></script>
    <script>
    apitoken = $('#api-token')[0].value;
        $.ajax({
            url:"/api/scrathpad",
            type:"GET",
            headers: {
                "Authorization": "Bearer " + apitoken
            },
            success: (e) => {
                $("#editor").html(e);
            }
        });
        $(document).ready(function() {
          demo.initDashboardPageCharts();
        });
    </script>
@endpush
