<?php
$condition = array(
            'select'        => 'std_id, std_name',
            'where'         => array(
                                'std_status' => 1,
                                'is_deleted' => 0
                            ),
            'order_by'      => 'std_name ASC',
            'return_type'   => 'all'
        );
$students = $dblms->getRows(STUDENTS, $condition);
echo '
<div class="row mb-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-filter-line align-bottom me-1"></i> Date
                </h5>
            </div>

            <form action="prints.php?view='.LMS_VIEW.'" method="POST" autocomplete="off">
                <div class="card-body">
                    <div class="row g-3 justify-content-center">

                        <div class="col-12 col-md-6 mb-2">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="date" data-provider="flatpickr" data-date-format="Y-m-d" data-range-date="true" value="'.($_GET['date'] ?? '').'" required>
                        </div>

                        <div class="col-12 col-md-6 mb-2">
                            <label class="form-label">Student</label>
                            <select name="std_id" class="form-control" data-choices>
                                <option value="">All Students</option>';
                                foreach ($students as $student) {
                                    echo '<option value="'.$student['std_id'].'" '.((isset($_GET['std_id']) && $_GET['std_id'] == $student['std_id']) ? 'selected' : '').'>'.$student['std_name'].'</option>';
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