<?php
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

                        <div class="col-md-6">
                            <label class="form-label">Date</label>
                            <input type="text" class="form-control" name="date" data-provider="flatpickr" data-date-format="Y-m-d" data-range-date="true" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-control" data-choices name="status">
                                <option value="">Choose one</option>';
                                foreach (get_inquiry_status() as $key => $status) {
                                    echo '<option value="'.$key.'">'.$status.'</option>';
                                }
                                echo'
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type</label>
                            <select class="form-control" data-choices name="type">
                                <option value="">Choose one</option>';
                                foreach (get_inquiry_type() as $key => $type) {
                                    echo '<option value="'.$key.'">'.$type.'</option>';
                                }
                                echo'
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Source</label>
                            <select class="form-control" data-choices name="source">
                                <option value="">Choose one</option>';
                                foreach (get_inquiry_source() as $key => $source) {
                                    echo '<option value="'.$key.'">'.$source.'</option>';
                                }
                                echo'
                            </select>
                        </div>

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
