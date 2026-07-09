<?php
include "composer.php";
$search_word    = '';
$search_by      = '';
$filters        = 'search';
if (!empty($_GET['search_word'])) {
    $search_word     = cleanvars($_GET['search_word']);
    $search_by      .= 'AND (m.mail_subject LIKE "%'.$search_word.'%" OR md.mail_sender LIKE "%'.$search_word.'%" OR md.mail_reciver LIKE "%'.$search_word.'%")';
    $filters        .= '&search_word='.$search_word.'';
}

$condition = array ( 
                    'select'        =>  'm.mail_id, m.mail_slug, m.mail_status, m.is_read_by_adm, m.is_sent, m.mail_subject, m.mail_body, m.date_added, md.mail_sender, md.mail_reciver'
                    ,'join'         =>  'INNER JOIN '.MAIL_SEND_DETAIL.' AS md ON m.mail_id = md.id_mail'
                    ,'where' 	    =>  array( 
                                                'm.is_deleted'    => 0
                                            )
                    ,'search_by'    =>  ''.$search_by.''
                    ,'group_by'     =>  ' m.mail_id '
                    ,'order_by'     =>  'm.mail_id DESC'
                    ,'return_type'  =>  'count' 
                   );
$count = $dblms->getRows(MAIL_SEND .' AS m', $condition);

if ($page == 0 || empty($page)) { $page = 1; }
$prev       = $page - 1;
$next       = $page + 1;
$lastpage   = ceil($count / $Limit);
$lpm1       = $lastpage - 1;

$condition['order_by'] = "m.mail_id DESC LIMIT ".($page - 1) * $Limit.",$Limit";
$condition['return_type'] = 'all';

$rowsList = $dblms->getRows(MAIL_SEND.' AS m', $condition, $sql);
echo'
<div class="total-mails card custom-card">
    <div class="p-3 d-flex align-items-center border-bottom">
        <div class="me-3">
            <input class="form-check-input" type="checkbox" id="checkAll" value="" aria-label="...">
        </div>
        <a href="'.SERVER_URL.'mail-list" class="  fs-14" data-bs-toggle="tooltip" title="" data-bs-original-title="refresh">
            <i class="fe fe-rotate-cw text-muted"></i>
        </a>
        <div class="dropdown ms-3">
            <a href="javascript:void(0);" class=" text-muted border-0 fs-14" data-bs-toggle="dropdown" aria-expanded="true">
                <i class="fe fe-more-vertical"></i>
            </a>
            <ul class="dropdown-menu" role="menu" data-popper-placement="bottom-start">
                <li><a class="dropdown-item" href="javascript:void(0);">Read</a></li>
                <li><a class="dropdown-item" href="javascript:void(0);">Unread</a></li>
                <li><a class="dropdown-item" href="javascript:void(0);">Mark As Read</a></li>
                <li><a class="dropdown-item" href="javascript:void(0);">Spam</a></li>
                <li><a class="dropdown-item" href="javascript:void(0);">Delete</a></li>
            </ul>
        </div>
    </div>';
    if ($rowsList) {
        echo'
        <div class="mail-messages" id="mail-messages">
            <ul class="list-unstyled mb-0">';
                $srno = ($page == 1 ? 0 : ($page - 1) * $Limit);
                foreach ($rowsList as $row) {
                    $srno++;
                    echo '
                    <li class="'.($row['is_read_by_adm']?'':'active').'">
                        <div class="d-flex align-items-top">
                            <div class="me-3 mt-1">
                                <input class="form-check-input checkbox" type="checkbox" value="" aria-label="...">
                            </div>
                            <div class="flex-fill ms-2 subject-container">
                                <a href="'.SERVER_URL.'mail-detail/'.get_dataHashingOnlyExp($row['mail_id'], true).'" class="stretched-link"></a>
                                <p class="mb-0  main-mail-subject">
                                    <span class="d-block mb-0 fw-semibold fs-14">'.$row['mail_subject'].'</span>
                                        <span class="fs-12 text-muted text-truncate"><span class="badge bg-danger rounded-1">mail</span>
                                        '.html_entity_decode(html_entity_decode(strip_tags($row['mail_body']))).'
                                    </span>
                                </p>
                            </div>
                            <div class="icons-mail">
                                <div class=" mail-date float-end text-muted fw-normal fs-11">
                                    <span class="me-1 d-inline-block">
                                        <i class="ri-attachment-2 align-middle fs-12"></i>
                                    </span>
                                    '.get_TimeAgo($row['date_added']).'
                                </div>
                                <div class="mail-hover-icons d-xl-block d-none">
                                    <div class="d-flex justify-content-end align-items-center">
                                        <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="mark as read">
                                            <i class="fe fe-mail"></i>
                                        </a>
                                        <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="delete">
                                            <i class="fe fe-trash"></i>
                                        </a>
                                        <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="Snooze">
                                            <i class="fe fe-clock"></i>
                                        </a>
                                        <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="archive">
                                            <i class="fe fe-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>';
                }
                echo'
            </ul>
        </div>';
        include_once('include/pagination.php');
    } else {
        echo'
        <div class="text-center text-danger mt-5">
            <h5 class="mt-2">Sorry! No Record Found</h5>
        </div>';
    }
    echo'
