<?php 
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms = new dblms();

$condition = array(
                     'select'       =>  'd.designation_id, d.designation_status,d.designation_ordering, d.designation_name, d.designation_code'
                    ,'where'        =>  array(
                                                 'd.is_deleted'     => 0
                                                ,'d.designation_id'         => cleanvars($_GET['designation_id'])
                                            )
                    ,'return_type'  =>  'single'
);
$desi = $dblms->getRows(DESIGNATIONS . ' d', $condition);
$inputFields                  = array(
    'designation_name'      => array( 'type' => 'text', 'title' => 'Name'  , 'required' => '1', 'class' => 'col-md-12' )    
  , 'designation_code'      => array( 'type' => 'text', 'title' => 'Code'  , 'required' => '1', 'class' => 'col-md-12' )    
);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Designation</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="designations.php" autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="designation_id" value="'.$desi['designation_id'].'"/>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Ordering <span class="text-danger">*</span></label>
                        <input type="number" value="'.$desi['designation_ordering'].'" name="designation_ordering" id="designation_ordering" class="form-control" required readonly>
                    </div>';
                    foreach($inputFields as $name => $field): 
                        echo '
                        <div class="'.$field['class'].' mb-2">
                            <label class="form-label">'.ucwords(strtolower($field['title'])).' '.(($field['required'])? '<span class="text-danger">*</span>': '').'</label>
                            <input type="'.$field['type'].'" name="'.$name.'" id="'.$name.'" value="'.$desi[$name].'" class="form-control" '.(($field['required'])? 'required': '').'>
                        </div>';
                    endforeach; 
                    echo ' 
                </div>
                <div class="row">';
                    foreach($textareaFields as $name => $field): 
                        echo '
                        <div class="'.$field['class'].' mb-2">
                            <label class="form-label">'.$field['title'].' '.(($field['required'])? '<span class="text-danger">*</span>': '').'</label>
                            <textarea class="form-control" id="'.$field['type'].'" name="'.$name.'" '.(($field['required'])? 'required': '').'>'.$desi[$name].'</textarea>
                        </div>';
                    endforeach;
                    echo '
                </div>
                <div class="row"> 
                    <div class="col mb-2">
                        <label class="form-label">Staus <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="designation_status" required>
                            <option value=""> Choose one</option>';
                                foreach(get_status() as $key => $status):
                                    echo'<option value="'.$key.'" '.((cleanvars($desi['designation_status']) == $key)? 'selected': '').'>'.$status.'</option>';
                                endforeach;
                                echo'
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Designation</button>
                </div>
            </div>
        </form>
    </div>
</div>';
?>