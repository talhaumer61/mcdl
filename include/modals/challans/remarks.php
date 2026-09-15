<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
include "../../functions/functions.php";
$dblms = new dblms();

$condition = array(
    'select'       => 'ch.remarks, ch.status, ch.receipt',
    'where'        => array(
        'ch.is_deleted' => 0,
        'ch.challan_id' => cleanvars($_GET['challan_id'])
    ),
    'order_by'     => 'ch.challan_id ASC',
    'return_type'  => 'single'
);

$row = $dblms->getRows(CHALLANS . ' ch', $condition);

/* Receipt handling */
$receiptHtml = '<span class="text-muted">No receipt uploaded</span>';
if (!empty($row['receipt'])) {

    $receiptPath = 'uploads/images/receipt/' . $row['receipt'];
    $ext = strtolower(pathinfo($row['receipt'], PATHINFO_EXTENSION));

    /* File icon */
    switch ($ext) {
        case 'pdf':
            $icon = 'ri-file-pdf-line text-danger';
            break;
        case 'jpg':
        case 'jpeg':
        case 'png':
            $icon = 'ri-image-line text-info';
            break;
        default:
            $icon = 'ri-file-line';
    }

    $receiptHtml = '
        <a href="' . $receiptPath . '" target="_blank" class="text-decoration-none">
            <i class="' . $icon . ' fs-20 align-middle me-1"></i>
            <span>' . htmlspecialchars($row['receipt']) . '</span>
        </a>';
}

echo '
<script src="assets/js/app.js"></script>

<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-primary p-3">
            <h5 class="modal-title">
                <i class="ri-file-info-line align-bottom me-1"></i>
                Challan Details
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">

            <div class="row mb-3">
                <div class="col-4 fw-semibold">Status:</div>
                <div class="col-8">' . get_payments($row['status']) . '</div>
            </div>

            <div class="row mb-3">
                <div class="col-4 fw-semibold">Remarks:</div>
                <div class="col-8">' . nl2br($row['remarks']) . '</div>
            </div>

            <div class="row mb-3">
                <div class="col-4 fw-semibold">Receipt:</div>
                <div class="col-8">' . $receiptHtml . '</div>
            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">
                <i class="ri-close-circle-line align-bottom me-1"></i>Close
            </button>
        </div>
    </div>
</div>';
?>