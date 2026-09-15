<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
include "../../functions/functions.php";
$dblms = new dblms();
include "../../functions/login_func.php";
checkCpanelLMSALogin();

$condition = array ( 
                         'select' 	    =>  'o.*, a.*, po.org_name as parent_name, po.org_reg as parent_reg'
                        ,'join' 	    =>  'INNER JOIN '.ADMINS.' a ON a.adm_id = o.id_loginid
                                             LEFT JOIN '.SKILL_AMBASSADOR.' po ON po.org_id = o.parent_org AND po.is_deleted = 0'
                        ,'where' 	    =>  array(  
                                                    'o.org_id'    => cleanvars($_GET['view_id'])
                                                )
                        ,'return_type'  =>  'single' 
                    ); 
$row = $dblms->getRows(SKILL_AMBASSADOR.' o', $condition);

// CHECK ADMIN IMAGE EXIST           
$photo = SITE_URL.'uploads/images/default_male.jpg';
if(!empty($row['org_photo'])){
    $file_url = SITE_URL.'uploads/images/organization/'.$row['org_photo'];
    if (check_file_exists($file_url)) {
        $photo = $file_url;
    }
}

echo'
<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" id="offcanvasRightLabel">Skill Ambassador Details</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body p-0 overflow-hidden">
    <div data-simplebar style="height: calc(100vh - 112px);">
        <div class="acitivity-timeline p-4">
            '.get_leave($row['org_status']).'
            <div class="card-body text-center p-0">
                <div class="position-relative d-inline-block">
                    <img src="'.$photo.'" alt="" class="avatar-lg rounded-circle img-thumbnail">
                    <span class="contact-active position-absolute rounded-circle bg-success"><span class="visually-hidden"></span>
                </div>
                <h5 class="mt-2 mb-0">'.$row['org_name'].'</h5>
                <span class="fs-12">@'.$row['adm_username'].'</span>
            </div>
            <hr>
            <div class="card-body p-0">
                <div class="table-responsive table-card">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="fw-medium" scope="row">Type</th>
                                <td>'.get_skill_ambassador_type($row['org_type']).'</td>
                            </tr>';
                            if($row['org_type'] == 2){
                                echo'
                                <tr>
                                    <th class="fw-medium" scope="row">Parent Org</th>
                                    <td>'.$row['parent_name'].' ('.$row['parent_reg'].')</td>
                                </tr>';
                            }
                            echo'
                            <tr>
                                <th class="fw-medium" scope="row">Email</th>
                                <td>'.$row['adm_email'].'</td>
                            </tr>
                            <tr>
                                <th class="fw-medium" scope="row">Reg No</th>
                                <td>'.$row['org_reg'].'</td>
                            </tr>
                            <tr>
                                <th class="fw-medium" scope="row">Members Permission</th>
                                <td>'.get_YesNoStatus($row['allow_add_members']).'</td>
                            </tr>
                            <tr>
                                <th class="fw-medium" scope="row">Referral Validation</th>
                                <td>'.date('Y-m-d', strtotime($row['org_link_from'])).' to '.date('Y-m-d', strtotime($row['org_link_to'])).'</td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="fw-medium" scope="row">Phone</th>
                                <td>'.(!empty($row['org_phone']) ? ''.$row['org_phone'].' <a class="copy-message cursor-pointer" title="Copy" onclick="copyToClipboard(\''.$row['org_phone'].'\');"><i class="ri-file-copy-line ms-2 text-muted align-bottom"></i></a>' : '<span class="text-danger">Not Set</span>').'</td>
                            </tr>
                            <tr>
                                <th class="fw-medium" scope="row">Telephone</th>
                                <td>'.(!empty($row['org_telephone']) ? ''.$row['org_telephone'].' <a class="copy-message cursor-pointer" title="Copy" onclick="copyToClipboard(\''.$row['org_telephone'].'\');"><i class="ri-file-copy-line ms-2 text-muted align-bottom"></i></a>' : '<span class="text-danger">Not Set</span>').'</td>
                            </tr>
                            <tr>
                                <th class="fw-medium" scope="row">Whatsapp</th>
                                <td>'.(!empty($row['org_whatsapp']) ? ''.$row['org_whatsapp'].' <a class="copy-message cursor-pointer" title="Copy" onclick="copyToClipboard(\''.$row['org_whatsapp'].'\');"><i class="ri-file-copy-line ms-2 text-muted align-bottom"></i></a>' : '<span class="text-danger">Not Set</span>').'</td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <h6>Referral Link</h6>
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td>'.WEBSITE_URL.'signup/'.$row['org_referral_link'].' <a class="copy-message cursor-pointer" title="Copy" onclick="copyToClipboard(\''.WEBSITE_URL.'signup/'.$row['org_referral_link'].'\');"><i class="ri-file-copy-line ms-2 text-muted align-bottom"></i></a></td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <h6>Portfolio Link</h6>
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td>'.($row['cv_url'] ? '<a href="'.$row['cv_url'].'" target="_blank">'.$row['cv_url'].'</a> <a class="copy-message cursor-pointer" title="Copy" onclick="copyToClipboard(\''.$row['cv_url'].'\');"><i class="ri-file-copy-line ms-2 text-muted align-bottom"></i></a>' : '<span class="text-danger">Not Set</span>').'</td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <h6>Address</h6>
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td>'.(!empty($row['org_address']) ? $row['org_address'] : '<span class="text-danger">Not Found...!</span>').'</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>';