<?php 
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms = new dblms();

$condition = array(
                     'select'       =>  's.skill_id, s.skill_status,s.skill_ordering, s.skill_name, s.skill_detail'
                    ,'where'        =>  array(
                                                 's.is_deleted'     => 0
                                                ,'skill_id'         => cleanvars($_GET['skill_id'])
                                            )
                    ,'return_type'  =>  'single'
);
$skill = $dblms->getRows(SKILLS . ' s', $condition);

$inputFields            = array(
    'skill_name'        => array( 'type' => 'text'      , 'title' => 'Name'         , 'required' => '1', 'class' => 'col-md-12' )    
);
$textareaFields         = array(
    'skill_detail'      => array( 'type' => 'ckeditor0' , 'title' => 'Detail'       , 'required' => '1', 'class' => 'col-md-12' )    
);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Skill</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="skills.php" autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="skill_id" value="'.$skill['skill_id'].'"/>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Ordering <span class="text-danger">*</span></label>
                        <input type="number" value="'.$skill['skill_ordering'].'" name="skill_ordering" id="skill_ordering" class="form-control" required readonly>
                    </div>';
                    foreach($inputFields as $name => $field): 
                        echo '
                        <div class="'.$field['class'].' mb-2">
                            <label class="form-label">'.ucwords(strtolower($field['title'])).' '.(($field['required'])? '<span class="text-danger">*</span>': '').'</label>
                            <input type="'.$field['type'].'" name="'.$name.'" id="'.$name.'" value="'.$skill[$name].'" class="form-control" '.(($field['required'])? 'required': '').'>
                        </div>';
                    endforeach; 
                    echo ' 
                </div>
                <div class="row">';
                    foreach($textareaFields as $name => $field): 
                        echo '
                        <div class="'.$field['class'].' mb-2">
                            <label class="form-label">'.$field['title'].' '.(($field['required'])? '<span class="text-danger">*</span>': '').'</label>
                            <textarea class="form-control" id="'.$field['type'].'" name="'.$name.'" '.(($field['required'])? 'required': '').'>'.$skill[$name].'</textarea>
                        </div>';
                    endforeach;
                    echo '
                </div>
                <div class="row"> 
                    <div class="col mb-2">
                        <label class="form-label">Staus <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="skill_status" required>
                            <option value=""> Choose one</option>';
                                foreach(get_status() as $key => $status):
                                    echo'<option value="'.$key.'" '.((cleanvars($skill['skill_status']) == $key)? 'selected': '').'>'.$status.'</option>';
                                endforeach;
                                echo'
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Skill</button>
                </div>
            </div>
        </form>
    </div>
</div>';
?>