<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_mstcat'])) {
    // COURSES
    $condition = array ( 
                             'select'       =>  'mas_id, mas_name'
                            ,'where'        =>  array(
                                                         'id_mstcat'       => cleanvars($_POST['id_mstcat'])
                                                        ,'mas_status'  =>  '1'
                                                        ,'is_deleted'   =>  '0'
                                                    )
                            ,'order_by'     =>  'mas_name'
                            ,'return_type'  =>  'all'
                        );
    $MASTER_TRACK = $dblms->getRows(MASTER_TRACK, $condition);
    echo'
    <script src="assets/js/app.js"></script>
    <label class="form-label">Master Track <span class="text-danger">*</span></label>
    <select class="form-control" data-choices required name="'.(isset($_POST['name'])?$_POST['name'].'"':'id_mas[]" multiple').'>';
    if($MASTER_TRACK){
        echo'<option value="">Choose</option>';
        foreach($MASTER_TRACK as $row) {
            echo'<option value="'.$row['mas_id'].'" '.(isset($_POST['admoff_degree']) && ($row['mas_id'] == $_POST['admoff_degree']) ? 'selected':'').'>'.$row['mas_name'].'</option>';
        }
    }else{
        echo'<option value="">No Record Found</option>';
    }
    echo'</select>';
}
?>