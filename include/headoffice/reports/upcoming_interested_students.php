<?php
$selectedType = $_GET['enroll_type'] ?? '';
$selectedCourse = $_GET['course_id'] ?? '';
$condition = array ( 
                         'select'       =>  'c.curs_id, c.curs_name'
                        ,'join'         =>  'INNER JOIN '.COURSES.' c ON c.curs_id = ic.id_interest AND c.curs_status = 1 AND c.is_deleted = 0'
                        ,'where' 	    =>  array( 
                                                     'ic.status'    => 1,
                                                     'ic.type'      => $selectedType
                                                )
                        ,'group_by'    =>  'ic.id_interest'
                        ,'return_type'  =>  'all' 
                    ); 
$COURSES = $dblms->getRows(STUDENT_INTERESTED_COURSES.' ic', $condition,$sql);
echo'
<div class="row mb-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-filter-line align-bottom me-1"></i>Filters
                </h5>
            </div>

            <form action="prints.php?view='.LMS_VIEW.'" method="POST" autocomplete="off">
                <div class="card-body">
                    <div class="row g-3 justify-content-center">

                        <div class="col-12 col-md-6">
                            <label class="form-label">Date</label>
                            <input type="text" class="form-control" name="date" data-provider="flatpickr" data-date-format="Y-m-d" data-range-date="true" readonly>
                        </div>
                        
                        <!-- Course -->
                        <div class="col-12 col-md-6">
                            <label class="form-label">'.($selectedType == 3 ? 'Courses' : 'Trainings').'</label>
                            <select name="course_id" 
                                    class="form-control" 
                                    data-choices>
                                <option value="">All</option>';

                                if(!empty($COURSES)){
                                    foreach($COURSES as $course){
                                        echo '
                                        <option value="'.$course['curs_id'].'" '.($selectedCourse == $course['curs_id'] ? 'selected' : '').'>
                                            '.$course['curs_name'].'
                                        </option>';
                                    }
                                }

                                echo'
                            </select>
                        </div>
                        ';
                        if(!empty($selectedType)){
                           echo' <input type="hidden" name="enroll_type" value="'.$selectedType.'">';
                        }
                        echo'
                        <div class="col-md-12 text-center mt-3">
                            <button type="submit" class="btn btn-primary px-4">
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
