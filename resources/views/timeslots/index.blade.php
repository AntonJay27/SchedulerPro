@extends('layouts.app')
@section('title')
Timeslots
@endsection
@section('content')
@include('bootstrap.icons')

<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 page-container">
    <div class="row">
        <div class="page_title">
            <span>Timeslots</span>
        </div>
    </div>

    <div class="menubar">
        @include('partials.timeslot_bar', ['buttonTitle' => ''])
    </div>

    <div class="message-container">
        <i class="fa fa-info-circle"></i>
        <div class="message">The arrangements of timeslots here will be reflected in the generated schedule ...</div>
    </div>

    <div class="page-body" id="resource-container">
        @include('timeslots.table')
    </div>
</div>

<input type="hidden" id="txt_baseUrl" value="<?php echo config('app.url'); ?>">

@include('timeslots.modals')
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        let baseUrl = $('#txt_baseUrl').val();
    });
</script>
<script src="{{URL::asset('public/js/timeslots/index.js')}}"></script>
@endsection