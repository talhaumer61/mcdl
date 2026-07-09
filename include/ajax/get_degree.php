<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_faculty'])) {
    // COURSES
    $condition = array ( 
                             'select'       =>  'deg_id, deg_name'
                            ,'where'        =>  array(
                                                         'id_faculty'       => cleanvars($_POST['id_faculty'])
                                                        ,'deg_status'  =>  '1'
                                                        ,'is_deleted'   =>  '0'
                                                    )
                            ,'order_by'     =>  'deg_name'
                            ,'return_type'  =>  'all'
                        );
    $DEGREE = $dblms->getRows(DEGREE, $condition);
    echo'
    <script src="assets/js/app.js"></script>
    <label class="form-label">DEGREE <span class="text-danger">*</span></label>
    <select class="form-control" data-choices required name="'.(isset($_POST['name'])?$_POST['name'].'"':'id_deg[]" multiple').'>';
    if($DEGREE){
        echo'<option value="">Choose</option>';
        foreach($DEGREE as $row) {
            echo'<option value="'.$row['deg_id'].'" '.(isset($_POST['admoff_degree']) && ($row['deg_id'] == $_POST['admoff_degree']) ? 'selected':'').'>'.$row['deg_name'].'</option>';
        }
    }else{
        echo'<option value="">No Record Found</option>';
    }
    echo'</select>';
}
?>