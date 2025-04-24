<?php
// EDITABLE RECORD
$condition = array ( 
    'select' 	    =>  'qb.qns_id, qb.qns_status, qb.qns_question, qb.qns_file, qb.qns_level, qb.qns_type, qb.qns_marks, qb.id_lesson, GROUP_CONCAT(qbd.option_id) option_id_comma, GROUP_CONCAT(qbd.qns_option) qns_option_comma, GROUP_CONCAT(qbd.option_true) option_true_comma'
    ,'join'         =>  'LEFT JOIN '.QUESTION_BANK_DETAIL.' qbd ON qbd.id_qns = qb.qns_id'   
    ,'where' 	    =>  array(  
                                 'qb.is_deleted'            => 0
                                ,'id_curs'                  => cleanvars(CURS_ID)
                                ,'qb.id_session'            => cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                ,'qb.id_campus'             => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                ,'qb.qns_id'                => cleanvars(LMS_EDIT_ID)
                            )
    ,'group_by'     =>  'qb.qns_id' 
    ,'return_type'  =>  'single' 
); 
$row = $dblms->getRows(QUESTION_BANK.' qb', $condition);

// COURSE LESSONS
$condition = array ( 
                        'select' 	    =>  'lesson_id, lesson_topic'
                        ,'where' 	    =>  array(  
                                                     'is_deleted'           => 0
                                                    ,'id_curs'              => cleanvars(CURS_ID)
                                                    ,'id_session'           => cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                    ,'id_campus'            => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                )
                        ,'order_by'     =>  'lesson_id DESC'
                        ,'return_type'  =>  'all' 
                    ); 
$COURSES_LESSONS  = $dblms->getRows(COURSES_LESSONS, $condition);
echo'
<form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
    <div class="row">
        <div class="col-12" >
            <div class="row">
                <div class="col-md-12 mb-2">
                    <label class="form-label">Question <span class="text-danger">*</span> </label>
                    <textarea class="form-control ckeditor1" id="ckeditor1" name="qns_question" required="">'.html_entity_decode(html_entity_decode($row['qns_question'])).'</textarea>
                </div>
                <div class="col-md-12 mb-2">
                    <label class="form-label">Topic <span class="text-danger">*</span></label>
                    <select class="form-control" data-choices data-choices-removeItem multiple name="id_lesson[]" required="">
                        <option value=""> Choose atleast one</option>';
                        foreach($COURSES_LESSONS as $key => $val):
                            echo'<option value="'.$val['lesson_id'].'" '.(in_array($val['lesson_id'], explode(',',$row['id_lesson'])) ? 'selected' : '').'>'.$val['lesson_topic'].'</option>';
                        endforeach;
                        echo'
                    </select>
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label">Attach File</label>
                    <input type="file" class="form-control" id="fileInput" name="qns_file" accept=".pdf, .xlsx, .xls, .doc, .docx, .ppt, .pptx, .png, .jpg, .jpeg, .rar, .zip">
                    <p id="errorMessage" class="text-danger" style="display: none;">File must be less than 5MB.</p>
                    <span class="text-danger fw-bold" style="font-size: 12px;">(pdf, xlsx, xls, doc, docx, ppt, pptx, png, jpg, jpeg, rar, zip)</span>
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label">Staus <span class="text-danger">*</span></label>
                    <select class="form-control" data-choices name="qns_status" required="">
                        <option value=""> Choose one</option>';
                        foreach(get_status() as $key => $status):
                            echo'<option value="'.$key.'" '.($key == $row['qns_status'] ? 'selected' : '').'>'.$status.'</option>';
                        endforeach;
                        echo'
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label">Difficulty Level <span class="text-danger">*</span></label>
                    <select class="form-control" data-choices name="qns_level" required="">
                        <option value=""> Choose one</option>';
                        foreach(get_QnsLevel() as $key => $val):
                            echo'<option value="'.$key.'" '.($key == $row['qns_level'] ? 'selected' : '').'>'.$val.'</option>';
                        endforeach;
                        echo'
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label">Question Type <span class="text-danger">*</span></label>
                    <select class="form-control" data-choices name="qns_type" required="" onchange="get_QuestionType(this.value);">
                        <option value=""> Choose one</option>';
                        foreach(get_QnsType() as $key => $val):
                            echo'<option value="'.$key.'" '.($key == $row['qns_type'] ? 'selected' : '').'>'.$val.'</option>';
                        endforeach;
                        echo'
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label">Question Marks</label>
                    <input type="number" class="form-control" id="qns_marks" value="'.(!empty($row['qns_marks']) ? $row['qns_marks'] : '0').'" name="qns_marks" readonly="">
                </div>
                <div class="col-md-12 mb-2" id="multipleCh" style="display: none;">
                    <label class="form-label">Multiple Choice <span class="text-danger">*</span></label>
                    <table class="table table-bordered table-nowrap align-middle">
                        <tr>
                            <th class="text-center">Sr.</th>
                            <th class="text-center">Option</th>
                            <th class="text-center">Correct</th>
                            <th class="text-center">Sr.</th>
                            <th class="text-center">Option</th>
                            <th class="text-center">Correct</th>
                        </tr>';
                        if (!empty(LMS_EDIT_ID) && !empty($row['qns_option_comma'])) {
                            $sr = 0;
                            $qns_option_array   = explode(',',$row['qns_option_comma']);
                            $option_true_array  = explode(',',$row['option_true_comma']);
                            echo'
                            <tr>';
                                foreach($qns_option_array as $key => $val):
                                    $sr++;
                                    echo'
                                        <td class="text-center">'.$sr.'</td>
                                        <td class="text-center"><input class="form-control" value="'.$val.'" name="qns_option[]"></td>
                                        <td class="text-center"><input class="form-check-input" type="radio" value="'.($key+1).'" '.($option_true_array[$key] == 1 ? 'checked' : '').' name="is_true"></td>
                                        '.(($key == 1)? '</tr><tr>': '').'';
                                endforeach;
                                echo'
                            </tr>';
                        } else {
                            echo'
                            <tr>
                                <td class="text-center">1</td>
                                <td class="text-center"><input class="form-control" name="qns_option[]"></td>
                                <td class="text-center"><input class="form-check-input" type="radio" value="1" name="is_true"></td>
                                <td class="text-center">2</td>
                                <td class="text-center"><input class="form-control" name="qns_option[]"></td>
                                <td class="text-center"><input class="form-check-input" type="radio" value="2" name="is_true"></td>
                            </tr>
                            <tr>
                                <td class="text-center">3</td>
                                <td class="text-center"><input class="form-control" name="qns_option[]"></td>
                                <td class="text-center"><input class="form-check-input" type="radio" value="3" name="is_true"></td>
                                <td class="text-center">4</td>
                                <td class="text-center"><input class="form-control" name="qns_option[]"></td>
                                <td class="text-center"><input class="form-check-input" type="radio" value="4" name="is_true"></td>
                            </tr>';
                        }
                        echo'
                    </table>  
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="hstack gap-2 justify-content-end">
        <a href="'.moduleName().'.php?'.$redirection.'" class="btn btn-danger btn-sm""><i class="ri-close-circle-line align-bottom me-1"></i>Close</a>
        <button type="submit" class="btn btn-primary btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Question</button>
    </div>
</form>';
?>