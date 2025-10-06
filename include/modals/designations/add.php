<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
include "../../functions/functions.php";
$dblms = new dblms();

$inputFields                  = array(
    'designation_name'      => array( 'type' => 'text'    , 'title' => 'Name'     , 'required' => '1', 'class' => 'col-md-12' )    
  , 'designation_code'      => array( 'type' => 'text'    , 'title' => 'Code'     , 'required' => '1', 'class' => 'col-md-12' )    
);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-primary p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-add-circle-line align-bottom me-1"></i>Add Designation</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="designations.php" autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Ordering <span class="text-danger">*</span></label>
                        <input type="number" value="'.$_GET['ordering'].'" name="designation_ordering" id="designation_ordering" class="form-control" required="" readonly="">
                    </div>';
                    foreach($inputFields as $name => $field): 
                        echo '
                        <div class="'.$field['class'].'">
                            <div class="mb-2">
                                <label class="form-label">'.ucwords(strtolower($field['title'])).' '.(($field['required'])? '<span class="text-danger">*</span>': '').'</label>
                                <input type="'.$field['type'].'" name="'.$name.'" id="'.$name.'" class="form-control" '.(($field['required'])? 'required': '').' '.(($field['readonly'])? 'readonly=""': '').'>
                            </div>
                        </div>';
                    endforeach; 
                    echo ' 
                </div>
                
                <div class="row"> 
                    <div class="col mb-2">
                        <label class="form-label">Staus <span class="text-danger">*</span></label>
                        <select class="form-control select2-show-search form-select" required name="designation_status" data-placeholder="Select">
                            <option label="Select"></option>';
                                foreach(get_status() as $key => $status):
                                    echo '
                                    <option value="'.$key.'">'.$status.'</option>';
                                endforeach;
                                echo '
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" name="submit_add"><i class="ri-add-circle-line align-bottom me-1"></i>Add Designation</button>
                </div>
            </div>
        </form>
    </div>
</div>';
?>
