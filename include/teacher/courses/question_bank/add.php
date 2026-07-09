<?php
$lessons    = $coursecls->get_courselessons(CURS_ID);

echo'
<form autocomplete="off" class="form-validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-md-12 mb-2">
                    <label class="form-label">Question <span class="text-danger">*</span> </label>
                    <textarea class="form-control ckeditor1" id="ckeditor1" name="qns_question" required=""></textarea>
                </div>
                <div class="col-md-12 mb-2">
                    <label class="form-label">Topic <span class="text-danger">*</span></label>
                    <select class="form-control" data-choices data-choices-removeItem multiple name="id_lesson[]" required="">
                        <option value=""> Choose one</option>';
                        foreach($lessons as $key => $val):
                            echo'<option value="'.$val['lesson_id'].'" >'.$val['lesson_topic'].'</option>';
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
                            echo'<option value="'.$key.'" >'.$status.'</option>';
                        endforeach;
                        echo'
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label">Difficulty Level <span class="text-danger">*</span></label>
                    <select class="form-control" data-choices name="qns_level" required="">
                        <option value=""> Choose one</option>';
                        foreach(get_QnsLevel() as $key => $val):
                            echo'<option value="'.$key.'" >'.$val.'</option>';
                        endforeach;
                        echo'
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label">Question Type <span class="text-danger">*</span></label>
                    <select class="form-control" data-choices name="qns_type" required="" onchange="get_QuestionType(this.value);">
                        <option value=""> Choose one</option>';
                        foreach(get_QnsType() as $key => $val):
                            echo'<option value="'.$key.'" >'.$val.'</option>';
                        endforeach;
                        echo'
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label">Question Marks</label>
                    <input type="number" class="form-control" id="qns_marks" value="" name="qns_marks" readonly="">
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
                        </tr>
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
                        </tr>
                    </table>  
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="hstack gap-2 justify-content-end">
        <a href="'.moduleName().'.php?'.$redirection.'" class="btn btn-danger btn-sm""><i class="ri-close-circle-line align-bottom me-1"></i>Close</a>
        <button type="submit" class="btn btn-primary btn-sm" name="submit_add"><i class="ri-add-circle-line align-bottom me-1"></i>Add Question</button>
    </div>
</form>';
