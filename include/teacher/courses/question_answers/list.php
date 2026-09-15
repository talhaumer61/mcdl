<?php
$search_word    = '';
$search_query   = '';
$filters        = 'search&'.$redirection.'';

if (!empty($_GET['search_word'])) {
    $search_word     = $_GET['search_word'];
    $search_query   .= 'AND (a.adm_fullname LIKE "%'.$search_word.'%")';
    $filters        .= '&search_word='.$search_word.'';
}

$condition = array(
                     'select'       =>  'qa.id_user, s.std_gender, a.adm_fullname, a.adm_username, a.adm_photo
                                        , SUM(CASE WHEN qa.read_status = 2 THEN 1 ELSE 0 END) as unReadMsg'
                    ,'join'         =>  'INNER JOIN '.STUDENTS.' s ON s.std_id = qa.id_user AND s.is_deleted = 0
                                         INNER JOIN '.ADMINS.' a ON a.adm_id = s.std_loginid AND a.is_deleted = 0'
                    ,'where'        =>  array(
                                                 'qa.id_curs'       =>   cleanvars(CURS_ID)
                                                ,'qa.type'          =>   1
                                                ,'qa.is_deleted'    =>   0
                                            )
                    ,'search_by'    =>  ''.$search_query.''
                    ,'group_by'     =>  'qa.id_user'
                    ,'order_by'     =>  'qa.datetime_sent DESC'
                    ,'return_type'  =>  'count'
                );
$count = $dblms->getRows(QUESTION_ANSWERS.' qa', $condition, $sql);
echo'
<div class="row justify-content-end">
    <div class="col-3">
        <form class="form-horizontal" id="form" enctype="multipart/form-data" method="get" autocomplete="off" accept-charset="utf-8">
            <input type="hidden" name="id" value="'.CURS_ID.'">
            <input type="hidden" name="view" value="'.LMS_VIEW.'">
            <input type="hidden" name="tab" value="manage_course">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Search..." id="searchInput" name="search_word" value="'.$search_word.'">
                <button type="submit" class="btn btn-primary btn-sm" name="search"><i class="ri-search-2-line"></i></button>
            </div>
        </form>
    </div>
</div>';       
if ($page == 0 || empty($page)) { $page = 1; }
$prev        = $page - 1;
$next        = $page + 1;
$lastpage    = ceil($count / $Limit);   //lastpage = total pages // items per page, rounded up
$lpm1        = $lastpage - 1;

$condition['order_by']      = "qa.datetime_sent DESC LIMIT " . ($page - 1) * $Limit . ",$Limit";
$condition['return_type']   = 'all';

$rowslist = $dblms->getRows(QUESTION_ANSWERS.' qa', $condition);
if ($rowslist) {
    echo'
    <div class="table-responsive table-card">
        <div class="chat-message-list">
            <ul class="list-unstyled chat-list chat-user-list" id="userList">';
                $srno = ($page == 1 ? 0 : ($page - 1) * $Limit);    
                foreach ($rowslist as $row) {
                    $srno++;
                    // CHECK ADMIN IMAGE EXIST
                    if($row['std_gender'] == '2'){
                        $adm_photo = SITE_URL.'uploads/images/default_female.jpg';
                    }else{
                        $adm_photo = SITE_URL.'uploads/images/default_male.jpg';
                    }
                    if(!empty($row['adm_photo'])){
                        $file_url = SITE_URL.'uploads/images/admin/'.$row['adm_photo'];
                        if (check_file_exists($file_url)) {
                            $adm_photo = $file_url;
                        }
                    }
                    echo'
                    <li class="m-1 border">
                        <a href="?chat&id_std='.$row['id_user'].'&'.$redirection.'" class="unread-msg-user">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 chat-user-img online align-self-center me-3 ms-0">
                                    <div class="avatar-sm">
                                        <img src="'.$adm_photo.'" class="rounded-circle avatar-sm" alt="">
                                    </div>
                                    <span class="user-status"></span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="text-truncate mb-0">'.$row['adm_fullname'].'</h6>
                                    <p class="text-muted mb-0"><span class="fw-medium">@'.$row['adm_username'].'</span></p>
                                </div>
                                <div class="flex-shrink-0">';
                                    if(!empty($row['unReadMsg'])){
                                        echo'<span class="badge badge-soft-success rounded p-1">'.(($row['unReadMsg'] < 10 && !empty($row['unReadMsg'])) ? '0'.$row['unReadMsg'] : $row['unReadMsg']).'</span>';
                                    }
                                    echo'
                                </div>
                            </div>
                        </a>
                    </li>';
                }
                echo'
            </ul>
        </div>';               
        include_once('include/pagination.php');
        echo'
    </div>';
} else {
    echo'
    <div class="noresult" style="display: block">
        <div class="text-center">
            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px">
            </lord-icon>
            <h5 class="mt-2">Sorry! No Record Found</h5>
            <!--<p class="text-muted">We\'ve searched more than 150+ Orders We did not find any orders for you search.</p>-->
        </div>
    </div>';
}
?>