<?php
$revenueYears = isset($_GET['year']) ? intval($_GET['year']) : date("Y");
$condition  = [
    'select'       =>  'SUM(total_amount) AS total_amount, SUM(paid_amount) AS paid_amount',
    'where'        =>  [
        'status'        => 1,
        'is_deleted'    => 0,
    ],
    'search_by'    =>  ' AND paid_date LIKE "%'.$revenueYears.'%" ',
    'return_type'  =>  'single'
];
$CHALLANS = $dblms->getRows(CHALLANS,$condition, $sql);

$currentMonth = date("Y-m");
$monthCondition  = [
    'select'       =>  'SUM(total_amount) AS total_amount',
    'where'        =>  [
        'status'        => 1,
        'is_deleted'    => 0,
    ],
    'search_by'    =>  ' AND paid_date LIKE "%' . $currentMonth . '%" ',
    'return_type'  =>  'single'
];
$MONTH_CHALLANS = $dblms->getRows(CHALLANS, $monthCondition, $sql);

// $conditionByType = [
//     'select'        => 'ec.id_type, SUM(c.paid_amount) AS total_paid',
//     'join'          => 'INNER JOIN '.ENROLLED_COURSES.' ec ON ec.secs_id = c.id_enroll',
//     'where'         => [
//         'c.status'     => 1,
//         'c.is_deleted' => 0,
//     ],
//     'search_by'     => ' AND c.paid_date LIKE "%' . $revenueYears . '%" ',
//     'group_by'      => 'ec.id_type',
//     'return_type'   => 'all'
// ];
// $TYPE_TOTALS = $dblms->getRows(CHALLANS.' c' , $conditionByType);

