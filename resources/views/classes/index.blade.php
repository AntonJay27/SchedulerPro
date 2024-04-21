@extends('layouts.app')
@section('title')
Classes
@endsection
@section('content')
@include('bootstrap.icons')

<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 page-container">
    <div class="row">
        <div class="page_title">
            <span>Classes</span>
        </div>
    </div>

    <div class="menubar">
        @include('partials.menu_bar', ['buttonTitle' => ''])
    </div>

    <div class="page-body" id="resource-container">
        @include('classes.table')
    </div>
</div>
<input type="hidden" id="txt_baseUrl" value="<?php echo config('app.url'); ?>">

@include('classes.modals')
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        let baseUrl = $('#txt_baseUrl').val();
    });
</script>
<script src="{{URL::asset('public/js/classes/index.js')}}"></script>
@endsection