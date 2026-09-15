<?php
include "../../../dbsetting/lms_vars_config.php";
include "../../../dbsetting/classdbconection.php";
include "../../../functions/functions.php";
include "../../../functions/login_func.php";
$dblms = new dblms();
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-xl" >
    <div class="modal-content">
        <div class="modal-header bg-light p-3">
            <h5 class="modal-title text-dark" id="exampleModalLabel"><i class="ri-add-circle-line align-bottom me-1"></i>Add '.moduleName(LMS_VIEW).'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <label class="form-label">Lecture <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices data-choices-removeItem multiple name="id_lecture[]" required="">
                            <option value=""> Choose atleast one</option>';
                            foreach(get_LessonLectures() as $key => $val):
                                echo'<option value="'.$key.'">'.$val.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col-md-12 mb-2">
                        <label class="form-label">Staus <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="announcement_status" required="">
                            <option value=""> Choose one</option>';
                            foreach(get_status() as $key => $status):
                                echo'<option value="'.$key.'">'.$status.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col-md-12 mb-2">
                        <label class="form-label">Topic <span class="text-danger">*</span> </label>
                        <input class="form-control" id="announcement_topic" name="announcement_topic" required="">
                    </div>
                    <div class="col-md-12 mb-2">
                        <label class="form-label">Detail <span class="text-danger">*</span> </label>
                        <textarea class="form-control ckeditor1" id="ckeditor1" name="announcement_detail" required=""></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" name="submit_add"><i class="ri-add-circle-line align-bottom me-1"></i>Add '.moduleName(LMS_VIEW).'</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    CKEDITOR.replace(\'ckeditor1\');
</script>';
?>








