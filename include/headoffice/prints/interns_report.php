<?php
if( empty($_POST['date']) || !isset($_POST['date']) || $_POST['date'] == '') {
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
</style>
';
$dateRange = $_POST['date'] ?? '';

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

if (strtotime($fromDate) > strtotime($toDate)) {
    [$fromDate, $toDate] = [$toDate, $fromDate];
}

$searchBy = " AND joining_date BETWEEN '{$fromDate} 00:00:00' AND '{$toDate} 23:59:59'";


$condition = array(
            'select'    => 'id, ref_no, full_name, father_name, email, phone, cnic, id_role, status, joining_date, leaving_date',
            'where'       => array(
                                'is_deleted' => 0
                            ),
            'search_by'   => $searchBy,
            'group_by' => 'id',
            'return_type' => 'all'
        );

$students = $dblms->getRows(INTERNS. ' i', $condition);
if (!empty($students)) {

    echo '
    <table id="printResult">
        <thead>
            <tr class="text-center">
                <th>#</th>
                <th>Ref no#</th>
                <th>Name</th>
                <th>Father Name</th>
                <th width="150">Contact No</th>
                <th width="200">Email</th>
                <th>CNIC</th>
                <th>Role</th>
                <th>Status</th>
                <th>Duration</th> 
                <th>Start Date</th>
                <th>End Date</th>
                <th>Documents</th>
            </tr>
        </thead>
        <tbody>';

            $sr = 0;
            $allRoles = getInternRoles();
            $allStatuses = getInternStatus();

            foreach ($students as $row) {
                $docCondition = array(
                    'select'      => 'doc_type, doc_title',
                    'where'       => array(
                        'id_intern'  => $row['id'],
                        'is_deleted' => 0
                    ),
                    'return_type' => 'all'
                );

                $documents = $dblms->getRows(INTERN_DOCS, $docCondition);

                $documentList = '-';

                if (!empty($documents)) {
                    $docTypes = [
                        1 => 'CV',
                        2 => 'CNIC',
                        3 => 'Education Certificates',
                        4 => 'License',
                        5 => 'Other'
                    ];

                    $documentList = '<ul style="margin:0; padding-left:18px;">';

                    foreach ($documents as $doc) {

                        $documentList .= '<li><span class="fw-bold">'
                            . (isset($docTypes[$doc['doc_type']]) ? $docTypes[$doc['doc_type']] : 'N/A')
                            . '</span> - '
                            . htmlspecialchars($doc['doc_title'])
                            . '</li>';
                    }

                    $documentList .= '</ul>';
                }
                $sr++;
                
                // Get text values from the arrays
                $roleText = isset($allRoles[$row['id_role']]) ? $allRoles[$row['id_role']] : 'N/A';
                $statusText = isset($allStatuses[$row['status']]) ? $allStatuses[$row['status']] : 'N/A';

                // Calculate Duration
                $duration = '-';
                // Only calculate if status is 4 and date is valid
                if ($row['status'] == 4 && !empty($row['leaving_date']) && $row['leaving_date'] != '0000-00-00') {
                    $start = new DateTime($row['joining_date']);
                    $end = new DateTime($row['leaving_date']);
                    $diff = $start->diff($end);
                    
                    // Calculate total months: (Years * 12) + Months
                    $months = ($diff->y * 12) + $diff->m;
                    $duration = $months . ' Month' . ($months > 1 ? 's' : '');
                }

                echo '
                    <tr>
                        <td>'.$sr.'</td>
                        <td class="text-center">'.$row['ref_no'].'</td>
                        <td>'.$row['full_name'].'</td>
                        <td>'.$row['father_name'].'</td>
                        <td>'.$row['phone'].'</td>
                        <td>'.$row['email'].'</td>
                        <td>'.$row['cnic'].'</td>
                        <td>'.$roleText.'</td>
                        <td class="text-center">'.$statusText.'</td>
                        <td class="text-center">'.$duration.'</td>
                        <td class="text-center">'.$row['joining_date'].'</td>
                        <td class="text-center">'.$row['leaving_date'].'</td>
                        <td '.($documentList== '-' ? 'class="text-center"' : '').'>'.$documentList.'</td>
                    </tr>
                ';
            }

        echo '
        </tbody>
    </table>';

} else {

    echo '
    <table id="printResult">
        <tr>
            <td>
                <h4 class="text-center text-danger">No Record Found</h4>
            </td>
        </tr>
    </table>';

}
?>