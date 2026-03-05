<?php
if(!isset($_POST['date'])  ) {
    header("Location: reports.php?view=" . LMS_VIEW);
    exit();
}
$dateRange = $_POST['date'] ?? '';

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
            'select'        => 'ec.id_type, COUNT(DISTINCT ec.id_std) AS total_students, SUM(ch.total_amount) AS total_payment',
            'join'          => 'INNER JOIN '.CHALLANS.' ch ON FIND_IN_SET(ec.secs_id, ch.id_enroll) AND ch.status = 1
                                ',
            'where'         => array(
                                'ec.secs_status' => 1,
                                'ec.is_deleted' => 0
                            ),
            'search_by'     => $searchBy,
            'group_by'      => 'ec.id_type',
            'return_type'   => 'all'
        );

$summaryData = $dblms->getRows(ENROLLED_COURSES.' ec', $condition);
echo'

    <!-- TABLE -->
    <table id="printResult">
        <thead>
            <tr class="text-center">
                <th width="15%">Learning Type</th>
                <th>Total Students</th>
                <th>Total Payment</th>
            </tr>
        </thead>
        <tbody>';
            $typeSummary = [];
            if (!empty($summaryData)) {
                foreach ($summaryData as $row) {
                    $typeSummary[$row['id_type']] = $row;
                }
            }
            foreach ($enroll_type as $type) {
                $totalStudents = $typeSummary[$type['id']]['total_students'] ?? 0;
                $totalPayment  = $typeSummary[$type['id']]['total_payment'] ?? 0;

                echo '<tr class="text-center"> 
                        <td>'.$type['name'].'</td>
                        <td>'.$totalStudents.'</td>
                        <td>'.$totalPayment.'</td>
                    </tr>';
            }

            echo'
        </tbody>
    </table>


';
?>