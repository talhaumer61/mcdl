<?php

// lesson information
$result     = $coursecls->get_courselessondetail(LMS_EDIT_ID);

// COURSES lesson DOWNLOADS
$downloads  = $coursecls->get_lessondownloads($result['lesson_id']);

$lessons    = $coursecls->get_courselessons(CURS_ID);


echo'
<script src="assets/js/app.js"></script>
<form autocomplete="off" class="form-validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
    <div class="row">
        <div class="col mb-2">
            <label class="form-label">'.moduleName(get_CourseWise($curs['curs_wise'])).' <span class="text-danger">*</span></label>
            <select class="form-control" data-choices name="id_week" required="">
                <option value=""> Choose one</option>';
                foreach(get_LessonWeeks() as $key => $val):
                    echo'<option value="'.$key.'" '.(($key == $result['id_week'])? 'selected': '').'>'.moduleName(get_CourseWise($curs['curs_wise'])).' '.$val.'</option>';
                endforeach;
                echo'
            </select>
        </div>
        <div class="col mb-2">
            <label class="form-label">Lecture <span class="text-danger">*</span></label>
            <select class="form-control" data-choices name="id_lecture" required="">
                <option value=""> Choose one</option>';
                foreach(get_LessonLectures() as $key => $val):
                    echo'<option value="'.$key.'" '.(($key == $result['id_lecture'])? 'selected': '').'>'.$val.'</option>';
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
                    echo'<option value="'.$key.'" '.(($key == $result['lesson_status'])? 'selected': '').'>'.$status.'</option>';
                endforeach;
                echo'
            </select>
        </div>
        <div class="col mb-2">
            <label class="form-label">Topic Content <span class="text-danger">*</span></label>
            <select class="form-control" data-choices name="lesson_content" onchange="get_TopicContent(this.value);" required="">
                <option value=""> Choose one</option>';
                foreach (get_topic_content() as $key => $value) {
                    echo'<option value="'.$key.'" '.(($key == $result['lesson_content'])? 'selected': '').'>'.$value.'</option>';
                }
                echo'
            </select>
        </div>
    </div>';
    if($lessons){
        echo' 
        <div class="row">                       
            <div class="col mb-2">
                <label class="form-label">Parent Topic</label>
                <select class="form-control" data-choices name="id_parent_topic">
                    <option value=""> Choose one</option>';
                    foreach($lessons as $parent):
                        if($result['lesson_id'] != $parent['lesson_id']) {
                            echo '<option value="' . $parent['lesson_id'] . '" ' . ($parent['course_id'] == $result['id_parent_topic'] ? 'selected' : '') . '>' . $parent['lesson_topic'] . '</option>';
                        }
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
            <input class="form-control" id="lesson_video_code" name="lesson_video_code" value="'.$result['lesson_video_code'].'" required="">
        </div>
        <div class="col mb-2">
            <label class="form-label">Topic <span class="text-danger">*</span> </label>
            <input class="form-control" id="lesson_topic" name="lesson_topic" value="'.$result['lesson_topic'].'" required="">
        </div>
    </div>
    <div class="row">
        <div class="col mb-2" id="idReading">
            <label class="form-label">Reading Detail <span class="text-danger">*</span></label>
            <textarea class="form-control ckeditor2" id="ckeditor2" name="lesson_reading_detail" required="">'.html_entity_decode(html_entity_decode($result['lesson_reading_detail'])).'</textarea>
        </div>
    </div>
    <div class="row">
        <div class="col mb-2">
            <label class="form-label">Detail <span class="text-danger">*</span> </label>
            <textarea class="form-control ckeditor1" id="ckeditor1" name="lesson_detail" required="">'.html_entity_decode(html_entity_decode($result['lesson_detail'])).'</textarea>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header alert-dark">
            <h6 class="mb-0"><i class="bx bx-info-circle align-middle fs-18 me-1"></i>Resource (Optional)</h6>
        </div>
        <div class="card-body border">';
            if ($downloads) {
                foreach ($downloads AS $key => $val) {
                    echo'
                    <div class="row" id="rowResource">
                        <div class="col">
                            <label class="form-label">Resource Title </label>
                            <input type="text" class="form-control" name="file_name[]" value="'.$val['file_name'].'"/>
                        </div>
                        <div class="col">
                            <label class="form-label">Attach File </label>
                            <input class="form-control" type="file" accept=".pdf, .xlsx, .xls, .doc, .docx, .ppt, .pptx, .png, .jpg, .jpeg" name="file[]" id="fileInput">
                            <p id="errorMessage" class="text-danger" style="display: none;">File must be less than 5MB.</p>
                            <div class="text-primary mt-1">Upload valid files. Only <span class="text-danger fw-bold">pdf, xlsx, xls, doc, docx, ppt, pptx, png, jpg, jpeg</span> are allowed.</div>
                        </div>
                        <div class="col">
                            <label class="form-label">Url </label>
                            <input type="text" class="form-control" name="resource_url[]" value="'.$val['url'].'"/>
                        </div>
                        <div class="col-md-1" style="margin-top: 12px;">
                            <i class="ri-'.($key == 0?'add':'close').'-circle-line" '.($key == 0?'onclick="addResource()"':'onclick="editResource(this.id)" id="addResource'.$key.'"').' style="font-size: 40px;"></i>
                            <input type="hidden" name="id_resource[]" value="'.$val['id'].'">
                        </div>
                    </div>';
                }
            } else {
                echo'
                <div class="row" id="rowResource">
                    <div class="col">
                        <label class="form-label">Resource Title </label>
                        <input type="text" class="form-control" name="file_name[]" />
                    </div>
                    <div class="col">
                        <label class="form-label">Attach File </label>
                        <input class="form-control" type="file" accept=".pdf, .xlsx, .xls, .doc, .docx, .ppt, .pptx, .png, .jpg, .jpeg" name="file[]" id="fileInput">
                        <p id="errorMessage" class="text-danger" style="display: none;">File must be less than 5MB.</p>
                        <div class="text-primary mt-2">Upload valid files. Only <span class="text-danger fw-bold">pdf, xlsx, xls, doc, docx, ppt, pptx, png, jpg, jpeg</span> are allowed.</div>
                    </div>
                    <div class="col">
                        <label class="form-label">Url </label>
                        <input type="text" class="form-control" name="resource_url[]"/>
                    </div>
                    <div class="col-md-1" style="margin-top: 12px;">
                        <i class="ri-add-circle-line" onclick="addResource()" style="font-size: 40px;"></i>
                    </div>
                </div>';
            }
            echo'
        </div>
    </div>
    <hr>
    <div class="hstack gap-2 justify-content-end">
        <a href="'.moduleName().'.php?'.$redirection.'" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Close</a>
        <button type="submit" class="btn btn-primary btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(LMS_VIEW).'</button>
    </div>
</form>
<script type="text/javascript">';
    if (!empty($result['lesson_video_code']) && !empty($result['lesson_reading_detail'])){
        
    } else if (!empty($result['lesson_video_code'])) {
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
include_once ('include/teacher/courses/lesson_plan/script.php');
