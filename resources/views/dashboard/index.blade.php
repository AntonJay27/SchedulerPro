@extends('layouts.app')
@section('title')
Admin
@endsection
@section('content')
@include('bootstrap.icons')

<style type="text/css">
    .progress-bar-animated {
        animation: 1s linear infinite progress-bar-stripes;
    }
</style>

<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 page-container">
    <div class="page-body menubar">

        <div class="row">
            <?php $page = Request::segment(1); ?>
            <div class="page-title">
                <span>Dashboard</span>
            </div>

            <div class="myAccount">
                <a class="menu-link {{ ($page == 'my_account') ? 'active' : '' }}">
                    <a href="<?php echo config('app.url'); ?>my_account">
                        <span class="admin" style="font-size: 15px; font-weight: 500; font-family: sans-serif">Administrator<i class="bi bi-person-circle" style="margin-left: 5px"></i></span>
                    </a>
                </a>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row cards-container">
                    <?php $count = 1; ?>
                    @foreach ($data['cards'] as $card)
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" style="padding: 3px;">
                        <div class="card card-{{ $count++ }}">
                            <div class="card-title">
                                <span class="pull-right icon {{ $card['icon'] }}"></span>
                                <h3>{{ $card['title'] }}</h3>
                            </div>

                            <div class="card-body">
                                <span>{{ $card['value'] }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>    
        <div class="gen-sched">
            <h6>Generate New Schedules</h6>
            <div class="container" id="div_progressBarContainer" hidden>
                <br>
                <div class="progress">
                  <div class="progress-bar progress-bar-striped progress-bar-animated" id="div_progressBar" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                </div>
                <center><i>Processing, please wait...</i></center>
            </div>
            <div class="timetable-btn">
                <button id="resource-add-button"><img src="{{ URL('public/storage/gene.png') }}" style="height: 60px; width:60px"></button>
            </div>
        </div>
    </div>

    <div id="resource-container">
        @include('dashboard.timetables')
    </div>
</div>

<input type="hidden" id="txt_baseUrl" value="<?php echo config('app.url'); ?>">

@include('dashboard.modals')
@endsection

@section('scripts')
<script src="{{URL::asset('public/js/dashboard/index.js')}}"></script>
<script src="{{URL::asset('public/js/dashboard/dashboard.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        DASHBOARD.loadSchedules();

        $('#generate_btn').on('click',function(){
            DASHBOARD.generateSchedule();
        });
    });
</script>
@endsection