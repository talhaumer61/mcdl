<?php
echo' 
<div class="row mb-3">
    <div class="col-md-12">
        <div class="row mb-3">';
            foreach (get_offering_type() as $key => $value) {
                echo'
                <div class="col-xl-4 col-md-4 mb-3">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-bold text-muted text-truncate mb-0">';
                                        if($key == 1){
                                            echo'<a href="programs.php">'.$value.'</a>';
                                        } else if ($key == 2) {
                                            echo'<a href="master_track.php">'.$value.'</a>';
                                        } else if ($key == 3) {
                                            echo'<a href="courses.php?id_type=1">'.$value.'</a>';
                                        } else if ($key == 4) {
                                            echo'<a href="courses.php?id_type=2">'.$value.'</a>';
                                        }
                                        echo'
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="'.($key == 1?$PROGRAMS['prg_count']:($key == 2?$MASTER_TRACK['mas_count']:($key == 3?$COURSES['course']:($key == 4?$COURSES['etraning']:'')))).'"></span></h4>                                        
                                    <a href="javascript: void(0);" class="text-decoration-underline text-muted">
                                        Total '.$value.'s
                                    </a>
                                </div>
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="'.($key == 1?$LEARNERS['prg']:($key == 2?$LEARNERS['mas']:($key == 3?$LEARNERS['curs']:($key == 4?$LEARNERS['training']:'')))).'"></span></h4>
                                    <a href="javascript: void(0);" class="text-decoration-underline text-muted">
                                        Total Enrollments
                                    </a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">';
                                    if ($key == 1) {
                                        echo'
                                        <span class="avatar-title bg-soft-primary rounded fs-3"><i class="ri-book-3-line text-primary"></i></span>';
                                    } elseif ($key == 2) {
                                        echo'
                                        <span class="avatar-title bg-soft-success rounded fs-3"><i class="ri-book-open-fill text-success"></i></span>';
                                    } elseif ($key == 3) {
                                        echo'
                                        <span class="avatar-title bg-soft-warning rounded fs-3"><i class="ri-book-open-line text-warning"></i></span>';
                                    } elseif ($key == 4) {
                                        echo'
                                        <span class="avatar-title bg-soft-danger rounded fs-3"><i class="lab la-elementor text-danger"></i></span>';
                                    }
                                    echo'
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
            }
            echo'
            <div class="col-xl-4 col-md-4 mb-3">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-bold text-muted text-truncate mb-0">Referral</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="'.$STUDENTS_GENDER['ref_signup'].'"></span></h4>
                                <a href="javascript: void(0);" class="text-decoration-underline text-muted">
                                    Total Sign Up
                                </a>
                            </div>
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="'.$LEARNERS['ref_enrollments'].'"></span></h4>
                                <a href="javascript: void(0);" class="text-decoration-underline text-muted">
                                    Total Enrollments
                                </a>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-secondary rounded fs-3"><i class="bx bx-selection text-secondary"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-4 mb-3">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-bold text-muted text-truncate mb-0">Summary</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="'.$STUDENTS_GENDER['total_signup'].'"></span></h4>
                                <a href="javascript: void(0);" class="text-decoration-underline text-muted">
                                    Total Sign Up
                                </a>
                            </div>
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="'.$LEARNERS['total'].'"></span></h4>
                                <a href="javascript: void(0);" class="text-decoration-underline text-muted">
                                    Total Enrollments
                                </a>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-info rounded fs-3"><i class="ri-article-line text-info"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Enrollment & Completion</h4>
                        <!--
                        <div class="flex-shrink-0">
                            <div class="dropdown card-header-dropdown">
                                <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="text-muted">Report<i class="mdi mdi-chevron-down ms-1"></i></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Download Report</a>
                                    <a class="dropdown-item" href="#">Export</a>
                                    <a class="dropdown-item" href="#">Import</a>
                                </div>
                            </div>
                        </div>
                        -->
                    </div>
                    <div class="card-body">
                        <div id="enrollment-and-completion" data-colors=\'["--vz-warning", "--vz-danger"]\' class="apex-charts" dir="ltr"></div>
                    </div>
                    <script>
                        var chartRadialbarMultipleColors = getChartColorsArray("enrollment-and-completion");
                        chartRadialbarMultipleColors && (options = {
                            series: ['.$LEARNERS['total'].','.$Curs_Completion.'],
                            chart: {
                                height: 350,
                                type: "radialBar"
                            },
                            plotOptions: {
                                radialBar: {
                                    dataLabels: {
                                        name: {
                                            fontSize: "22px"
                                        },
                                        value: {
                                            fontSize: "16px",
                                            formatter: function(val) {
                                                return val;
                                            }
                                        },
                                        total: {
                                            show: !0,
                                            label: "Completion",
                                            formatter: function(r) {
                                                return '.$Curs_Completion.'
                                            }
                                        }
                                    }
                                }
                            },
                            labels: [ "Enrollment","Completion"],
                            colors: chartRadialbarMultipleColors
                        }, (chart = new ApexCharts(document.querySelector("#enrollment-and-completion"), options)).render());
                    </script>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Gender Count</h4>
                    </div>
                    <div class="card-body">
                        <div id="multiple_radialbar" data-colors=\'["--vz-primary", "--vz-success", "--vz-warning", "--vz-danger"]\' class="apex-charts" dir="ltr"></div>
                    </div>
                    <script>
                        var chartRadialbarMultipleColors = getChartColorsArray("multiple_radialbar");
                        chartRadialbarMultipleColors && (options = {
                            series: ['.$STUDENTS_GENDER['male_count'].','.$STUDENTS_GENDER['female_count'].','.$STUDENTS_GENDER['other_count'].'],
                            chart: {
                                height: 350,
                                type: "radialBar"
                            },
                            plotOptions: {
                                radialBar: {
                                    dataLabels: {
                                        name: {
                                            fontSize: "22px"
                                        },
                                        value: {
                                            fontSize: "16px",
                                            formatter: function(val) {
                                                return val;
                                            }
                                        },
                                        total: {
                                            show: !0,
                                            label: "Others",
                                            formatter: function(r) {
                                                return '.$STUDENTS_GENDER['other_count'].'
                                            }
                                        }
                                    }
                                }
                            },
                            labels: [';
                                foreach (get_gendertypes() as $key => $value) {
                                    echo'"'.$value.'",';
                                }
                                echo'
                            ],
                            colors: chartRadialbarMultipleColors
                        }, (chart = new ApexCharts(document.querySelector("#multiple_radialbar"), options)).render());
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>';