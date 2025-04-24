<?php
echo'
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Refferrals</h4>
            </div>
            <div class="card-body">
                <div id="chart-bar-label-rotation" data-colors=\'["--vz-primary", "--vz-success", "--vz-warning", "--vz-danger"]\' class="e-charts"></div>
            </div>
            <script>
                var posList, labelOption, app = {}, chartBarLabelRotationColors = getChartColorsArray("chart-bar-label-rotation");
                chartBarLabelRotationColors && (chartDom = document.getElementById("chart-bar-label-rotation"), myChart = echarts.init(chartDom), app.configParameters = {
                    rotate: {
                        min: -90,
                        max: 90
                    },
                    align: {
                        options: {
                            left: "left",
                            center: "center",
                            right: "right"
                        }
                    },
                    verticalAlign: {
                        options: {
                            top: "top",
                            middle: "middle",
                            bottom: "bottom"
                        }
                    },
                    position: {
                        options: (posList = ["left", "right", "top", "bottom", "inside", "insideTop", "insideLeft", "insideRight", "insideBottom", "insideTopLeft", "insideTopRight", "insideBottomLeft", "insideBottomRight"]).reduce(function(t, e) {
                            return t[e] = e, t
                        }, {})
                    },
                    distance: {
                        min: 0,
                        max: 100
                    }
                }, app.config = {
                    rotate: 90,
                    align: "left",
                    verticalAlign: "middle",
                    position: "insideBottom",
                    distance: 15,
                    onChange: function() {
                        var t = {
                            rotate: app.config.rotate,
                            align: app.config.align,
                            verticalAlign: app.config.verticalAlign,
                            position: app.config.position,
                            distance: app.config.distance
                        };
                        myChart.setOption({
                            series: [{
                                label: t
                            }, {
                                label: t
                            }, {
                                label: t
                            }, {
                                label: t
                            }]
                        })
                    }
                }, (option = {
                    grid: {
                        left: "0%",
                        right: "0%",
                        bottom: "0%",
                        containLabel: !0
                    },
                    tooltip: {
                        trigger: "axis",
                        axisPointer: {
                            type: "shadow"
                        }
                    },
                    legend: {
                        data: ["Total Referral", "Total Referred"],
                        textStyle: {
                            color: "#858d98"
                        }
                    },
                    color: chartBarLabelRotationColors,
                    toolbox: {
                        show: !0,
                        orient: "vertical",
                        left: "right",
                        top: "center",
                        feature: {
                            mark: {
                                show: !0
                            },
                            dataView: {
                                show: !0,
                                readOnly: !(labelOption = {
                                    show: !0,
                                    position: app.config.position,
                                    distance: app.config.distance,
                                    align: app.config.align,
                                    verticalAlign: app.config.verticalAlign,
                                    rotate: app.config.rotate,
                                    formatter: "{c}  {name|{a}}",
                                    fontSize: 16,
                                    rich: {
                                        name: {}
                                    }
                                })
                            },
                            magicType: {
                                show: !0,
                                type: ["line", "bar", "stack"]
                            },
                            restore: {
                                show: !0
                            },
                            saveAsImage: {
                                show: !0
                            }
                        }
                    },
                    xAxis: [
                        {
                            type: "category",
                            axisTick: {
                                show: !1
                            },
                            data: [';
                                foreach ($ENROLLED_COURSES as $key => $value) {
                                    echo'"'.$value['curs_name'].'",';
                                }
                                echo'
                            ],
                            axisLine: {
                                lineStyle: {
                                    color: "#858d98"
                                }
                            }
                        }
                    ],
                    yAxis: {
                        type: "value",
                        axisLine: {
                            lineStyle: {
                                color: "#858d98"
                            }
                        },
                        splitLine: {
                            lineStyle: {
                                color: "rgba(133, 141, 152, 0.1)"
                            }
                        }
                    },
                    textStyle: {
                        fontFamily: "Poppins, sans-serif"
                    },
                    series: [
                        {
                            name: "Total Referral",
                            type: "bar",
                            barGap: 0,
                            label: labelOption,
                            emphasis: {
                                focus: "series"
                            },
                            data: [';
                                foreach ($ENROLLED_COURSES as $key => $value) {
                                    // COURSES COUNT
                                    $condition  =   [
                                                        'select'       =>  'DISTINCT ref_id'
                                                        ,'where'        =>  [
                                                                                'ref_status'    => 1,
                                                                                'is_deleted'    => 0,
                                                                            ]
                                                        ,'search_by'    =>  ' AND FIND_IN_SET('.$value['curs_id'].',id_curs) '
                                                        ,'return_type'  =>  'count'
                                    ];
                                    $REFERRAL_CONTROL = $dblms->getRows(REFERRAL_CONTROL,$condition);
                                    echo $REFERRAL_CONTROL.',';
                                }
                                echo'
                            ]
                        }, {
                            name: "Total Referred",
                            type: "bar",
                            label: labelOption,
                            emphasis: {
                                focus: "series"
                            },
                            data: [';
                                foreach ($ENROLLED_COURSES as $key => $value) {
                                    // COURSES COUNT
                                    $condition  =   [
                                                        'select'       =>  'COUNT(DISTINCT id_curs) AS count'
                                                        ,'where'        =>  [
                                                                                'ref_shr_status'    => 1,
                                                                                'is_deleted'    => 0,
                                                                                'id_curs'       => $value['curs_id'],
                                                                            ]
                                                        ,'return_type'  =>  'single'
                                    ];
                                    $REFERRAL_TEACHER_SHARING = $dblms->getRows(REFERRAL_TEACHER_SHARING,$condition);
                                    echo $REFERRAL_TEACHER_SHARING['count'].',';
                                }
                                echo'
                            ]
                        }
                    ]
                }) && myChart.setOption(option));
            </script>
        </div>
    </div>
</div>';