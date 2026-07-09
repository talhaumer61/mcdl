<?php
    echo '
    <title>Manage Profile - '.TITLE_HEADER.'</title>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid mb-5"> ';
                include_once ('profile/query.php');
                include_once ('profile/view.php');
                echo '
            </div>
        </div>
    </div>';  
?>