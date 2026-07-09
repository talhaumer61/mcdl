<?php
$profileMenu = array(
    'details'              => array( 'title' => 'Details'               , 'view' => 'details'            , 'icon' => 'ri-file-paper-2-line'  ,   'color' =>  'success')
   ,'qualification'        => array( 'title' => 'Qualification'         , 'view' => 'qualification'      , 'icon' => 'ri-book-open-line'     ,   'color' =>  'info')
   ,'experience'           => array( 'title' => 'Experience'            , 'view' => 'experience'         , 'icon' => 'ri-user-follow-line'   ,   'color' =>  'primary')
   ,'language_skills'      => array( 'title' => 'Language Skills'       , 'view' => 'language_skills'    , 'icon' => 'ri-emphasis-cn'        ,   'color' =>  'warning')
   ,'training'             => array( 'title' => 'Training'              , 'view' => 'training'           , 'icon' => 'ri-line-chart-line'    ,   'color' =>  'danger')
   ,'membership'           => array( 'title' => 'Membership'            , 'view' => 'membership'         , 'icon' => 'ri-team-line'          ,   'color' =>  'dark')
   ,'achievements'         => array( 'title' => 'Achievements'          , 'view' => 'achievements'       , 'icon' => 'ri-shield-star-line'   ,   'color' =>  'secondary')
   ,'publications'         => array( 'title' => 'Publications'          , 'view' => 'publications'       , 'icon' => 'ri-newspaper-line'     ,   'color' =>  'success')
   ,'bank_information'     => array( 'title' => 'Bank Information'      , 'view' => 'bank_information'   , 'icon' => 'ri-bank-line'          ,   'color' =>  'info')
);

// NOTIFICATIONS
$today = date('Y-m-d');
$conditions = array ( 
                         'select'       =>	'not_id, not_title, not_description, start_date, end_date, dated, display_location, display_audience'
                        ,'where'        =>	array( 
                                                         'not_status'   => 1
                                                        ,'is_deleted'   => 0
                                                    )
                        ,'search_by'    =>  ' AND start_date <= "'.$today.'" AND end_date >= "'.$today.'" AND FIND_IN_SET(1, display_location) AND FIND_IN_SET(2, display_audience)'
                        ,'order_by'     =>	'dated DESC'
                        ,'return_type'	=>	'count'
                    ); 
$countNot = $dblms->getRows(NOTIFICATIONS, $conditions);
$conditions['return_type'] = 'all';
$NOTIFICATIONS = $dblms->getRows(NOTIFICATIONS, $conditions);

