@extends('layouts.app')
@section('title')
Professors
@endsection
@section('content')
@include('bootstrap.icons')

<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 page-container">
    <div class="row">
        <div class="page_title">
            <span>Professors</span>
        </div>
    </div>

    <div class="menubar">
        @include('partials.menu_bar', ['buttonTitle' => ''])
    </div>

    <div class="page-body" id="resource-container">
        @include('professors.table')
    </div>
</div>

<input type="hidden" id="txt_baseUrl" value="<?php echo config('app.url'); ?>">

@include('professors.modals')
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        let baseUrl = $('#txt_baseUrl').val();
    });
</script>
<script src="{{URL::asset('public/js/professors/index.js')}}"></script>
@endsection