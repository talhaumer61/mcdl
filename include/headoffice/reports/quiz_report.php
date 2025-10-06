<?php
// QUIZ
$condition      = [
                 'select'       =>  'q.quiz_title, q.quiz_no_qns,q.quiz_totalmarks, q.quiz_passingmarks, COUNT(qs.qzstd_id) AS std_attempted_count, 
                                     COUNT(CASE WHEN qs.qzstd_pass_fail = 1 THEN 1 END) AS std_pass_count,
                                     COUNT(CASE WHEN qs.qzstd_pass_fail = 0 THEN 1 END) AS std_fail_count'
                ,'join'         =>  'INNER JOIN '.QUIZ_STUDENTS.' AS qs ON qs.id_quiz = q.quiz_id
                                     INNER JOIN '.QUIZ_STUDENT_DETAILS.' AS qsd ON qsd.id_qzstd = qs.qzstd_id'
                ,'where'        =>  [
                                        'q.quiz_status' => 1,
                                        'q.is_deleted'  => 0,
                                    ]
                ,'group_by'     =>  ' q.quiz_id '
                ,'return_type'  =>  'all'
];
$QUIZ         = $dblms->getRows(QUIZ.' AS q',$condition);
echo'
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center"> 
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>'.$reports[LMS_VIEW].'</h5>';
            if ($QUIZ) {
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
        <div id="header" style="display:none;">'.$reports[LMS_VIEW].' List</div>';
        if ($QUIZ) {
            echo'
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>                        
                        <th width="40" class="text-center">No.</th>
                        <th>Quiz Name</th>
                        <th class="text-center" width="150">Total Marks</th>
                        <th class="text-center" width="150">Passing Marks</th>
                        <th class="text-center" width="150">Attempted Quiz</th>
                        <th class="text-center" width="150">Pass</th>
                        <th class="text-center" width="150">Fail</th>
                    </tr>
                </thead>
                <tbody>';
                    $srno = 0;
                    foreach ($QUIZ       as $row) {
                        $srno++;
                        echo '
                        <tr style="vertical-align: middle;">
                            <td class="text-center">'.$srno.'</td>
                            <td>'.html_entity_decode(html_entity_decode($row['quiz_title'])).'</td>
                            <td class="text-center">'.$row['quiz_totalmarks'].'</td>
                            <td class="text-center">'.$row['quiz_passingmarks'].'</td>
                            <td class="text-center">'.$row['std_attempted_count'].'</td>
                            <td class="text-center">';
                                echo'
                                '.$row['std_pass_count'].'
                            </td>
                            <td class="text-center">';
                                echo'
                                '.$row['std_fail_count'].'
                            </td>
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
    // if ($QUIZ) {
    //     echo'
    //     <div class="card-footer">
    //         <div class="d-flex align-items-center">
    //             <h5 class="card-title mb-0 flex-grow-1"></h5>
    //             <div class="flex-shrink-0">
    //                 <button class="btn btn-danger btn-sm" onclick="printSection(\'landscape\', \''.$reports[LMS_VIEW].'\')"><i class="ri-add-circle-line align-bottom me-1"></i>Print</button>
    //                 <button class="btn btn-success btn-sm"><i class="ri-add-circle-line align-bottom me-1"></i>Excel</button>
    //             </div>
    //         </div>
    //     </div>';
    // }
    echo'
</div>';
?>