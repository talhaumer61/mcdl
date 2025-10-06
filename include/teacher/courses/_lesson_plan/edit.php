<?php
// 
$condition = array (
                         'select'       =>  '*'
                        ,'where'        =>  array(
                                                    'lesson_id'    => cleanvars(LMS_EDIT_ID)
                                                )
                        ,'return_type'  =>  'single'
);
$row = $dblms->getRows(COURSES_LESSONS, $condition);

$condition = array ( 
                         'select' 	    =>  'lesson_id, lesson_topic'
                        ,'where' 	    =>  array(  
                                                     'is_deleted'           =>  0
                                                    ,'lesson_status'        =>  1
                                                    ,'id_curs'				=>  cleanvars(CURS_ID)
                                                    ,'id_session'           =>  cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                    ,'id_campus'            =>  cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                    // ,'id_teacher'           =>  cleanvars($_SESSION['userlogininfo']['EMPLYID'])
                                                )
                        ,'not_equal'    =>  array(
                                                    'lesson_id'             =>  cleanvars(LMS_EDIT_ID)
                                                )
                        ,'order_by'     =>  'lesson_id DESC'
                        ,'return_type'  =>  'all' 
); 
$COURSES_LESSONS = $dblms->getRows(COURSES_LESSONS, $condition);
echo'
<script src="assets/js/app.js"></script>
<form autocomplete="off" class="form-validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
    <input type="hidden" name="lesson_id" value="'.$row['lesson_id'].'"/>
    <div class="row">
        <div class="col mb-2">
            <label class="form-label">Week <span class="text-danger">*</span></label>
            <select class="form-control" data-choices name="id_week" required="">
                <option value=""> Choose one</option>';
                foreach(get_LessonWeeks() as $key => $val):
                    echo'<option value="'.$key.'" '.(($key == $row['id_week'])? 'selected': '').'>'.$val.'</option>';
                endforeach;
                echo'
            </select>
        </div>
        <div class="col mb-2">
            <label class="form-label">Lecture <span class="text-danger">*</span></label>
            <select class="form-control" data-choices name="id_lecture" required="">
                <option value=""> Choose one</option>';
                foreach(get_LessonLectures() as $key => $val):
                    echo'<option value="'.$key.'" '.(($key == $row['id_lecture'])? 'selected': '').'>'.$val.'</option>';
                endforeach;
                echo'
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col mb-2">
            <label class="form-label">Status <span class="text-danger">*</span></label>
            <select class="form-control" data-choices name="lesson_status" required="">
                <option value=""> Choose one</option>';
                foreach(get_status() as $key => $status):
                    echo'<option value="'.$key.'" '.(($key == $row['lesson_status'])? 'selected': '').'>'.$status.'</option>';
                endforeach;
                echo'
            </select>
        </div>
        <div class="col mb-2">
            <label class="form-label">Topic Content <span class="text-danger">*</span></label>
            <select class="form-control" data-choices name="lesson_content" onchange="get_TopicContent(this.value);" required="">
                <option value=""> Choose one</option>';
                foreach (get_topic_content() as $key => $value) {
                    echo'<option value="'.$key.'" '.(($key == $row['lesson_content'])? 'selected': '').'>'.$value.'</option>';
                }
                echo'
            </select>
        </div>
    </div>';
    if($course_lesson){
        echo' 
        <div class="row">                       
            <div class="col mb-2">
                <label class="form-label">Parent Topic</label>
                <select class="form-control" data-choices name="id_parent_topic">
                    <option value=""> Choose one</option>';
                    foreach($course_lesson as $parent):
                        echo'<option value="'.$parent['lesson_id'].'" '.($parent['course_id'] == $row['id_parent_topic'] ? 'selected' : '').'>'.$parent['lesson_topic'].'</option>';
                    endforeach;
                    echo'
                </select>
            </div>
        </div>';
    }
    echo'
    <div class="row">
        <div class="col" id="idVideo">
            <label class="form-label">Video Code <span class="text-danger">*</span></label>
            <input class="form-control" id="lesson_video_code" name="lesson_video_code" value="'.$row['lesson_video_code'].'" required="">
        </div>
        <div class="col mb-2">
            <label class="form-label">Topic <span class="text-danger">*</span> </label>
            <input class="form-control" id="lesson_topic" name="lesson_topic" value="'.$row['lesson_topic'].'" required="">
        </div>
    </div>
    <div class="row">
        <div class="col mb-2" id="idReading">
            <label class="form-label">Reading Detail <span class="text-danger">*</span></label>
            <textarea class="form-control ckeditor2" id="ckeditor2" name="lesson_reading_detail" required="">'.html_entity_decode(html_entity_decode($row['lesson_reading_detail'])).'</textarea>
        </div>
    </div>
    <div class="row">
        <div class="col mb-2">
            <label class="form-label">Detail <span class="text-danger">*</span> </label>
            <textarea class="form-control ckeditor1" id="ckeditor1" name="lesson_detail" required="">'.html_entity_decode(html_entity_decode($row['lesson_detail'])).'</textarea>
        </div>
    </div>
    <hr>
    <div class="hstack gap-2 justify-content-end">
        <a href="'.moduleName().'.php?'.$redirection.'" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Close</a>
        <button type="submit" class="btn btn-primary btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(LMS_VIEW).'</button>
    </div>
</form>
<script type="text/javascript">';
    if (!empty($row['lesson_video_code']) && !empty($row['lesson_reading_detail'])){
        
    } else if (!empty($row['lesson_video_code'])) {
        echo '
        $("#idReading").hide();';
    } else {
        echo '
        $("#idVideo").hide();';
    }
    echo'
    CKEDITOR.replace(\'ckeditor1\');
    CKEDITOR.replace(\'ckeditor2\');

    function get_TopicContent(id = ""){
        var idVideo     = $("#idVideo");
        var idReading   = $("#idReading");
        if (id == 1) {
            idVideo.fadeIn();
            idReading.fadeOut();
            idReading.find("textarea").removeAttr("required");
        } else if (id == 2) {            
            idReading.fadeIn();
            idVideo.fadeOut();
            idVideo.find("input").removeAttr("required");
        } else if (id == 3) {
            idVideo.fadeIn();
            idReading.fadeIn();
        } else {
            idVideo.fadeOut();
            idReading.fadeOut();
            idVideo.find("input").removeAttr("required");
            idReading.find("textarea").removeAttr("required");
        }
    }
</script>';
?>