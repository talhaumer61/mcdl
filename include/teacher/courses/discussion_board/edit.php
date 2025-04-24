<?php
// RECORD TO EDIT
$condition = array ( 
                         'select' 	    =>  'discussion_id, discussion_status, id_lecture, discussion_subject, discussion_detail, discussion_startdate, discussion_enddate'
                        ,'where' 	    =>  array(  
                                                     'is_deleted'           => 0
                                                    ,'id_session'           => cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                    ,'id_campus'            => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                    // ,'id_teacher'           => cleanvars($_SESSION['userlogininfo']['EMPLYID'])
                                                    ,'discussion_id'        => cleanvars(LMS_EDIT_ID)
                                                )
                        ,'return_type'  =>  'single' 
                    ); 
$row = $dblms->getRows(COURSES_DISCUSSION, $condition);
echo'
<form autocomplete="off" class="form-validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
    <div class="row">
        <div class="col mb-2">
            <label class="form-label">Lecture <span class="text-danger">*</span></label>
            <select class="form-control" data-choices data-choices-removeItem multiple name="id_lecture[]" required="">
                <option value=""> Choose one</option>';
                foreach(get_LessonLectures() as $key => $val):
                    echo'<option value="'.$key.'" '.((in_array($key, explode(',',$row['id_lecture'])))? 'selected': '').'>'.$val.'</option>';
                endforeach;
                echo'
            </select>
        </div>
    </div>
    <div class="row">';
        /*
        echo'
        <div class="col-md-6 mb-2">
            <label class="form-label">Date <span class="text-danger">*</span></label>
            <input type="text" name="discussion_date" id="discussion_date" class="form-control" data-provider="flatpickr" data-date-format="Y-m-d" data-deafult-date="'.date('Y-m-d',strtotime($row['discussion_startdate'])).' to '.date('Y-m-d',strtotime($row['discussion_enddate'])).'" data-range-date="true" required>
        </div>';
        */
        echo'
        <div class="col-md-6 mb-2">
            <label class="form-label">Staus <span class="text-danger">*</span></label>
            <select class="form-control" data-choices name="discussion_status" required="">
                <option value=""> Choose one</option>';
                foreach(get_status() as $key => $status):
                    echo'<option value="'.$key.'" '.(($key == $row['discussion_status'])? 'selected': '').'>'.$status.'</option>';
                endforeach;
                echo'
            </select>
        </div>
        <div class="col mb-2">
            <label class="form-label">Subject <span class="text-danger">*</span> </label>
            <input class="form-control" id="discussion_subject" name="discussion_subject" value="'.$row['discussion_subject'].'" required="">
        </div>
    </div>
    <div class="row">
        <div class="col mb-2">
            <label class="form-label">Detail <span class="text-danger">*</span> </label>
            <textarea class="form-control ckeditor1" id="ckeditor1" name="discussion_detail" required="">'.html_entity_decode(html_entity_decode($row['discussion_detail'])).'</textarea>
        </div>
    </div>
    <hr>
    <div class="hstack gap-2 justify-content-end">
        <a href="'.moduleName().'.php?'.$redirection.'" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Close</a>
        <button type="submit" class="btn btn-primary btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(LMS_VIEW).'</button>
    </div>
</form>';
?>