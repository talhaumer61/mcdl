<?php
echo'
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i>'.moduleName(false).' List</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="verti-sitemap">
                    <ul class="list-unstyled mb-0">
                        <li class="p-0 parent-title"><a href="javascript: void(0);" class="fw-semibold fs-14">Registration Reports</a></li>
                        <li class="mb-3">
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=registration-report" class="fw-medium text-primary">Registration Report</a>
                                </div>
                            </div>
                        </li>

                        <li class="p-0 parent-title"><a href="javascript: void(0);" class="fw-semibold fs-14">Admission Reports</a></li>
                        <li class="mb-3">
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=student-individual-details-report" class="fw-medium text-primary">Student Individual Details</a>
                                </div>
                            </div>
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=all-students-detail-report" class="fw-medium text-primary">All Students Detail</a>
                                </div>
                            </div>
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=admission-inquiries-report" class="fw-medium text-primary">Admission Inquiries Report (Overall)</a>
                                </div>
                            </div>
                        </li>
                        
                        <li class="p-0 parent-title"><a href="javascript: void(0);" class="fw-semibold fs-14">Learning Reports</a></li>
                        <li class="mb-3">
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=learning-wise-students-report" class="fw-medium text-primary">Learning Wise Students (Total)</a>
                                </div>
                            </div>
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=trainings-enrolled-students-report&enroll_type=4" class="fw-medium text-primary">Enrolled Students (Training-Wise)</a>
                                </div>
                            </div>
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=courses-enrolled-students-report&enroll_type=3" class="fw-medium text-primary">Enrolled Students (Course-Wise)</a>
                                </div>
                            </div>
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=trainings-enrolled-students-report&enroll_type=4" class="fw-medium text-primary">Students in Specific Training (Wise)</a>
                                </div>
                            </div>
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=courses-enrolled-students-report&enroll_type=3" class="fw-medium text-primary">Students in Specific Certificate Course (Wise)</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="col-sm-4">
                <div class="verti-sitemap">
                    <ul class="list-unstyled mb-0">
                        <li class="p-0 parent-title"><a href="javascript: void(0);" class="fw-semibold fs-14">Challans & Payment Reports</a></li>
                        <li class="mb-3">
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=paid-challans-report&pay_status=1" class="fw-medium text-primary">Paid Challans</a>
                                </div>
                            </div>
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=unpaid-challans-report&pay_status=2" class="fw-medium text-primary">Unpaid Challans</a>
                                </div>
                            </div>
                        </li>

                        <li class="p-0 parent-title"><a href="javascript: void(0);" class="fw-semibold fs-14">Course Completion Reports</a></li>
                        <li class="mb-3">
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=course-completion-report" class="fw-medium text-primary">Completion Report</a>
                                </div>
                            </div>
                        </li>

                        <li class="p-0 parent-title"><a href="javascript: void(0);" class="fw-semibold fs-14">Skill Ambassador Reports</a></li>
                        <li class="mb-3">
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=skill-ambassadors-report" class="fw-medium text-primary">Skill Ambassador Details</a>
                                </div>
                            </div>
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=individual-skill-ambassador-report" class="fw-medium text-primary">Individual Skill Ambassador Details</a>
                                </div>
                            </div>
                        </li>
                        
                        <li class="p-0 parent-title"><a href="javascript: void(0);" class="fw-semibold fs-14">Interested Students Reports</a></li>
                        <li class="mb-3">
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=upcoming-courses-interested-students&enroll_type=3" class="fw-medium text-primary">Upcoming Courses Interested Students</a>
                                </div>
                            </div>
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=upcoming-trainings-interested-students&enroll_type=4" class="fw-medium text-primary">Upcoming Trainings Interested Students</a>
                                </div>
                            </div>
                        </li>
                        <li class="p-0 parent-title"><a href="javascript: void(0);" class="fw-semibold fs-14">Quizzes</a></li>
                        <li class="mb-3">
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=quiz-report" class="fw-medium text-primary">Quiz Report</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="col-sm-4">
                <div class="verti-sitemap">
                    <ul class="list-unstyled mb-0">
                        <li class="p-0 parent-title"><a href="javascript: void(0);" class="fw-semibold fs-14">Finance Reports</a></li>
                        <li class="mb-3">
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=unpaid-challans-yearly" class="fw-medium text-primary">Total Unpaid Challans (All Learning-Yearly)</a>
                                </div>
                            </div>
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=unpaid-challans-monthly" class="fw-medium text-primary">Total Unpaid Challans (All Learning-Monthly)</a>
                                </div>
                            </div>
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=total-revenue" class="fw-medium text-primary">Total Revenue (All Learning)</a>
                                </div>
                            </div>
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=trainings-total-revenue&type=4" class="fw-medium text-primary">Total Revenue in Trainings</a>
                                </div>
                            </div>
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=courses-total-revenue&type=3" class="fw-medium text-primary">Total Revenue in Courses</a>
                                </div>
                            </div>
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=specific-training-total-revenue&type=4" class="fw-medium text-primary">Total Revenue in Specific Training</a>
                                </div>
                            </div>
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=specific-course-total-revenue&type=3" class="fw-medium text-primary">Total Revenue in Specific Courses</a>
                                </div>
                            </div>
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=skill-ambassadors-total-revenue" class="fw-medium text-primary">Skill Ambassador Revenue</a>
                                </div>
                            </div>
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=skill-ambassadors-trainings-total-revenue&type=4" class="fw-medium text-primary">Total Revenue in Trainings (by Skill Ambassador)</a>
                                </div>
                            </div>
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=skill-ambassadors-courses-total-revenue&type=3" class="fw-medium text-primary">Total Revenue in Courses (by Skill Ambassador)</a>
                                </div>
                            </div>
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=skill-ambassadors-specific-training-revenue&type=4" class="fw-medium text-primary">Total Revenue in Specific Training (by Skill Ambassador)</a>
                                </div>
                            </div>
                            <div class="first-list">
                                <div class="list-wrap">
                                    <a href="reports.php?view=skill-ambassadors-specific-course-revenue&type=3" class="fw-medium text-primary">Total Revenue in Specific Courses (by Skill Ambassador)</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>';
?>