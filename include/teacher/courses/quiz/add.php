<?php
$condition = array(
                     'select'       =>  'cl.id_week'
                    ,'join'         =>  'INNER JOIN '.QUESTION_BANK.' qb ON FIND_IN_SET(cl.lesson_id, qb.id_lesson)'
                    ,'where'        =>  array(  
                                                     'cl.is_deleted'    => 0
                                                    ,'cl.id_curs'       => cleanvars($_GET['id'])
                                                   // ,'cl.id_session'    => cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                    ,'cl.id_campus'     => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                )
                    ,'group_by'     =>  'cl.id_week'
                    ,'order_by'     =>  'cl.id_week'
                    ,'return_type'  =>  'all'
);
$COURSES_LESSONS = $dblms->getRows(COURSES_LESSONS.' cl', $condition, $sql);
echo'
<form autocomplete="off" class="form-validate" id="get_QnsFrom" enctype="multipart/form-data" method="post" accept-charset="utf-8">
    <input type="hidden" name="id" value="'.cleanvars($_GET['id']).'">
    <input type="hidden" name="view" value="'.cleanvars($_GET['view']).'">
    <div class="row mb-2">
        <div class="col">
            <label class="form-label">Title <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="quiz_title" required>
        </div>
        <div class="col">
            <label class="form-label">'.get_CourseWise($curs['curs_wise']).' <span class="text-danger">*</span></label>
            <select class="form-control" data-choices name="id_week" id="id_week" onchange="openQnsLock(this.value);" required="">
                <option value=""> Choose one</option>';                
                foreach($COURSES_LESSONS as $week):
                    echo'<option value="'.$week['id_week'].'">'.get_CourseWise($curs['curs_wise']).' '.get_LessonWeeks($week['id_week']).'</option>';
                endforeach;
                echo'
            </select>
        </div>
    </div>
    <div class="row mb-2">    
        <div class="col">
            <label class="form-label">Time <span class="text-danger">*</span></label>
            <select class="form-control" data-choices name="quiz_time" required="">
                <option value=""> Choose one</option>';
                foreach(get_ExpectedTime() as $key => $val):
                    echo'<option value="'.$key.'">'.$val.'</option>';
                endforeach;
                echo'
            </select>
        </div>                  
        <div class="col">
            <label class="form-label">Status <span class="text-danger">*</span></label>
            <select class="form-control" data-choices name="quiz_status" required="">
                <option value=""> Choose one</option>';
                foreach(get_status() as $key => $val):
                    echo'<option value="'.$key.'">'.$val.'</option>';
                endforeach;
                echo'
            </select>
        </div>
        <div class="col">
            <label class="form-label">Publish <span class="text-danger">*</span></label>
            <select class="form-control" data-choices name="is_publish" required="">
                <option value=""> Choose one</option>';
                foreach(get_is_publish() as $key => $val):
                    echo'<option value="'.$key.'">'.$val.'</option>';
                endforeach;
                echo'
            </select>
        </div>
        <div class="col">
            <label class="form-label">Passing Percentage <span class="text-danger">*</span> <span class="text-info">(1-100)%</span></label>
            <input type="number" min="1" max="100" class="form-control" name="quiz_pass_percentage" id="quiz_pass_percentage" required="">
        </div>
    </div>
    <div class="card mb-3" id="m_lock">
        <div class="card-header alert-dark p-2">
            <div class="d-flex align-items-center">
                <h5 class="card-title mb-0 flex-grow-1">
                    <i class="ri-file-paper-2-fill align-bottom me-1"></i>
                    Multiple Choice
                    <input type="hidden" name="qns_type_m" value="3"></input>
                </h5>
            </div>
        </div>
        <div class="card-body border">
            <div class="row">
                <div class="col">
                    <label class="form-label">Question Level <span class="text-danger">*</span></label>
                    <select class="form-control" data-choices name="difficulty_level_m" onchange="MultipleChoice(this.value);">
                        <option value=""> Choose one</option>';
                        foreach(get_QnsLevel() as $key => $status):
                            echo'<option value="'.$key.'">'.$status.'</option>';
                        endforeach;
                        echo'
                    </select>
                </div>
                <div class="col" id="no_of_question_m">
                    <label class="form-label">No of question <span class="text-danger">*</span></label>
                    <select class="form-control" data-choices name="no_of_question_m" onchange="get_question_search_m(this.value);">
                        <option value=""> Choose one</option>';
                        foreach(get_HowManyQns() as $key => $status):
                            echo'<option value="'.$key.'">'.$status.'</option>';
                        endforeach;
                        echo'
                    </select>
                </div>
            </div>
            <div class="row mb-2" id="get_MultipleChoice"></div>
        </div>
    </div>
    <div class="card mb-3" id="s_lock">
        <div class="card-header alert-dark p-2">
            <div class="d-flex align-items-center">
                <h5 class="card-title mb-0 flex-grow-1">
                    <i class="ri-file-paper-2-fill align-bottom me-1"></i>
                    Short Question
                    <input type="hidden" name="qns_type_s" value="1"></input>
                </h5>
            </div>
        </div>
        <div class="card-body border">
            <div class="row">
                <div class="col">
                    <label class="form-label">Question Level</label>
                    <select class="form-control" data-choices name="difficulty_level_s" onchange="ShortQuestion(this.value);">
                        <option value=""> Choose one</option>';
                        foreach(get_QnsLevel() as $key => $status):
                            echo'<option value="'.$key.'">'.$status.'</option>';
                        endforeach;
                        echo'
                    </select>
                </div>
                <div class="col" id="no_of_question_s">
                    <label class="form-label">No of question</label>
                    <select class="form-control" data-choices name="no_of_question_s" onchange="get_question_search_s(this.value);">
                        <option value=""> Choose one</option>';
                        foreach(get_HowManyQns() as $key => $status):
                            if(!isset($_SESSION['QUIZQUESTIONS'][$key])) {
                                echo'<option value="'.$key.'">'.$status.'</option>';
                            }
                        endforeach;
                        echo'
                    </select>
                </div>
            </div>
            <div class="row" id="get_ShortQuestion"></div>
        </div>
    </div>
    <div id="TotalMarks"></div>
    <div class="row mb-2">
        <div class="col">
            <label class="form-label">Instruction</label>
            <textarea type="text" id="ckeditor1" class="form-control" name="quiz_instruction"></textarea>
        </div>
    </div>
    <hr>
    <div class="hstack gap-2 justify-content-end">
        <a href="'.moduleName().'.php?'.$redirection.'" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Close</a>
        <button type="submit" class="btn btn-primary btn-sm" name="submit_add"><i class="ri-add-circle-line align-bottom me-1"></i>Add '.moduleName(LMS_VIEW).'</button>
    </div>
</form>
<script>
    CKEDITOR.replace("ckeditor1");
    $("#m_lock").hide();
    $("#s_lock").hide();

    function openQnsLock(id) {
        if (id != "") {
            $("#m_lock").show();
            $("#s_lock").show();
        } else {
            $("#m_lock").hide();
            $("#s_lock").hide();
        }
        get_question_search_m();
        get_question_search_s();
    }
    $("#no_of_question_m").hide();
    function MultipleChoice (difficulty_level_m) {
        if (difficulty_level_m != "") {
            $("#no_of_question_m").show();
        } else {
            $("#no_of_question_m").hide();
        }        
    }
    function get_question_search_m(no_of_question_m = ""){
        if (no_of_question_m != "") {
            var formData = $("#get_QnsFrom").serialize();
            $.ajax({
                 url        : "include/ajax/get_MultipleChoiceQns.php"
                ,data       : formData
                ,success    : function(response){
                    $("#get_MultipleChoice").html(response);
                }
            });
        }
    }
    $("#no_of_question_s").hide();
    function ShortQuestion (difficulty_level_s) {
        if (difficulty_level_s != "") {
            $("#no_of_question_s").show();
        } else {
            $("#no_of_question_s").hide();
        }        
    }
    function get_question_search_s(no_of_question_s = ""){
        if (no_of_question_s != "") {
            var formData = $("#get_QnsFrom").serialize();
            $.ajax({
                 url        : "include/ajax/get_ShortQns.php"
                ,data       : formData
                ,success    : function(response){
                    $("#get_ShortQuestion").html(response);
                }
            });
        }
    }
</script>';
?>