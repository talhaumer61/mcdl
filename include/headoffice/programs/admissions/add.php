<?php
$condition = array ( 
                        'select'        =>  'prg_id, prg_name, prg_eligibility',
                        'where'         =>  array(
                                                        'prg_status '       =>  1
                                                    ,  'is_deleted'       =>  0
                                                ), 
                        'order_by' 		=>  'prg_id',
                        'return_type'   =>  'all'
                    ); 
$Programs = $dblms->getRows(PROGRAMS, $condition);
$condition = array ( 
                        'select'        =>  'sess_id, sess_name',
                        'where'         =>  array(
                                                    'is_deleted'       =>  0
                                                ), 
                        'order_by' 		=>  'sess_id',
                        'return_type'   =>  'all'
                    ); 
$ACADEMIC_SESSION = $dblms->getRows(ACADEMIC_SESSION, $condition);

// LANGUAGES
$condition = array ( 
                         'select'       =>  'lang_id, lang_name, lang_code'
                        ,'where'        =>  array(
                                                    'lang_status'   =>  '1'
                                                )
                        ,'order_by'     =>  'lang_name'
                        ,'return_type'  =>  'all'
                    ); 
$languages = $dblms->getRows(LANGUAGES, $condition);

$feeFields = array(
     'deposit'                  => array( 'type' => 'number', 'title' => 'Deposit'                , 'required' => '1', 'class' => 'col-md-4' )    
    ,'payment_per_smester'      => array( 'type' => 'number', 'title' => 'Payment Per Smester'    , 'required' => '1', 'class' => 'col-md-4' )    
    ,'total_payments'           => array( 'type' => 'number', 'title' => 'Total Payments'         , 'required' => '1', 'class' => 'col-md-4' )    
    ,'total_package'            => array( 'type' => 'number', 'title' => 'Total Package'          , 'required' => '1', 'class' => 'col-md-4' )    
    ,'examination_fee'          => array( 'type' => 'number', 'title' => 'Examination Fee'        , 'required' => '1', 'class' => 'col-md-4' )    
    ,'portal_fee'               => array( 'type' => 'number', 'title' => 'Portal Fee'             , 'required' => '1', 'class' => 'col-md-4' )    
    ,'library_fee'              => array( 'type' => 'number', 'title' => 'Library Fee'            , 'required' => '1', 'class' => 'col-md-4' )    
    ,'student_card_fee'         => array( 'type' => 'number', 'title' => 'Student Card Fee'       , 'required' => '1', 'class' => 'col-md-4' )    
    ,'library_mag_fee'          => array( 'type' => 'number', 'title' => 'Library Magzine Fee'    , 'required' => '1', 'class' => 'col-md-4' )    
);
$inputFields = array(
     'totalseats'               => array( 'type' => 'number', 'title' => 'Program Seats'          , 'required' => '1', 'class' => 'col-md-4' )    
    ,'classdays'                => array( 'type' => 'number', 'title' => 'Class Days'             , 'required' => '1', 'class' => 'col-md-4' )    
);
$textareaFields = array(
     'detail'                   => array( 'type' => 'ckeditor0', 'title' => 'Detail'                    , 'required' => '1', 'class' => 'col-md-12' )    
    ,'eligibility_criteria'     => array( 'type' => 'ckeditor1', 'title' => 'Eligibility Criteria'      , 'required' => '1', 'class' => 'col-md-6' )    
    ,'prg_for'                  => array( 'type' => 'ckeditor2', 'title' => 'Program For'               , 'required' => '1', 'class' => 'col-md-6' )    
    ,'apply_enroll'             => array( 'type' => 'ckeditor3', 'title' => 'Apply Enroll'              , 'required' => '1', 'class' => 'col-md-6' )    
    ,'cohorts_deadlines'        => array( 'type' => 'ckeditor4', 'title' => 'Cohorts and Deadlines'     , 'required' => '1', 'class' => 'col-md-6' )    
    ,'class_profile'            => array( 'type' => 'ckeditor5', 'title' => 'Class Profile'             , 'required' => '1', 'class' => 'col-md-6' )    
    ,'programme_length'         => array( 'type' => 'ckeditor6', 'title' => 'Programme Length'          , 'required' => '1', 'class' => 'col-md-6' )    
    ,'career_outcomes'          => array( 'type' => 'ckeditor7', 'title' => 'Career Outcomes'           , 'required' => '1', 'class' => 'col-md-6' )    
    ,'alumni_benefits'          => array( 'type' => 'ckeditor9', 'title' => 'Alumni Benefits'           , 'required' => '1', 'class' => 'col-md-6' )
);
echo '
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header bg-primary">
                <h5 class="mb-0 modal-title"><i class="ri-add-circle-line align-bottom me-1"></i>Add Admission Program</h5>
            </div>
            <form enctype="multipart/form-data" autocomplete="off" method="post" accept-charset="utf-8">  
                <div class="card-body create-project-main">
                    <div class="card mb-3">
                        <div class="card-header alert-dark">
                            <h6 class="mb-0"><i class="bx bx-info-circle align-middle fs-18 me-1"></i>Program Information</h6>
                        </div>
                        <div class="card-body border">
                            <div class="row">        
                                <div class="col-md-4">
                                    <div class="mb-2">
                                        <label class="form-label">Program <span class="text-danger">*</span></label>
                                        <select class="form-control" data-choices name="program" required>
                                            <option value="">Choose one</option>';
                                            foreach ($Programs as $key => $val):
                                                echo'<option value="'.$val['prg_id'].'|'.$val['prg_name'].'|'.$val['prg_eligibility'].'">'.$val['prg_name'].'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-2">
                                        <label class="form-label">Admission Year <span class="text-danger">*</span></label>
                                        <select class="form-control" data-choices name="academic_sess" required>
                                            <option value="">Choose one</option>';
                                            foreach ($ACADEMIC_SESSION as $key => $val):
                                                echo'<option value="'.$val['sess_id'].'">'.$val['sess_name'].'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-2">
                                        <label class="form-label">Language <span class="text-danger">*</span></label>
                                        <select class="form-control" data-choices name="id_language" required>
                                            <option value="">Choose one</option>';
                                            foreach ($languages as $lang):
                                                echo'<option value="'.$lang['lang_id'].'">'.$lang['lang_name'].' - '.$lang['lang_code'].'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-2">
                                        <label class="form-label">Entry Test <span class="text-danger">*</span></label>
                                        <select class="form-control" data-choices name="entrytest" required>
                                            <option value="">Choose one</option>';
                                            foreach (get_is_publish() as $key => $val):
                                                echo'<option value="'.$key.'">'.$val.'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>';
                                foreach($inputFields as $name => $field): 
                                    echo '
                                    <div class="'.$field['class'].'">
                                        <div class="mb-2">
                                            <label class="form-label">'.ucwords(strtolower($field['title'])).' '.(($field['required'])? '<span class="text-danger">*</span>': '').'</label>
                                            <input type="'.$field['type'].'" name="'.$name.'" id="'.$name.'" class="form-control" '.(($field['required'])? 'required': '').' placeholder="0">
                                        </div>
                                    </div>';
                                endforeach; 
                                echo ' 
                                <div class="col-md-4">
                                    <div class="mb-2">
                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-control" data-choices name="status" required>
                                            <option value="">Choose one</option>';
                                            $statuses = get_status();
                                            foreach ($statuses as $key => $status):
                                                echo'<option value="'.$key.'">'.$status.'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-2">
                                        <label class="form-label">Study Mode <span class="text-danger">*</span></label>
                                        <select class="form-control" data-choices name="study_mode" required>
                                            <option value="">Choose one</option>';
                                            foreach (get_study_mode() as $key => $val):
                                                echo'<option value="'.$key.'">'.$val.'</option>';
                                            endforeach;
                                            echo'
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Introduction Video (YT Short Code) <span class="text-danger">*</span></label>
                                    <input type="text" name="intro_video" class="form-control" required="">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label mb-2">Study Time <span class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="col">
                                            <div class="mb-2">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" name="morning" id="morning" value="1">
                                                    <label class="form-check-label" for="formCheck6">Morning</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="mb-2">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" name="evening" id="evening" value="1">
                                                    <label class="form-check-label" for="formCheck6">Evening</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="mb-2">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" name="weekend" id="weekend" value="1">
                                                    <label class="form-check-label" for="formCheck6">Weekend</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </div>   
                        </div>   
                    </div>   
                    <div class="card mb-3">
                        <div class="card-header alert-primary">
                            <h6 class="mb-0"><i class="bx bx-briefcase align-middle fs-18 me-1"></i>Fee Information</h6>
                        </div>
                        <div class="card-body border">
                            <div class="row">';
                                foreach($feeFields as $name => $field): 
                                    echo '
                                    <div class="'.$field['class'].'">
                                        <div class="mb-2">
                                            <label class="form-label">'.ucwords(strtolower($field['title'])).' '.(($field['required'])? '<span class="text-danger">*</span>': '').'</label>
                                            <input type="'.$field['type'].'" name="'.$name.'" id="'.$name.'" class="form-control" '.(($field['required'])? 'required': '').' placeholder="0.00">
                                        </div>
                                    </div>';
                                endforeach; 
                                echo '  
                                </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header alert-secondary">
                            <h6 class="mb-0"><i class="bx bx-paperclip align-middle fs-18 me-1"></i>Description\'s</h6>
                        </div>
                        <div class="card-body border">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2">
                                        <label class="form-label">Short Description <span class="text-danger">*</span></label>
                                        <input class="form-control" name="shortdetail" required=""></input>
                                    </div>
                                </div>
                            </div>
                            <div class="row">';
                                foreach($textareaFields as $name => $field): 
                                    echo '
                                    <div class="'.$field['class'].'">
                                        <div class="mb-2">
                                            <label class="form-label">'.$field['title'].' '.(($field['required'])? '<span class="text-danger">*</span>': '').'</label>
                                            <textarea class="form-control" id="'.$field['type'].'" name="'.$name.'" '.(($field['required'])? 'required': '').'></textarea>
                                        </div>
                                    </div>';
                                endforeach;
                                echo '
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <label class="form-label">Keywords</label>
                                    <input type="text" class="form-control" name="metakeyword" id="choices-text-remove-button" data-choices data-choices-limit="10" data-choices-removeItem/>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="metadescription"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <a href="'.moduleName().'.php" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Close</a>
                        <button type="submit" class="btn btn-primary btn-sm" name="submit_add"><i class="ri-add-circle-line align-bottom me-1"></i>Add '.moduleName(false).'</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>';
?>