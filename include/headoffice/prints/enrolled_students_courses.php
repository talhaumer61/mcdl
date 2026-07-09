<?php
$dateRange = $_GET['date'] ?? '';

// Defaults
$fromDate = date('Y-m-01');
$toDate   = date('Y-m-t');

if (!empty($dateRange)) {

    // split by "to"
    $parts = explode('to', $dateRange);

    // assign if available
    $fromDate = !empty($parts[0]) ? $parts[0] : $fromDate;
    $toDate   = !empty($parts[1]) ? $parts[1] : $toDate;

    // if only one date selected, use same date
    if (empty($parts[1])) {
        $toDate = $fromDate;
    }
}

// Fix reverse range
if (strtotime($fromDate) > strtotime($toDate)) {
    [$fromDate, $toDate] = [$toDate, $fromDate];
}

$searchBy = " AND ec.date_added BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59'";


$condition = array(
                'select'      => 'c.curs_name AS course_name, COUNT(DISTINCT ec.id_std) AS total_students',
                'join'        => 'INNER JOIN '.COURSES.' c ON ec.id_curs = c.curs_id',
                'where'       => array(
                                    'ec.id_type' => 3,
                                    'ec.is_deleted'  => 0
                                ),
                'search_by'   => $searchBy,
                'group_by'    => 'ec.id_curs',
                'order_by'    => 'total_students DESC',
                'return_type' => 'all'
);

$trainings = $dblms->getRows(ENROLLED_COURSES.' ec', $condition);

echo '
<table id="printResult">
    <thead>
        <tr>
            <th>#</th>
            <th>Course</th>
            <th>Total Students</th>
        </tr>
    </thead>
    <tbody>';

    $sr = 1;
    foreach ($trainings as $row) {
        echo '
        <tr>
            <td>'.$sr++.'</td>
            <td>'.$row['course_name'].'</td>
            <td class="text-center">'.$row['total_students'].'</td>
        </tr>';
    }
echo '
    </tbody>
</table>';

?>