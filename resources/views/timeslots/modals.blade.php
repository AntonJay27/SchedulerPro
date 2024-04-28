<!-- Modal for adding a new timeslot -->
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

                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="timeslot-box">
                                        <label class="placeholder">From</label>
                                        <div class="select2-wrapper">
                                            <select id="from-select" name="from" class="select2">
                                                @for($i = 0; $i <= 23; $i++)
                                                    @foreach(['00', '30'] as $subPart)
                                                    <option value="{{ (($i < 10) ? "0" : "") . $i . ":" . $subPart }}">
                                                        {{ (($i < 10) ? "0" : "") . $i . ":" . $subPart }}
                                                    </option>
                                                    @endforeach
                                                @endfor
                                            </select>
                                        </div>                                                                
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="timeslot-box">
                                        <label class="placeholder">To</label>
                                        <div class="select2-wrapper">
                                            <select id="to-select" name="to" class="select2">
                                                @for($i = 0; $i <= 23; $i++)
                                                    @foreach(['00', '30'] as $subPart)
                                                    <option value="{{ (($i < 10) ? "0" : "") . $i . ":" . $subPart }}">
                                                        {{ (($i < 10) ? "0" : "") . $i . ":" . $subPart }}
                                                    </option>
                                                    @endforeach
                                                @endfor
                                            </select>
                                        </div>  
                                    </div>
                                </div>
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
                                <button type="submit" id="confirm_btn_md">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>