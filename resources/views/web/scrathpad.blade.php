@extends('layouts.app', ['pageSlug' => 'scrathpad'])

@section('content')
<div class="row">
    
</div>
@endsection

@push('js')
<script src="{{ asset('black') }}/js/plugins/chartjs.min.js"></script>
<script>
    $(document).ready(function() {
        demo.initDashboardPageCharts();
    });
</script>
@endpush