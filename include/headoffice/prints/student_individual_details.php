<?php
if(!isset($_POST['date'])) {
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
    $toDate   = !empty($parts[1]) ? trim($parts[1]) : $fromDate;

    if (empty($parts[1])) {
        $toDate = $fromDate;
    }
}

if (strtotime($fromDate) > strtotime($toDate)) {
    [$fromDate, $toDate] = [$toDate, $fromDate];
}

$studentFilter = '';
if (!empty($_POST['std_id'])) {
    $std_id = cleanvars($_POST['std_id']);
    $studentFilter = " AND ec.id_std = '$std_id' ";
}

$searchBy = " 
    AND ec.date_added BETWEEN '{$fromDate} 00:00:00' 
    AND '{$toDate} 23:59:59' 
    $studentFilter
";

$condition = array(
    'select'    => 's.std_id, s.std_name, s.std_gender, s.std_address_1, a.adm_email, a.adm_phone, ec.id_type, ec.date_added, c.curs_name, ch.without_discount_amount, ch.discount, ch.total_amount, ch.status, sa.org_name',
    'join'      => 'INNER JOIN '.STUDENTS.' s ON ec.id_std = s.std_id
                    INNER JOIN '.ADMINS.' a ON s.std_loginid = a.adm_id
                    INNER JOIN '.COURSES.' c ON ec.id_curs = c.curs_id
                    INNER JOIN '.CHALLANS.' ch ON FIND_IN_SET(ec.secs_id, ch.id_enroll)
                    LEFT JOIN '.SKILL_AMBASSADOR.' sa ON ec.id_org = sa.org_id',
    'where'     => array(
                    'ec.secs_status' => 1,
                    'ec.is_deleted'  => 0
                ),
    'search_by'   => $searchBy,
    'return_type' => 'all'
);

$students = $dblms->getRows(ENROLLED_COURSES.' ec', $condition);

$enrollType = array_column($enroll_type, 'name', 'id');
$fee_status = array_column($payments, 'name', 'id');

$grouped = [];
if (!empty($students)) {
    foreach ($students as $row) {
        $grouped[$row['std_id']][] = $row;
    }
}
if (!empty($grouped)) {

    echo '
    <table id="printResult">
        <thead>
            <tr>
                <th>#</th>
                <th>Student Name</th>
                <th>Email</th>
                <th class="text-center">Contact No</th>
                <th class="text-center">Gender</th>
                <th>Address</th>
                <th class="text-center">Learning Category</th>
                <th class="text-center">Learning Detail</th>
                <th class="text-center">Fee Amount</th>
                <th class="text-center">Discount</th>
                <th class="text-center">Total Amount</th>
                <th class="text-center">Fee Status</th>
                <th class="text-center">Date of Enrollment</th>
                <th class="text-center">Referral</th>
            </tr>
        </thead>
        <tbody>
    ';

    $sr = 0;

    foreach ($grouped as $std_id => $records) {

        $sr++;
        $rowspan = count($records);
        $firstRow = true;

        foreach ($records as $row) {

            echo '<tr>';

            if ($firstRow) {
                echo '
                <td rowspan="'.$rowspan.'" class="text-center">'.$sr.'</td>
                <td rowspan="'.$rowspan.'">'.$row['std_name'].'</td>
                <td rowspan="'.$rowspan.'">'.$row['adm_email'].'</td>
                <td rowspan="'.$rowspan.'" class="text-center">
                    '.(!empty($row['adm_phone']) ? $row['adm_phone'] : "-").'
                </td>
                <td rowspan="'.$rowspan.'" class="text-center">
                    '.(!empty($row['std_gender']) ? get_gendertypes($row['std_gender']) : "-").'
                </td>
                <td rowspan="'.$rowspan.'">
                    '.(!empty($row['std_address_1']) ? $row['std_address_1'] : "-").'
                </td>
                ';
                $firstRow = false;
            }

            echo '
                <td class="text-center">'.($enrollType[$row['id_type']] ?? "-").'</td>
                <td class="text-center">'.$row['curs_name'].'</td>
                <td class="text-center">'.$row['without_discount_amount'].'</td>
                <td class="text-center">'.$row['discount'].'</td>
                <td class="text-center">'.$row['total_amount'].'</td>
                <td class="text-center">'.($fee_status[$row['status']] ?? "-").'</td>
                <td class="text-center">'.date('Y-m-d', strtotime($row['date_added'])).'</td>
                <td class="text-center">'.(!empty($row['org_name']) ? $row['org_name'] : "-").'</td>
            </tr>';
        }
    }

    echo '
        </tbody>
    </table>
    ';

} else {
    echo '
    <table>
        <tr>
            <td class="text-center text-danger" style="padding:20px; font-weight:bold;">
                No record found
            </td>
        </tr>
    </table>
    ';
}
?>