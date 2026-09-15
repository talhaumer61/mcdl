<?php
if(empty($_POST['date']) || !isset($_POST['date']) || $_POST['date'] == '' ) {
    sessionMsg("Error", "You must enter a date.", "danger");
    header("Location: reports.php?view=" . LMS_VIEW);
    exit();
}

echo '
<style>
@page {
    size: A4 landscape;
    margin: 10mm;
}
.student-section { margin-top: 30px; page-break-inside: avoid; }
.summary-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
.summary-table th, .summary-table td { border: 1px solid #000; padding: 8px; }
.std-heading { font-weight: bold; font-size: 16px; text-align: center; }
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
    'select'      => 's.std_id, s.std_name, s.std_gender, s.std_address_1, a.adm_email, a.adm_phone, ec.id_type, ec.date_added, c.curs_name, ch.without_discount_amount, ch.discount, ch.total_amount, ch.status, sa.org_name',
    'join'        => 'INNER JOIN '.STUDENTS.' s ON ec.id_std = s.std_id
                      INNER JOIN '.ADMINS.' a ON s.std_loginid = a.adm_id
                      INNER JOIN '.COURSES.' c ON ec.id_curs = c.curs_id
                      INNER JOIN '.CHALLANS.' ch ON FIND_IN_SET(ec.secs_id, ch.id_enroll)
                      LEFT JOIN '.SKILL_AMBASSADOR.' sa ON ec.id_org = sa.org_id',
    'where'       => array(
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
    $i=0;
    foreach ($grouped as $std_id => $records) {
        $info = $records[0];
        $i++;
        if($i > 1) {
            echo '<div class="page-break"></div>';
        }
        echo '
        <div class="student-section">
            <table class="summary-table">
                <thead>
                    <tr>
                        <th colspan="2" class="std-heading">'.$info['std_name'].'</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="50%"><strong>Email:</strong> '.$info['adm_email'].'</td>
                        <td width="50%"><strong>Contact:</strong> '.(!empty($info['adm_phone']) ? $info['adm_phone'] : "-").'</td>
                    </tr>
                    <tr>
                        <td><strong>Gender:</strong> '.(!empty($info['std_gender']) ? get_gendertypes($info['std_gender']) : "-").'</td>
                        <td><strong>Address:</strong> '.(!empty($info['std_address_1']) ? html_entity_decode(html_entity_decode($info['std_address_1'])) : "-").'</td>
                    </tr>
                </tbody>
            </table>
            <table id="printResult" style="width:100%; margin-bottom: 20px;">
                <thead>
                    <tr>
                        <th class="text-center">Category</th>
                        <th>Course Name</th>
                        <th class="text-center">Fee</th>
                        <th class="text-center">Discount</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Enrollment Date</th>
                        <th class="text-center">Referral</th>
                    </tr>
                </thead>
                <tbody>';
                    foreach ($records as $row) {
                        echo '
                        <tr>
                            <td class="text-center">'.($enrollType[$row['id_type']] ?? "-").'</td>
                            <td>'.$row['curs_name'].'</td>
                            <td class="text-center">'.number_format($row['without_discount_amount']).'</td>
                            <td class="text-center">'.number_format($row['discount']).'</td>
                            <td class="text-center">'.number_format($row['total_amount']).'</td>
                            <td class="text-center">'.($fee_status[$row['status']] ?? "-").'</td>
                            <td class="text-center">'.date('Y-m-d', strtotime($row['date_added'])).'</td>
                            <td class="text-center">'.(!empty($row['org_name']) ? $row['org_name'] : "-").'</td>
                        </tr>';
                    }
                    echo '
                </tbody>
            </table>
        </div>';
    }

} else {
    echo '
    <table>
        <tr>
            <td class="text-center text-danger" style="padding:20px; font-size:16px; font-weight:bold;">
                No record found
            </td>
        </tr>
    </table>';
}
?>