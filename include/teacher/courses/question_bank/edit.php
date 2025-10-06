<?php
// EDITABLE RECORD
$result    = $coursecls->get_coursequestionbank(LMS_EDIT_ID, CURS_ID);

// COURSE LESSONS
$lessons    = $coursecls->get_courselessons(CURS_ID);
echo'
<form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
    <div class="row">
        <div class="col-12" >
            <div class="row">
                <div class="col-md-12 mb-2">
                    <label class="form-label">Question <span class="text-danger">*</span> </label>
                    <textarea class="form-control ckeditor1" id="ckeditor1" name="qns_question" required="">'.html_entity_decode(html_entity_decode($result['qns_question'])).'</textarea>
                </div>
                <div class="col-md-12 mb-2">
                    <label class="form-label">Topic <span class="text-danger">*</span></label>
                    <select class="form-control" data-choices data-choices-removeItem multiple name="id_lesson[]" required="">
                        <option value=""> Choose atleast one</option>';
                        foreach($lessons as $key => $val):
                            echo'<option value="'.$val['lesson_id'].'" '.(in_array($val['lesson_id'], explode(',',$result['id_lesson'])) ? 'selected' : '').'>'.$val['lesson_topic'].'</option>';
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
                            echo'<option value="'.$key.'" '.($key == $result['qns_status'] ? 'selected' : '').'>'.$status.'</option>';
                        endforeach;
                        echo'
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label">Difficulty Level <span class="text-danger">*</span></label>
                    <select class="form-control" data-choices name="qns_level" required="">
                        <option value=""> Choose one</option>';
                        foreach(get_QnsLevel() as $key => $val):
                            echo'<option value="'.$key.'" '.($key == $result['qns_level'] ? 'selected' : '').'>'.$val.'</option>';
                        endforeach;
                        echo'
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label">Question Type <span class="text-danger">*</span></label>
                    <select class="form-control" data-choices name="qns_type" required="" onchange="get_QuestionType(this.value);">
                        <option value=""> Choose one</option>';
                        foreach(get_QnsType() as $key => $val):
                            echo'<option value="'.$key.'" '.($key == $result['qns_type'] ? 'selected' : '').'>'.$val.'</option>';
                        endforeach;
                        echo'
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label">Question Marks</label>
                    <input type="number" class="form-control" id="qns_marks" value="'.(!empty($result['qns_marks']) ? $result['qns_marks'] : '0').'" name="qns_marks" readonly="">
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
                        if (!empty(LMS_EDIT_ID) && !empty($result['qns_option_comma'])) {
                            $sr = 0;
                            $qns_option_array   = explode(',',$result['qns_option_comma']);
                            $option_true_array  = explode(',',$result['option_true_comma']);
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