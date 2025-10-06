<?php
$search_word    = '';
$search_query   = '';
$filters        = 'search&'.$redirection.'';

if (!empty($_GET['search_word'])) {
    $search_word     = $_GET['search_word'];
    $search_query   .= 'AND (ct.city_name LIKE "%'.$search_word.'%")';
    $filters        .= '&search_word='.$search_word.'';
}

$condition = array ( 
                         'select' 	    =>  'ct.city_id, ct.id_substate, ct.id_state, ct.city_ordering, ct.id_country, ct.city_name, ct.city_codedigit, ct.city_codealpha, ct.city_latitude, ct.city_longitude, ct.city_status, sbs.substate_name, s.state_name, c.country_name'
                        ,'join' 	    =>  'INNER JOIN '.SUB_STATES.' sbs ON sbs.substate_id = ct.id_substate
                                             INNER JOIN '.STATES.' s ON s.state_id = ct.id_state
                                             INNER JOIN '.COUNTRIES.' c ON c.country_id = ct.id_country'
                        ,'where' 	    =>  array( 
                                                    'ct.is_deleted' 	=> 0 
                                                ) 
                        ,'search_by'    =>  ''.$search_query.''
                        ,'order_by' 	=>  'ct.city_ordering ASC'
                        ,'return_type' 	=>  'count' 
                    ); 
$count = $dblms->getRows(CITIES.' ct', $condition);
echo'
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>'.moduleName(false).' List</h5>
            <div class="flex-shrink-0">
                <a class="btn btn-primary btn-sm" onclick="showAjaxModalZoom(\'include/modals/cities/add.php?ordering='.get_ordering(CITIES).'\');"><i class="ri-add-circle-line align-bottom me-1"></i>'.moduleName(false).'</a>
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

        $condition['order_by'] = "ct.city_ordering ASC LIMIT " . ($page - 1) * $Limit . ",$Limit";
        $condition['return_type'] = 'all';

        $rowsList = $dblms->getRows(CITIES.' ct', $condition);
        if ($rowsList) {
            echo'
            <div class="table-responsive table-card">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="40" class="text-center">Sr.</th>
                            <th>Name</th>
                            <th width="70" class="text-center">Ordering</th>
                            <th width="100" class="text-center">Code (Digit)</th>
                            <th width="110" class="text-center">Code (Alpha)</th>
                            <th width="70" class="text-center">Latitude</th>
                            <th width="70" class="text-center">Longitude</th>
                            <th width="90" class="text-center">Sub State</th>
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
                                <td>'.$row['city_name'].'</td>
                                <td class="text-center">'.$row['city_ordering'].'</td>
                                <td class="text-center">'.$row['city_codedigit'].'</td>
                                <td class="text-center">'.$row['city_codealpha'].'</td>
                                <td class="text-center">'.$row['city_latitude'].'</td>
                                <td class="text-center">'.$row['city_longitude'].'</td>
                                <td class="text-center">'.$row['substate_name'].'</td>
                                <td class="text-center">'.get_status($row['city_status']).'</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-end" style="cursor: pointer;">
                                            <li><a class="dropdown-item" onclick="showAjaxModalZoom(\'include/modals/cities/edit.php?city_id='.$row['city_id'].'\');"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                            <li><a class="dropdown-item" onclick="confirm_modal(\'cities.php?deleteid='.$row['city_id'].'\');"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>
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