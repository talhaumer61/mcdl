<?php
$revenueYears = date("Y");
$condition  = [
                 'select'       =>  'SUM(total_amount) AS total_amount, SUM(paid_amount) AS paid_amount'
                ,'where'        =>  [
                                        'status'        => 1,
                                        'is_deleted'    => 0,
                                    ]
                ,'search_by'    =>  ' AND paid_date LIKE "%'.$revenueYears.'%" '
                ,'return_type'  =>  'single'
];
$CHALLANS = $dblms->getRows(CHALLANS,$condition, $sql);
echo' 
<div class="row mb-3">
    <div class="col">
        <div class="card card-height-100">
            <div class="card-header border-0 align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Revenue</h4>
                <!--
                <div>
                    <button type="button" class="btn btn-soft-secondary btn-sm">
                        ALL
                    </button>
                    <button type="button" class="btn btn-soft-secondary btn-sm">
                        1M
                    </button>
                    <button type="button" class="btn btn-soft-secondary btn-sm">
                        6M
                    </button>
                    <button type="button" class="btn btn-soft-primary btn-sm">
                        1Y
                    </button>
                </div>
                -->
            </div>
            <div class="card-header p-0 border-0 bg-soft-light">
                <div class="row g-0 text-center">
                    <div class="col-6 col-sm-4">
                        <div class="p-3 border border-dashed border-start-0">
                            <h5 class="mb-1">
                                <span class="counter-value" data-target="'.intval($CHALLANS['total_amount']).'">0</span>
                            </h5>
                            <p class="text-muted mb-0">Total Revenue</p>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4">
                        <div class="p-3 border border-dashed border-start-0">
                            <h5 class="mb-1">
                                <span class="counter-value" data-target="'.intval($CHALLANS['paid_amount']).'">0</span>
                            </h5>
                            <p class="text-muted mb-0">Total Profit</p>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4">
                        <div class="p-3 border border-dashed border-start-0 border-end-0">
                            <h5 class="mb-1">
                                <span class="counter-value" data-target="'.intval($CHALLANS['paid_amount'] * 100 / $CHALLANS['total_amount']).'">0</span>%
                            </h5>
                            <p class="text-muted mb-0">Avg. Profit Rate</p>
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
                                name: "'.($revenueYears-4).'",
                                data: [';
                                    for ($i=1;$i<=12;$i++) { 
                                        $condition  = [
                                                         'select'       =>  'SUM(paid_amount) AS paid_amount'
                                                        ,'where'        =>  [
                                                                                'status'        => 1,
                                                                                'is_deleted'    => 0,
                                                                            ]
                                                        ,'search_by'    =>  ' AND paid_date LIKE "%'.($revenueYears-4).'%" AND paid_date LIKE "%'.($revenueYears-4).'-'.($i<=10?'0':'').''.$i.'%" '
                                                        ,'return_type'  =>  'single'
                                        ];
                                        $CHALLANS = $dblms->getRows(CHALLANS,$condition);
                                        echo'
                                        '.intval($CHALLANS['paid_amount']).',';
                                    }
                                    echo'
                                ]
                            },
                            {
                                name: "'.($revenueYears-3).'",
                                data: [';
                                    for ($i=1;$i<=12;$i++) { 
                                        $condition  = [
                                                         'select'       =>  'SUM(paid_amount) AS paid_amount'
                                                        ,'where'        =>  [
                                                                                'status'        => 1,
                                                                                'is_deleted'    => 0,
                                                                            ]
                                                        ,'search_by'    =>  ' AND paid_date LIKE "%'.($revenueYears-3).'%" AND paid_date LIKE "%'.($revenueYears-3).'-'.($i<=10?'0':'').''.$i.'%" '
                                                        ,'return_type'  =>  'single'
                                        ];
                                        $CHALLANS = $dblms->getRows(CHALLANS,$condition);
                                        echo'
                                        '.intval($CHALLANS['paid_amount']).',';
                                    }
                                    echo'
                                ]
                            },
                            {
                                name: "'.($revenueYears-2).'",
                                data: [';
                                    for ($i=1;$i<=12;$i++) { 
                                        $condition  = [
                                                         'select'       =>  'SUM(paid_amount) AS paid_amount'
                                                        ,'where'        =>  [
                                                                                'status'        => 1,
                                                                                'is_deleted'    => 0,
                                                                            ]
                                                        ,'search_by'    =>  ' AND paid_date LIKE "%'.($revenueYears-2).'%" AND paid_date LIKE "%'.($revenueYears-2).'-'.($i<=10?'0':'').''.$i.'%" '
                                                        ,'return_type'  =>  'single'
                                        ];
                                        $CHALLANS = $dblms->getRows(CHALLANS,$condition);
                                        echo'
                                        '.intval($CHALLANS['paid_amount']).',';
                                    }
                                    echo'
                                ]
                            },
                            {
                                name: "'.($revenueYears-1).'",
                                data: [';
                                    for ($i=1;$i<=12;$i++) { 
                                        $condition  = [
                                                         'select'       =>  'SUM(paid_amount) AS paid_amount'
                                                        ,'where'        =>  [
                                                                                'status'        => 1,
                                                                                'is_deleted'    => 0,
                                                                            ]
                                                        ,'search_by'    =>  ' AND paid_date LIKE "%'.($revenueYears-1).'%" AND paid_date LIKE "%'.($revenueYears-1).'-'.($i<=10?'0':'').''.$i.'%" '
                                                        ,'return_type'  =>  'single'
                                        ];
                                        $CHALLANS = $dblms->getRows(CHALLANS,$condition);
                                        echo'
                                        '.intval($CHALLANS['paid_amount']).',';
                                    }
                                    echo'
                                ]
                            },
                            {
                                name: "'.$revenueYears.'",
                                data: [';
                                    for ($i=1;$i<=12;$i++) { 
                                        $condition  = [
                                                         'select'       =>  'SUM(paid_amount) AS paid_amount'
                                                        ,'where'        =>  [
                                                                                'status'        => 1,
                                                                                'is_deleted'    => 0,
                                                                            ]
                                                        ,'search_by'    =>  ' AND paid_date LIKE "%'.$revenueYears.'%" AND paid_date LIKE "%'.$revenueYears.'-'.($i<=10?'0':'').''.$i.'%" '
                                                        ,'return_type'  =>  'single'
                                        ];
                                        $CHALLANS = $dblms->getRows(CHALLANS,$condition);
                                        echo'
                                        '.intval($CHALLANS['paid_amount']).',';
                                    }
                                    echo'
                                ]
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