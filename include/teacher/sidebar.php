<?php
echo'
<div class="app-menu navbar-menu">
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index.html" class="logo logo-dark">
            <span class="logo-sm">
                <img src="assets/images/brand/logo.png" alt="" height="50">
            </span>
            <span class="logo-lg">
                <img src="assets/images/brand/logo.png" alt="" height="40">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index.html" class="logo logo-light">
            <span class="logo-sm">
                <img src="assets/images/brand/logo.png" alt="" height="50">
            </span>
            <span class="logo-lg">
                <img src="assets/images/brand/logo.png" alt="" height="40">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>
    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item"><a class="nav-link menu-link" href="dashboard.php"><i class="bx bxs-dashboard"></i> <span data-key="t-dashboard">Dashboard</span></a></li>
                <li class="nav-item"><a class="nav-link menu-link" href="profile.php"><i class="ri-user-settings-line"></i> <span data-key="t-profile">Profile</span></a></li>
                <li class="nav-item"><a class="nav-link menu-link" href="notifications.php"><i class="bx bx-bell"></i> <span data-key="t-notifications">Notifications</span></a></li>';
                $condition  =   [ 
                                    'select' 	    => 'rc.ref_id',
                                    'join'          => 'INNER JOIN '.COURSES.' AS c ON FIND_IN_SET(c.curs_id, rc.id_curs) AND c.curs_status = 1 AND c.is_deleted = 0 ',
                                    'where' 	    => [
                                                            'rc.ref_status'     =>  1,
                                                            'rc.is_deleted'     =>  0,
                                                    ],
                                    'search_by'     => ' AND rc.ref_date_time_from < "'.date('Y-m-d G:i:s').'" AND rc.ref_date_time_to > "'.date('Y-m-d G:i:s').'" AND FIND_IN_SET('.$_SESSION['userlogininfo']['LOGINIDA'].', rc.id_user) ',
                                    'order_by'      => ' c.curs_id DESC ',
                                    'return_type'   => 'all',
                                ]; 
                $REFERRAL_CONTROL = $dblms->getRows(REFERRAL_CONTROL.' AS rc', $condition);
                if ($REFERRAL_CONTROL) {
                    echo'
                    <li class="nav-item"><a class="nav-link menu-link" href="course_referral.php"><i class="bx bx-bell"></i> <span data-key="t-course_referral">Course Referral</span></a></li>';
                }
                echo'
            </ul>
        </div>
    </div>
</div>';
?>