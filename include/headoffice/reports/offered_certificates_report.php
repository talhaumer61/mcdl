<?php
// DEGREE
$condition      = [
                 'select'       =>  'd.deg_name'
                ,'join'         =>  'INNER JOIN '.ADMISSION_OFFERING.' AS ao ON ao.admoff_degree = d.deg_id'
                ,'where'        =>  [
                                        'd.deg_status'    => 1,
                                        'd.is_deleted'    => 0,
                                    ]
                ,'group_by'     =>  ' d.deg_id '
                ,'return_type'  =>  'all'
];
$DEGREE         = $dblms->getRows(DEGREE.' AS d',$condition);
// DEGREE
$condition      = [
                 'select'       =>  'mt.mas_name'
                ,'join'         =>  'INNER JOIN '.ADMISSION_OFFERING.' AS ao ON ao.admoff_degree = mt.mas_id'
                ,'where'        =>  [
                                        'mt.mas_status'    => 1,
                                        'mt.is_deleted'    => 0,
                                    ]
                ,'group_by'     =>  ' mt.mas_id '
                ,'return_type'  =>  'all'
];
$MASTER_TRACK   = $dblms->getRows(MASTER_TRACK.' AS mt',$condition);
// COURSES
$condition      = [
                 'select'       =>  'c.curs_name'
                ,'join'         =>  'INNER JOIN '.ADMISSION_OFFERING.' AS ao ON ao.admoff_degree = c.curs_id'
                ,'where'        =>  [
                                        'c.curs_status'   => 1,
                                        'c.is_deleted'    => 0,
                                    ]
                ,'group_by'     =>  ' c.curs_id '
                ,'return_type'  =>  'all'
];
$COURSES        = $dblms->getRows(COURSES.' AS c',$condition);
echo'
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>'.$reports[LMS_VIEW].'</h5>';
            if ($COURSES || $DEGREE || $MASTER_TRACK) {
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
        if ($COURSES || $DEGREE || $MASTER_TRACK) {
            echo'
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>                        
                        <th width="40" class="text-center">No.</th>
                        <th width="200">Certificate Type</th>
                        <th>Certificate Name</th>
                    </tr>
                </thead>
                <tbody>';
                    $srno = 0;
                    foreach ($DEGREE        as $row) {
                        $srno++;
                        echo '
                        <tr style="vertical-align: middle;">
                            <td class="text-center">'.$srno.'</td>
                            <td>'.get_offering_type(1).'</td>
                            <td>'.html_entity_decode(html_entity_decode($row['deg_name'])).'</td>
                        </tr>';
                    }
                    foreach ($MASTER_TRACK  as $row) {
                        $srno++;
                        echo '
                        <tr style="vertical-align: middle;">
                            <td class="text-center">'.$srno.'</td>
                            <td>'.get_offering_type(2).'</td>
                            <td>'.html_entity_decode(html_entity_decode($row['mas_name'])).'</td>
                        </tr>';
                    }
                    foreach ($COURSES       as $row) {
                        $srno++;
                        echo '
                        <tr style="vertical-align: middle;">
                            <td class="text-center">'.$srno.'</td>
                            <td>'.get_offering_type(3).'</td>
                            <td>'.html_entity_decode(html_entity_decode($row['curs_name'])).'</td>
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
    </div>';
    if ($COURSES || $DEGREE || $MASTER_TRACK) {
        echo'
        <div class="card-footer">
            <div class="d-flex align-items-center">
                <h5 class="card-title mb-0 flex-grow-1"></h5>
                <div class="flex-shrink-0">
                    <button class="btn btn-danger btn-sm" onclick="printSection(\'landscape\', \''.$reports[LMS_VIEW].'\')"><i class="ri-add-circle-line align-bottom me-1"></i>Print</button>
                    <button class="btn btn-success btn-sm"><i class="ri-add-circle-line align-bottom me-1"></i>Excel</button>
                </div>
            </div>
        </div>';
    }
    echo'
</div>';
?>