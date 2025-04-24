<?php
include "../../../dbsetting/lms_vars_config.php";
include "../../../dbsetting/classdbconection.php";
include "../../../functions/functions.php";
$dblms = new dblms();
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered" >
    <div class="modal-content">
        <div class="modal-header bg-primary p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-add-circle-line align-bottom me-1"></i>Add '.moduleName(LMS_VIEW).'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="id" value="'.cleanvars($_GET['id']).'">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <label class="form-label">Lecture <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices multiple name="id_lecture[]" required="">
                            <option value=""> Choose one</option>';
                            $array 			= explode(',',$COURSES_DISCUSSION['id_lecture']);
                            foreach(get_LessonLectures() as $key => $val):
                                echo'<option value="'.$key.'" '.((in_array($key,$array))? 'selected': '').'>'.$val.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="text" name="discussion_date" id="discussion_date" class="form-control" data-provider="flatpickr" data-date-format="d M, Y" '.((!empty($_GET['discussion_id']))? 'data-deafult-date="'.date("d M, Y",strtotime($COURSES_DISCUSSION['discussion_startdate'])).' to '.date("d M, Y",strtotime($COURSES_DISCUSSION['discussion_enddate'])).'"': '').' data-range-date="true" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Staus <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="discussion_status" required="">
                            <option value=""> Choose one</option>';
                            foreach(get_status() as $key => $status):
                                echo'<option value="'.$key.'" '.(($key == $COURSES_DISCUSSION['discussion_status'])? 'selected': '').'>'.$status.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col-md-12 mb-2">
                        <label class="form-label">Subject <span class="text-danger">*</span> </label>
                        <input class="form-control" id="discussion_subject" name="discussion_subject" value="'.$COURSES_DISCUSSION['discussion_subject'].'" required="">
                    </div>
                    <div class="col-md-12 mb-2">
                        <label class="form-label">Detail <span class="text-danger">*</span> </label>
                        <textarea class="form-control ckeditor1" id="ckeditor1" name="discussion_detail" required="">'.html_entity_decode(html_entity_decode($COURSES_DISCUSSION['discussion_detail'])).'</textarea>
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








