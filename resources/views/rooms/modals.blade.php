<!-- Modal for adding a new room -->
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
                                <label class="placeholder">Name</label>
                                <input type="text" name="name" required>
                            </div>

                            <div class="input-box">
                                <label class="placeholder">Lab</label>
                                <select name="lab" class="select-control" style="width:100%;" required>
                                    <option value="">---</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
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