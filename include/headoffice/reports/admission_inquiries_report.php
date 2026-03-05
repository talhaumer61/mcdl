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

                        <div class="col-12 col-md-4 mb-2">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="date" data-provider="flatpickr" data-date-format="Y-m-d" data-range-date="true" value="'.($_GET['date'] ?? '').'" required>
                        </div>

                        <div class="col-12 col-md-4 mb-2">
                            <label class="form-label"> Learning Type <span class="text-danger">*</span></label>
                            <select name="enroll_type" id="enroll_type" class="form-control" data-choices required>
                                <option value="">Select Learning Type</option>
                                ';
                                foreach ($enroll_type as  $type) {
                                    echo "<option value='".$type['id']."' ".( (!empty($_GET['enroll_type']) && $_GET['enroll_type'] == $type['id']) ? 'selected' : '' ).">".$type['name']."</option>";
                                }
                                echo'
                            </select>
                        </div>

                        <div class="col-12 col-md-4 mb-2">
                            <label class="form-label">Course/Training</label>
                            <select name="course_id" id="course_id" class="form-control">
                                <option value="">Select Type</option>
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
function loadCourses() {
    const enrollType = document.getElementById('enroll_type');
    const courseSelect = document.getElementById('course_id');

    if (!enrollType || !enrollType.value) return;

    fetch("include/ajax/get_report_courses.php?type=" + enrollType.value)
        .then(res => res.text())
        .then(data => {
            // 1. Destroy the existing Choices UI if it exists
            // Themes usually store the instance on the element itself
            if (courseSelect.choices) {
                courseSelect.choices.destroy();
            }

            // 2. Update the raw HTML options
            courseSelect.innerHTML = data;

            // 3. Re-initialize Choices manually
            // We set 'data-choices' to true so the theme knows it's active
            courseSelect.setAttribute('data-choices', 'true');
            
            // This 'new Choices' line handles the visual transformation
            const newInstance = new Choices(courseSelect, {
                searchEnabled: true,
                shouldSort: false,
                removeItemButton: true,
            });

            // 4. Store the instance so we can destroy it next time
            courseSelect.choices = newInstance;
        })
        .catch(err => console.error("Error:", err));
}

window.addEventListener('load', function() {
    const enrollType = document.getElementById('enroll_type');
    
    enrollType.addEventListener('change', loadCourses);

    if (enrollType.value) {
        setTimeout(loadCourses, 500);
    }
});
</script>
