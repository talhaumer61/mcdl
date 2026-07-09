<?php
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
                <div class="ms-1 header-item">
                    <a href="reports.php" title="Reports" class="btn btn-secondary">
                        <i class="bx bx-bar-chart fs-20 align-middle"></i> Reports
                    </a>
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
                    <button type="button" title="Full Screen" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-toggle="fullscreen">
                        <i class="bx bx-fullscreen fs-22"></i>
                    </button>
                </div>

                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" title="Swap Theme" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode">
                        <i class="bx bx-moon fs-22"></i>
                    </button>
                </div>
                
                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" title="Profile Options" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                        <a class="dropdown-item" href="profile.php"><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Profile</span></a>
                        <!--<a class="dropdown-item" href="auth-lockscreen-basic.php"><i class="mdi mdi-lock text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Lock screen</span></a>-->
                        <a class="dropdown-item" href="index.php?logout"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Logout</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>';
?>