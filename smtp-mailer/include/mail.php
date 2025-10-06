<?php
echo'
<div class="d-sm-flex d-block align-items-center justify-content-between page-header-breadcrumb">
    <h4 class="fw-medium mb-0">Mail</h4>
    <div class="ms-sm-1 ms-0">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Email</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.moduleName(CONTROLER).'</li>
            </ol>
        </nav>
    </div>
</div>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="main-mail-container p-2 gap-4 d-md-flex">';
            if (!empty(ZONE)) {
                include "mail/detail.php";
            } else {
                include "mail/list.php";
            }
            echo'
        </div>
    </div>
</div>';