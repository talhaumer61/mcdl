<?php
echo'
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header bg-primary">
                <h5 class="modal-title mb-0"><i class="ri-add-circle-line align-bottom me-1"></i>Add '.moduleName(false).'</h5>
            </div>
            <form enctype="multipart/form-data" autocomplete="off" method="post" accept-charset="utf-8">
                <div class="card-body create-project-main">
                    <div class="card mb-3">
                        <div class="card-header alert-primary">
                            <h6 class="mb-0"><i class="bx bx-paperclip align-middle  fs-18 me-1"></i>Degree <span class="text-danger">(Mandatory)</span></h6>
                        </div>
                        <div class="card-body border">
                            <div class="row mb-2">
                                <div class="col">
                                    <label class="form-label">Learner Type <span class="text-danger">*</span></label>
                                    <select class="form-control" name="learner_type" onchange="get_LearnerType(this.value)" data-choices required="">
                                        <option value="">Choose one</option>';
                                        foreach (get_LeanerType() as $key => $val):
                                            echo'<option value="'.$key.'">'.$val.'</option>';
                                        endforeach;
                                        echo'
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="form-label">Type <span class="text-danger">*</span></label>
                                    <select class="form-control" name="admoff_type" onchange="get_offering_degree(this.value)" data-choices required="">
                                        <option value="">Choose one</option>';
                                        foreach (get_offering_type() as $key => $status):
                                            echo'<option value="'.$key.'">'.$status.'</option>';
                                        endforeach;
                                        echo'
                                    </select>
                                </div>
                            </div>
                            <div id="offering_detail"></div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header alert-primary">
                            <h6 class="mb-0"><i class="bx bx-paperclip align-middle  fs-18 me-1"></i>Timming & Status <span class="text-danger">(Mandatory)</span></h6>
                        </div>
                        <div class="card-body border">
                            <div class="row mb-2">
                                <div class="col">
                                    <label class="form-label">Starting Date</label>
                                    <input type="text" data-provider="flatpickr" class="form-control" name="admoff_startdate" required>
                                </div>
                                <div class="col">
                                    <label class="form-label">Ending Date</label>
                                    <input type="text" data-provider="flatpickr" class="form-control" name="admoff_enddate" required>
                                </div>
                                <div class="col">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-control" name="admoff_status" data-choices required="">
                                        <option value="">Choose one</option>';
                                        foreach (get_status() as $key => $status):
                                            echo'<option value="'.$key.'">'.$status.'</option>';
                                        endforeach;
                                        echo'
                                    </select>
                                </div>
                                <div class="col" id="course_amount" hidden>
                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label">Amount In PKR <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="admoff_amount" required>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Amount In USD <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="admoff_amount_in_usd" required>
                                        </div>
                                    </div>
                                </div>
                            </div>   
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <a href="'.moduleName().'.php" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Close</a>
                        <button type="submit" class="btn btn-primary btn-sm" name="submit_add"><i class="ri-add-circle-line align-bottom me-1"></i>Add '.moduleName(false).'</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>';
?>
<script>
    var originalHtml = "";
    function get_LearnerType(learner_type) {
        if (learner_type == 2) {
            if (!originalHtml) {
                originalHtml = $("#course_amount").html();
            }
            $("#course_amount").attr("hidden", true).empty();
        } else {
            if (!$("#course_amount").html()) {
                $("#course_amount").html(originalHtml);
            }
            $("#course_amount").removeAttr("hidden");
        }
    }
</script>