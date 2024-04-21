<!-- Modal for adding a new class -->
<div class="modal custom-modal" id="resource-modal">
    <div class="modal-dialog modal-lg">
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
                        <div id="subject-box" class="col-lg-10 col-md-10 col-sm-10 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
                            {{ csrf_field() }}

                            <div class="input-box">
                                <label class="placeholder">Course/Yr/Blk</label>
                                <input type="text" name="name">
                            </div>

                            <div class="input-box">
                                <label class="placeholder">Academic Period</label>
                                <div class="select2-wrapper">
                                    <select id="academic-period-select" name="academic_period_id" class="select2">
                                        <option selected disabled>Select an academic period</option>
                                        @foreach ($academicPeriods as $period)
                                            <option value="{{ $period->id }}">{{ $period->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group class-form" >
                                <label class="side-icon"><i class="bi bi-plus-circle" id="subject-add"></i></label>

                                <div class="subject-add">
                                    <div class="col-md-7 col-sm-5 col-xs-12">
                                        Subject
                                    </div>

                                    <div class="col-md-1 col-sm-5 col-xs-12">
                                        Units
                                    </div>
                                </div>

                                <div id="subjects-container">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="form-group">
                        <div class="row" id="modal_footer">
                            <div>
                                <button type="button" id="cancel_btn_xs" data-dismiss="modal">Cancel</button>
                            </div>

                            <div>
                                <button type="submit" id="confirm_btn_xs">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Hidden Subject Template -->
<div id="subject-template" class="hidden">
     <div class="row subject-form appended-subject" id="subject-{ID}-container">

        <div class="col-md-7 col-sm-4 col-xs-10" id="subject_template">
            <div class="select2-wrapper">
                <select class="subject-select" name="subject-{ID}" required>
                    <option selected disabled></option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-4 col-sm-4 col-xs-10">
            <input type="number" class="subject-units" name="subject-{ID}-units" style="margin-bottom: 5px" required max="9">
        </div>

        <div class="col-md-1 col-sm-1 col-xs-1">
            <span class="fa fa-close close-icon subject-remove" title="Remove Subject" data-id="{ID}"></span>
        </div>
    </div>
</div>