</div>';
include "recepients.php";



// <li class="active">
//     <div class="d-flex align-items-top">
//         <div class="me-3 mt-1">
//             <input class="form-check-input" type="checkbox" value="" aria-label="...">
//         </div>
//         <div class="me-2 lh-1">
//             <a href="javascript:void(0);" class="main-mail-star  d-inline-block">
//                 <i class="bx bx-star"></i>
//             </a>
//         </div>
//         <div class="me-1 lh-1">
//             <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" class="main-mail-star d-inline-block" data-bs-original-title="mark as Important">
//                 <i class="bx bx-label"></i>
//             </a>
//         </div>
//         <div class="flex-fill ms-2 subject-container">
//             <a href="mail-chat.html" class="stretched-link"></a>
//             <p class="mb-0  main-mail-subject">
//                 <span class="d-block mb-0 fw-semibold fs-14">New Project details</span>
//                 <span class="fs-12 text-muted text-truncate">
//                     <span class="badge bg-danger rounded-1">mail</span>
//                     &#128525;Lorem ipsum dolor sit amet consectetur adipisicing elit- viva mus elemen tum semper nisi enean vulputat enean commodo li gula eget dolor&#128077; cum socia eget dolor  gula eget dolor
//                 </span>
//             </p>
//         </div>
//         <div class="icons-mail">
//             <div class=" mail-date float-end text-muted fw-normal fs-11"><span class="me-1 d-inline-block"><i class="ri-attachment-2 align-middle fs-12"></i></span>1:32PM</div>
//             <div class="mail-hover-icons d-xl-block d-none">
//                 <div class="d-flex justify-content-end align-items-center">
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="mark as read">
//                         <i class="fe fe-mail"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="delete">
//                         <i class="fe fe-trash"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="Snooze">
//                         <i class="fe fe-clock"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="archive">
//                         <i class="fe fe-download"></i>
//                     </a>
//                 </div>
//             </div>
//         </div>
//     </div>
// </li>
// <li>
//     <div class="d-flex align-items-top">
//         <div class="me-3 mt-1">
//             <input class="form-check-input" type="checkbox" value="" aria-label="...">
//         </div>
//         <div class="me-2 lh-1">
//             <a href="javascript:void(0);" class="main-mail-star  d-inline-block">
//                 <i class="bx bx-star"></i>
//             </a>
//         </div>
//         <div class="me-1 lh-1">
//             <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" class="main-mail-star d-inline-block" data-bs-original-title="mark as Important">
//                 <i class="bx bx-label"></i>
//             </a>
//         </div>
//         <div class="flex-fill ms-2 subject-container">
//             <a href="mail-chat.html" class="stretched-link"></a>
//             <p class="mb-0  main-mail-subject">
//                 <span class="d-block mb-0 fw-semibold fs-14">Most Probable date of project completion</span>
//                 <span class="fs-12 text-muted text-truncate">
//                     Lorem ipsum dolor sit amet consectetur adipisicing elit- viva mus elemen tum semper nisi enean vulputat enean commodo li gula eget dolor cum socia eget dolor  gula eget dolor
//                 </span>
//             </p>
//         </div>
//         <div class="icons-mail">
//             <div class=" mail-date float-end text-muted fw-normal fs-11"><span class="me-1 d-inline-block"><i class="ri-attachment-2 align-middle fs-12"></i></span>1:32PM</div>
//             <div class="mail-hover-icons d-xl-block d-none">
//                 <div class="d-flex justify-content-end align-items-center">
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="mark as read">
//                         <i class="fe fe-mail"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="delete">
//                         <i class="fe fe-trash"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="Snooze">
//                         <i class="fe fe-clock"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="archive">
//                         <i class="fe fe-download"></i>
//                     </a>
//                 </div>
//             </div>
//         </div>
//     </div>
// </li>
// <li class="active">
//     <div class="d-flex align-items-top">
//         <div class="me-3 mt-1">
//             <input class="form-check-input" type="checkbox" value="" aria-label="...">
//         </div>
//         <div class="me-2 lh-1">
//             <a href="javascript:void(0);" class="main-mail-star  d-inline-block">
//                 <i class="bx bx-star"></i>
//             </a>
//         </div>
//         <div class="me-1 lh-1">
//             <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" class="main-mail-star d-inline-block" data-bs-original-title="mark as Important">
//                 <i class="bx bx-label"></i>
//             </a>
//         </div>
//         <div class="flex-fill ms-2 subject-container">
//             <a href="mail-chat.html" class="stretched-link"></a>
//             <p class="mb-0  main-mail-subject">
//                 <span class="d-block mb-0 fw-semibold fs-14">Personal Mail</span>
//                 <span class="fs-12 text-muted text-truncate">
//                     Lorem ipsum dolor sit amet consectetur adipisicing elit- viva mus elemen tum semper  nisi enean vulputat enean commodo &#128522;&#128525;li gula eget dolor cum socia eget dolor  gula eget dolor
//                 </span>
//             </p>
//         </div>
//         <div class="icons-mail">
//             <div class=" mail-date float-end text-muted fw-normal fs-11"><span class="me-1 d-inline-block"><i class="ri-attachment-2 align-middle fs-12"></i></span>1:32PM</div>
//             <div class="mail-hover-icons d-xl-block d-none">
//                 <div class="d-flex justify-content-end align-items-center">
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="mark as read">
//                         <i class="fe fe-mail"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="delete">
//                         <i class="fe fe-trash"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="Snooze">
//                         <i class="fe fe-clock"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="archive">
//                         <i class="fe fe-download"></i>
//                     </a>
//                 </div>
//             </div>
//         </div>
//     </div>
// </li>
// <li class="active">
//     <div class="d-flex align-items-top">
//         <div class="me-3 mt-1">
//             <input class="form-check-input" type="checkbox" value="" aria-label="...">
//         </div>
//         <div class="me-2 lh-1">
//             <a href="javascript:void(0);" class="main-mail-star  d-inline-block">
//                 <i class="bx bx-star"></i>
//             </a>
//         </div>
//         <div class="me-1 lh-1">
//             <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" class="main-mail-star d-inline-block" data-bs-original-title="mark as Important">
//                 <i class="bx bx-label"></i>
//             </a>
//         </div>
//         <div class="flex-fill ms-2 subject-container">
//             <a href="mail-chat.html" class="stretched-link"></a>
//             <p class="mb-0  main-mail-subject">
//                 <span class="d-block mb-0 fw-semibold fs-14">Applying for bank loan?</span>
//                 <span class="fs-12 text-muted text-truncate">
//                     <span class="badge bg-success rounded-1">Friends</span>
//                     Lorem ipsum dolor sit amet consectetur adipisicing elit- viva mus elemen tum semper nisi enean vulputat enean commodo li gula eget dolor cum socia eget dolor  gula eget dolor
//                 </span>
//             </p>
//         </div>
//         <div class="icons-mail">
//             <div class=" mail-date float-end text-muted fw-normal fs-11"><span class="me-1 d-inline-block"><i class="ri-attachment-2 align-middle fs-12"></i></span>1:32PM</div>
//             <div class="mail-hover-icons d-xl-block d-none">
//                 <div class="d-flex justify-content-end align-items-center">
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="mark as read">
//                         <i class="fe fe-mail"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="delete">
//                         <i class="fe fe-trash"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="Snooze">
//                         <i class="fe fe-clock"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="archive">
//                         <i class="fe fe-download"></i>
//                     </a>
//                 </div>
//             </div>
//         </div>
//     </div>
// </li>
// <li>
//     <div class="d-flex align-items-top">
//         <div class="me-3 mt-1">
//             <input class="form-check-input" type="checkbox" value="" aria-label="...">
//         </div>
//         <div class="me-2 lh-1">
//             <a href="javascript:void(0);" class="main-mail-star  d-inline-block">
//                 <i class="bx bx-star"></i>
//             </a>
//         </div>
//         <div class="me-1 lh-1">
//             <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" class="main-mail-star d-inline-block" data-bs-original-title="mark as Important">
//                 <i class="bx bx-label"></i>
//             </a>
//         </div>
//         <div class="flex-fill ms-2 subject-container">
//             <a href="mail-chat.html" class="stretched-link"></a>
//             <p class="mb-0  main-mail-subject">
//                 <span class="d-block mb-0 fw-semibold fs-14">
//                     Life Insurance Corporation</span>
//                 <span class="fs-12 text-muted text-truncate">
//                     Lorem ipsum dolor sit amet consectetur adipisicing elit- viva mus elemen tum &#129306;semper nisi enean vulputat enean commodo li gula eget dolor cum socia eget dolor  gula eget dolor
//                 </span>
//             </p>
//         </div>
//         <div class="icons-mail">
//             <div class=" mail-date float-end text-muted fw-normal fs-11"><span class="me-1 d-inline-block"><i class="ri-attachment-2 align-middle fs-12"></i></span>1:32PM</div>
//             <div class="mail-hover-icons d-xl-block d-none">
//                 <div class="d-flex justify-content-end align-items-center">
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="mark as read">
//                         <i class="fe fe-mail"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="delete">
//                         <i class="fe fe-trash"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="Snooze">
//                         <i class="fe fe-clock"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="archive">
//                         <i class="fe fe-download"></i>
//                     </a>
//                 </div>
//             </div>
//         </div>
//     </div>
// </li>
// <li>
//     <div class="d-flex align-items-top">
//         <div class="me-3 mt-1">
//             <input class="form-check-input" type="checkbox" value="" aria-label="...">
//         </div>
//         <div class="me-2 lh-1">
//             <a href="javascript:void(0);" class="main-mail-star  d-inline-block">
//                 <i class="bx bx-star"></i>
//             </a>
//         </div>
//         <div class="me-1 lh-1">
//             <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" class="main-mail-star d-inline-block" data-bs-original-title="mark as Important">
//                 <i class="bx bx-label"></i>
//             </a>
//         </div>
//         <div class="flex-fill ms-2 subject-container">
//             <a href="mail-chat.html" class="stretched-link"></a>
//             <p class="mb-0  main-mail-subject">
//                 <span class="d-block mb-0 fw-semibold fs-14">
//                     Life Insurance Corporation</span>
//                 <span class="fs-12 text-muted text-truncate">
//                     <span class="badge bg-danger rounded-1">mail</span>
//                     Lorem ipsum dolor sit amet consectetur adipisicing elit- viva mus elemen tum semper nisi enean vulputat enean commodo li gula eget dolor cum socia eget dolor  gula eget dolor
//                 </span>
//             </p>
//         </div>
//         <div class="icons-mail">
//             <div class=" mail-date float-end text-muted fw-normal fs-11"><span class="me-1 d-inline-block"><i class="ri-attachment-2 align-middle fs-12"></i></span>1:32PM</div>
//             <div class="mail-hover-icons d-xl-block d-none">
//                 <div class="d-flex justify-content-end align-items-center">
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="mark as read">
//                         <i class="fe fe-mail"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="delete">
//                         <i class="fe fe-trash"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="Snooze">
//                         <i class="fe fe-clock"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="archive">
//                         <i class="fe fe-download"></i>
//                     </a>
//                 </div>
//             </div>
//         </div>
//     </div>
// </li>
// <li class="active">
//     <div class="d-flex align-items-top">
//         <div class="me-3 mt-1">
//             <input class="form-check-input" type="checkbox" value="" aria-label="...">
//         </div>
//         <div class="me-2 lh-1">
//             <a href="javascript:void(0);" class="main-mail-star  d-inline-block">
//                 <i class="bx bx-star"></i>
//             </a>
//         </div>
//         <div class="me-1 lh-1">
//             <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" class="main-mail-star d-inline-block" data-bs-original-title="mark as Important">
//                 <i class="bx bx-label"></i>
//             </a>
//         </div>
//         <div class="flex-fill ms-2 subject-container">
//             <a href="mail-chat.html" class="stretched-link"></a>
//             <p class="mb-0  main-mail-subject">
//                 <span class="d-block mb-0 fw-semibold fs-14">
//                     &#128522;History of planets are discovered yesterday.....</span>
//                 <span class="fs-12 text-muted text-truncate">
//                     Lorem ipsum dolor sit amet consectetur adipisicing elit- viva mus elemen tum semper nisi enean vulputat enean commodo li gula eget dolor cum socia eget dolor  gula eget dolor
//                 </span>
//             </p>
//         </div>
//         <div class="icons-mail">
//             <div class=" mail-date float-end text-muted fw-normal fs-11"><span class="me-1 d-inline-block"><i class="ri-attachment-2 align-middle fs-12"></i></span>1:32PM</div>
//             <div class="mail-hover-icons d-xl-block d-none">
//                 <div class="d-flex justify-content-end align-items-center">
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="mark as read">
//                         <i class="fe fe-mail"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="delete">
//                         <i class="fe fe-trash"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="Snooze">
//                         <i class="fe fe-clock"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="archive">
//                         <i class="fe fe-download"></i>
//                     </a>
//                 </div>
//             </div>
//         </div>
//     </div>
// </li>
// <li>
//     <div class="d-flex align-items-top">
//         <div class="me-3 mt-1">
//             <input class="form-check-input" type="checkbox" value="" aria-label="...">
//         </div>
//         <div class="me-2 lh-1">
//             <a href="javascript:void(0);" class="main-mail-star  d-inline-block">
//                 <i class="bx bx-star"></i>
//             </a>
//         </div>
//         <div class="me-1 lh-1">
//             <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" class="main-mail-star d-inline-block" data-bs-original-title="mark as Important">
//                 <i class="bx bx-label"></i>
//             </a>
//         </div>
//         <div class="flex-fill ms-2 subject-container">
//             <a href="mail-chat.html" class="stretched-link"></a>
//             <p class="mb-0  main-mail-subject">
//                 <span class="d-block mb-0 fw-semibold fs-14">
//                     New Project details</span>
//                 <span class="fs-12 text-muted text-truncate">
//                     <span class="badge bg-info rounded-1">Home</span>
//                     Lorem ipsum dolor sit amet consectetur adipisicing elit- viva mus elemen tum semper nisi enean vulputat enean commodo li gula eget dolor cum socia eget dolor  gula eget dolor
//                 </span>
//             </p>
//         </div>
//         <div class="icons-mail">
//             <div class=" mail-date float-end text-muted fw-normal fs-11"><span class="me-1 d-inline-block"><i class="ri-attachment-2 align-middle fs-12"></i></span>1:32PM</div>
//             <div class="mail-hover-icons d-xl-block d-none">
//                 <div class="d-flex justify-content-end align-items-center">
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="mark as read">
//                         <i class="fe fe-mail"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="delete">
//                         <i class="fe fe-trash"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="Snooze">
//                         <i class="fe fe-clock"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="archive">
//                         <i class="fe fe-download"></i>
//                     </a>
//                 </div>
//             </div>
//         </div>
//     </div>
// </li>
// <li class="active">
//     <div class="d-flex align-items-top">
//         <div class="me-3 mt-1">
//             <input class="form-check-input" type="checkbox" value="" aria-label="...">
//         </div>
//         <div class="me-2 lh-1">
//             <a href="javascript:void(0);" class="main-mail-star  d-inline-block">
//                 <i class="bx bx-star"></i>
//             </a>
//         </div>
//         <div class="me-1 lh-1">
//             <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" class="main-mail-star d-inline-block" data-bs-original-title="mark as Important">
//                 <i class="bx bx-label"></i>
//             </a>
//         </div>
//         <div class="flex-fill ms-2 subject-container">
//             <a href="mail-chat.html" class="stretched-link"></a>
//             <p class="mb-0  main-mail-subject">
//                 <span class="d-block mb-0 fw-semibold fs-14">
//                     Life Insurance Corporation</span>
//                 <span class="fs-12 text-muted text-truncate">
//                     Lorem ipsum dolor sit amet consectetur adipisicing elit- viva mus elemen tum semper nisi enean vulputat enean commodo &#128525;li gula eget dolor cum socia eget dolor  gula eget dolor
//                 </span>
//             </p>
//         </div>
//         <div class="icons-mail">
//             <div class=" mail-date float-end text-muted fw-normal fs-11"><span class="me-1 d-inline-block"><i class="ri-attachment-2 align-middle fs-12"></i></span>1:32PM</div>
//             <div class="mail-hover-icons d-xl-block d-none">
//                 <div class="d-flex justify-content-end align-items-center">
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="mark as read">
//                         <i class="fe fe-mail"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="delete">
//                         <i class="fe fe-trash"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="Snooze">
//                         <i class="fe fe-clock"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="archive">
//                         <i class="fe fe-download"></i>
//                     </a>
//                 </div>
//             </div>
//         </div>
//     </div>
// </li>
// <li>
//     <div class="d-flex align-items-top">
//         <div class="me-3 mt-1">
//             <input class="form-check-input" type="checkbox" value="" aria-label="...">
//         </div>
//         <div class="me-2 lh-1">
//             <a href="javascript:void(0);" class="main-mail-star  d-inline-block">
//                 <i class="bx bx-star"></i>
//             </a>
//         </div>
//         <div class="me-1 lh-1">
//             <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" class="main-mail-star d-inline-block" data-bs-original-title="mark as Important">
//                 <i class="bx bx-label"></i>
//             </a>
//         </div>
//         <div class="flex-fill ms-2 subject-container">
//             <a href="mail-chat.html" class="stretched-link"></a>
//             <p class="mb-0  main-mail-subject">
//                 <span class="d-block mb-0 fw-semibold fs-14">
//                     Life Insurance Corporation</span>
//                 <span class="fs-12 text-muted text-truncate">
//                     <span class="badge bg-danger rounded-1">mail</span>
//                     Lorem ipsum dolor sit amet consectetur adipisicing elit-&#127881;&#127874; viva mus elemen tum semper nisi enean vulputat enean commodo li gula eget dolor cum socia eget dolor  gula eget dolor
//                 </span>
//             </p>
//         </div>
//         <div class="icons-mail">
//             <div class=" mail-date float-end text-muted fw-normal fs-11"><span class="me-1 d-inline-block"><i class="ri-attachment-2 align-middle fs-12"></i></span>1:32PM</div>
//             <div class="mail-hover-icons d-xl-block d-none">
//                 <div class="d-flex justify-content-end align-items-center">
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="mark as read">
//                         <i class="fe fe-mail"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="delete">
//                         <i class="fe fe-trash"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="Snooze">
//                         <i class="fe fe-clock"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="archive">
//                         <i class="fe fe-download"></i>
//                     </a>
//                 </div>
//             </div>
//         </div>
//     </div>
// </li>
// <li class="border-bottom">
//     <div class="d-flex align-items-top">
//         <div class="me-3 mt-1">
//             <input class="form-check-input" type="checkbox" value="" aria-label="...">
//         </div>
//         <div class="me-2 lh-1">
//             <a href="javascript:void(0);" class="main-mail-star  d-inline-block">
//                 <i class="bx bx-star"></i>
//             </a>
//         </div>
//         <div class="me-1 lh-1">
//             <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" class="main-mail-star d-inline-block" data-bs-original-title="mark as Important">
//                 <i class="bx bx-label"></i>
//             </a>
//         </div>
//         <div class="flex-fill ms-2 subject-container">
//             <a href="mail-chat.html" class="stretched-link"></a>
//             <p class="mb-0  main-mail-subject">
//                 <span class="d-block mb-0 fw-semibold fs-14">
//                     New Project details</span>
//                 <span class="fs-12 text-muted text-truncate">
//                     <span class="badge bg-info rounded-1">Home</span>
//                     Lorem ipsum dolor sit amet consectetur adipisicing elit- viva mus elemen tum semper nisi enean vulputat enean commodo li gula eget dolor cum socia eget dolor  gula eget dolor
//                 </span>
//             </p>
//         </div>
//         <div class="icons-mail">
//             <div class=" mail-date float-end text-muted fw-normal fs-11"><span class="me-1 d-inline-block"><i class="ri-attachment-2 align-middle fs-12"></i></span>1:32PM</div>
//             <div class="mail-hover-icons d-xl-block d-none">
//                 <div class="d-flex justify-content-end align-items-center">
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="mark as read">
//                         <i class="fe fe-mail"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="delete">
//                         <i class="fe fe-trash"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="Snooze">
//                         <i class="fe fe-clock"></i>
//                     </a>
//                     <a href="javascript:void(0);" data-bs-toggle="tooltip" title="" data-bs-original-title="archive">
//                         <i class="fe fe-download"></i>
//                     </a>
//                 </div>
//             </div>
//         </div>
//     </div>
// </li>