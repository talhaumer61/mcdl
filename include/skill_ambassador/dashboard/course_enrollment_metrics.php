<?php
echo'
<div class="row mb-3">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Course Enrollment (Weekly, Monthly) '.date('Y').'</h4>
            </div>
            <div class="card-body">
                <canvas id="bar" class="chartjs-chart" data-colors=\'["--vz-grey", "--vz-secondary", "--vz-success", "--vz-info", "--vz-primary", "--vz-danger", "--vz-dark", "--vz-primary", "--vz-success", "--vz-secondary"]\'></canvas>
            </div>
            <script>
                var barChart, isbarchart = document.getElementById("bar");
                barChartColor = getChartColorsArray("bar"), barChartColor && (isbarchart.setAttribute("width", isbarchart.parentElement.offsetWidth), barChart = new Chart(isbarchart, {
                    type: "bar",
                    data: {
                        labels: [';
                            for ($i = 1; $i <= 12; $i++) {
                                echo "\"".get_month(($i>=10?$i:'0'.$i))."\",";
                            }
                            echo'
                        ],
                        datasets: [';
                            for ($i = 1; $i < get_WeeksInMonth(); $i++) {
                                echo'
                                {
                                    label: "Week '.$i.'",
                                    backgroundColor: barChartColor['.($i%get_WeeksInMonth()).'],
                                    borderColor: barChartColor['.($i%get_WeeksInMonth()).'],
                                    borderWidth: 1,
                                    hoverBackgroundColor: barChartColor['.($i%get_WeeksInMonth()).'],
                                    hoverBorderColor: barChartColor['.($i%get_WeeksInMonth()).'],
                                    data: [';
                                        for ($j=1; $j <= 12; $j++) {
                                            $countDate      = get_WeekStartEndDates(date('Y').'-'.($j).'-'.get_DaysInMonth($j,date('Y')), $i);
                                            $start_date     = $countDate['start_date'];
                                            $end_date       = $countDate['end_date'];
                                            
                                            // COURSE_ENROLLMENT_METRICS COUNT
                                            $condition  = [
                                                                'select'       =>  'COUNT(secs_id) AS row_count'
                                                                ,'where'        =>  [
                                                                                            'secs_status'    => 1,
                                                                                            'is_deleted'    => 0,
                                                                                            'id_org'        => $_SESSION['userlogininfo']['LOGINORGANIZATIONID'],
                                                                                    ]
                                                                ,'search_by'    =>  ' AND date_added BETWEEN "'.$start_date.'" AND "'.$end_date.'" '
                                                                ,'return_type'  =>  'single'
                                            ];
                                            $COURSE_ENROLLMENT_METRICS = $dblms->getRows(ENROLLED_COURSES,$condition);
                                            if ($COURSE_ENROLLMENT_METRICS['row_count'] != 0) {
                                                echo $COURSE_ENROLLMENT_METRICS['row_count'].",";
                                            } else {
                                                echo "0,";
                                            }
                                        }
                                        echo'
                                    ]
                                },';
                            }
                            echo'
                        ]
                    }
                }));
            </script>
        </div>
    </div>
</div>';