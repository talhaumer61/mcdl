<?php
$search_word    = '';
$search_query   = '';
$filters        = 'search';
if (!empty($_GET['search_word'])) {
    $search_word     = cleanvars($_GET['search_word']);
    $search_query   .= ' AND (s.std_name LIKE "%'.$search_word.'%" OR s.city_name LIKE "%'.$search_word.'%")';
    $filters        .= '&search_word='.$search_word.'';
}

$condition = array(
                     'select'       =>  's.std_id, s.std_name, s.std_status, s.std_gender, s.city_name, s.std_dob, s.std_level, s.std_loginid, a.adm_photo, a.adm_username, a.adm_email'
                    ,'join'         =>  'INNER JOIN '.ADMINS.' a ON a.adm_id = s.std_loginid AND a.adm_status = 1 AND a.is_deleted = 0'
                    ,'where'        =>  array(
                                                's.is_deleted'  => 0
                                            )
                    ,'search_by'    =>  ''.$search_query.''
                    ,'order_by'     =>  's.std_id ASC'
                    ,'return_type'  =>  'count'
);
$count = $dblms->getRows(STUDENTS.' s', $condition, $sql);
echo '
<style type="text/css">
        .table-responsive {
            overflow: visible; /* Allow dropdown to be fully visible */
        }
        .table td {
            position: relative; /* Ensures dropdown aligns correctly */
        }
        .dropdown-menu {
            z-index: 9999;
            position: absolute;
            right: 0;
            top: 100%;
            display: none;
            background-color: white;
            border: 1px solid #ddd;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
        }
        .dropdown.show .dropdown-menu {
            display: block;
        }
    </style>
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>'.moduleName(false).' List</h5>
        </div>
    </div>
    <div class="card-body">        
        <div class="row justify-content-end">
            <div class="col-3">
                <form class="form-horizontal" id="form" enctype="multipart/form-data" method="get" autocomplete="off" accept-charset="utf-8">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Search..." name="search_word" value="'.$search_word.'">
                        <button type="submit" class="btn btn-primary btn-sm" name="search"><i class="ri-search-2-line"></i></button>
                    </div>
                </form>
            </div>
        </div>';
        if ($page == 0 || empty($page)) { $page = 1; }
        $prev       = $page - 1;
        $next       = $page + 1;
        $lastpage   = ceil($count / $Limit);   //lastpage = total pages // items per page, rounded up
        $lpm1       = $lastpage - 1;

        $condition['order_by'] = "s.std_id ASC LIMIT " . ($page - 1) * $Limit . ",$Limit";
        $condition['return_type'] = 'all';
        $rowsList = $dblms->getRows(STUDENTS.' s', $condition, $sql);
        if ($rowsList) {
            echo'
            <div class="table-responsive table-card">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="40" class="text-center">Sr.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th width="120">City</th>
                            <th width="120" class="text-center">DOB</th>
                            <th width="70" class="text-center">Level</th>
                            <th width="70" class="text-center">Gender</th>
                            <th width="70" class="text-center">Status</th>
                            <th width="70" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>';
                        $srno = ($page == 1 ? 0 : ($page - 1) * $Limit);
                        foreach ($rowsList as $row) {
                            $srno++;
                            // CHECK IMAGE EXIST
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
                            echo '
                            <tr style="vertical-align: middle;">
                                <td class="text-center">'.$srno.'</td>
                                <td>
                                    <span>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-sm bg-light rounded p-1">
                                                    <img src="'.$adm_photo.'" alt="" class="img-fluid d-block">
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fs-14 mb-1">'.$row['std_name'].'</h5>
                                                <p class="text-muted mb-0">@'.$row['adm_username'].'</p>
                                            </div>
                                        </div>
                                    </span>
                                </td>
                                <td>'.$row['adm_email'].'</td>
                                <td>'.(!empty($row['city_name']) ? $row['city_name'] : '<span class="text-danger">Not Set</span>').'</td>
                                <td class="text-center">'.($row['std_dob'] != NULL ? date('d M, Y', strtotime($row['std_dob'])) : '<span class="text-danger">Not Set</span>').'</td>
                                <td class="text-center">'.get_levels($row['std_level']).'</td>
                                <td class="text-center">'.($row['std_gender'] != 0 ? get_gendertypes($row['std_gender']) : '').'</td>
                                <td class="text-center">'.get_status($row['std_status']).'</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-end" style="cursor: pointer;">
                                            <li><a class="dropdown-item" onclick="showAjaxModalView(\'include/modals/'.moduleName().'/view.php?std_id='.$row['std_id'].'\');" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                                            <li><a class="dropdown-item" onclick="confirm_modal(\''.moduleName().'.php?deleteid='.$row['std_id'].'&std_loginid='.$row['std_loginid'].'\');"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>';
                        }
                        echo'
                    </tbody>
                </table>';                
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
        echo'
    </div>
</div>';
?>