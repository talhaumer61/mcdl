<?php
$search_word    = '';
$search_query   = '';
$filters        = 'search&'.$redirection.'';

if (!empty($_GET['search_word'])) {
    $search_word     = $_GET['search_word'];
    $search_query   .= 'AND (sub.substate_name LIKE "%'.$search_word.'%")';
    $filters        .= '&search_word='.$search_word.'';
}

$condition = array ( 
                         'select' 	    =>  'sub.substate_id, sub.substate_name, sub.substate_ordering, sub.substate_latitude, sub.substate_longitude,sub.id_state,sub.id_country, sub.substate_status, s.state_name, c.country_name'
                        ,'join' 	    =>  "INNER JOIN ".STATES." s ON s.state_id = sub.id_state
                                             INNER JOIN ".COUNTRIES." c ON c.country_id = sub.id_country"
                        ,'where' 	    =>  array( 
                                                    'sub.is_deleted' 	=> 0 
                                                ) 
                        ,'search_by'    =>  ''.$search_query.''
                        ,'order_by' 	=>  'sub.substate_ordering ASC'
                        ,'return_type' 	=>  'count' 
                    ); 
$count = $dblms->getRows(SUB_STATES.' sub', $condition);
echo'
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>'.moduleName(false).' List</h5>
            <div class="flex-shrink-0">
                <a class="btn btn-primary btn-sm" onclick="showAjaxModalZoom(\'include/modals/substates/add.php?ordering='.get_ordering(SUB_STATES).'\');"><i class="ri-add-circle-line align-bottom me-1"></i>'.moduleName(false).'</a>
            </div>
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

        $condition['order_by'] = "sub.substate_ordering ASC LIMIT " . ($page - 1) * $Limit . ",$Limit";
        $condition['return_type'] = 'all';

        $rowsList = $dblms->getRows(SUB_STATES.' sub', $condition);
        if ($rowsList) {
            echo'
            <div class="table-responsive table-card">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="40" class="text-center">Sr.</th>
                            <th>Name</th>
                            <th width="70" class="text-center">Ordering</th>
                            <th width="70" class="text-center">State</th>
                            <th width="70" class="text-center">Country</th>
                            <th width="70" class="text-center">Latitude</th>
                            <th width="70" class="text-center">Longitude</th>
                            <th width="70" class="text-center">Status</th>
                            <th width="60" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>';
                        $srno = ($page == 1 ? 0 : ($page - 1) * $Limit);
                        foreach ($rowsList as $row) {
                            $srno++;
                            echo '
                            <tr style="vertical-align: middle;">
                                <td class="text-center">'.$srno.'</td>
                                <td>'.$row['substate_name'].'</td>
                                <td class="text-center">'.$row['substate_ordering'].'</td>
                                <td class="text-center">'.$row['state_name'].'</td>
                                <td class="text-center">'.$row['country_name'].'</td>
                                <td class="text-center">'.$row['substate_latitude'].'</td>
                                <td class="text-center">'.$row['substate_longitude'].'</td>
                                <td class="text-center">'.get_status($row['substate_status']).'</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-end" style="cursor: pointer;">
                                            <li><a class="dropdown-item" onclick="showAjaxModalZoom(\'include/modals/substates/edit.php?substate_id='.$row['substate_id'].'\');"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                            <li><a class="dropdown-item" onclick="confirm_modal(\'substates.php?deleteid='.$row['substate_id'].'\');"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>
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
