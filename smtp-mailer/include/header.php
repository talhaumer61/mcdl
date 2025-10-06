<?php
echo'
<!DOCTYPE html> 
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="gradient" data-menu-styles="dark">
<head>
    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Description" content="Velvet - Codeigniter Bootstrap5 Admin & Dashboard Template">
    <meta name="Author" content="Spruko Technologies Private Limited">
    <meta name="keywords" content="admin, admin panel, admin dashboard, admin panel template, bootstrap admin template, bootstrap 5 dashboard, admin panel bootstrap, codeigniter, dashboard, template dashboard, bootstrap admin dashboard, codeigniter 4, framework codeigniter">
    <!-- TITLE -->
    <title> Velvet - Codeigniter Bootstrap5 Admin &amp; Dashboard Template </title>
    <!-- FAVICON -->
    <link rel="icon" href="'.SERVER_URL.'assets/images/brand-logos/fav.ico" type="image/x-icon">
    <!-- BOOTSTRAP CSS -->
    <link  id="style" href="'.SERVER_URL.'assets/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- ICONS CSS -->
    <link href="'.SERVER_URL.'assets/css/icons.css" rel="stylesheet">
    <!-- STYLES CSS -->
    <link href="'.SERVER_URL.'assets/css/styles.css" rel="stylesheet">
    <!-- MAIN JS -->
    <script src="'.SERVER_URL.'assets/js/main.js"></script>
    <!-- NODE WAVES CSS -->
    <link href="'.SERVER_URL.'assets/libs/node-waves/waves.min.css" rel="stylesheet"> 
    <!-- SIMPLEBAR CSS -->
    <link rel="stylesheet" href="'.SERVER_URL.'assets/libs/simplebar/simplebar.min.css">
    <!-- COLOR PICKER CSS -->
    <link rel="stylesheet" href="'.SERVER_URL.'assets/libs/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="'.SERVER_URL.'assets/libs/%40simonwep/pickr/themes/nano.min.css">
    <!-- CHOICES CSS -->
    <link rel="stylesheet" href="'.SERVER_URL.'assets/libs/choices.js/public/assets/styles/choices.min.css">
    <!-- CHOICES JS -->
    <script src="'.SERVER_URL.'assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>
    <!-- QUILL CSS -->
    <link rel="stylesheet" href="'.SERVER_URL.'assets/libs/quill/quill.snow.css">
    <link rel="stylesheet" href="'.SERVER_URL.'assets/libs/quill/quill.bubble.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body>';
    include "switcher.php";
    echo'
    <div id="loader">
        <img src="'.SERVER_URL.'assets/images/media/loader.svg" alt="">
    </div>
    <div class="page">';
        include "topbar.php";
        include "sidebar.php";
        $sqlstring	= "";
        $adjacents	= 3;
        if(!($Limit)) 	{ $Limit = 20; } 
        if($page)		{ $start = ($page - 1) * $Limit; } else {	$start = 0;	}