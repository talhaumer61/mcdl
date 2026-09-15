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

$condition = array(
                     'select'       =>  'quiz_id, quiz_status, quiz_title, quiz_instruction, id_week, quiz_no_qns, quiz_time, is_publish, quiz_pass_percentage'
                    ,'where'        =>  array(  
                                                     'is_deleted'           => 0
                                                    // ,'id_session'           => cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                    ,'id_campus'            => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                    ,'quiz_id'              => cleanvars(LMS_EDIT_ID)
                                                    // ,'id_teacher'           => cleanvars($_SESSION['userlogininfo']['EMPLYID'])
                                                )
                    ,'order_by'     =>  'quiz_id DESC'
                    ,'return_type'  =>  'single'
);
$QUIZ = $dblms->getRows(QUIZ, $condition);
if ($QUIZ['is_publish'] != 1) {
    echo'
    <form autocomplete="off" class="form-validate" id="get_QnsFrom" enctype="multipart/form-data" method="post" accept-charset="utf-8">
        <input type="hidden" name="id" value="'.cleanvars($_GET['id']).'">
        <input type="hidden" name="view" value="'.cleanvars($_GET['view']).'">
        <div class="row mb-2">
            <div class="col">
                <label class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="quiz_title" value="'.$QUIZ['quiz_title'].'" required>
            </div>
            <div class="col">
                <label class="form-label">'.get_CourseWise($curs['curs_wise']).' <span class="text-danger">*</span></label>
                <select class="form-control" data-choices name="id_week" id="id_week" onchange="openQnsLock(this.value);" required="">
                    <option value=""> Choose one</option>';
                    foreach($COURSES_LESSONS as $week):
                        echo'<option value="'.$week['id_week'].'" '.($week['id_week'] == $QUIZ['id_week']?'selected':'').'>'.get_CourseWise($curs['curs_wise']).' '.get_LessonWeeks($week['id_week']).'</option>';
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
                        echo'<option value="'.$key.'" '.($key == $QUIZ['quiz_time']?'selected':'').'>'.$val.'</option>';
                    endforeach;
                    echo'
                </select>
            </div>                  
            <div class="col">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-control" data-choices name="quiz_status" required="">
                    <option value=""> Choose one</option>';
                    foreach(get_status() as $key => $val):
                        echo'<option value="'.$key.'" '.($key == $QUIZ['quiz_status']?'selected':'').'>'.$val.'</option>';
                    endforeach;
                    echo'
                </select>
            </div>
            <div class="col">
                <label class="form-label">Publish <span class="text-danger">*</span></label>
                <select class="form-control" data-choices name="is_publish" required="">
                    <option value=""> Choose one</option>';
                    foreach(get_is_publish() as $key => $val):
                        echo'<option value="'.$key.'" '.($key == $QUIZ['is_publish']?'selected':'').'>'.$val.'</option>';
                    endforeach;
                    echo'
                </select>
            </div>
            <div class="col">
                <label class="form-label">Passing Percentage <span class="text-danger">*</span> <span class="text-info">(1-100)%</span></label>
                <input type="number" min="1" max="100" class="form-control" name="quiz_pass_percentage" id="quiz_pass_percentage" value="'.$QUIZ['quiz_pass_percentage'].'" required="">
            </div>
        </div>';
        $condition = array(
                             'select'       =>  'quiz_qns_id, quiz_qns_level, quiz_qns_type, quiz_qns_question, quiz_qns_option, quiz_qns_marks'
                            ,'where'        =>  array(  
                                                         'quiz_qns_type'    => 3
                                                        ,'id_quiz'          => $QUIZ['quiz_id']
                                                    )
                            ,'return_type'  =>  'all'
        );
        $QUIZ_QUESTIONS = $dblms->getRows(QUIZ_QUESTIONS, $condition);
        echo'
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
                        <select class="form-control" data-choices name="difficulty_level_m" required="" onchange="MultipleChoice(this.value);">
                            <option value=""> Choose one</option>';
                            foreach(get_QnsLevel() as $key => $status):
                                echo'<option value="'.$key.'" '.($key == $QUIZ_QUESTIONS[0]['quiz_qns_level']?'selected':'').'>'.$status.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col" id="no_of_question_m">
                        <label class="form-label">No of question <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="no_of_question_m" required="" onchange="get_question_search_m(this.value);">
                            <option value=""> Choose one</option>';
                            foreach(get_HowManyQns() as $key => $status):
                                echo'<option value="'.$key.'" '.($key == count($QUIZ_QUESTIONS)?'selected':'').'>'.$status.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                </div>
                <div class="row mb-2" id="get_MultipleChoice">';
                    if ($QUIZ_QUESTIONS) {
                        echo'
                        <div class="mt-2">
                            <table class="table table-bordered align-middle">
                                <tr>
                                    <th>Multiple Choice Questions</th>
                                    <th width="100" class="text-center">Option 1</th>
                                    <th width="100" class="text-center">Option 2</th>
                                    <th width="100" class="text-center">Option 3</th>
                                    <th width="100" class="text-center">Option 4</th>
                                    <th width="10" class="text-center">Marks</th>
                                </tr>';
                                $totolMarks = 0;
                                foreach($QUIZ_QUESTIONS AS $key => $val) {            
                                    echo'
                                    <tr>
                                        <td>
                                            <input type="hidden" value="'.$val['quiz_qns_question'].'" name="qns_question[]">
                                            <input type="hidden" value="'.$val['quiz_qns_type'].'" name="qns_type[]">
                                            <input type="hidden" value="'.$val['quiz_qns_level'].'" name="qns_level[]">
                                            Q'.($key+1).': '.html_entity_decode(html_entity_decode(html_entity_decode($val['quiz_qns_question']))).'
                                        </td>';
                                        foreach(json_decode(html_entity_decode($val['quiz_qns_option']),true) as $oKey => $oVal) {
                                            echo'
                                            <td class="text-'.($oVal['option_true'] == 1?'success':'danger').' text-center">'.($oVal['option_true'] == 1?'<i class="mdi mdi-check-bold align-middle"></i>':'').'  '.moduleName($oVal['qns_option']).'</td>';
                                        }
                                        echo'
                                        <td class="text-center">
                                            '.$val['quiz_qns_marks'].' 
                                            <textarea name="qns_options[]" hidden>'.$val['quiz_qns_option'].'</textarea>
                                            <input type="hidden" value="'.$val['quiz_qns_marks'].'" name="qns_marks[]">
                                        </td>
                                    </tr>';
                                    $totolMarks += $val['quiz_qns_marks'];
                                }                
                                echo'
                                <tr>
                                    <th class="text-end" colspan="5">Total Marks</th>
                                    <th width="100" class="text-center" colspan="4">'.$totolMarks.'</th>
                                    <input type="hidden" value="'.$totolMarks.'" name="quiz_totalmarks_m">
                                </tr>
                            </table>
                        </div>';
                    }
                    echo'
                </div>
            </div>
        </div>';
        $condition = array(
                             'select'       =>  'quiz_qns_id, quiz_qns_level, quiz_qns_type, quiz_qns_question, quiz_qns_option, quiz_qns_marks'
                            ,'where'        =>  array(  
                                                         'quiz_qns_type'    => 1
                                                        ,'id_quiz'          => $QUIZ['quiz_id']
                                                    )
                            ,'return_type'  =>  'count'
        );
        $COUNT_QUIZ_QUESTIONS = $dblms->getRows(QUIZ_QUESTIONS, $condition);
        $condition['return_type'] = 'all';
        $QUIZ_QUESTIONS = $dblms->getRows(QUIZ_QUESTIONS, $condition);
        echo'
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
                                echo'<option value="'.$key.'" '.($key == $QUIZ_QUESTIONS[0]['quiz_qns_level']?'selected':'').'>'.$status.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col" id="no_of_question_s">
                        <label class="form-label">No of question</label>
                        <select class="form-control" data-choices name="no_of_question_s" onchange="get_question_search_s(this.value);">
                            <option value=""> Choose one</option>';
                            foreach(get_HowManyQns() as $key => $status):
                                echo'<option value="'.$key.'" '.($key == $COUNT_QUIZ_QUESTIONS ? 'selected' : '').'>'.$status.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                </div>
                <div class="row" id="get_ShortQuestion">';
                    if ($QUIZ_QUESTIONS) {
                        echo'
                        <div class="mt-2">
                            <table class="table table-bordered align-middle">
                                <tr>
                                    <th>Short Questions</th>
                                    <th width="10" class="text-center">Marks</th>
                                </tr>';
                                $totolMarks = 0;
                                foreach($QUIZ_QUESTIONS AS $key => $val) {            
                                    echo'
                                    <tr>
                                        <td>
                                            <input type="hidden" value="'.$val['quiz_qns_question'].'" name="qns_question[]">
                                            <input type="hidden" value="'.$val['quiz_qns_type'].'" name="qns_type[]">
                                            <input type="hidden" value="'.$val['quiz_qns_level'].'" name="qns_level[]">
                                            Q'.($key+1).': '.html_entity_decode(html_entity_decode(html_entity_decode($val['quiz_qns_question']))).'
                                        </td>
                                        <td class="text-center">
                                            '.$val['quiz_qns_marks'].' 
                                            <input type="hidden" value="'.$val['quiz_qns_marks'].'" name="qns_marks[]">
                                        </td>
                                    </tr>';
                                    $totolMarks += $val['quiz_qns_marks'];
                                }               
                                echo'
                                <tr>
                                    <th class="text-end" colspan="1">Total Marks</th>
                                    <th width="100" class="text-center" colspan="4">'.$totolMarks.'</th>
                                    <input type="hidden" value="'.$totolMarks.'" name="quiz_totalmarks_s">
                                </tr>
                            </table>
                        </div>';
                    }
                    echo'
                </div>
            </div>
        </div>
        <div id="TotalMarks"></div>
        <div class="row mb-2">
            <div class="col">
                <label class="form-label">Instruction</label>
                <textarea type="text" id="ckeditor1" class="form-control" name="quiz_instruction">'.html_entity_decode(html_entity_decode($QUIZ['quiz_instruction'])).'</textarea>
            </div>
        </div>
        <hr>
        <div class="hstack gap-2 justify-content-end">
            <a href="'.moduleName().'.php?'.$redirection.'" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Close</a>
            <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(LMS_VIEW).'</button>
        </div>
    </form>

    <script>
        CKEDITOR.replace("ckeditor1");';
        if (empty($QUIZ['id_week'])) {
            echo'
            $("#m_lock").hide();
            $("#s_lock").hide();';
        }   
        echo'
        function openQnsLock(id) {
            if (id != "") {
                $("#m_lock").show();
                $("#s_lock").show();
            } else {
                $("#m_lock").hide();
                $("#s_lock").hide();
            }
        }';
        if (empty($QUIZ['id_week'])) {
            echo'
            $("#no_of_question_m").hide();';
        }
        echo'
        function MultipleChoice (difficulty_level_m) {
            if (difficulty_level_m != "") {
                $("#no_of_question_m").show();
            } else {
                $("#no_of_question_m").hide();
            }        
        }
        function get_question_search_m(no_of_question_m){
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
        }';
        if (empty($QUIZ['id_week'])) {
            echo'
            $("#no_of_question_s").hide();';
        }
        echo'
        function ShortQuestion (difficulty_level_s) {
            if (difficulty_level_s != "") {
                $("#no_of_question_s").show();
            } else {
                $("#no_of_question_s").hide();
            }        
        }
        function get_question_search_s(no_of_question_s){
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
}
?>