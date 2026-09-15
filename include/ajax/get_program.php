<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_cat'])) {
    // COURSES
    $condition = array ( 
                             'select'       =>  'ap.id, ap.program , ap.total_package'
                            ,'join'         =>  'INNER JOIN '.PROGRAMS.' p on ap.id_prg = p.prg_id'                                                     
                            ,'where'        =>  array(
                                                         'p.id_cat'         =>  cleanvars($_POST['id_cat'])
                                                        ,'ap.status'        =>  '1'
                                                        ,'ap.is_deleted'    =>  '0'
                                                    )
                            ,'order_by'     =>  'program'
                            ,'return_type'  =>  'all'
                        );
    $ADMISSION_PROGRAMS = $dblms->getRows(ADMISSION_PROGRAMS.' ap', $condition);
    if($ADMISSION_PROGRAMS){
        foreach($ADMISSION_PROGRAMS as $row) {
            echo '<input type="hidden" name="amount-'.$row['id'].'" data-amount="'.$row['total_package'].'">';
        }
    }
    echo'
    <script src="assets/js/app.js"></script>
    <label class="form-label">Program <span class="text-danger">*</span></label>
    <select class="form-control" data-choices onchange="program_amount(this.value)" required name="'.(isset($_POST['name'])?$_POST['name'].'"':'id_deg[]" multiple').'>';
        if($ADMISSION_PROGRAMS){
            echo'<option value="">Choose one</option>';
            foreach($ADMISSION_PROGRAMS as $row) {
                echo'<option value="'.$row['id'].'" '.(isset($_POST['admoff_degree']) && ($row['id'] == $_POST['admoff_degree']) ? 'selected':'').' >'.$row['program'].'</option>';
            }
        }else{
            echo'<option value="">No Record Found</option>';
        }
        echo'
    </select>';
}
?>