<?php
if(!isset($_GET['type']) || empty($_GET['type'])) {
    header("Location: reports.php");
    exit();
}
$type = $_GET['type'];
$condition = array(
    'select'    => 'c.curs_id, c.curs_name',
    'join'      => 'INNER JOIN '.ENROLLED_COURSES.' ec ON ec.id_curs = curs_id AND ec.id_type = '.$type.' AND ec.secs_status = 1 AND ec.is_deleted = 0',
    'where'     => array(
                        'c.curs_status'  => 1,
                        'c.is_deleted'   => 0
                    ),
                    'group_by'  => 'c.curs_id',
    'return_type' => 'all'
);

$data = $dblms->getRows(COURSES.' c', $condition);
$enrollType = array_column($enroll_type, 'name', 'id');
echo '
<div class="row mb-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-filter-line align-bottom me-1"></i> Date Filter
                </h5>
            </div>

            <form action="prints.php?view='.LMS_VIEW.'" method="POST" autocomplete="off">
                <div class="card-body">
                    <div class="row g-3 justify-content-center">

                        <div class="col-12 col-md-6">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="date" data-provider="flatpickr" data-date-format="Y-m-d" data-range-date="true" value="'.($_GET['date'] ?? '').'" readonly>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="mb-1">'.$enrollType[$type].' <span class="text-danger">*</span></label>
                            <select name="id_curs" id="id_curs" class="form-select" data-choices required>
                                <option value="">Choose...</option>';
                                foreach($data as $key => $value){
                                    echo '<option value="'.$value['curs_id'].'">'.$value['curs_name'].'</option>';
                                }
                            echo'
                            </select>
                        </div>

                        <div class="col-md-12 text-center mt-3">
                            <button type="submit" class="btn btn-primary px-3">
                                <i class="ri-search-line me-1"></i> View Results
                            </button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>';

?>