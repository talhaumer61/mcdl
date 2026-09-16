<?php
echo '
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

                        <!-- Date Range -->
                        <div class="col-12 col-md-4">
                            <label class="form-label">Date</label>
                            <input type="text" class="form-control" name="date" data-provider="flatpickr" data-date-format="Y-m-d" data-range-date="true" value="'.($_GET['date'] ?? '').'" readonly>
                        </div>

                        <!-- Pay Mode -->
                        <div class="col-12 col-md-4">
                            <label class="form-label">Pay Mode</label>
                            <select class="form-control" name="pay_mode" data-choices>
                                <option value="">Choose Pay Mode</option>';
                                foreach(get_paymode() as $key => $value):
                                    echo '<option value="'.$key.'" '.((isset($_GET['pay_mode']) && $key == $_GET['pay_mode']) ? 'selected' : '').'>'.$value.'</option>';
                                endforeach;
                                echo '
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="col-12 col-md-4">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="status" data-choices>
                                <option value="">Choose Status</option>
                                <option value="1">Paid</option>
                                <option value="2">Pending</option>
                            </select>
                        </div>

                        <!-- Submit Button -->
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