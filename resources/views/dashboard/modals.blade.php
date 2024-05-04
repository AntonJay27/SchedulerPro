<!-- Modal for creating a new timetable -->
<div class="modal custom-modal" id="resource-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true"><i class="bi bi-x-square"></i></span>
                </button>

                <h4 class="modal-heading"></h4>
            </div>

            <form class="form" method="POST" action="" id="resource-form">
                <input type="hidden" name="_method">
                <div class="modal-body">
                    <div id="errors-container">
                        @include('partials.modal_errors')
                    </div>

                    <div class="row" id="inp_data">
                        <div class="col-lg-10 col-md-10 col-sm-10 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
                            {{ csrf_field() }}

                            <div class="input-box">
                                <label class="placeholder">Timetable Header</label>
                                <input type="text" placeholder="e.g. A.Y. 2023-2024: Second Semester" id="txt_timeTableHeader" name="name" required>
                            </div>

                            <div class="input-box">
                                <label class="placeholder">Academic Period</label>
                                <div class="select2-wrapper">
                                    <select name="academic_period_id" class="academic-period-select" id="slc_academicPeriod">
                                        <option selected disabled>Select an academic period</option>
                                        @foreach ($academicPeriods as $period)
                                        <option value="{{ $period->id }}">{{ $period->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <label id="select_label">Days w/Classes</label>
                            <div>
                                @foreach ($days as $day)
                                <div id="academic_period_id">
                                    <input name="day_{{ $day->id }}" type="checkbox" for="day_{{ $day->id }}" value="{{ $day->name }}" @if($day->id <= 5) checked @endif>
                                    <label id="day_{{ $day->id }}">{{ $day->name }}</label>
                                </div>
                                @endforeach
                            </div>                         
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="form-group">
                        <div class="row" id="modal_footer">
                            <div class="cancel-btn">
                                <button type="button" id="cancel_btn_md" data-dismiss="modal">Cancel</button>
                            </div>

                            <div class="confirm-btn">
                                <button type="button" id="generate_btn">Generate</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal custom-modal" id="modal_printPreviewSchedule">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true"><i class="bi bi-x-square"></i></span>
                </button>

                <h4 class="modal-heading"></h4>
            </div>

            <div class="modal-body">
                <iframe src="" id="iframe_printSchedule" style="width:100%; height: 75vh;"></iframe>
                <br>
                <br>
                <center>
                    <a href="#" id="lnk_pdfPreview" target="_blank">View full page in new tab</a>
                </center>
            </div>
        </div>
    </div>
</div>