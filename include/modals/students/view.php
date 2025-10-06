<?php
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms = new dblms();

$condition = array(
                     'select'       =>  's.std_id, s.std_name, s.std_status, s.std_gender, s.city_name, s.std_address_1, 
                                         s.std_address_2, s.id_skills, s.id_intrests, s.std_about, s.std_dob, a.adm_phone, 
                                         a.adm_photo, a.adm_email, a.adm_username, GROUP_CONCAT(sk.skill_name) as std_intrests'
                    ,'join'         =>  'INNER JOIN '.ADMINS.' a ON a.adm_id = s.std_loginid
                                         LEFT JOIN '.SKILLS.' sk ON FIND_IN_SET(sk.skill_id, s.id_intrests)'
                    ,'where'        =>  array(
                                                 's.is_deleted'     => 0
                                                ,'s.std_id'         => cleanvars($_GET['std_id'])
                                            )
                    ,'return_type'  =>  'single'
);
$row = $dblms->getRows(STUDENTS.' s', $condition, $sql);

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
                         'select'       =>	'ec.secs_id, ec.id_type, ec.secs_status, ec.id_curs, ec.id_mas, ec.id_ad_prg, 
                                             c.curs_name ,m.mas_name ,p.prg_name'
                        ,'join'         =>	'LEFT JOIN '.ADMISSION_PROGRAMS.' ap ON ap.id = ec.id_ad_prg
                                             LEFT JOIN '.MASTER_TRACK.' m ON m.mas_id = ec.id_mas    
                                             LEFT JOIN '.PROGRAMS.' p ON p.prg_id = ap.id_prg
                                             LEFT JOIN '.COURSES.' c ON c.curs_id = ec.id_curs' 
                        ,'where'        =>	array( 
                                                   'ec.id_std'          => cleanvars($row['std_id'])
                                                  ,'ec.is_deleted'      => 0
                                                )
                        ,'return_type'	=>	'all'
                    ); 
$ENROLLED_COURSES = $dblms->getRows(ENROLLED_COURSES.' ec',$condition, $sql);
echo'
<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" id="offcanvasRightLabel">Student Details '.$_GET['std_id'].'</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body p-0 overflow-hidden">
    <div data-simplebar style="height: calc(100vh - 112px);">
        <div class="acitivity-timeline p-4">
            '.get_status($row['std_status']).'
            <div class="card-body text-center p-0">
                <div class="position-relative d-inline-block">
                    <img src="'.$photo.'" alt="" class="avatar-lg rounded-circle img-thumbnail">
                    <span class="contact-active position-absolute rounded-circle bg-success"><span class="visually-hidden"></span>
                </div>
                <h5 class="mt-2 mb-0">'.$row['std_name'].'</h5>
                <span class="fs-12">@'.$row['adm_username'].'</span>
            </div>
            <hr>
            <div class="card-body p-0">
                <div class="table-responsive table-card">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="fw-medium" scope="row">Email</th>
                                <td>'.$row['adm_email'].'</td>
                            </tr>
                            <tr>
                                <th class="fw-medium" scope="row">Date of Birth</th>
                                <td>'.(!empty($row['std_dob']) ? date('d M, Y', strtotime($row['std_dob'])) : '').'</td>
                            </tr>
                            <tr>
                                <th class="fw-medium" scope="row">Gender</th>
                                <td>'.get_gendertypes($row['std_gender']).'</td>
                            </tr>
                            <tr>
                                <th class="fw-medium" scope="row">City</th>
                                <td>'.$row['city_name'].'</td>
                            </tr>
                            <tr>
                                <th class="fw-medium" scope="row">Phone#</th>
                                <td>'.$row['adm_phone'].'</td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <h6>Interests</h6>
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td>';
                                    if(!empty($row['id_intrests'])){
                                        foreach (explode(',', $row['std_intrests']) as $key => $value) {
                                            echo'<span class="badge bg-secondary rounded-pill me-2 mb-2">'.$value.'</span>';
                                        }
                                    }else{
                                        echo'<span class="text-danger">Not Found...!</span>';
                                    }
                                    echo'
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <h6>Permanent Address</h6>
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td>'.(!empty($row['std_address_1']) ? $row['std_address_1'] : '<span class="text-danger">Not Found...!</span>').'</td>
                            </tr>
                        </tbody>
                    </table>
                    <h6>Postal Address</h6>
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td>'.(!empty($row['std_address_2']) ? $row['std_address_2'] : '<span class="text-danger">Not Found...!</span>').'</td>
                            </tr>
                        </tbody>
                    </table>                    
                    <hr>
                    <h6 class="mt-3">Enrollment Detail</h6>
                    <table class="table border mb-0">
                        <tbody>';
                            if($ENROLLED_COURSES){
                                foreach ($ENROLLED_COURSES as $key => $value) {
                                    if($value['id_type'] == 1){
                                        $name = $value['prg_name'];
                                    }elseif($value['id_type'] == 2){
                                        $name = $value['mas_name'];
                                    } elseif($value['id_type'] == 3 || $value['id_type'] == 4){
                                        $name = $value['curs_name'];                           
                                    }
                                    echo'                                
                                    <tr>
                                        <td colspan="2">'.$name.'</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">'.get_enroll_type($value['id_type']).'</td>
                                        <td class="text-center">'.get_payments($value['secs_status']).'</td>
                                    </tr>';
                                }
                            } else {
                                echo '<td class="text-center text-danger">Not Found...!</td>';
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