<?php
echo'
<!doctype html>
<html lang="en" data-layout="horizontal" data-topbar="dark" data-sidebar-size="lg">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">
        <!-- Plugins css -->
        <link href="assets/libs/dropzone/dropzone.css" rel="stylesheet" type="text/css" />
        <!-- jsvectormap css -->
        <link href="assets/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />
        <!--Swiper slider css-->
        <link href="assets/libs/swiper/swiper-bundle.min.css" rel="stylesheet" type="text/css" />
        <!-- Layout config Js -->
        <script src="assets/js/layout.js"></script>
        <!-- Bootstrap Css -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
        <!-- custom Css-->
        <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />
        <!-- gridjs css -->
        <link rel="stylesheet" href="assets/libs/gridjs/theme/mermaid.min.css">
        <!-- SWEETALERT JS/CSS -->
        <link rel="stylesheet" href="assets/sweetalert/sweetalert_custom.css">
        <script src="assets/sweetalert/sweetalert.min.js"></script>
        <!-- CKEDITOR -->
        <script src="assets/ckeditor/ckeditor.js"></script> 
        <!-- JQUERY -->
        <script src="assets/js/jquery.js"></script>
        <!-- Chart JS -->
        <script src="assets/libs/chart.js/chart.min.js"></script>
        <!-- apexcharts -->
        <script src="assets/libs/apexcharts/apexcharts.min.js"></script>
        <!-- echarts js -->
        <script src="assets/libs/echarts/echarts.min.js"></script>

        <script>
            function getChartColorsArray(t) {
                if (null !== document.getElementById(t)) {
                    t = document.getElementById(t).getAttribute("data-colors");
                    if (t) return (t = JSON.parse(t)).map(function(t) {
                        var e = t.replace(" ", "");
                        if (-1 === e.indexOf(",")) {
                            var a = getComputedStyle(document.documentElement).getPropertyValue(e);
                            return a || e
                        }
                        t = t.split(",");
                        return 2 != t.length ? e : "rgba(" + getComputedStyle(document.documentElement).getPropertyValue(t[0]) + "," + t[1] + ")"
                    })
                }
            }
        </script>
    </head>
    <style>
        .cke_notification {
            display: none !important;
        }
    </style>
    <body>
        <div id="layout-wrapper">';
            include_once "include/sessionMsg.php";
            include_once "include/detail_missing.php";
            include_once "include/".get_logintypes($_SESSION['userlogininfo']['LOGINAFOR'])."/topbar.php";  
            include_once "include/".get_logintypes($_SESSION['userlogininfo']['LOGINAFOR'])."/sidebar.php";

			$sqlstring	= "";
			$adjacents	= 3;
			if(!($Limit)) 	{ $Limit = 20; } 
			if($page)		{ $start = ($page - 1) * $Limit; } else {	$start = 0;	}

            echo'
            <div class="main-content">';
?>