echo '<div class="row mb-3">
    <div class="col">
        <div class="card card-height-100">
            <div class="card-header border-0 align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Revenue - '.$revenueYears.'</h4>
                <div class="flex-shrink-0">
                    <div class="dropdown card-header-dropdown">
                        <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="fw-semibold text-uppercase fs-12">Sort by: </span><span class="text-muted">Year <i class="mdi mdi-chevron-down ms-1"></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">';
                            for ($year = date('Y') - 3; $year <= date('Y'); $year++) {
                                echo '<a class="dropdown-item" href="?year='.$year.'">'.$year.'</a>';
                            }
                        echo '</div>
                    </div>
                </div>
            </div>
            <div class="card-header p-0 border-0 bg-soft-light">';
            /*
            echo'
                <div class="row g-0 text-center">';
                foreach ($TYPE_TOTALS as $row) {
                    echo '<div class="col-6 col-sm-6">
                        <div class="p-3 border border-dashed border-start-0">
                            <h5 class="mb-1">
                                <span class="counter-value" data-target="'.intval($row['total_paid']).'">0</span>
                            </h5>
                            <p class="text-muted mb-0">'.get_enroll_type($row['id_type']).'</p>
                        </div>
                    </div>';
                }
                echo '</div>';
            */
                echo'
                <div class="row g-0 text-center">
                    <div class="col-6 col-sm-6">
                        <div class="p-3 border border-dashed border-start-0">
                            <h5 class="mb-1">
                                <span class="counter-value" data-target="'.intval($MONTH_CHALLANS['total_amount']).'">0</span>
                            </h5>
                            <p class="text-muted mb-0">Current Month Total</p>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6">
                        <div class="p-3 border border-dashed border-start-0 border-end-0">
                            <h5 class="mb-1">
                                <span class="counter-value" data-target="'.intval($CHALLANS['paid_amount']).'">0</span>
                            </h5>
                            <p class="text-muted mb-0">Current Year Total</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0 pb-2">
                <div>
                    <div id="revenue_matrics" data-colors=\'["--vz-success", "--vz-gray-300", "--vz-warning", "--vz-danger"]\' class="apex-charts" dir="ltr"></div>
                </div>
                <script>
                    var columnoptions, chartAudienceColumnChartsColors = getChartColorsArray("revenue_matrics");
                    chartAudienceColumnChartsColors && (columnoptions = {
                        series: [
                            {
                                name: "Degree",
                                data: [';
                                    for ($i=1;$i<=12;$i++) {
                                        $month = ($i<=9 ? '0' : '') . $i;
                                        $condition  = [
                                            'select'       =>  'SUM(c.paid_amount) AS total',
                                            'join'         =>  'INNER JOIN '.ENROLLED_COURSES.' ec ON ec.secs_id = c.id_enroll',
                                            'where'        =>  [
                                                'c.status'     => 1,
                                                'c.is_deleted' => 0,
                                                'ec.id_type'   => 1
                                            ],
                                            'search_by'    =>  ' AND c.paid_date LIKE "'.$revenueYears.'-'.$month.'%" ',
                                            'return_type'  =>  'single'
                                        ];
                                        $data = $dblms->getRows(CHALLANS.' c', $condition);
                                        echo intval($data['total']).',';
                                    }
                                    echo ']
                            },
                            {
                                name: "Mater Track",
                                data: [';
                                    for ($i=1;$i<=12;$i++) {
                                        $month = ($i<=9 ? '0' : '') . $i;
                                        $condition  = [
                                            'select'       =>  'SUM(c.paid_amount) AS total',
                                            'join'         =>  'INNER JOIN '.ENROLLED_COURSES.' ec ON ec.secs_id = c.id_enroll',
                                            'where'        =>  [
                                                'c.status'     => 1,
                                                'c.is_deleted' => 0,
                                                'ec.id_type'   => 2
                                            ],
                                            'search_by'    =>  ' AND c.paid_date LIKE "'.$revenueYears.'-'.$month.'%" ',
                                            'return_type'  =>  'single'
                                        ];
                                        $data = $dblms->getRows(CHALLANS.' c', $condition);
                                        echo intval($data['total']).',';
                                    }
                                    echo ']
                            },
                            {
                                name: "Course",
                                data: [';
                                    for ($i=1;$i<=12;$i++) {
                                        $month = ($i<=9 ? '0' : '') . $i;
                                        $condition  = [
                                            'select'       =>  'SUM(c.paid_amount) AS total',
                                            'join'         =>  'INNER JOIN '.ENROLLED_COURSES.' ec ON ec.secs_id = c.id_enroll',
                                            'where'        =>  [
                                                'c.status'     => 1,
                                                'c.is_deleted' => 0,
                                                'ec.id_type'   => 3
                                            ],
                                            'search_by'    =>  ' AND c.paid_date LIKE "'.$revenueYears.'-'.$month.'%" ',
                                            'return_type'  =>  'single'
                                        ];
                                        $data = $dblms->getRows(CHALLANS.' c', $condition);
                                        echo intval($data['total']).',';
                                    }
                                    echo ']
                            },
                            {
                                name: "e-Training",
                                data: [';
                                    for ($i=1;$i<=12;$i++) {
                                        $month = ($i<=9 ? '0' : '') . $i;
                                        $condition  = [
                                            'select'       =>  'SUM(c.paid_amount) AS total',
                                            'join'         =>  'INNER JOIN '.ENROLLED_COURSES.' ec ON ec.secs_id = c.id_enroll',
                                            'where'        =>  [
                                                'c.status'     => 1,
                                                'c.is_deleted' => 0,
                                                'ec.id_type'   => 4
                                            ],
                                            'search_by'    =>  ' AND c.paid_date LIKE "'.$revenueYears.'-'.$month.'%" ',
                                            'return_type'  =>  'single'
                                        ];
                                        $data = $dblms->getRows(CHALLANS.' c', $condition);
                                        echo intval($data['total']).',';
                                    }
                                    echo ']
                            }
                        ],
                        chart: {
                            type: "bar",
                            height: 306,
                            stacked: !0,
                            toolbar: {
                                show: !1
                            }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: !1,
                                columnWidth: "30%",
                                borderRadius: 6
                            }
                        },
                        dataLabels: {
                            enabled: !1
                        },
                        legend: {
                            show: !0,
                            position: "bottom",
                            horizontalAlign: "center",
                            fontWeight: 400,
                            fontSize: "8px",
                            offsetX: 0,
                            offsetY: 0,
                            markers: {
                                width: 9,
                                height: 9,
                                radius: 4
                            }
                        },
                        stroke: {
                            show: !0,
                            width: 2,
                            colors: ["transparent"]
                        },
                        grid: {
                            show: !1
                        },
                        colors: chartAudienceColumnChartsColors,
                        xaxis: {
                            categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                            axisTicks: {
                                show: !1
                            },
                            axisBorder: {
                                show: !0,
                                strokeDashArray: 1,
                                height: 1,
                                width: "100%",
                                offsetX: 0,
                                offsetY: 0
                            }
                        },
                        yaxis: {
                            show: !1
                        },
                        fill: {
                            opacity: 1
                        }
                    }, (chart = new ApexCharts(document.querySelector("#revenue_matrics"), columnoptions)).render());
                </script>
            </div>
        </div>
    </div>
</div>';
