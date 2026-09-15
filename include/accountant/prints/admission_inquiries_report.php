<?php
if(empty($_POST['date']) || !isset($_POST['date']) || $_POST['date'] == '' ) {
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
    // 1. Group students by course name
    $groupedData = [];
    foreach ($students as $row) {
        $groupedData[$row['curs_name']][] = $row;
    }

    $sr = 0; // Global Serial Number
    $grandTotal = count($students);

    // Loop through each course to create a separate section
    foreach ($groupedData as $courseName => $courseStudents) {
        
        // 2. Section Header (Outside the table for a "Heading First" look)
        echo '
        <div class="mt-4">
            <h4 class="mb-3 text-center fw-bold">
                ' . $courseName . '
            </h4>

            <table id="printResult">
                <thead>
                    <tr class="text-center">
                        <th width="5%">#</th>
                        <th width="20%">Student Name</th>
                        <th width="15%">Email</th>
                        <th width="10%">Contact No</th>
                        <th width="30%">Address</th>
                        <th width="7%">Gender</th>
                        <th width="13%">Date of Enrollment</th>
                    </tr>
                </thead>
                <tbody>';

                    $courseCount = 0; 

                    foreach ($courseStudents as $row) {
                        $sr++;
                        $courseCount++;
                        echo '
                        <tr>
                            <td class="text-center">'.$sr.'</td>
                            <td>'.$row['std_name'].'</td>
                            <td>'.(!empty($row['adm_email']) ? $row['adm_email'] : "-").'</td>
                            <td class="text-center">'.(!empty($row['adm_phone']) ? $row['adm_phone'] : "-").'</td>
                            <td '.( empty($row['std_address_1'])? 'class="text-center"' : '').'>'.(!empty($row['std_address_1']) ? $row['std_address_1'] : "-").'</td>
                            <td class="text-center">'.($row['std_gender'] != 0 ? get_gendertypes($row['std_gender']) : "-").'</td>
                            <td class="text-center">'.date('d M Y', strtotime($row['date_added'])).'</td>
                        </tr>';
                    }

                    // 3. Course Sub-total Footer for this specific table
                    echo '
                    <tr>
                        <th width="5%">Total</th>
                        <th width="20%">'.$courseCount.'</th>
                        <th colspan="5"></th>
                    </tr>
                </tbody>
            </table>
        </div>';
    }

    // 4. Grand Total (Final Summary)
    echo '
    <div class="mt-4 rounded p-2" style="background: #e1dede; border: 1px solid black;">
        <h5 class="fw-bold m-0">
            Total Students: ' . $grandTotal . '
        </h5>
    </div>';

} else {
    echo '<p class="text-danger" style="text-align:center; font-weight:bold; margin-top:20px;">No Record Found!</p>';
}
?>