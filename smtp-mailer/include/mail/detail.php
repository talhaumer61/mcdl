<?php
$values = array ( 
                    'is_read_by_adm'        => 1,
                    'date_modify'		=>	date('Y-m-d G:i:s'),
                    'ip_modify'			=>	IP,
);
$MAIL_SEND_READ = $dblms->Update(MAIL_SEND, $values, ' mail_id = '.cleanvars(get_dataHashingOnlyExp(ZONE, false)).'');
if ($MAIL_SEND_READ) {
    include "composer.php";
    $condition = array ( 
                         'select'       =>  'm.mail_id, m.mail_subject, m.mail_body, m.date_added'
                        ,'where' 	    =>  array( 
                                                    'm.is_deleted'  => 0,
                                                    'm.mail_id'     => cleanvars(get_dataHashingOnlyExp(ZONE, false))
                                            )
                        ,'return_type'  =>  'single' 
    );
    $MAIL_SEND = $dblms->getRows(MAIL_SEND .' AS m', $condition);
    $condition = array ( 
                         'select'       =>  'md.mail_sender, md.mail_reciver, md.mail_sender_name, md.mail_reciver_name'
                        ,'where' 	    =>  array( 
                                                    'md.id_mail'    => $MAIL_SEND['mail_id']
                                            )
                        ,'return_type'  =>  'all' 
    );
    $MAIL_SEND_DETAIL = $dblms->getRows(MAIL_SEND_DETAIL .' AS md', $condition);
    echo'
    <div class="mails-information card custom-card">
        <div class="mail-info-header d-xxl-flex align-items-center">
            <div class="mail-option-read d-md-flex  d-block">
                <a href="'.SERVER_URL.'mail-list" class=" vertical-phone" data-bs-toggle="tooltip" title="" data-bs-original-title="Back to inbox">
                    <i class="bx bx-left-arrow-alt"></i>
                </a>
                <a href="javascript:void(0);" class=" vertical-phone" data-bs-toggle="tooltip" title="" data-bs-original-title="Archive">
                    <i class="bx bx-download"></i>
                </a>
                <a href="javascript:void(0);" class=" vertical-phone" data-bs-toggle="tooltip" title="" data-bs-original-title="Report spam">
                    <i class="bx bx-error-alt"></i>
                </a>
                <a href="javascript:void(0);" class=" vertical-phone" data-bs-toggle="tooltip" title="" data-bs-original-title="delete">
                    <i class="bx bx-trash-alt"></i>
                </a>
                <a href="javascript:void(0);" class=" vertical-phone" data-bs-toggle="tooltip" title="" data-bs-original-title="mark as unread">
                    <i class="bx bx-envelope"></i>
                </a>
                <a href="javascript:void(0);" class=" vertical-phone d-none d-xl-block" data-bs-toggle="dropdown" title="Snooze">
                    <i class="bx bx-time-five"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item  fs--14 font-weight-semibold  border-bottom" href="javascript:void(0);">Snooze Until..</a> </li>
                    <li><a class="dropdown-item  fs--14" href="javascript:void(0);">Today</a> </li>
                    <li><a class="dropdown-item  fs--14" href="javascript:void(0);">Tommaro</a> </li>
                    <li><a class="dropdown-item  fs--14" href="javascript:void(0);">This weekend</a> </li>
                    <li><a class="dropdown-item  fs--14" href="javascript:void(0);">Last weekend</a> </li>
                </ul>
                <a href="javascript:void(0);" class=" vertical-phone  profile-user d-none d-xl-block " data-bs-toggle="dropdown" title="move to">
                    <i class="bx bx-folder-open"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item  fs--14 font-weight-semibold  border-bottom" href="javascript:void(0);">Move TO</a></li>
                    <li><a class="dropdown-item  fs--14" href="javascript:void(0);"><i class="fa fa-inbox me-2"></i>Inbox</a></li>
                    <li><a class="dropdown-item  fs--14" href="javascript:void(0);"><i class="far fa-paper-plane me-2"></i> Sent</a></li>
                    <li><a class="dropdown-item  fs--14" href="javascript:void(0);"><i class="far fa-trash-alt  me-2"></i>Trash</a></li>
                    <li><a class="dropdown-item  fs--14" href="javascript:void(0);"><i class="far fa-file me-2"></i>Drafts</a></li>
                    <li><a class="dropdown-item  fs--14" href="javascript:void(0);"><i class="fas fa-download me-2"></i>  Archive</a></li>
                </ul>
                <a href="javascript:void(0);" class=" vertical-phone  profile-user d-none d-xl-block " data-bs-toggle="dropdown" title="copy to">
                    <i class="bx bx-copy"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item  fs--14 font-weight-semibold  border-bottom" href="javascript:void(0);">Copy TO</a></li>
                    <li><a class="dropdown-item  fs--14" href="javascript:void(0);"><i class="fa fa-inbox me-2"></i>Inbox</a></li>
                    <li><a class="dropdown-item  fs--14" href="javascript:void(0);"><i class="far fa-paper-plane me-2"></i> Sent</a></li>
                    <li><a class="dropdown-item  fs--14" href="javascript:void(0);"><i class="far fa-trash-alt  me-2"></i>Trash</a></li>
                    <li><a class="dropdown-item  fs--14" href="javascript:void(0);"><i class="far fa-file me-2"></i>Drafts</a></li>
                    <li><a class="dropdown-item  fs--14" href="javascript:void(0);"><i class="fas fa-download me-2"></i>  Archive</a></li>
                </ul>
                <a href="javascript:void(0);" class=" vertical-phone  profile-user d-none d-lg-block " data-bs-toggle="dropdown" title="Label as">
                    <i class="bx bx-label"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item fs--14 font-weight-semibold  border-bottom" href="javascript:void(0);">Label as</a></li>
                    <li class="dropdown-item fs--14"><label class="ckbox"><input class="form-check-input" type="checkbox"><span class="ms-2">Main</span></label></li>
                    <li class="dropdown-item fs--14"><label class="ckbox"><input class="form-check-input" type="checkbox"><span class="ms-2">Home</span></label></li>
                    <li class="dropdown-item fs--14"><label class="ckbox"><input class="form-check-input" type="checkbox"><span class="ms-2">work</span></label></li>
                    <li class="dropdown-item fs--14"><label class="ckbox"><input class="form-check-input" type="checkbox"><span class="ms-2">Friends</span></label></li>
                </ul>
                <a href="javascript:void(0);" class=" vertical-phone" data-bs-toggle="dropdown" title="more">
                    <i class="bx bx-dots-vertical-rounded"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item  fs--14" href="javascript:void(0);"> Mark As Important</a></li>
                    <li><a class="dropdown-item  fs--14" href="javascript:void(0);"> Unread</a></li>
                    <li><a class="dropdown-item  fs--14" href="javascript:void(0);"> Mark as Read</a></li>
                    <li><a class="dropdown-item  fs--14" href="javascript:void(0);">Add Star</a></li>
                    <li><a class="dropdown-item  fs--14" href="javascript:void(0);"> delete</a></li>
                    <li><a class="dropdown-item  fs--14" href="javascript:void(0);"> None</a></li>
                </ul>
            </div>
            <div class="unstyled inbox-pagination ms-auto mb-0 ps-3">
                <span>
                    <a href="javascript:void(0);" class="text-muted d-inline-flex">'.get_TimeAgo($MAIL_SEND['date_added']).'</a>
                </span>
            </div>
        </div>
        <div class="mail-info-body p-4" id="mail-info-body">
            <div class="d-xl-flex d-block align-items-center justify-content-between mb-4">
                <div><p class="fs-20 fw-semibold mb-0">'.$MAIL_SEND['mail_subject'].'</p></div>
                <div class="float-xl-end"><span class="me-2 fs-12 text-muted">'.date('M-d-Y,G:iA', strtotime($MAIL_SEND['date_added'])).'</span></div>
            </div>
            <div class="d-flex align-items-center">';
                foreach ($MAIL_SEND_DETAIL as $key => $value) {
                    echo'
                    <div class="me-1">
                        <span class="avatar avatar-md online me-2 avatar-rounded mail-msg-avatar">
                            '.get_FirstToCharOfName($value['mail_reciver_name']).'
                        </span>
                    </div>    
                    <div class="flex-fill">
                        <h6 class="mb-0 fw-semibold">'.moduleName($value['mail_reciver_name']).'</h6>
                        <span class="text-muted fs-12">'.moduleName($value['mail_reciver']).'</span>
                    </div>
                    '.($key%5 == 4?'</div><div class="d-flex align-items-center">':'').'';
                }
                echo'
            </div>
            <div class="main-mail-content my-4">
                <p class="fs-14 fw-semibold mb-2">
                    '.html_entity_decode(html_entity_decode($MAIL_SEND['mail_body'])).'
                </p>
            </div>
        </div>
        <div class="mail-info-footer d-sm-flex align-items-center justify-content-between">
            <div class="mb-3 mb-sm-0">
                <button class="btn btn-icon btn-light m-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Print">
                    <i class="ri-printer-line"></i>
                </button>
                <button class="btn btn-icon btn-light  m-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Reload">
                    <i class="ri-refresh-line"></i>
                </button>
            </div>
        </div>
    </div>';
    include "recepients.php";
}