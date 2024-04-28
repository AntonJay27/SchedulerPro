<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 sidebar">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 site-logo-container">
            <h3 class="text-center site-logo">Scheduler<span>Pro</span></h3>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <ul class="menu">
                <?php $page = Request::segment(1); ?>
                <li class="menu-link {{ ($page == 'dashboard') ? 'active' : '' }}">
                    <a href="<?php echo config('app.url'); ?>dashboard"><span class="text">Dashboard</span></a>
                </li>
                <li class="menu-link {{ ($page == 'classes') ? 'active' : '' }}">
                    <a href="<?php echo config('app.url'); ?>classes"><span class="text">Classes</span></a>
                </li>
                <li class="menu-link {{ ($page == 'professors') ? 'active' : '' }}">
                    <a href="<?php echo config('app.url'); ?>professors"><span class="text">Professors</span></a>
                </li>
                <li class="menu-link {{ ($page == 'subjects') ? 'active' : '' }}">
                    <a href="<?php echo config('app.url'); ?>subjects"><span class="text">Subjects</span></a>
                </li>
                <li class="menu-link {{ ($page == 'rooms') ? 'active' : '' }}">
                    <a href="<?php echo config('app.url'); ?>rooms"><span class="text">Rooms</span></a>
                </li>
                <li class="menu-link {{ ($page == 'timeslots') ? 'active' : '' }}">
                    <a href="<?php echo config('app.url'); ?>timeslots"><span class="text">Timeslots</span></a>
                </li>
            </ul>
            <span class="menu-link">
                <a href="<?php echo config('app.url'); ?>logout"><i class="bi bi-box-arrow-in-left"></i></a>
            </span>
        </div>
    </div>
</div>