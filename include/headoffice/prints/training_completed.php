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
                'select'      => 'c.curs_name, s.std_name, s.std_address_1, std_gender, a.adm_email, a.adm_phone, ec.date_added',
                'join'        => 'INNER JOIN '.COURSES.' c ON ec.id_curs = c.curs_id
                                 INNER JOIN '.STUDENTS.' s ON ec.id_std = s.std_id
                                 INNER JOIN '.ADMINS.' a ON s.std_loginid = a.adm_id',
                'where'       => array(
                                    'ec.id_type' => 4,
                                    'ec.secs_status' => 1,
                                    'ec.is_deleted'  => 0
                                ),
                'search_by'   => $searchBy,
                'return_type' => 'all'
);

$trainings = $dblms->getRows(ENROLLED_COURSES.' ec', $condition);

echo '
<table id="printResult">
    <thead>
        <tr>
            <th>#</th>
            <th>Training</th>
            <th>Student Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Gender</th>
            <th>Date of Enrollment</th>
        </tr>
    </thead>
    <tbody>';

    $sr = 1;
    foreach ($trainings as $row) {
        echo '
        <tr>
            <td>'.$sr++.'</td>
            <td>'.$row['curs_name'].'</td>
            <td>'.$row['std_name'].'</td>
            <td>'.$row['adm_email'].'</td>
            <td '.(!empty($row['adm_phone']) ? $row['adm_phone'] : "class='text-center'").'>'.(!empty($row['adm_phone']) ? $row['adm_phone'] : "-").'</td>
            <td '.(!empty($row['std_address_1']) ? $row['std_address_1'] : "class='text-center'").'>'.(!empty($row['std_address_1']) ? $row['std_address_1'] : "-").'</td>
            <td '.(!empty($row['std_gender']) ? $row['std_gender'] : "class='text-center'").'>'.($row['std_gender'] ? get_gendertypes($row['std_gender']) : '-').'</td>
            <td>'.date('Y-m-d', strtotime($row['date_added'])).'</td>
        </tr>';
    }
echo '
    </tbody>
</table>';

?>