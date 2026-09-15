<?php
echo'
<div class="app-menu navbar-menu">
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index.html" class="logo logo-dark">
            <span class="logo-sm">
                <img src="'.SITE_URL.'assets/images/brand/logo.png" alt="" height="50">
            </span>
            <span class="logo-lg">
                <img src="'.SITE_URL.'assets/images/brand/logo.png" alt="" height="40">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index.html" class="logo logo-light">
            <span class="logo-sm">
                <img src="'.SITE_URL.'assets/images/brand/logo.png" alt="" height="50">
            </span>
            <span class="logo-lg">
                <img src="'.SITE_URL.'assets/images/brand/logo.png" alt="" height="40">
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
                <li class="nav-item">
                    <a class="nav-link menu-link" href="dashboard.php">
                        <i class="bx bxs-dashboard"></i> <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarReferral_control" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarReferral_control">
                        <i class="bx bx-share-alt"></i> <span data-key="t-Referral_control">Referral Control</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarReferral_control">
                        <ul class="nav nav-sm flex-column">';
                            if (date('Y-m-d', strtotime($_SESSION['userlogininfo']['LOGINORGANIZATIONLINKEXPIRYTO'])) >= date('Y-m-d')) {
                                echo'
                                <li class="nav-item">
                                    <a href="referral_link.php" class="nav-link" data-key="t-Referrallink"> Referral Link </a>
                                </li>';
                            }
                            echo'
                            <li class="nav-item">
                                <a href="account_created_link.php" class="nav-link" data-key="t-Accountcreatedlink"> Account Created Link </a>
                            </li>
                            <li class="nav-item">
                                <a href="enrolled_from_link.php" class="nav-link" data-key="t-Enrolledfromlink"> Enrolled From Link </a>
                            </li>
                        </ul>
                    </div>
                </li>';
                if($_SESSION['userlogininfo']['LOGINORGANIZATIONADDMEMBERS'] == 1){
                    echo'
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#skill_ambassador" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="skill_ambassador">
                            <i class="bx bx-share-alt"></i> <span data-key="t-skill_ambassador">Skill Ambassador</span>
                        </a>
                        <div class="collapse menu-dropdown" id="skill_ambassador">
                            <ul class="nav nav-sm flex-column">                            
                                <li class="nav-item">
                                    <a href="skill_ambassador.php?view=add" class="nav-link" data-key="t-skill_ambassador_add"> Add Member</a>
                                </li>
                                <li class="nav-item">
                                    <a href="skill_ambassador.php" class="nav-link" data-key="t-skill_ambassador_list"> Skill Ambassador List </a>
                                </li>
                            </ul>
                        </div>
                    </li>';
                }
                echo'
            </ul>
        </div>
    </div>
</div>';
?>