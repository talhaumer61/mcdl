<?php
$notification_check = 0;
// EMPLY DETAIL MISSING
if(!empty($_SESSION['userlogininfo']['EMPLYID'])){
    $notification_check = 2;

    $condition = array ( 
                             'select' 		=>	'edu.id as education, exp.id as experience, lang.id as language_skills, trn.id as professional_skills'
                            ,'join' 		=>	'LEFT JOIN '.EMPLOYEE_EDUCATIONS.' edu ON edu.id_employee = e.emply_id AND edu.status = 1 AND edu.is_deleted = 0
                                                 LEFT JOIN '.EMPLOYEE_EXPERIENCE.' exp ON exp.id_employee = e.emply_id AND exp.status = 1 AND exp.is_deleted = 0
                                                 LEFT JOIN '.EMPLOYEE_LANGUAGE_SKILLS.' lang ON lang.id_employee = e.emply_id AND lang.status = 1 AND lang.is_deleted = 0
                                                 LEFT JOIN '.EMPLOYEE_TRAININGS.' trn ON trn.id_employee = e.emply_id AND trn.status = 1 AND trn.is_deleted = 0' 
                            ,'where' 		=>	array( 
                                                         'e.emply_status'     => '1'
                                                        ,'e.is_deleted'       => '0'
                                                        ,'e.emply_id'         => cleanvars($_SESSION['userlogininfo']['EMPLYID']) 
                                                    ) 
                            ,'group_by'     =>	'e.emply_id'
                            ,'return_type'	=>	'single'
                        ); 
    $rowDetail = $dblms->getRows(EMPLOYEES.' e', $condition);

    $array_missing = array();
    foreach ($rowDetail as $key => $value) {
        if($value == '' && !is_int($key)){
            array_push($array_missing, moduleName($key));
        }
    }
    $missing = implode(", ", $array_missing);
    if($missing != '' && $_SESSION['SHOWNOTIFICATION'] == 1){
        unset($_SESSION['SHOWNOTIFICATION']);    
        echo'
        <script src="assets/js/app.js"></script>        
        <div class="modal fade" id="notificationModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger p-3">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="ri-user-line align-bottom me-1"></i>Complete your profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                    </div>
                    <div class="modal-body">
                        <b>Important details missing:</b>
                        <p style="font-size: 14px; padding: 1rem;" class="m-0">
                            Complete your profile by providing 
                            <i class="text-danger me-1 ms-1">"'.$missing.'"</i> 
                            Go to Profile Setting
                            <i><a href="'.SITE_URL.'profile.php" class="text-danger"> Click Here</a></i>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Skip for Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    }
}

// ORG DETAIL MISSING
if(!empty($_SESSION['userlogininfo']['LOGINORGANIZATIONID'])){
    $notification_check = 3;

    $condition = array ( 
                             'select' 		=>	'o.org_photo as display_photo, o.org_phone as phone_number, o.org_city as city, o.org_address as address, o.cv_file as portfolio_cv, b.id as bank_detail'
                            ,'join'         =>  'LEFT JOIN '.SA_BANK_DETAILS.' b ON b.id_org = o.org_id AND b.status = 1 AND b.is_deleted = 0'
                            ,'where' 		=>	array( 
                                                         'o.org_status'     => '1'
                                                        ,'o.is_deleted'     => '0'
                                                        ,'o.org_id'       => cleanvars($_SESSION['userlogininfo']['LOGINORGANIZATIONID'])
                                                    ) 
                            ,'group_by'     =>	'o.org_id'
                            ,'return_type'	=>	'single'
                        ); 
    $rowDetail = $dblms->getRows(SKILL_AMBASSADOR.' o', $condition);

    $array_missing = array();
    foreach ($rowDetail as $key => $value) {
        if($value == '' && !is_int($key)){
            array_push($array_missing, moduleName($key));
        }
    }
    $missing = implode(", ", $array_missing);
    if($missing != '' && $_SESSION['SHOWNOTIFICATION'] == 1){
        unset($_SESSION['SHOWNOTIFICATION']);    
        echo'
        <script src="assets/js/app.js"></script>        
        <div class="modal fade" id="notificationModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger p-3">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="ri-user-line align-bottom me-1"></i>Complete your profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                    </div>
                    <div class="modal-body">
                        <b>Important details missing:</b>
                        <p style="font-size: 14px; padding: 1rem;" class="m-0">
                            Complete your profile by providing 
                            <i class="text-danger me-1 ms-1">"'.$missing.'"</i> 
                            Go to Profile Setting
                            <i><a href="'.SITE_URL.'profile.php" class="text-danger"> Click Here</a></i>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Skip for Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    }
}

// NOTIFICATION
$today = date('Y-m-d');
$condition = array(
                     'select'       =>  'not_id, not_status, not_title, not_description, start_date, end_date, display_location, display_audience'
                    ,'where'        =>  array(
                                                 'is_deleted'   =>  0
                                                ,'not_status'   =>  1
                                            )
                    ,'search_by'    =>  ' AND start_date <= "'.$today.'" AND end_date >= "'.$today.'" AND FIND_IN_SET(2, display_location) AND FIND_IN_SET('.$notification_check.', display_audience)'
                    ,'order_by'     =>  'not_id DESC'
                    ,'return_type'  =>  'single'
                );
$rowNot = $dblms->getRows(NOTIFICATIONS, $condition, $sql);
if($rowNot && $_SESSION['NOTIFICATION'] == 1){
    unset($_SESSION['NOTIFICATION']);
    echo'
    <script src="assets/js/app.js"></script>        
    <div class="modal fade" id="shownotificationModal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger p-3">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="ri-notification-2-line align-bottom me-1"></i>Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                </div>
                <div class="modal-body">
                    '.html_entity_decode(html_entity_decode($rowNot['not_description'])).'
                </div>
            </div>
        </div>
    </div>';
}
echo'
<script type="text/javascript">
    $(window).on("load", function() {
        // Show the first modal
        $("#notificationModal").modal("show");

        // Wait for a small delay to check if the modal is actually shown
        setTimeout(function() {
            if ($("#notificationModal").hasClass("show")) {
                // If the first modal is open, show the second when it\'s closed
                $("#notificationModal").on("hidden.bs.modal", function() {
                    $("#shownotificationModal").modal("show");
                });
            } else {
                // If the first modal is not open, show the second modal immediately
                $("#shownotificationModal").modal("show");
            }
        }, 500); // Adjust delay if needed
    });
</script>';
?>