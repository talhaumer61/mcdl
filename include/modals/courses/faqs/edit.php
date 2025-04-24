<?php
include "../../../dbsetting/lms_vars_config.php";
include "../../../dbsetting/classdbconection.php";
include "../../../functions/functions.php";
$dblms = new dblms();
include "../../../functions/login_func.php";
checkCpanelLMSALogin();

// EDITABLE RECORD
$condition = array ( 
                         'select' 	    =>  'id, status, question, answer, id_lecture, id_lesson'
                        ,'where' 	    =>  array(  
                                                     'is_deleted'           => 0
                                                    ,'id_session'           => cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                    ,'id_campus'            => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                    // ,'id_teacher'           => cleanvars($_SESSION['userlogininfo']['EMPLYID'])
                                                    ,'id'                   => cleanvars(LMS_EDIT_ID)
                                                )
                        ,'return_type'  =>  'single' 
                    ); 
$row = $dblms->getRows(COURSES_FAQS, $condition);

// COURSE LESSONS
$condition = array ( 
                         'select' 	    =>  'lesson_id, lesson_topic'
                        ,'where' 	    =>  array(  
                                                     'is_deleted'           =>  0
                                                    ,'lesson_status'        =>  1
                                                    ,'id_curs'				=>  cleanvars($_GET['id'])
                                                    ,'id_session'           =>  cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                    ,'id_campus'            =>  cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                    // ,'id_teacher'           =>  cleanvars($_SESSION['userlogininfo']['EMPLYID'])
                                                )
                        ,'order_by'     =>  'lesson_id DESC'
                        ,'return_type'  =>  'all' 
); 
$COURSES_LESSONS = $dblms->getRows(COURSES_LESSONS, $condition);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered" >
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(LMS_VIEW).'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="edit_id" value="'.LMS_EDIT_ID.'">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <label class="form-label">Topic <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices data-choices-removeItem multiple name="id_lesson[]" required="">
                            <option value=""> Choose atleast one</option>';
                            foreach($COURSES_LESSONS as $key => $val):                                
                                echo'<option value="'.$val['lesson_id'].'" '.(in_array($val['lesson_id'], explode(',',$row['id_lesson'])) ? 'selected': '').'>'.$val['lesson_topic'].'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col-md-12 mb-2">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="status" required="">
                            <option value=""> Choose one</option>';
                            foreach(get_status() as $key => $status):
                                echo'<option value="'.$key.'" '.(($key == $row['status']) ? 'selected': '').'>'.$status.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col-md-12 mb-2">
                        <label class="form-label">Question <span class="text-danger">*</span> </label>
                        <input class="form-control" id="question" name="question" value="'.$row['question'].'" required="">
                    </div>
                    <div class="col-md-12 mb-2">
                        <label class="form-label">Qnswer <span class="text-danger">*</span> </label>
                        <textarea class="form-control ckeditor1" id="ckeditor1" name="answer" required="">'.html_entity_decode(html_entity_decode($row['answer'])).'</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(LMS_VIEW).'</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    CKEDITOR.replace(\'ckeditor1\');
</script>';
?>








