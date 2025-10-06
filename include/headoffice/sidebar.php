<?php
$reports = [
    'course_completion_report'          => 'Course Completion Report',
    'certificates_report'               => 'Certificates Report',
    'offered_certificates_report'       => 'Offered Certificates Report',
    'enrolled_certificates_report'      => 'Enrolled Certificates Report',
    'quiz_report'                       => 'Quiz Report',
];
echo'
<div class="app-menu navbar-menu">
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index.html" class="logo logo-dark">
            <span class="logo-sm">
                <img src="assets/images/brand/logo.png" alt="" height="50">
            </span>
            <span class="logo-lg">
                <img src="assets/images/brand/logo.png" alt="" height="40">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index.html" class="logo logo-light">
            <span class="logo-sm">
                <img src="assets/images/brand/logo.png" alt="" height="50">
            </span>
            <span class="logo-lg">
                <img src="assets/images/brand/logo.png" alt="" height="40">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>
    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="dashboard.php">
                        <i class="bx bxs-dashboard"></i> <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="challans.php">
                        <i class="ri-visa-fill"></i> <span data-key="t-skills">Challans</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="students.php">
                        <i class="ri-group-2-line"></i> <span data-key="t-skills">Students</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="departments.php">
                        <i class="ri-building-2-line"></i> <span data-key="t-departments">Departments</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarCourse" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCourse">
                        <i class="bx bx-layer"></i> <span data-key="t-course">Course</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarCourse">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item"><a href="course_categories.php?id_type=1" class="nav-link" data-key="t-coursecategories"> Categories</a></li>
                            <li class="nav-item"><a href="courses.php?id_type=1" class="nav-link" data-key="t-courses"> Courses</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebareTraining" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebareTraining">
                        <i class="bx bx-layer"></i> <span data-key="t-course">e-Training</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebareTraining">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item"><a href="course_categories.php?id_type=2" class="nav-link" data-key="t-coursecategories"> Categories</a></li>
                            <li class="nav-item"><a href="courses.php?id_type=2" class="nav-link" data-key="t-courses"> e-Trainings</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarMasterTrack" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarMasterTrack">
                        <i class="bx bx-layer"></i> <span data-key="t-course">MasterTrack</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarMasterTrack">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item"><a href="master_track_categories.php" class="nav-link" data-key="t-coursecategories"> Categories</a></li>
                            <li class="nav-item"><a href="master_track.php" class="nav-link" data-key="t-courses"> Master Track</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarProgram" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarProgram">
                        <i class="bx bx-layer"></i> <span data-key="t-program">Program</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarProgram">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item"><a href="program_categories.php" class="nav-link" data-key="t-programcategories">Categories</a></li>
                            <li class="nav-item"><a href="programs.php" class="nav-link" data-key="t-programs"> Programs</a></li>
                            <li class="nav-item"><a href="admission_programs.php" class="nav-link" data-key="t-admissionprograms">Admissions Programs</a></li>
                            <li class="nav-item"><a href="admission_offering.php" class="nav-link" data-key="t-admissionprograms">Admission Offering</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="teacher_engagement_interest.php">
                        <i class="ri-coupon-line"></i> <span data-key="t-teacher-engagement-interest">Teacher Engagement Interest</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="skill_ambassador.php">
                        <i class="ri-coupon-line"></i> <span data-key="t-skill_ambassador">Skill Ambassador</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="faculties.php">
                        <i class="ri-community-line"></i> <span data-key="t-dashboard">Faculties</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="notifications.php">
                        <i class="bx bx-bell"></i> <span data-key="t-notifications">Notifications</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="coupons.php">
                        <i class="ri-coupon-line"></i> <span data-key="t-coupons">Coupons</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="discounts.php">
                        <i class="ri-coupon-fill"></i> <span data-key="t-discounts">Discounts</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="referral_control.php">
                        <i class="ri-list-settings-line"></i> <span data-key="t-referral-control">Referral Control</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="faqs.php">
                        <i class="ri-double-quotes-l"></i> <span data-key="t-faqs">Website Faq\'s</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="reviews.php">
                        <i class="ri-double-quotes-l"></i> <span data-key="t-reviews">Reviews</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="blogs.php">
                        <i class="ri-cast-line"></i> <span data-key="t-blogs">Blogs</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarEmployees" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarEmployees">
                        <i class="ri-group-line"></i> <span data-key="t-employees">Employees</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarEmployees">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item"><a href="designations.php" class="nav-link" data-key="t-designations"> Designations</a></li>
                            <li class="nav-item"><a href="employees.php" class="nav-link" data-key="t-employees"> Employees</a></li>
                            <li class="nav-item"><a href="teacherlogin.php" class="nav-link" data-key="t-teacherlogin"> Teacher Login</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarEmployees" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarEmployees">
                        <i class="ri-file-paper-2-line"></i> <span data-key="t-employees">Reports</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarEmployees">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item"><a href="upcoming_course_interested_students.php" class="nav-link" data-key="t-designations"> Upcoming Course Interest</a></li>';
                            foreach ($reports as $key => $value) {
                                echo'
                                <li class="nav-item"><a href="reports.php?view='.$key.'" class="nav-link" data-key="t-'.$key.'"> '.$value.'</a></li>';
                            }
                            echo'
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarSettings" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSettings">
                        <i class="bx bx-share-alt"></i> <span data-key="t-settings">Settings</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarSettings">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="currencies.php" class="nav-link" data-key="t-currencies"> Currencies </a>
                            </li>
                            <li class="nav-item">
                                <a href="#sidebarArea" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarArea" data-key="t-asetting"> Area
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarArea">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="regions.php" class="nav-link" data-key="t-regions">Regions</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="countries.php" class="nav-link" data-key="t-countries">Countries</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="states.php" class="nav-link" data-key="t-states">States</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="substates.php" class="nav-link" data-key="t-substates">Sub States</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="cities.php" class="nav-link" data-key="t-cities">Cities</a>
                                        </li>
                                    </ul>
                                </div>
                            </li> 
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>';
?>