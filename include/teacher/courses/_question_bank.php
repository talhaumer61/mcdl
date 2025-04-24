<?php
include_once ('question_bank/query.php');
echo'
<div class="card mb-5">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-information-line align-bottom me-1"></i>'.ucwords(str_replace('_', ' ', LMS_VIEW)).'</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12" >';
                if (isset($_GET['add'])) {
                    include_once ('question_bank/question_bank_add_edit.php');
                } else if (!empty($_GET['qns_id'])) {
                    include_once ('question_bank/question_bank_add_edit.php');
                } else {
                    include_once ('question_bank/list.php');
                }
                echo'
            </div>
        </div>
    </div>
</div>';
?>