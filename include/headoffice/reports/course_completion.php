<?php
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

                        <div class="col-12 col-md-4">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="date" data-provider="flatpickr" data-date-format="Y-m-d" data-range-date="true" required>
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="mb-1">Type</label>
                            <select name="enroll_type" id="enroll_type" class="form-select" data-choices required>
                                <option value="">Choose Type</option>';
                                foreach($enroll_type as $t){
                                    echo '<option value="'.$t['id'].'" '.($_GET['enroll_type'] == $t['id'] ? 'selected':'').'>'.$t['name'].'</option>';
                                }
                            echo'
                            </select>
                        </div>

                        <!-- Course -->
                        <div class="col-md-4 col-12">
                            <label>Course/Training</label>
                            <select name="course_id" id="course_id" class="form-select">
                                <option value="">Select Type First</option>
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
<script>
let courseChoices = null;

function loadCourses(){
    let type   = document.getElementById('enroll_type').value;
    let course = document.getElementById('course_id');

    if(!type){
        course.innerHTML = '<option value="">Select Type First</option>';
        if(courseChoices) courseChoices.destroy();
        courseChoices = new Choices(course);
        return;
    }

    fetch("include/ajax/get_report_courses.php?type="+type)
        .then(res => res.text())
        .then(html => {

            // Destroy old Choices instance
            if(courseChoices) courseChoices.destroy();

            // Update options
            course.innerHTML = '<option value="">All</option>' + html;

            // Re-init Choices
            courseChoices = new Choices(course);
        });
}

document.getElementById('enroll_type').addEventListener('change', loadCourses);
window.addEventListener('DOMContentLoaded', loadCourses);
</script>

