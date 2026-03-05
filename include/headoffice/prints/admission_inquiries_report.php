<?php
if(!isset($_POST['date']) || !isset($_POST['enroll_type']) ) {
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

$fromDate = date('Y-m-01');
$toDate   = date('Y-m-t');

if (!empty($dateRange)) {

    $parts = explode('to', $dateRange);

    $fromDate = !empty($parts[0]) ? trim($parts[0]) : $fromDate;
    $toDate   = !empty($parts[1]) ? trim($parts[1]) : $toDate;

    if (empty($parts[1])) {
        $toDate = $fromDate;
    }
}

if (strtotime($fromDate) > strtotime($toDate)) {
    [$fromDate, $toDate] = [$toDate, $fromDate];
}

$type = cleanvars($_POST['enroll_type']);
$courseFilter = '';

if (!empty($_POST['course_id'])) {
    $course_id = cleanvars($_POST['course_id']);
    $courseFilter = " AND ec.id_curs = '$course_id' ";
}

$searchBy = "
    AND ec.date_added BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59'
    AND ec.id_type = '$type'
    $courseFilter
";

$condition = array(
    'select' => 's.std_name, s.std_gender, a.adm_email, a.adm_phone, s.std_address_1, ec.id_type, ec.date_added, c.curs_name',
    'join'   => 'INNER JOIN '.STUDENTS.' s ON ec.id_std = s.std_id
                 INNER JOIN '.ADMINS.' a ON s.std_loginid = a.adm_id
                 INNER JOIN '.COURSES.' c ON ec.id_curs = c.curs_id',
    'where'  => array(
        'ec.secs_status' => 1,
        'ec.is_deleted'  => 0
    ),
    'search_by'   => $searchBy,
    'return_type' => 'all'
);

$students = $dblms->getRows(ENROLLED_COURSES.' ec', $condition);

if (!empty($students)) {

    echo '
    <table id="printResult">
        <thead>
            <tr>
                <th width="40">#</th>
                <th class="text-center">'.($_POST['enroll_type'] 
                    ? get_offering_type($_POST['enroll_type']) 
                    : "All").'</th>
                <th>Student Name</th>
                <th>Email</th>
                <th class="text-center">Contact No</th>
                <th class="text-center">Address</th>
                <th class="text-center">Gender</th>
                <th class="text-center">Date of Enrollment</th>
            </tr>
        </thead>
        <tbody>';

    $sr = 0;
    $singleCourseSelected = !empty($_GET['course_id']);
    $totalStudents = count($students);

    foreach ($students as $row) {

        $sr++;

        echo '<tr>';
        echo '<td>'.$sr.'</td>';

        if ($singleCourseSelected) {
            if ($sr == 1) {
                echo "<td class='text-center' rowspan='$totalStudents'>{$row['curs_name']}</td>";
            }
        } else {
            echo "<td class='text-center'>{$row['curs_name']}</td>";
        }

        echo '
            <td>'.$row['std_name'].'</td>
            <td class="text-center">'.(!empty($row['adm_email']) ? $row['adm_email'] : "-").'</td>
            <td class="text-center">'.(!empty($row['adm_phone']) ? $row['adm_phone'] : "-").'</td>
            <td class="text-center">'.(!empty($row['std_address_1']) ? $row['std_address_1'] : "-").'</td>
            <td class="text-center">'.($row['std_gender'] != 0 ? get_gendertypes($row['std_gender']) : "-").'</td>
            <td class="text-center">'.date('Y-m-d', strtotime($row['date_added'])).'</td>
        </tr>';
    }
    echo '
        <tr >
            <th></th>
            <th class="text-center">TOTAL</th>
            <th colspan="6">'.$totalStudents.'</th>
        </tr>';

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