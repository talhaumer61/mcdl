<?php 
$rootDir = 'prints/';

$viewNames = [
    'registration-report'          => 'registration_report',
    'student-individual-details-report'   => 'student_individual_details',
    'all-students-detail-report'   => 'student_individual_details',
    'course-completion-report'      => 'course_completion',
    'paid-challans-report'         => 'challans_report',
    'unpaid-challans-report'         => 'challans_report',
    'admission-inquiries-report'   => 'admission_inquiries_report',
    'learning-wise-students-report'   => 'learning_wise_students_report',
    'trainings-enrolled-students-report'  => 'admission_inquiries_report',
    'courses-enrolled-students-report'  => 'admission_inquiries_report',
    'skill-ambassadors-report'   => 'skill_ambassadors',
    'individual-skill-ambassador-report'   => 'individual_skill_ambassadors',
    'unpaid-challans-yearly'   => 'unpaid_challans_yearly',
    'unpaid-challans-monthly'   => 'unpaid_challans_monthly',
    'total-revenue'   => 'total_revenue',
    'trainings-total-revenue'   => 'type_total_revenue',
    'courses-total-revenue'   => 'type_total_revenue',
    'specific-training-total-revenue'   => 'specific_total_revenue',
    'specific-course-total-revenue'   => 'specific_total_revenue',
    'skill-ambassadors-total-revenue'   => 'ambassadors_total_revenue',
    'skill-ambassadors-trainings-total-revenue'   => 'ambassadors_type_total_revenue',
    'skill-ambassadors-courses-total-revenue'   => 'ambassadors_type_total_revenue',
    'skill-ambassadors-specific-training-revenue'   => 'ambassadors_specific_total_revenue',
    'skill-ambassadors-specific-course-revenue'   => 'ambassadors_specific_total_revenue',
    'upcoming-courses-interested-students'   => 'upcoming_interested_students',
    'upcoming-trainings-interested-students'   => 'upcoming_interested_students',
    'quiz-report'   => 'quiz_report',
];

$view = $_GET['view'] ?? '';

echo'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.8/css/bootstrap.min.css" integrity="sha512-2bBQCjcnw658Lho4nlXJcc6WkV/UxpE/sAokbXPxQNGqmNdQrWqtw26Ns9kFF/yG792pKR1Sx8/Y1Lf1XN4GKA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.8/js/bootstrap.min.js"></script>
    <title>'.moduleName(LMS_VIEW).'</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 35px;
        }

        .print-actions {
            text-align: right;
            margin-bottom: 15px;
        }

        .btn {
            padding: 6px 12px;
            font-size: 12px;
            cursor: pointer;
        }

        h3 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #e1dede;
            font-weight: bold;
        }

        @media print {
            .print-actions {
                display: none !important;
            }
                
        }
    </style>
</head>
<body>

    <!-- ACTION BUTTONS -->
    <div class="print-actions">
        <button class="btn btn-outline-danger" onclick="window.print()">🖨 Print</button>
        <button class="btn btn-outline-success" id="export_button">📥 Export to Excel</button>
    </div>';

    $dateRange = $_POST['date'] ?? '';

    $fromDate = date('Y-m-01');
    $toDate   = date('Y-m-t');

    if ($dateRange !== '') {
        $parts = explode('to', $dateRange);

        $fromDate = $parts[0] ?? $fromDate;
        $toDate   = $parts[1] ?? $fromDate;

        if (empty($parts[1])) {
            $toDate = $fromDate;
        }
    }
    if (strtotime($fromDate) > strtotime($toDate)) {
        [$fromDate, $toDate] = [$toDate, $fromDate];
    }

    echo'
    <!-- REPORT HEADER -->
    <div class="report-header d-flex align-items-center p-3 mb-0 border-bottom-0" style="border:1px solid black;">
        <!-- Logo -->
        <div class="logo col-md-4">
            <img src="assets/images/brand/logo.png" alt="Logo" height="50">
        </div>

        <!-- Center Title -->
        <div class="report-title text-center col-md-4">
            <h5 class="fw-bold m-0">'.SITE_NAME.'</h5>
            <h5 class="m-0">';
            if(LMS_VIEW == 'challans-report') {
                $payment = [
                    '1' => ['name' => 'Paid'],
                    '2' => ['name' => 'Unpaid']
                ];
                echo $payment[$_GET['pay_status']]['name'].' Challans Report';
            } 
            elseif (LMS_VIEW == 'admission-inquiries-report' && isset($_GET['enroll_type'])) {
                foreach ($enroll_type as  $type) {
                    if($type['id'] == $_GET['enroll_type']) {
                        echo 'Enrolled Students ('. $type['name'] .')';
                    }
                }
            }
            else {
                echo moduleName(LMS_VIEW);
            }
            echo'</h5>
        </div>';

        if (!empty($_POST['date'])): 
            echo'
            <div class="report-dates text-end" style="flex:1; font-size:12px;">
                <strong>From:</strong> '.(!empty($fromDate) ? $fromDate : date('Y-m-01')).'<br>
                <strong>To:</strong> '.(!empty($toDate) ? $toDate : date('Y-m-t')).'
            </div>';
        endif;
        echo'
    </div>
    ';
    if (isset($viewNames[LMS_VIEW])) {
        include_once($rootDir . '/' . $viewNames[LMS_VIEW] . '.php');
    } else {
        header('Location: reports.php');
        exit;
    };
echo '
</body>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script>

    // EXPORT TO EXCEL
    function html_table_to_excel(type){
        var data = document.getElementById("printResult");
        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
        XLSX.write(file, { bookType: type, bookSST: true, type: "base64" });
        XLSX.writeFile(file, "'.LMS_VIEW.'." + type);
    }

    const export_button = document.getElementById("export_button");
    export_button.addEventListener("click", () =>  {
        html_table_to_excel("xlsx");
    });
</script>
</html>';
?>