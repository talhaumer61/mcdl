<?php
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms = new dblms();

$condition = array ( 
                    'select'        =>  '*',
                    'where' 	    =>  [
                                            'is_deleted'  => 0,
                                            'id'          => cleanvars(LMS_EDIT_ID),
                                        ],
                    'return_type'  =>  'single' 
                   ); 
$INTERNS = $dblms->getRows(INTERNS, $condition);

if($INTERNS['gender'] == '2'){
    $photo = SITE_URL.'uploads/images/default_female.jpg';
} else {            
    $photo = SITE_URL.'uploads/images/default_male.jpg';
}
if(!empty($INTERNS['photo']) && file_exists('uploads/images/interns/'.$INTERNS['photo'])){
    $photo = SITE_URL.'uploads/images/interns/'.$INTERNS['photo'];
}
echo '
<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" id="offcanvasRightLabel">Intern Detail</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body p-0 overflow-hidden">
    <div data-simplebar style="height: calc(100vh - 112px);">
        <div class="acitivity-timeline p-4">
            <div class="card-body text-center">
                <div class="position-relative d-inline-block">
                    <img src="'.$photo.'" alt="" class="avatar-lg rounded-circle img-thumbnail">
                    <span class="contact-active position-absolute rounded-circle bg-success"><span class="visually-hidden"></span>
                </div>
                <h5 class="mt-4 mb-1">'.$INTERNS['full_name'].'</h5>
                <p class="text-muted">'.$INTERNS['father_name'].'</p>

                <ul class="list-inline mb-0">
                    <li class="list-inline-item avatar-xs">
                        <a href="tel:'.$INTERNS['phone'].'" class="avatar-title bg-soft-success text-success fs-15 rounded">
                            <i class="ri-phone-line"></i>
                        </a>
                    </li>
                    <li class="list-inline-item avatar-xs">
                        <a href="mailto:'.$INTERNS['email'].'" class="avatar-title bg-soft-danger text-danger fs-15 rounded">
                            <i class="ri-mail-line"></i>
                        </a>
                    </li>
                    <!--
                    <li class="list-inline-item avatar-xs">
                        <a href="javascript:void(0);" class="avatar-title bg-soft-warning text-warning fs-15 rounded">
                            <i class="ri-question-answer-line"></i>
                        </a>
                    </li>
                    -->
                </ul>
            </div>
            <div class="card-body">
                <h6 class="text-muted text-uppercase fw-semibold mb-3">Detail</h6>
                <div class="table-responsive table-card">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="fw-medium" scope="row">Status</td>
                                <td>'.getInternStatus($INTERNS['status']).'</td>
                            </tr>
                            <tr>                            
                                <td class="fw-medium" scope="row">Reference No</td>
                                <td>'.$INTERNS['ref_no'].'</td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">Applied Date</td>
                                <td>'.$INTERNS['applied_date'].'</td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">Selection Date</td>
                                <td>'.$INTERNS['selection_date'].'</td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">Joining Date</td>
                                <td>'.$INTERNS['joining_date'].'</td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">Leaving Date</td>
                                <td>'.$INTERNS['leaving_date'].'</td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">CNIC</td>
                                <td>'.$INTERNS['cnic'].'</td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">Role</td>
                                <td>'.getInternRoles($INTERNS['id_role']).'</td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">Gender</td>
                                <td>'.get_gendertypes($INTERNS['gender']).'</td>
                            </tr>
                            <tr>
                                <td class="fw-medium" scope="row">Highest Qualification</td>
                                <td>'.get_educationtypes($INTERNS['highest_qualification']).'</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <h6 class="text-muted text-uppercase fw-semibold mb-3">Address Detail</h6>
                <p class="text-muted mb-4">'.$INTERNS['address'].'</p>
            </div>
        </div>
    </div>
</div>';
?>