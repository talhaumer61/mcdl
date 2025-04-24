<?php
$condition = array ( 
                         'select'       =>  'ic.type, ic.id_interest, c.curs_name'
                        ,'join'         =>  'INNER JOIN '.COURSES.' c ON c.curs_id = ic.id_interest AND c.curs_status = 1 AND c.is_deleted = 0'
                        ,'where' 	    =>  array( 
                                                     'ic.type'      => 3
                                                    ,'ic.status'    => 1
                                                )
                        ,'group_by'     =>  'ic.id_interest'
                        ,'return_type'  =>  'all'
                    ); 
$COURSES = $dblms->getRows(STUDENT_INTERESTED_COURSES.' ic', $condition);

$id_interest    = '';
$search_by      = '';
if (!empty($_GET['id_interest'])) {
    $id_interest    = cleanvars($_GET['id_interest']);
    $search_by     .= ' AND ic.id_interest = "'.cleanvars($id_interest).'" ';
}

// LIST
$condition = array ( 
                         'select'       =>  'ic.*, c.curs_name, c.curs_photo'
                        ,'join'         =>  'INNER JOIN '.COURSES.' c ON c.curs_id = ic.id_interest'
                        ,'where' 	    =>  array( 
                                                     'ic.type'      => 3
                                                    ,'ic.status'    => 1
                                                )
                        ,'search_by'    =>  "$search_by"
                        ,'return_type'  =>  'all' 
                    ); 
$STUDENT_INTERESTED_COURSES = $dblms->getRows(STUDENT_INTERESTED_COURSES.' ic', $condition,$sql);
echo' 
<title>'.moduleName(false).' - '.TITLE_HEADER.'</title>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">'.moduleName(false).'</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                            <li class="breadcrumb-item"><a href="'.moduleName().'.php" class="text-primary">'.moduleName(false).'</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-filter-line align-bottom me-1"></i>Filters</h5>
                        </div>
                    </div>
                    <form autocomplete="off" class="form-validate" enctype="multipart/form-data" method="get" accept-charset="utf-8">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-sm-6">
                                    <label class="form-label">Couses</label>
                                    <select class="form-control" id="id_interest" name="id_interest" data-choices>
                                        <option value="">Choose one</option>';
                                        foreach($COURSES as $key => $value):
                                            echo '
                                            <option value="'.$value['id_interest'].'" '.($value['id_interest'] == $id_interest ? 'selected' : '').'>'.$value['curs_name'].' - ('.get_enroll_type($value['type']).')</option>';
                                        endforeach;
                                        echo '
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="hstack gap-2 justify-content-center">
                                <button type="submit" class="btn btn-primary btn-sm" name="search"><i class="ri-search-line align-bottom me-1"></i>Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>'.moduleName(false).' List</h5>
                            <div class="flex-shrink-0">
                                <button onclick="print_report(\'printResult\')" class="mr-xs btn btn-danger btn-xs"><i class="ri-printer-line align-middle"></i> Print</button>
                                <button id="export_button" class="btn btn-success btn-xs"><i class="ri-upload-cloud-line align-middle"></i> Excel</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">';
                        if ($STUDENT_INTERESTED_COURSES) {
                            echo'
                            <div class="table-responsive table-card" id="printResult">
                                <div id="header" style="display:none;">
                                    <h5 class="text-center mb-3">'.moduleName(false).'</h5>
                                </div>
                                <table class="table mb-0">
                                    <thead class="table-light">
                                        <tr>                        
                                            <th width="40" class="text-center">Sr.</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Interest in</th>
                                            <th width="120" class="text-center">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                        $srno = 0;
                                        foreach ($STUDENT_INTERESTED_COURSES as $row) {
                                            $curs_photo = ((!empty($row['curs_photo']) && file_exists('uploads/images/courses/'.$row['curs_photo'])) ? 'uploads/images/courses/'.$row['curs_photo'].'' : 'uploads/default.png');
                                            $srno++;
                                            echo'
                                            <tr style="vertical-align: middle;">
                                                <td class="text-center">'.$srno.'</td>
                                                <td>'.$row['name'].'</td>
                                                <td>'.$row['email'].'</td>
                                                <td>
                                                    <span>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0 me-3">
                                                                <div class="avatar-sm bg-light rounded p-1">
                                                                    <img src="'.$curs_photo.'" alt="" class="img-fluid d-block">
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h5 class="fs-14 mb-1">'.$row['curs_name'].'</h5>
                                                                <p class="text-muted mb-0">'.get_enroll_type($row['type']).'</p>
                                                            </div>
                                                        </div>
                                                    </span>
                                                </td>
                                                <td class="text-center">'.date('d M, Y', strtotime($row['date_posted'])).'</td>
                                            </tr>';
                                        }
                                        echo'
                                    </tbody>
                                </table>
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
                </div>
            </div>
        </div>
    </div>
</div>';
?>