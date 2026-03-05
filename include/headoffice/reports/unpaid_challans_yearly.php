<?php
$condition = array(
            'select'        => 'MIN(YEAR(date_added)) AS min_year',
            'where'         => array(
                                'secs_status' => 1,
                                'is_deleted' => 0
                            ),
            
            'return_type'   => 'single'
        );
$year = $dblms->getRows(ENROLLED_COURSES, $condition);
$startYear = $year['min_year'] ?? date('Y');
$currentYear = date('Y');
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
                <input type="hidden" name="view" value="'.LMS_VIEW.'">
                <div class="card-body">
                    <div class="row g-3 justify-content-center">

                        <div class="col-12 col-md-6 mb-2">
                            <label class="form-label">Year</label>
                            <select name="year" class="form-control">
                                <option value="">Select any year...</option>';
                                for($y = $currentYear; $y >= $startYear; $y--){
                                    $sel = ($_GET['year'] == $y) ? 'selected' : '';
                                    echo "<option value='$y' $sel>$y</option>";
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