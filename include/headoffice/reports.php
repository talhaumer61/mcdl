<?php 
$rootDir = 'reports/';

$viewNames = [
    'registration-report'                           => 'registration_report',
    'student-individual-details-report'             => 'student_individual_details',
    'all-students-detail-report'                    => 'student_individual_details',
    'enrolled-students-trainings-report'            => 'enrolled_students_trainings',
    'enrolled-students-courses-report'              => 'enrolled_students_courses',
    'course-completion-report'                      => 'course_completion',
    'paid-challans-report'                          => 'challans_report',
    'unpaid-challans-report'                        => 'challans_report',
    'admission-inquiries-report'                    => 'admission_inquiries_report',
    'learning-wise-students-report'                 => 'learning_wise_students_report',
    'trainings-enrolled-students-report'            => 'admission_inquiries_report',
    'courses-enrolled-students-report'              => 'admission_inquiries_report',
    'skill-ambassadors-report'                      => 'skill_ambassadors',
    'individual-skill-ambassador-report'            => 'individual_skill_ambassadors',
    'unpaid-challans-yearly'                        => 'unpaid_challans_yearly',
    'unpaid-challans-monthly'                       => 'unpaid_challans_monthly',
    'total-revenue'                                 => 'total_revenue',
    'trainings-total-revenue'                       => 'type_total_revenue',
    'courses-total-revenue'                         => 'type_total_revenue',
    'specific-training-total-revenue'               => 'specific_total_revenue',
    'specific-course-total-revenue'                 => 'specific_total_revenue',
    'skill-ambassadors-total-revenue'               => 'ambassadors_total_revenue',
    'skill-ambassadors-trainings-total-revenue'     => 'ambassadors_type_total_revenue',
    'skill-ambassadors-courses-total-revenue'       => 'ambassadors_type_total_revenue',
    'skill-ambassadors-specific-training-revenue'   => 'ambassadors_specific_total_revenue',
    'skill-ambassadors-specific-course-revenue'     => 'ambassadors_specific_total_revenue',
    'upcoming-courses-interested-students'          => 'upcoming_interested_students',
    'upcoming-trainings-interested-students'        => 'upcoming_interested_students',
    'quiz-report'                                   => 'quiz_report',

];
echo' 
<title>'.moduleName(false).' - '.TITLE_HEADER.'</title>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">'.moduleName(false).' - '.(LMS_VIEW ? ucwords(str_replace('-', ' ', LMS_VIEW)) : 'List').'</h4> 
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">'.moduleName(false).'</a></li>
                            <li class="breadcrumb-item"><a href="'.moduleName().'.php?view='.LMS_VIEW.'" class="text-primary">'.(LMS_VIEW ? ucwords(str_replace('-', ' ', LMS_VIEW)) : 'List').'</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-lg-12">';

                if (isset($viewNames[LMS_VIEW])) {
                    include_once($rootDir . '/' . $viewNames[LMS_VIEW] . '.php');
                } else {
                    include_once($rootDir . '/list.php');
                }
                echo'
            </div>
        </div>
    </div>
</div>';
?>