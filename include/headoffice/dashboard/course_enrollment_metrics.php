<?php
$selectedYear = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

function getWeeksInMonth($month, $year) {
    $firstDay = new DateTime("$year-$month-01");
    $lastDay = new DateTime($firstDay->format('Y-m-t'));
    $weeks = [];

    $week = 1;
    while ($firstDay <= $lastDay) {
        $start = clone $firstDay;
        $end = clone $firstDay;
        $end->modify('sunday this week');
        if ($end > $lastDay) {
            $end = $lastDay;
        }

        $weeks[] = ['start' => $start->format('Y-m-d'), 'end' => $end->format('Y-m-d')];
        $firstDay->modify('monday next week');
        $week++;
    }

    return $weeks;
}

echo '
<div class="row mb-3">
    <div class="col">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Course Enrollment (Weekly, Monthly) '.$selectedYear.'</h4>
                <div class="flex-shrink-0">
                    <div class="dropdown card-header-dropdown">
                        <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="fw-semibold text-uppercase fs-12">Sort by: </span><span class="text-muted">Year<i class="mdi mdi-chevron-down ms-1"></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">';
                            for ($year = 2022; $year <= date('Y'); $year++):
                                echo '<a class="dropdown-item" href="dashboard.php?year='.$year.'">'.$year.'</a>';
                            endfor;
                        echo '</div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="bar" class="chartjs-chart" data-colors=\'["--vz-primary", "--vz-success", "--vz-info", "--vz-secondary", "--vz-danger", "--vz-dark", "--vz-warning"]\'></canvas>
            </div>

            <script>
                var barChart, isbarchart = document.getElementById("bar");
                var barChartColor = getChartColorsArray("bar");

                isbarchart.setAttribute("width", isbarchart.parentElement.offsetWidth);

                barChart = new Chart(isbarchart, {
                    type: "bar",
                    data: {
                        labels: [';
                                    for ($i = 1; $i <= 12; $i++) {
                                        echo '"'.get_month(sprintf('%02d', $i)).'",';
                                    }
                                    echo '
                                ],
                        datasets: [';

                                    $maxWeeks = 0;
                                    $enrollmentData = []; // Store [week][month] = count

                                    for ($month = 1; $month <= 12; $month++) {
                                        $weeks = getWeeksInMonth($month, $selectedYear);
                                        $maxWeeks = max($maxWeeks, count($weeks));
                                        foreach ($weeks as $w => $dates) {
                                            $start = $dates['start'];
                                            $end = $dates['end'];

                                            $condition = [
                                                'select' => 'COUNT(secs_id) AS row_count',
                                                'where' => [
                                                    'secs_status' => 1,
                                                    'is_deleted' => 0,
                                                ],
                                                'search_by' => ' AND date_added BETWEEN "'.$start.' 00:00:00.000000" AND "'.$end.' 23:59:59.999999" ',
                                                'return_type' => 'single'
                                            ];
                                            $row = $dblms->getRows(ENROLLED_COURSES, $condition, $sql);
                                            $enrollmentData[$w+1][$month] = $row['row_count'] ?? 0;
                                        }
                                    }

                                    // Fill chart datasets
                                    for ($week = 1; $week <= $maxWeeks; $week++) {
                                        echo '
                                        {
                                            label: "Week '.$week.'",
                                            backgroundColor: barChartColor['.($week % 7).'],
                                            borderColor: barChartColor['.($week % 7).'],
                                            borderWidth: 1,
                                            hoverBackgroundColor: barChartColor['.($week % 7).'],
                                            hoverBorderColor: barChartColor['.($week % 7).'],
                                            data: [';

                                        for ($month = 1; $month <= 12; $month++) {
                                            echo $enrollmentData[$week][$month] ?? 0;
                                            echo ',';
                                        }

                                        echo ']
                                        },';
                                    }
                                    echo '
                                ]
                    }
                });
            </script>
        </div>
    </div>
</div>';
?>