<?php 
require_once("../../../dbsetting/lms_vars_config.php");
require_once("../../../dbsetting/classdbconection.php");
require_once("../../../functions/functions.php");
$dblms  = new dblms();
require_once("../../../functions/login_func.php");
checkCpanelLMSALogin();
$condition = array ( 
                        'select' 	    =>  'language_name,speaking,listenting,reading,writing'
                        ,'where' 	    =>  array(  
                                                     'is_deleted'           => 0
                                                    ,'id_campus'            => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                    ,'id_employee'          => cleanvars($_SESSION['userlogininfo']['EMPLYID'])
                                                    ,'id'                   => cleanvars($_GET['lng_skill_id'])
                                                )
                        ,'return_type'  =>  'single' 
                    ); 
$EMPLOYEE_LANGUAGE_SKILLS    = $dblms->getRows(EMPLOYEE_LANGUAGE_SKILLS, $condition);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(LMS_VIEW).'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="lng_skill_id" id="lng_skill_id" value="'.cleanvars($_GET['lng_skill_id']).'"> 
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Language <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="language_name" id="language_name" value="'.$EMPLOYEE_LANGUAGE_SKILLS['language_name'].'" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Speaking <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="speaking" required="">
                            <option value=""> Choose one</option>';
                            foreach(get_LanguageLevel() as $key => $val):
                                echo'<option value="'.$key.'" '.(($key == $EMPLOYEE_LANGUAGE_SKILLS['speaking'])? 'selected': '').'>'.$val.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Listenting <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="listenting" required="">
                            <option value=""> Choose one</option>';
                            foreach(get_LanguageLevel() as $key => $val):
                                echo'<option value="'.$key.'" '.(($key == $EMPLOYEE_LANGUAGE_SKILLS['listenting'])? 'selected': '').'>'.$val.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Writing <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="writing" required="">
                            <option value=""> Choose one</option>';
                            foreach(get_LanguageLevel() as $key => $val):
                                echo'<option value="'.$key.'" '.(($key == $EMPLOYEE_LANGUAGE_SKILLS['writing'])? 'selected': '').'>'.$val.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Reading <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="reading" required="">
                            <option value=""> Choose one</option>';
                            foreach(get_LanguageLevel() as $key => $val):
                                echo'<option value="'.$key.'" '.(($key == $EMPLOYEE_LANGUAGE_SKILLS['reading'])? 'selected': '').'>'.$val.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(LMS_VIEW).'</button>
                </div>
            </div>
        </form>
    </div>
</div>';
?>