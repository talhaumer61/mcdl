<?php
$settingMenu = array(
    'currencies'        => array( 'title' => 'Currencies'       , 'view' => 'currencies'    , 'icon' => 'ri-coins-line'         , 'color' =>  'success')
   ,'regions'           => array( 'title' => 'Regions'          , 'view' => 'regions'       , 'icon' => 'ri-globe-line'         , 'color' =>  'info')
   ,'countries'         => array( 'title' => 'countries'        , 'view' => 'countries'     , 'icon' => 'ri-flag-line'          , 'color' =>  'primary')
   ,'states'            => array( 'title' => 'States'           , 'view' => 'states'        , 'icon' => 'ri-pin-distance-line'  , 'color' =>  'warning')
   ,'substates'         => array( 'title' => 'Sub States'       , 'view' => 'substates'     , 'icon' => 'ri-map-2-line'         , 'color' =>  'danger')
   ,'cities'            => array( 'title' => 'Cities'           , 'view' => 'cities'        , 'icon' => 'ri-road-map-line'      , 'color' =>  'dark')
   ,'Coupons'           => array( 'title' => 'Coupons'          , 'view' => 'coupons'       , 'icon' => 'ri-coupon-line'        , 'color' =>  'success')
   ,'Discounts'         => array( 'title' => 'Discounts'        , 'view' => 'discounts'     , 'icon' => 'ri-coupon-fill'        , 'color' =>  'info')
   ,'blogs'             => array( 'title' => 'Blogs'            , 'view' => 'blogs'         , 'icon' => 'ri-cast-line'          , 'color' =>  'primary')
);
$quickAccess = array(
    'designations'      => array( 'title' => 'Designations'     , 'view' => 'designations'  , 'icon' => 'ri-user-star-line'         , 'color' =>  'success')
   ,'employees'         => array( 'title' => 'Employees'        , 'view' => 'employees'     , 'icon' => 'ri-group-line'             , 'color' =>  'info')
   ,'teacherlogin'      => array( 'title' => 'Teacher Login'    , 'view' => 'teacherlogin'  , 'icon' => 'ri-admin-line'             , 'color' =>  'primary')
   ,'faculties'         => array( 'title' => 'Faculties'        , 'view' => 'faculties'     , 'icon' => 'ri-community-line'         , 'color' =>  'warning')
   ,'departments'       => array( 'title' => 'Departments'      , 'view' => 'departments'   , 'icon' => 'ri-building-2-line'        , 'color' =>  'danger')
   ,'notifications'     => array( 'title' => 'Notifications'    , 'view' => 'notifications' , 'icon' => 'bx bx-bell'                , 'color' =>  'dark')
);
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
                    <button type="button" title="Settings" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ri-settings-3-line fs-22"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg p-0 dropdown-menu-end">
                        <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0 fw-semibold fs-15"><i class="ri-menu-4-fill align-bottom me-1"></i>Settings </h6>
                                </div>
                            </div>
                        </div>
                        <div class="p-2">
                            <div class="row g-0">';
                                foreach ($settingMenu as $key => $value) {
                                    echo'
                                    <div class="col-4">
                                        <a class="dropdown-icon-item" onclick="window.location.href=\''.$value['view'].'.php\';" style="cursor: pointer;">
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
                    <button type="button" title="Quick Access" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-category-alt fs-22"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg p-0 dropdown-menu-end">
                        <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0 fw-semibold fs-15"><i class="ri-menu-4-fill align-bottom me-1"></i>Quick Access </h6>
                                </div>
                            </div>
                        </div>
                        <div class="p-2">
                            <div class="row g-0">';
                                foreach ($quickAccess as $key => $value) {
                                    echo'
                                    <div class="col-4">
                                        <a class="dropdown-icon-item" onclick="window.location.href=\''.$value['view'].'.php\';" style="cursor: pointer;">
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
                ';
                /*
                echo'
                <div class="dropdown topbar-head-dropdown ms-1 header-item">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" id="page-header-cart-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-shopping-bag fs-22"></i>
                        <span class="position-absolute topbar-badge cartitem-badge fs-10 translate-middle badge rounded-pill bg-info">5</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end p-0 dropdown-menu-cart" aria-labelledby="page-header-cart-dropdown">
                        <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0 fs-16 fw-semibold"> My Cart</h6>
                                </div>
                                <div class="col-auto">
                                    <span class="badge badge-soft-warning fs-13"><span class="cartitem-badge">7</span>
                                        items</span>
                                </div>
                            </div>
                        </div>
                        <div data-simplebar style="max-height: 300px;">
                            <div class="p-2">
                                <div class="text-center empty-cart" id="empty-cart">
                                    <div class="avatar-md mx-auto my-3">
                                        <div class="avatar-title bg-soft-info text-info fs-36 rounded-circle">
                                            <i class="bx bx-cart"></i>
                                        </div>
                                    </div>
                                    <h5 class="mb-3">Your Cart is Empty!</h5>
                                    <a href="apps-ecommerce-products.php" class="btn btn-success w-md mb-3">Shop Now</a>
                                </div>
                                <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">
                                    <div class="d-flex align-items-center">
                                        <img src="assets/images/products/img-1.png" class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">
                                        <div class="flex-1">
                                            <h6 class="mt-0 mb-1 fs-14">
                                                <a href="apps-ecommerce-product-details.php" class="text-reset">Branded
                                                    T-Shirts</a>
                                            </h6>
                                            <p class="mb-0 fs-12 text-muted">
                                                Quantity: <span>10 x $32</span>
                                            </p>
                                        </div>
                                        <div class="px-2">
                                            <h5 class="m-0 fw-normal">$<span class="cart-item-price">320</span></h5>
                                        </div>
                                        <div class="ps-2">
                                            <button type="button" class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn"><i class="ri-close-fill fs-16"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">
                                    <div class="d-flex align-items-center">
                                        <img src="assets/images/products/img-2.png" class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">
                                        <div class="flex-1">
                                            <h6 class="mt-0 mb-1 fs-14">
                                                <a href="apps-ecommerce-product-details.php" class="text-reset">Bentwood Chair</a>
                                            </h6>
                                            <p class="mb-0 fs-12 text-muted">
                                                Quantity: <span>5 x $18</span>
                                            </p>
                                        </div>
                                        <div class="px-2">
                                            <h5 class="m-0 fw-normal">$<span class="cart-item-price">89</span></h5>
                                        </div>
                                        <div class="ps-2">
                                            <button type="button" class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn"><i class="ri-close-fill fs-16"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">
                                    <div class="d-flex align-items-center">
                                        <img src="assets/images/products/img-3.png" class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">
                                        <div class="flex-1">
                                            <h6 class="mt-0 mb-1 fs-14">
                                                <a href="apps-ecommerce-product-details.php" class="text-reset">
                                                    Borosil Paper Cup</a>
                                            </h6>
                                            <p class="mb-0 fs-12 text-muted">
                                                Quantity: <span>3 x $250</span>
                                            </p>
                                        </div>
                                        <div class="px-2">
                                            <h5 class="m-0 fw-normal">$<span class="cart-item-price">750</span></h5>
                                        </div>
                                        <div class="ps-2">
                                            <button type="button" class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn"><i class="ri-close-fill fs-16"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">
                                    <div class="d-flex align-items-center">
                                        <img src="assets/images/products/img-6.png" class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">
                                        <div class="flex-1">
                                            <h6 class="mt-0 mb-1 fs-14">
                                                <a href="apps-ecommerce-product-details.php" class="text-reset">Gray
                                                    Styled T-Shirt</a>
                                            </h6>
                                            <p class="mb-0 fs-12 text-muted">
                                                Quantity: <span>1 x $1250</span>
                                            </p>
                                        </div>
                                        <div class="px-2">
                                            <h5 class="m-0 fw-normal">$ <span class="cart-item-price">1250</span></h5>
                                        </div>
                                        <div class="ps-2">
                                            <button type="button" class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn"><i class="ri-close-fill fs-16"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">
                                    <div class="d-flex align-items-center">
                                        <img src="assets/images/products/img-5.png" class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">
                                        <div class="flex-1">
                                            <h6 class="mt-0 mb-1 fs-14">
                                                <a href="apps-ecommerce-product-details.php" class="text-reset">Stillbird Helmet</a>
                                            </h6>
                                            <p class="mb-0 fs-12 text-muted">
                                                Quantity: <span>2 x $495</span>
                                            </p>
                                        </div>
                                        <div class="px-2">
                                            <h5 class="m-0 fw-normal">$<span class="cart-item-price">990</span></h5>
                                        </div>
                                        <div class="ps-2">
                                            <button type="button" class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn"><i class="ri-close-fill fs-16"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 border-bottom-0 border-start-0 border-end-0 border-dashed border" id="checkout-elem">
                            <div class="d-flex justify-content-between align-items-center pb-3">
                                <h5 class="m-0 text-muted">Total:</h5>
                                <div class="px-2">
                                    <h5 class="m-0" id="cart-item-total">$1258.58</h5>
                                </div>
                            </div>

                            <a href="apps-ecommerce-checkout.php" class="btn btn-success text-center w-100">
                                Checkout
                            </a>
                        </div>
                    </div>
                </div>';
                */
                echo'
                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" title="Full Screen" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-toggle="fullscreen">
                        <i class="bx bx-fullscreen fs-22"></i>
                    </button>
                </div>

                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" title="Swap Theme" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode">
                        <i class="bx bx-moon fs-22"></i>
                    </button>
                </div>';
                echo'
                <div class="dropdown topbar-head-dropdown ms-1 header-item">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bxs-message-dots fs-22"></i>
                        <span id="not-btn" class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger">0<span class="visually-hidden">unread messages</span></span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">

                        <div class="dropdown-head bg-primary bg-pattern rounded-top">
                            <div class="p-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0 fs-16 fw-semibold text-white"> Messages </h6>
                                    </div>
                                    <div class="col-auto dropdown-tabs">
                                        <span id="total-unread-badge" class="badge badge-soft-light fs-13">0 New</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-content" id="notificationItemsTabContent">
                            <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab" role="tabpanel">
                                <div data-simplebar style="max-height: 300px;" class="pe-2">
                                    <div id="unread-course-messages"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>';
                echo'
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