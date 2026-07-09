<?php
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms = new dblms();

$condition = array(
                     'select'       =>  'ch.*, s.std_name, s.std_gender, a.adm_photo, a.adm_phone, a.adm_email'
                    ,'join'         =>  'INNER JOIN '.STUDENTS.' s ON s.std_id = ch.id_std
                                         INNER JOIN '.ADMINS.' a ON a.adm_id = s.std_loginid'
                    ,'where'        =>  array(
                                                 'ch.is_deleted'    => 0
                                                ,'ch.challan_id'    => cleanvars($_GET['challan_id'])
                                            )
                    ,'order_by'     =>  'ch.challan_id ASC'
                    ,'return_type'  =>  'single'
);
$row = $dblms->getRows(CHALLANS.' ch', $condition);

// CHECK ADMIN IMAGE EXIST
if($row['std_gender'] == '2'){
    $photo = SITE_URL.'uploads/images/default_female.jpg';
}else{            
    $photo = SITE_URL.'uploads/images/default_male.jpg';
}
if(!empty($row['adm_photo'])){
    $file_url = SITE_URL.'uploads/images/admin/'.$row['adm_photo'];
    // if (check_file_exists($file_url)) {
        $photo = $file_url;
    // }
}

$condition = array (
                        'select'        =>	'ec.secs_id, ec.id_type, ec.id_curs, ec.id_mas, ec.id_ad_prg ,c.curs_name ,m.mas_name ,p.prg_name'
                        ,'join'         =>	'LEFT JOIN '.COURSES.' c ON c.curs_id = ec.id_curs
                                             LEFT JOIN '.MASTER_TRACK.' m ON m.mas_id = ec.id_mas
                                             LEFT JOIN '.ADMISSION_PROGRAMS.' ap ON ap.id = ec.id_ad_prg
                                             LEFT JOIN '.PROGRAMS.' p ON p.prg_id = ap.id_prg' 
                        ,'where'        =>	array( 
                                                  'ec.id_std'  => cleanvars($row['id_std']) 
                                                ) 
                        ,'search_by'    =>  ' AND ec.secs_id IN ('.$row['id_enroll'].')'
                        ,'return_type'	=>	'all'
                    ); 
$ENROLLED_COURSES = $dblms->getRows(ENROLLED_COURSES.' ec',$condition, $sql);
echo'
<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" id="offcanvasRightLabel">Challan Details</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body p-0 overflow-hidden">
    <div data-simplebar style="height: calc(100vh - 112px);">
        <div class="acitivity-timeline p-4">
            <div class="card-body text-center p-0">
                <div class="position-relative d-inline-block">
                    <img src="'.$photo.'" alt="" class="avatar-lg rounded-circle img-thumbnail">
                    <span class="contact-active position-absolute rounded-circle bg-success"><span class="visually-hidden"></span>
                </div>
                <h5 class="mt-4 mb-1">'.$row['std_name'].'</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive table-card">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="fw-medium" scope="row">Challan No</th>
                                <td>'.$row['challan_no'].'</td>
                            </tr>
                            <tr>
                                <th class="fw-medium" scope="row">Status</th>
                                <td>'.get_payments($row['status']).'</td>
                            </tr>
                            <tr>
                                <th class="fw-medium" scope="row">Issue Date</th>
                                <td>'.$row['issue_date'].'</td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">Due Date</td>
                                <td>'.$row['due_date'].'</td>
                            </tr>
                            <tr>
                                <th class="fw-medium" scope="row">Total Amount</th>
                                <td>'.$row['currency_code'].' '.$row['total_amount'].'</td>
                            </tr>
                            <tr>
                                <th class="fw-medium" scope="row">Paid Amount</th>
                                <td>PKR '.$row['paid_amount'].'</td>
                            </tr>
                            <tr>
                                <th class="fw-medium" scope="row">Paid Date</th>
                                <td>'.$row['paid_date'].'</td>
                            </tr>
                            <tr>
                                <th class="fw-medium" scope="row">Email</th>
                                <td>'.$row['adm_email'].'</td>
                            </tr>
                            <tr>
                                <th class="fw-medium" scope="row">Phone#</th>
                                <td>'.$row['adm_phone'].'</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <h6 class="mt-3">Enrollment Detail</h6>
                    <table class="table border mb-0">
                        <tbody>';
                            foreach ($ENROLLED_COURSES as $key => $value) {
                                if($value['id_type'] == 1){
                                    $name = $value['prg_name'];
                                }elseif($value['id_type'] == 2){
                                    $name = $value['mas_name'];
                                }elseif($value['id_type'] == 3 || $value['id_type'] == 4){
                                    $name = $value['curs_name'];                           
                                }
                                echo'                                
                                <tr>
                                    <td>'.$name.'</td>
                                    <td>'.get_enroll_type($value['id_type']).'</td>
                                </tr>';
                            }
                            echo'
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>';
?>