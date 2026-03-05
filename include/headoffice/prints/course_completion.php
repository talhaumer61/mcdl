<?php
$dateRange = $_POST['date'] ?? '';
$enrollType  = $_POST['enroll_type'] ?? '';
$courseID    = $_POST['course_id'] ?? '';

$fromDate = date('Y-m-01');
$toDate   = date('Y-m-t');

if (!empty($dateRange)) {

    $parts = explode('to', $dateRange);

    $fromDate = !empty($parts[0]) ? $parts[0] : $fromDate;
    $toDate   = !empty($parts[1]) ? $parts[1] : $toDate;

    if (empty($parts[1])) {
        $toDate = $fromDate;
    }
}

// Fix reverse range
if (strtotime($fromDate) > strtotime($toDate)) {
    [$fromDate, $toDate] = [$toDate, $fromDate];
}

$searchBy = " AND ec.date_added BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59'";

if($enrollType){
    $searchBy .= " AND ec.id_type = '$enrollType' ";
}

if($courseID){
    $searchBy .= " AND ec.id_curs = '$courseID' ";
}

$conditions = array(
                    'select'   => 'ec.secs_id, ec.id_std, ec.id_type, ec.id_curs, s.std_name, s.std_gender, c.curs_name, COUNT(DISTINCT cl.lesson_id) AS lesson_count, COUNT(DISTINCT ca.id) AS assignment_count, COUNT(DISTINCT q.quiz_id) AS quiz_count, COUNT(DISTINCT lt.track_id) AS track_count',

                    'join'      => 'INNER JOIN '.STUDENTS.' s ON ec.id_std = s.std_id
                                    LEFT JOIN '.COURSES.' c ON c.curs_id = ec.id_curs
                                    LEFT JOIN '.COURSES_LESSONS.' cl ON cl.id_curs = ec.id_curs AND cl.lesson_status=1 AND cl.is_deleted=0
                                    LEFT JOIN '.COURSES_ASSIGNMENTS.' ca ON ca.id_curs = ec.id_curs AND ca.status=1 AND ca.is_deleted=0
                                    LEFT JOIN '.QUIZ.' q ON q.id_curs = ec.id_curs AND q.quiz_status=1 AND q.is_deleted=0
                                    LEFT JOIN '.LECTURE_TRACKING.' lt ON lt.id_curs = ec.id_curs AND lt.id_std = ec.id_std AND lt.is_completed = 2',

                    'where' => array(
                        'ec.is_deleted' => 0,
                        'ec.secs_status'=> 1
                    ),

                    'search_by' => $searchBy,
                    'group_by'  => 'ec.secs_id',
                    'return_type' => 'all'
            );

$report = $dblms->getRows(ENROLLED_COURSES.' ec',$conditions);

$grouped = [];
if ($report) {
    foreach ($report as $r) {
        $grouped[$r['curs_name']][] = $r;
    }
}

echo '<table id="printResult">';
if (!empty($grouped)) {
    echo '
    <thead>
        <tr>
            <th width="25%" class="text-center">Course Name</th>
            <th width="20%">Student Name</th>
            <th>Gender</th>
            <th width="10%">Completion Status</th>
            <th width="10%">Progress %</th>
        </tr>
    </thead>
    <tbody>';

    $grandTotal = 0;
    foreach ($grouped as $courseName => $rows) {
        $rowspan = count($rows);
        $first = true;
        $grandTotal += $rowspan;

        foreach ($rows as $r) {
            $total = $r['lesson_count'] + $r['assignment_count'] + $r['quiz_count'];
            $done  = $r['track_count'];

            $percent = ($total > 0) ? round(($done / $total) * 100) : 0;
            if ($percent > 100) $percent = 100;

            $status = ($percent >= 100) ? 'Completed' : 'In Process';

            echo '<tr>';
            if ($first) {
                echo '<td rowspan="'.$rowspan.'" style="vertical-align:middle;font-weight:bold; text-align:center;">'.$courseName.'</td>';
                $first = false;
            }
            echo'
                <td>'.$r['std_name'].'</td>
                <td>'.($r['std_gender'] != 0 ? get_gendertypes($r['std_gender']) : "-").'</td>
                <td class="text-center">'.$status.'</td>
                <td>'.$percent.' %</td>
            </tr>';
        }
    }

    echo '
    <tr class="bg-light">
        <td class="fw-bold text-center">Total</td>
        <td colspan="4" class="fw-bold">'.$grandTotal.'</td>
    </tr>';

} else {
    // SHOW THIS IF NO RECORDS FOUND
    echo '
    <tbody>
        <tr>
            <td colspan="5" class="text-center">
                <h5 class="mt-2 text-danger">No Record Found</h5>
            </td>
        </tr>
    </tbody>';
}

echo '</table>';
?>