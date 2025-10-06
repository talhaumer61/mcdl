<?php
// DEGREE
$condition = array ( 
                         'select'        =>  'admoff_id, admoff_status, admoff_type, id_type, admoff_degree, id_cat, admoff_startdate, admoff_enddate, admoff_amount, admoff_amount_in_usd'
                        ,'where' 	    =>   array( 
                                                     'is_deleted'    => 0
                                                    ,'admoff_id'     => cleanvars(LMS_EDIT_ID)
                                                )
                        ,'return_type'  =>  'single' 
                    ); 
$row = $dblms->getRows(ADMISSION_OFFERING, $condition);

echo'
<script>
$(document).ready(function(){
    get_offering_degree('.$row['admoff_type'].','.$row['id_cat'].','.$row['admoff_degree'].');
})
</script>
<div class="row mb-5">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header bg-info">
                <h5 class="modal-title mb-0"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(false).'</h5>
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
                                            echo'<option value="'.$key.'" '.($row['id_type'] == $key ? 'selected':'').'>'.$val.'</option>';
                                        endforeach;
                                        echo'
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="form-label">Type <span class="text-danger">*</span></label>
                                    <select class="form-control" name="admoff_type" onchange="get_offering_degree(this.value)" data-choices required="">
                                        <option value="">Choose one</option>';
                                        foreach (get_offering_type() as $key => $status):
                                            echo'<option value="'.$key.'" '.($row['admoff_type'] == $key ? 'selected':'').'>'.$status.'</option>';
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
                                    <input type="text" data-provider="flatpickr" class="form-control" name="admoff_startdate" value="'.($row['admoff_startdate'] != '0000-00-00' ? $row['admoff_startdate'] : '').'" required>
                                </div>
                                <div class="col">
                                    <label class="form-label">Ending Date</label>
                                    <input type="text" data-provider="flatpickr" class="form-control" name="admoff_enddate" value="'.($row['admoff_enddate'] != '0000-00-00' ? $row['admoff_enddate'] : '').'" required>
                                </div>
                                <div class="col">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-control" name="admoff_status" data-choices required="">
                                        <option value="">Choose one</option>';
                                        foreach (get_status() as $key => $status):
                                            echo'<option value="'.$key.'" '.($key == $row['admoff_status'] ? 'selected':'').'>'.$status.'</option>';
                                        endforeach;
                                        echo'
                                    </select>
                                </div>
                                <div class="col" id="course_amount" hidden>
                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label">Amount In PKR</label>
                                            <input type="number" class="form-control" name="admoff_amount" value="'.$row['admoff_amount'].'" '.($row['admoff_type'] == '1' ? 'readonly':'').' required>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Amount In USD</label>
                                            <input type="number" class="form-control" name="admoff_amount_in_usd" value="'.$row['admoff_amount_in_usd'].'" '.($row['admoff_type'] == '1' ? 'readonly':'').' required>
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
                        <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(false).'</button>
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
    get_LearnerType(<?= $row['id_type']; ?>);
</script>