@extends('layouts.app')
@section('title')
Subjects
@endsection
@section('content')
@include('bootstrap.icons')

<style type="text/css">
    .select-control{
        border-radius: 0 !important;
        height: 40px;
        font-size: 1.2em;
    }
</style>

<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 page-container">
    <div class="row">
        <div class="page_title">
            <span>Subjects</span>
        </div>
    </div>

    <div class="menubar">
        @include('partials.menu_bar', ['buttonTitle' => ''])
    </div>

    <div class="page-body" id="resource-container">
        @include('subjects.table')
    </div>
</div>

<input type="hidden" id="txt_baseUrl" value="<?php echo config('app.url'); ?>">

@include('subjects.modals')
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        let baseUrl = $('#txt_baseUrl').val();
    });
</script>
<script src="{{URL::asset('public/js/subjects/index.js')}}"></script>
@endsection