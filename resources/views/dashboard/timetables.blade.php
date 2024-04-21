<div class="row" id="table">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <table class="table" id="tbl_schedules">
            <thead>
                <tr class="table-head">
                    <th id="table-bordered" style="width: 60%">Name</th>
                    <th id="table-bordered" style="width: 25%">Academic Year</th>
                    <th id="table-bordered" style="width: 10%">Print</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
        <div id="pagination">
            {!! $timetables->render() !!}
        </div>
    </div>
</div>