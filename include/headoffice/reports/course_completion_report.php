<?php

$condition  = [
                 'select'       =>  'gc.cert_type, gc.cert_name, COUNT(ec.id_std) AS std_count, COUNT(gc.cert_id) AS completion_count'
                ,'join'         =>  'LEFT JOIN '.GENERATED_CERTIFICATES.' AS gc ON gc.id_enroll = ec.secs_id'
                ,'where'        =>  [
                                        'ec.secs_status' => 1,
                                        'ec.is_deleted'  => 0
                                ]
                ,'group_by'      =>  ' ec.id_curs '
                ,'return_type'   =>  'all'
];
if (!empty($_GET['id_type'])) {
    $condition['where']['ec.id_type'] = $_GET['id_type'];
}
$ENROLLED_COURSES = $dblms->getRows(ENROLLED_COURSES.' AS ec ',$condition);
echo'
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
                                    <input type="hidden" name="view" value="course_completion_report">
                                    <select class="form-control" id="id_interest" name="id_type" data-choices>
                                        <option value="">Choose one</option>';
                                        foreach($enroll_type as $key => $value):
                                            echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
                                        endforeach;
                                        // foreach($COURSES as $key => $value):
                                        //     echo '
                                        //     <option value="'.$value['id_interest'].'" '.($value['id_interest'] == $id_interest ? 'selected' : '').'>'.$value['curs_name'].' - ('.get_enroll_type($value['type']).')</option>';
                                        // endforeach;
                                        echo '
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="hstack gap-2 justify-content-center">
                                <button type="submit" class="btn btn-primary btn-sm"><i class="ri-search-line align-bottom me-1"></i>Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>'.$reports[LMS_VIEW].'</h5>';
            if ($ENROLLED_COURSES) {
                echo'
                <div class="flex-shrink-0">
                    <button class="btn btn-danger btn-sm" onclick="print_report(\'printResult\')"><i class="ri-add-circle-line align-bottom me-1"></i>Print</button>
                    <button id="export_button" class="btn btn-success btn-sm"><i class="ri-add-circle-line align-bottom me-1"></i>Excel</button>
                </div>';
            }
            echo'
        </div>
    </div>
    <div class="card-body" id="printResult">
        <div id="header" style="display:none;">'.$reports[LMS_VIEW].' List</div>
        ';
        if ($ENROLLED_COURSES) {
            echo'
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>                        
                        <th width="40" class="text-center">No.</th>
                        <th width="200">Certificate Type</th>
                        <th>Certificate Name</th>
                        <th width="200" class="text-center">Enrollments Of Students</th>
                        <th width="200" class="text-center">Completions Of Students</th>
                    </tr>
                </thead>
                <tbody>';
                    $srno = 0;
                    foreach ($ENROLLED_COURSES as $row) {
                        $srno++;
                        echo '
                        <tr style="vertical-align: middle;">
                            <td class="text-center">'.$srno.'</td>
                            <td>'.get_offering_type($row['cert_type']).'</td>
                            <td>'.html_entity_decode(html_entity_decode($row['cert_name'])).'</td>
                            <td class="text-center">'.$row['std_count'].'</td>
                            <td class="text-center">'.$row['completion_count'].'</td>
                        </tr>';
                    }
                    echo'
                </tbody>
            </table>';
        } else {
            echo'
            <div class="noresult" style="display: block">
                <div class="text-center">
                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px">
                    </lord-icon>
                    <h5 class="mt-2">Sorry! No Record Found</h5>
                </div>
            </div>';
        }
        echo'
    </div>
</div>';
?>