<?php
if(!isset($_POST['date']) ) {
    sessionMsg("Error", "You must enter date.", "danger");
    header("Location: reports.php?view=" . LMS_VIEW);
    exit();
}
echo '
<style>
    @page {
        size: A4 landscape;
    }
</style>
';
$dateRange = $_POST['date'] ?? '';

$searchBy = "";

// date
if(!empty($dateRange)){
    $parts = explode('to', $dateRange);
    $fromDate = trim($parts[0]);
    $toDate   = !empty($parts[1]) ? trim($parts[1]) : $fromDate;
    $searchBy .= " AND dated BETWEEN '{$fromDate}' AND '{$toDate}'";
}

// status
$status = $_POST['status'] ?? '';
if(!empty($status)){    
    $searchBy .= " AND status = '".$status."' ";
}

// type
$type = $_POST['type'] ?? '';
if(!empty($type)){    
    $searchBy .= " AND type = '".$type."' ";
}

// source
$source = $_POST['source'] ?? '';
if(!empty($source)){    
    $searchBy .= " AND source = '".$source."' ";
}

$condition = array(
                     'select'       =>  'id, inquiry_no, status, type, source, fullname, fathername, phone, dated, remarks'
                    ,'where'        =>  array(
                                                'is_deleted'  => 0
                                            )
                    ,'search_by'    =>  ''.$searchBy.''
                    ,'return_type'  =>  'all'
                );
$INQUIRIES = $dblms->getRows(INQUIRIES, $condition);

if ($INQUIRIES) {

    echo '
    <table id="printResult">
        <thead>
            <tr class="text-center">
                <th width="40" class="text-center">Sr.</th>
                <th>Inquiry No</th>
                <th>Name</th>
                <th>Father Name</th>
                <th class="text-center" width="150">Phone</th>
                <th class="text-center" width="150">Type</th>
                <th class="text-center" width="150">Source</th>
                <th class="text-center" width="150">Status</th>
                <th class="text-center" width="150">Date</th>
            </tr>
        </thead>
        <tbody>';
            $totalStudents = 0;
            $srno = 0;
            foreach ($INQUIRIES as $row) {
                $srno++;
                echo '
                <tr style="vertical-align: middle;">
                    <td class="text-center">'.$srno.'</td>
                    <td>'.$row['inquiry_no'].'</td>
                    <td>'.$row['fullname'].'</td>
                    <td>'.$row['fathername'].'</td>
                    <td class="text-center">'.$row['phone'].'</td>
                    <td class="text-center">'.get_inquiry_type($row['type']).'</td>
                    <td class="text-center">'.get_inquiry_source($row['source']).'</td>
                    <td class="text-center">'.get_inquiry_status_offbadge($row['status']).'</td>
                    <td class="text-center">'.date('d M, Y', strtotime($row['dated'])).'</td>
                </tr>';
            }

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