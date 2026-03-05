<?php
if(!isset($_POST['date']) ) {
    header("Location: reports.php?view=" . LMS_VIEW);
    exit();
}
echo '
<style>
    @page {
        size: A4 landscape;
    }
</style>
';

$dateRange = $_POST['date'] ?? '';

$searchBy = "";

if(!empty($dateRange)){
    $parts = explode('to', $dateRange);

    $fromDate = trim($parts[0]);
    $toDate   = !empty($parts[1]) ? trim($parts[1]) : $fromDate;

    $searchBy .= " AND ic.date_posted BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59'";
}


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
                ,'seach_by'     =>  $searchBy
                ,'return_type'  =>  'all'
];
$QUIZ         = $dblms->getRows(QUIZ.' AS q',$condition);

if ($QUIZ) {

    echo '
    <table id="printResult">
        <thead>
            <tr class="text-center">
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
            $totalStudents = 0;
            $totalStudents = count($QUIZ);
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

    echo '
        </tbody>
    </table>';
}
else {

    echo '
    <table>
        <tr>
            <td class="text-center text-danger fw-bold">
                No Record Found!
            </td>
        </tr>
    </table>';
}
?>