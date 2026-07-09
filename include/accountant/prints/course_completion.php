<?php
// 1. Initial Checks & Setup
if(!isset($_POST['date']) || !isset($_POST['enroll_type']) ) {
    sessionMsg("Error", "You must enter date.", "danger");
    header("Location: reports.php?view=" . LMS_VIEW);
    exit();
}

echo '
<style>
    @page {
        size: A4 landscape;
        margin: 10mm;
    }
</style>
';


$dateRange = $_POST['date'] ?? '';
$enrollType  = $_POST['enroll_type'] ?? '';
$courseID    = $_POST['course_id'] ?? '';

$fromDate = date('Y-m-01');
$toDate   = date('Y-m-t');

if (!empty($dateRange)) {
    $parts = explode(' to ', $dateRange);
    $fromDate = !empty($parts[0]) ? trim($parts[0]) : $fromDate;
    $toDate   = !empty($parts[1]) ? trim($parts[1]) : $toDate;
    if (empty($parts[1])) { $toDate = $fromDate; }
}

if (strtotime($fromDate) > strtotime($toDate)) {
    [$fromDate, $toDate] = [$toDate, $fromDate];
}


$searchBy = " AND ec.date_added BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59'";
if($enrollType){ $searchBy .= " AND ec.id_type = '$enrollType' "; }
if($courseID){ $searchBy .= " AND ec.id_curs = '$courseID' "; }

$conditions = array(
    'select'    => 'ec.secs_id, ec.id_std, ec.id_type, ec.id_curs, s.std_name, s.std_gender, c.curs_name, COUNT(DISTINCT cl.lesson_id) AS lesson_count, COUNT(DISTINCT ca.id) AS assignment_count, COUNT(DISTINCT q.quiz_id) AS quiz_count, COUNT(DISTINCT lt.track_id) AS track_count',
    'join'      => 'INNER JOIN '.STUDENTS.' s ON ec.id_std = s.std_id
                    LEFT JOIN '.COURSES.' c ON c.curs_id = ec.id_curs
                    LEFT JOIN '.COURSES_LESSONS.' cl ON cl.id_curs = ec.id_curs AND cl.lesson_status=1 AND cl.is_deleted=0
                    LEFT JOIN '.COURSES_ASSIGNMENTS.' ca ON ca.id_curs = ec.id_curs AND ca.status=1 AND ca.is_deleted=0
                    LEFT JOIN '.QUIZ.' q ON q.id_curs = ec.id_curs AND q.quiz_status=1 AND q.is_deleted=0
                    LEFT JOIN '.LECTURE_TRACKING.' lt ON lt.id_curs = ec.id_curs AND lt.id_std = ec.id_std AND lt.is_completed = 2',
    'where'     =>  array(
                        'ec.is_deleted' => 0,
                        'ec.secs_status'=> 1
                    ),
    'search_by' => $searchBy,
    'group_by'  => 'ec.secs_id',
    'return_type' => 'all'
);

$report = $dblms->getRows(ENROLLED_COURSES.' ec', $conditions);

$grouped = [];
if ($report) {
    foreach ($report as $r) {
        $grouped[$r['curs_name']][] = $r;
    }
}

if (!empty($grouped)) {
    $sr = 0; 
    $grandTotal = count($report);

    foreach ($grouped as $courseName => $rows) {
        $courseCount = 0; 
        
        echo '
        <div class="mt-4">
            <h4 class="mb-3 text-center fw-bold">' . $courseName . '</h4>
            <table id="printResult">
                <thead>
                    <tr class="text-center">
                        <th width="5%">#</th>
                        <th width="30%">Student Name</th>
                        <th width="15%">Gender</th>
                        <th width="25%">Completion Status</th>
                        <th width="25%">Progress %</th>
                    </tr>
                </thead>
                <tbody>';

                    foreach ($rows as $r) {
                        $sr++;
                        $courseCount++;

                        $total = $r['lesson_count'] + $r['assignment_count'] + $r['quiz_count'];
                        $done  = $r['track_count'];
                        $percent = ($total > 0) ? round(($done / $total) * 100) : 0;
                        if ($percent > 100) $percent = 100;
                        $status = ($percent >= 100) ? 'Completed' : 'In Process';

                        echo '
                        <tr>
                            <td class="text-center">'.$sr.'</td>
                            <td>'.$r['std_name'].'</td>
                            <td class="text-center">'.($r['std_gender'] != 0 ? get_gendertypes($r['std_gender']) : "-").'</td>
                            <td class="text-center">'.$status.'</td>
                            <td class="text-center fw-bold">'.$percent.' %</td>
                        </tr>';
                    }

                    echo '
                    <tr class="bg-light">
                        <th class="text-center">Total</th>
                        <th colspan="4">'.$courseCount.'</th>
                    </tr>
                </tbody>
            </table>
        </div>';
    }

    echo '
    <div class="mt-4 rounded p-2" style="background: #e1dede; border: 1px solid black;">
        <h5 class="fw-bold m-0">Grand Total: ' . $grandTotal . '</h5>
    </div>';

} else {
    echo '
    <div>
        <table>
            <tbody>
                <tr>
                    <td class="text-center py-4">
                        <h4 class="text-danger fw-bold m-0">No Record Found!</h4>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>';
}
?>