<?php
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

                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="dashboard.php">
                        <i class="bx bxs-dashboard"></i> <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                <!-- Students -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="students.php">
                        <i class="ri-group-2-line"></i> <span data-key="t-skills">Students</span>
                    </a>
                </li> 

                <!-- Challans -->  
                <li class="nav-item">
                    <a class="nav-link menu-link" href="challans.php">
                        <i class="ri-visa-fill"></i> <span data-key="t-skills">Challans</span>
                    </a>
                </li>            
                                
                <!-- Reports -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="reports.php">
                        <i class="ri-file-paper-2-line"></i> <span data-key="t-employees">Reports</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>';
?>