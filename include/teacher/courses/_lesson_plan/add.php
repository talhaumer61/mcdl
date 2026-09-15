<?php
$condition = array ( 
                        'select' 	    =>  'lesson_id, lesson_topic'
                        ,'where' 	    =>  array(  
                                                     'is_deleted'           =>  0
                                                    ,'lesson_status'        =>  1
                                                    ,'id_curs'				=>  cleanvars(CURS_ID)
                                                    ,'id_session'           =>  cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                    ,'id_campus'            =>  cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                    ,'id_teacher'           =>  cleanvars($_SESSION['userlogininfo']['EMPLYID'])
                                                )
                        ,'order_by'     =>  'lesson_id DESC'
                        ,'return_type'  =>  'all' 
                    ); 
$COURSES_LESSONS    = $dblms->getRows(COURSES_LESSONS, $condition);
echo'
<script src="assets/js/app.js"></script>
<form autocomplete="off" class="form-validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
    <div class="row">
        <div class="col mb-2">
            <label class="form-label">Week <span class="text-danger">*</span></label>
            <select class="form-control" data-choices name="id_week" required="">
                <option value=""> Choose one</option>';
                foreach(get_LessonWeeks() as $key => $val):
                    echo'<option value="'.$key.'">'.$val.'</option>';
                endforeach;
                echo'
            </select>
        </div>
        <div class="col mb-2">
            <label class="form-label">Lecture <span class="text-danger">*</span></label>
            <select class="form-control" data-choices name="id_lecture" required="">
                <option value=""> Choose one</option>';
                foreach(get_LessonLectures() as $key => $val):
                    echo'<option value="'.$key.'">'.$val.'</option>';
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
                    echo'<option value="'.$key.'">'.$status.'</option>';
                endforeach;
                echo'
            </select>
        </div>
        <div class="col mb-2">
            <label class="form-label">Topic Content <span class="text-danger">*</span></label>
            <select class="form-control" data-choices name="lesson_content" onchange="get_TopicContent(this.value);" required="">
                <option value=""> Choose one</option>';
                foreach (get_topic_content() as $key => $value) {
                    echo'<option value="'.$key.'">'.$value.'</option>';
                }
                echo'
            </select>
        </div>
    </div>';
    if($COURSES_LESSONS){
        echo' 
        <div class="row">                       
            <div class="col mb-2">
                <label class="form-label">Parent Topic</label>
                <select class="form-control" data-choices name="id_parent_topic">
                    <option value=""> Choose one</option>';
                    foreach($COURSES_LESSONS as $row):
                        echo'<option value="'.$row['lesson_id'].'">'.$row['lesson_topic'].'</option>';
                    endforeach;
                    echo'
                </select>
            </div>
        </div>';
    }
    echo'
    <div class="row">
        <div class="col" id="idVideo" style="display: '.((empty($_GET['lesson_id']))? 'block': 'none').';">
            <label class="form-label">Video Code <span class="text-danger">*</span></label>
            <input class="form-control" id="lesson_video_code" name="lesson_video_code" required>
        </div>
        <div class="col mb-2">
            <label class="form-label">Topic <span class="text-danger">*</span> </label>
            <input class="form-control" id="lesson_topic" name="lesson_topic" required>
        </div>
    </div>
    <div class="row">
        <div class="col mb-2" id="idReading" style="display: '.((empty($_GET['lesson_id']))? 'block': 'none').';">
            <label class="form-label">Reading Detail <span class="text-danger">*</span></label>
            <textarea class="form-control ckeditor2" id="ckeditor2" name="lesson_reading_detail" required=""></textarea>
        </div>
    </div>
    <div class="row">
        <div class="col mb-2">
            <label class="form-label">Detail <span class="text-danger">*</span> </label>
            <textarea class="form-control ckeditor1" id="ckeditor1" name="lesson_detail" required=""></textarea>
        </div>
    </div>
    <hr>
    <div class="hstack gap-2 justify-content-end">
        <a href="'.moduleName().'.php?'.$redirection.'" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Close</a>
        <button type="submit" class="btn btn-primary btn-sm" name="submit_add"><i class="ri-add-circle-line align-bottom me-1"></i>Add '.moduleName(LMS_VIEW).'</button>
    </div>
</form>

<script type="text/javascript">';
    if (!empty($COURSES_LESSONS['lesson_video_code']) && !empty($COURSES_LESSONS['lesson_reading_detail'])){
        $flag = 3;
    } else if (!empty($COURSES_LESSONS['lesson_video_code'])) {
        $flag = 1;
    } else {
        $flag = 2;
    }
    echo '
    window.onload = function() {
        get_TopicContent('.$flag.');
    }
    
    CKEDITOR.replace(\'ckeditor1\');
    CKEDITOR.replace(\'ckeditor2\');

    function get_TopicContent(id = ""){
        var idVideo     = $("#idVideo");
        var idReading   = $("#idReading");
        if (id == 1) {
            idVideo.attr("style","display: block;");
            idReading.attr("style","display: none;");
        } else if (id == 2) {
            idReading.attr("style","display: block;");
            idVideo.attr("style","display: none;");
        } else if (id == 3) {
            idVideo.attr("style","display: block;");;
            idReading.attr("style","display: block;");;
        } else {
            idVideo.attr("style","display: none;");;
            idReading.attr("style","display: none;");;
        }        
    }
</script>';
?>