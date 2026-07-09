<?php
$get_query      = '';
$sql_query      = '';
$count          = 0;
$matches        = array();
$filters        = 'search';

if (!empty($_GET['sql_query'])) {    
    $get_query      = $_GET['sql_query'];
    $sql_query      = str_replace('`', '', $get_query);
    $filters       .= '&sql_query='.$sql_query.'';

    if (strpos(strtolower($sql_query), 'select') === 0) {
        if (preg_match("/SELECT(.*?)FROM/is", $sql_query, $matches)) {
            // COL NAME IN SELECT
            $selected_columns = trim($matches[1]);            
            $selected_columns = explode(',',$selected_columns);
            $selected_columns = array_map('trim', $selected_columns);

            // TABLE NAMES WITH ALIAS
            preg_match_all("/(?:FROM|JOIN)\s+([^\s]+)(?:\s+AS\s+(\w+))?/i", $sql_query, $matches);
            $table_name = $matches[1];
            $table_alias = $matches[2];
            $table_info = array_combine($table_alias, $table_name);

            $selected_columns_array = array();

            // COL NAMES TO VIEW IN TABLE
            foreach ($selected_columns as $string) {                
                $dot            = strcspn($string, '.');
                $alias          = substr($string, 0, $dot);

                $reversedString = strrev($string);
                $pos            = strcspn($reversedString, ' .');
                $substring      = substr($reversedString, 0, $pos);
                $finalSubstring = strrev($substring);
                
                if($finalSubstring == '*'){
                    if($alias == '*'){
                        $table = $table_name[0];
                    }else{
                        $table = $table_info[$alias];
                    }
                    $condition = array ( 
                                             'select'       =>  'column_name'
                                            ,'where' 	    =>  array( 
                                                                        'table_schema' => LMS_NAME
                                                                    )
                                            ,'search_by'    =>  ' AND table_name = "'.$table.'" '
                                            ,'return_type'  =>  'all' 
                                        );
                    $results = $dblms->getRows('information_schema.columns', $condition, $sql);
                    foreach ($results as $key => $value) {
                        array_push($selected_columns_array, $value['column_name']);
                    }
                }else{
                    array_push($selected_columns_array, $finalSubstring);
                }
            }
        }
    }
}
echo'
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>SQL Search</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row justify-content-end">
            <form class="form-horizontal" id="form" enctype="multipart/form-data" method="get" autocomplete="off" accept-charset="utf-8">
                <div class="col-12">
                    <input type="hidden" class="form-control" name="view" value="sql"/>
                    <textarea class="form-control" rows="10" name="sql_query" placeholder="Query you want to run">'.$get_query.'</textarea>
                </div>                
                <div class="col-12 my-3 text-end">
                    <button type="submit" class="btn btn-primary rounded-pill btn-sm" name="search"><i class="ri-search-2-line align-bottom"></i> Go</button>
                </div>
            </form>
        </div>';
        if(!empty($sql_query)){            
            if (strpos(strtolower($sql_query), 'select') === 0) {
                $sqllms	= $dblms->querylms($sql_query);
                $count = mysqli_num_rows($sqllms);

                if ($page == 0 || empty($page)) { $page = 1; }
                $prev       = $page - 1;
                $next       = $page + 1;
                $lastpage   = ceil($count / $Limit);   //lastpage = total pages // items per page, rounded up
                $lpm1       = $lastpage - 1;

                $sqllms	= $dblms->querylms("$sql_query LIMIT ".($page-1)*$Limit .",$Limit");
                if (mysqli_num_rows($sqllms) > 0) {
                    echo'
                    <div class="table-responsive table-card">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr style="vertical-align: middle;">
                                    <th width="40" class="text-center text-primary">Sr.</th>';
                                    foreach ($selected_columns_array as $key => $value) {
                                        echo'<th>'.$value.'</th>';
                                    }
                                    echo'
                                </tr>
                            </thead>
                            <tbody>';
                                $srno = ($page == 1 ? 0 : ($page - 1) * $Limit);
                                
                                $thisArray = array();
                                while($row = mysqli_fetch_array($sqllms)){
                                    $srno++;
                                    echo'
                                    <tr>';
                                        echo'<td class="text-center text-primary">'.$srno.'</td>';
                                        foreach ($selected_columns_array as $key => $value) {
                                            echo'<td>'.$row[$value].'</td>';
                                        }
                                        echo'
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
            }else{
                echo'
                <div class="noresult" style="display: block">
                    <div class="text-center">
                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px">
                        </lord-icon>
                        <h5 class="mt-2">Sorry! Query Not Correct to search</h5>
                        <!--<p class="text-muted">We\'ve searched more than 150+ Orders We did not find any orders for you search.</p>-->
                    </div>
                </div>';
            }
        }
        echo'
    </div>
</div>';
?>