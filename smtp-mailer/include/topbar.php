<?php
echo'
<header class="app-header">
    <div class="main-header-container container-fluid">
        <div class="header-content-left">
            <div class="header-element">
                <div class="horizontal-logo">
                    <a href="'.SERVER_URL.'mail-list" class="header-logo">
                        <img src="'.SERVER_URL.'assets/images/brand-logos/desktop-logo.png" alt="logo" class="desktop-logo">
                        <img src="'.SERVER_URL.'assets/images/brand-logos/toggle-logo.png" alt="logo" class="toggle-logo">
                        <img src="'.SERVER_URL.'assets/images/brand-logos/desktop-dark.png" alt="logo" class="desktop-dark">
                        <img src="'.SERVER_URL.'assets/images/brand-logos/toggle-dark.png" alt="logo" class="toggle-dark">
                    </a>
                </div>
            </div>
            <div class="header-element">
                <a aria-label="anchor" href="javascript:void(0);" class="sidemenu-toggle header-link" data-bs-toggle="sidebar">
                    <span class="open-toggle me-2">
                        <i class="bx bx-menu header-link-icon"></i>
                    </span>
                </a>
                <div class="main-header-center  d-none d-lg-block  header-link">
                    <input type="text" class="form-control form-control-lg" id="typehead" placeholder="Search for results..." autocomplete="off">
                    <button type="button"  aria-label="button" class="btn pe-1"><i class="fe fe-search" aria-hidden="true"></i></button>
                    <div id="headersearch" class="header-search">
                        <div class="p-3">
                            <div class="">
                                <p class="fw-semibold text-muted mb-2 fs-13">Recent Searches</p>
                                <div class="ps-2">
                                    <a  href="javascript:void(0)" class="search-tags"><i class="fe fe-search me-2"></i>People<span></span></a>
                                    <a  href="javascript:void(0)" class="search-tags"><i class="fe fe-search me-2"></i>Pages<span></span></a>
                                    <a  href="javascript:void(0)" class="search-tags"><i class="fe fe-search me-2"></i>Articles<span></span></a>
                                </div>
                            </div>
                            <div class="mt-3">
                                <p class="fw-semibold text-muted mb-2 fs-13">Apps and pages</p>
                                <ul class="ps-2">
                                    <li class="p-1 d-flex align-items-center text-muted mb-2 search-app">
                                        <a href="full-calendar.html"><span><i class="bx bx-calendar me-2 fs-14 bg-primary-transparent p-2 rounded-circle"></i>Calendar</span></a>
                                    </li>
                                    <li class="p-1 d-flex align-items-center text-muted mb-2 search-app">
                                        <a href="mail.html"><span><i class="bx bx-envelope me-2 fs-14 bg-primary-transparent p-2 rounded-circle"></i>Mail</span></a>
                                    </li>
                                    <li class="p-1 d-flex align-items-center text-muted mb-2 search-app">
                                        <a href="buttons.html"><span><i class="bx bx-dice-1 me-2 fs-14 bg-primary-transparent p-2 rounded-circle"></i>Buttons</span></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="mt-3">
                                <p class="fw-semibold text-muted mb-2 fs-13">Links</p>
                                <ul class="ps-2">
                                    <li class="p-1 align-items-center text-muted mb-1 search-app"><a href="javascript:void(0)" class="text-primary"><u>http://spruko/spruko.com</u></a></li>
                                    <li class="p-1 align-items-center text-muted mb-1 search-app"><a href="javascript:void(0)" class="text-primary"><u>http://spruko/spruko.com</u></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="py-3 border-top px-0">
                            <div class="text-center">
                                <a href="javascript:void(0)" class="text-primary text-decoration-underline fs-15">View all</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-element header-search d-lg-none d-block ">
                <a aria-label="anchor" href="javascript:void(0);" class="header-link" data-bs-toggle="modal" data-bs-target="#searchModal">
                    <i class="bx bx-search-alt-2 header-link-icon"></i>
                </a>
            </div>
        </div>
        <div class="header-content-right">
            <div class="header-element header-theme-mode">
                <a aria-label="anchor" href="javascript:void(0);" class="header-link layout-setting">
                    <i class="bx bx-sun bx-flip-horizontal header-link-icon ionicon  dark-layout"></i>
                    <i class="bx bx-moon bx-flip-horizontal header-link-icon ionicon light-layout"></i>
                </a>
            </div>     
            <div class="header-element mainuserProfile">
                <a href="javascript:void(0);" class="header-link dropdown-toggle" id="mainHeaderProfile" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <div class="d-sm-flex wd-100p">
                            <div class="avatar avatar-sm"><img alt="avatar" class="rounded-circle" src="assets/images/faces/1.jpg"></div>
                            <div class="ms-2 my-auto d-none d-xl-flex">
                                <h6 class=" font-weight-semibold mb-0 fs-13 user-name d-sm-block d-none">Harry Jones</h6>
                            </div>
                        </div>
                    </div>
                </a>
                <ul class="dropdown-menu  border-0 main-header-dropdown  overflow-hidden header-profile-dropdown" aria-labelledby="mainHeaderProfile">
                    <li><a class="dropdown-item border-bottom" href="profile.html"><i class="fs-13 me-2 bx bx-user"></i>Profile</a></li>
                    <li><a class="dropdown-item border-bottom" href="mail.html"><i class="fs-13 me-2 bx bx-comment"></i>Message</a></li>
                    <li><a class="dropdown-item border-bottom" href="mail-settings.html"><i class="fs-13 me-2 bx bx-cog"></i>Settings</a></li>
                    <li><a class="dropdown-item border-bottom" href="faqs.html"><i class="fs-13 me-2 bx bx-help-circle"></i>Help</a></li>
                    <li><a class="dropdown-item" href="signin-cover.html"><i class="fs-13 me-2 bx bx-arrow-to-right"></i>Log Out</a></li>
                </ul>
            </div>
            <div class="header-element">
                <a aria-label="anchor" href="javascript:void(0);" class="header-link switcher-icon ms-1" data-bs-toggle="offcanvas" data-bs-target="#switcher-canvas">
                    <i class="bx bx-cog bx-spin header-link-icon"></i>
                </a>
            </div>
        </div>
    </div>
</header>';