$socialMediaLinks = getSocialMediaLinks();
echo'
<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <div class="navbar-brand-box horizontal-logo">
                    <a href="dashboard.php" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="assets/images/brand/logo.png" alt="" height="50">
                        </span>
                        <span class="logo-lg">
                            <img src="assets/images/brand/logo.png" alt="" height="40">
                        </span>
                    </a>
                    <a href="dashboard.php" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="assets/images/brand/logo.png" alt="" height="50">
                        </span>
                        <span class="logo-lg">
                            <img src="assets/images/brand/logo.png" alt="" height="40">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
            </div>

            <div class="d-flex align-items-center">
                <div class="dropdown topbar-head-dropdown ms-1 header-item">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ri-user-settings-line fs-22"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg p-0 dropdown-menu-end">
                        <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0 fw-semibold fs-15"><i class="ri-menu-4-fill align-bottom me-1"></i>Profile Options </h6>
                                </div>
                            </div>
                        </div>
                        <div class="p-2">
                            <div class="row g-0">';
                                foreach ($profileMenu as $key => $value) {
                                    echo'
                                    <div class="col-4">
                                        <a class="dropdown-icon-item" onclick="window.location.href=\'profile.php?view='.$value['view'].'\';" style="cursor: pointer;">
                                            <i class="'.$value['icon'].' align-middle fs-24 text-'.$value['color'].'"></i>
                                            <span>'.$value['title'].'</span>
                                        </a>
                                    </div>';
                                }
                                echo'
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dropdown topbar-head-dropdown ms-1 header-item">
                    <button type="button" title="Social Media" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ri-share-line fs-22"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg p-0 dropdown-menu-end">
                        <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0 fw-semibold fs-15"><i class="ri-menu-4-fill align-bottom me-1"></i> Social Media</h6>
                                </div>
                            </div>
                        </div>
                        <div class="p-2">
                            <div class="row g-0">';
                                foreach ($socialMediaLinks as $key => $value) {
                                    echo '
                                    <div class="col-4">
                                        <a class="dropdown-icon-item" href="'.$value['url'].'" target="_blank">
                                            <i class="'.$value['icon'].' align-middle fs-24" style="color: '.$value['color'].';"></i>
                                            <span>'.$value['name'].'</span>
                                        </a>
                                    </div>';
                                }
                                echo '
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-toggle="fullscreen">
                        <i class="bx bx-fullscreen fs-22"></i>
                    </button>
                </div>

                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode">
                        <i class="bx bx-moon fs-22"></i>
                    </button>
                </div>';
                echo'
                <div class="dropdown topbar-head-dropdown ms-1 header-item">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-bell fs-22"></i>
                        <span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger">'.(!empty($countNot) ? $countNot : 0).'<span class="visually-hidden">unread messages</span></span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
                        <div class="dropdown-head bg-primary bg-pattern rounded-top">
                            <div class="p-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0 fs-16 fw-semibold text-white"> Notifications </h6>
                                    </div>
                                    <div class="col-auto dropdown-tabs">
                                        <span class="badge badge-soft-light fs-13"> '.(!empty($countNot) ? $countNot : 0).' New</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div data-simplebar style="max-height: 300px;">';
                            if($NOTIFICATIONS){
                                foreach ($NOTIFICATIONS as $keyNot => $valNot) { 
                                    $NotTitleFirst = substr($valNot['not_title'], 0, 1);                      
                                    echo'
                                    <div class="text-reset notification-item d-block dropdown-item position-relative">
                                        <div class="d-flex">
                                            <div class="avatar-xs me-3">
                                                <span class="avatar-title bg-soft-info text-info rounded-circle fs-16">
                                                    '.$NotTitleFirst.'
                                                </span>
                                            </div>
                                            <div class="flex-1">
                                                <a onclick="showAjaxModalZoom(\'include/modals/notifications/view_detail.php?view_id='.$valNot['not_id'].'\');" class="stretched-link cursor-pointer">
                                                    <h6 class="mt-0 mb-0 lh-base">'.$valNot['not_title'].'</h6>
                                                </a>
                                                <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                    <span><i class="mdi mdi-clock-outline"></i> '.timeAgo($valNot['dated']).'</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>';
                                }
                                echo'                                
                                <div class="my-3 text-center">
                                    <a href="notifications.php" class="btn btn-soft-success waves-effect waves-light">View All Notifications <i class="ri-arrow-right-line align-middle"></i></a>
                                </div>';
                            } else {
                                echo'
                                <div class="p-4">
                                    <div class="w-25 w-sm-50 pt-3 mx-auto">
                                        <img src="assets/images/svg/bell.svg" class="img-fluid" alt="user-pic">
                                    </div>
                                    <div class="text-center pb-5 mt-2">
                                        <h6 class="fs-18 fw-semibold lh-base">Hey! You have no notifications </h6>
                                    </div>
                                </div>';
                            }
                            echo'
                        </div>

                        <div class="tab-content" id="notificationItemsTabContent">
                            <div class="tab-pane fade p-4" id="alerts-tab" role="tabpanel" aria-labelledby="alerts-tab">
                                <div class="w-25 w-sm-50 pt-3 mx-auto">
                                    <img src="assets/images/svg/bell.svg" class="img-fluid" alt="user-pic">
                                </div>
                                <div class="text-center pb-5 mt-2">
                                    <h6 class="fs-18 fw-semibold lh-base">Hey! You have no any notifications </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dropdown topbar-head-dropdown ms-1 header-item">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bxs-message-dots fs-22"></i>
                        <span id="teacher-not-btn" class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger">'.(!empty($countNot) ? $countNot : 0).'<span class="visually-hidden">unread messages</span></span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
                        <div class="dropdown-head bg-primary bg-pattern rounded-top">
                            <div class="p-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0 fs-16 fw-semibold text-white"> Messages </h6>
                                    </div>
                                    <div class="col-auto dropdown-tabs">
                                        <span id="teacher-total-unread-badge" class="badge badge-soft-light fs-13"> '.(!empty($countNot) ? $countNot : 0).' New</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div data-simplebar style="max-height: 300px;">
                            <div id="teacher-unread-course-messages"></div>
                        </div>

                        <div class="tab-content" id="notificationItemsTabContent">
                            <div class="tab-pane fade p-4" id="alerts-tab" role="tabpanel" aria-labelledby="alerts-tab">
                                <div class="w-25 w-sm-50 pt-3 mx-auto">
                                    <img src="assets/images/svg/bell.svg" class="img-fluid" alt="user-pic">
                                </div>
                                <div class="text-center pb-5 mt-2">
                                    <h6 class="fs-18 fw-semibold lh-base">Hey! You have no any notifications </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                ';
                echo'
                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user" src="'.$_SESSION['userlogininfo']['LOGINPHOTO'].'" alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">'.$_SESSION['userlogininfo']['LOGINNAME'].'</span>
                                <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">'.get_admtypes($_SESSION['userlogininfo']['LOGINTYPE']).'</span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <h6 class="dropdown-header">Welcome !</h6>
                        <a class="dropdown-item" href="profile.php"><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Profile</span></a>';
                        if ($_SESSION['userlogininfo']['LOGINISTEACHER'] == 3) {
                            echo'<a class="dropdown-item" onclick="switch_to_student();" style="cursor: pointer;"><i class="ri-refresh-line text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Student Dashboard</span></a>';
                        }
                        echo'
                        <!--<a class="dropdown-item" href="auth-lockscreen-basic.php"><i class="mdi mdi-lock text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Lock screen</span></a>-->
                        <a class="dropdown-item" href="index.php?logout"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Logout</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
  function switch_to_student(){
    $.ajax({
        type: "POST",
        url: "include/ajax/switch_to_student.php",
        success: function() {
            window.location.href = "'.WEBSITE_URL.'signin";
        }
    }); 
  }
</script>';
?>