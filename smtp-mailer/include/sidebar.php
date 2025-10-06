<?php
$condition = array ( 
                    'select'        =>  'm.mail_id'
                    ,'join'         =>  'INNER JOIN '.MAIL_SEND_DETAIL.' AS md ON m.mail_id = md.id_mail'
                    ,'where' 	    =>  array( 
                                                'm.is_read_by_adm'  => 0,
                                                'm.is_deleted'      => 0
                                        )
                    ,'return_type'  =>  'count' 
                   );
$MAIL_SEND_COUNT = $dblms->getRows(MAIL_SEND .' AS m', $condition);
echo'
<aside class="app-sidebar" id="sidebar">
    <div class="main-sidebar-header">
        <a href="index.html" class="header-logo">
            <img src="'.SERVER_URL.'assets/images/brand-logos/desktop-logo.png" alt="logo" class="desktop-logo">
            <img src="'.SERVER_URL.'assets/images/brand-logos/toggle-logo.png" alt="logo" class="toggle-logo">
            <img src="'.SERVER_URL.'assets/images/brand-logos/desktop-dark.png" alt="logo" class="desktop-dark">
            <img src="'.SERVER_URL.'assets/images/brand-logos/toggle-dark.png" alt="logo" class="toggle-dark">
        </a>
    </div>
    <div class="main-sidebar" id="sidebar-scroll">
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path> </svg>
            </div>
            <ul class="main-menu">
                <li class="slide__category"><span class="category-name">Main</span></li>             
                <li class="slide">
                    <a href="'.SERVER_URL.'mail-list" class="side-menu__item">
                        <span class=" side-menu__icon">
                            <i class="bx bx-desktop"></i>
                        </span> 
                        <span class="side-menu__label">
                            Mail Box
                            '.($MAIL_SEND_COUNT?'
                            <span class="badge bg-warning-transparent ms-2 d-inline-block">'.$MAIL_SEND_COUNT.'</span>
                            <span class="badge bg-danger-transparent ms-2 d-inline-block">New</span>
                            ':'').'
                        </span>
                    </a>
                </li>
            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path> </svg></div>
        </nav>
    </div>
</aside>';