<?php
echo' 
<div class="row mb-3">
    <div class="col-md-12">
        <div class="row mb-3">
            <div class="col-xl-6 col-md-6 mb-3">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0"><strong>Total Earning</strong></p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="'.round($totalAmbassadorEarning) .'"></span></h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-secondary rounded fs-3"><i class="bx bx-selection text-secondary"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 mb-3">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0"><strong>Sub-Members Earning</strong></p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="'.round($subMembersTotalEarning) .'"></span></h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-secondary rounded fs-3"><i class="bx bx-selection text-secondary"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 mb-3">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0"><strong>Total Account Created</strong></p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="'.$STUDENTS.'"></span></h4>';
                                if(empty(LMS_EDIT_ID)){
                                    echo'<a href="account_created_link.php" class="text-decoration-underline text-muted">View List</a>';
                                } else {
                                    echo'<a href="account_created_link.php?view=account_created&edit_id='.LMS_EDIT_ID.'" class="text-decoration-underline text-muted">View List</a>';
                                }
                                echo'
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-secondary rounded fs-3"><i class="bx bx-selection text-secondary"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 mb-3">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0"><strong>Total Enrollments</strong></p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="'.$ENROLLED_COURSES.'"></span></h4>';
                                if(empty(LMS_EDIT_ID)){
                                    echo'<a href="enrolled_from_link.php" class="text-decoration-underline text-muted">View List</a>';
                                } else {
                                    echo'<a href="enrolled_from_link.php?view=enrolled_list&edit_id='.LMS_EDIT_ID.'" class="text-decoration-underline text-muted">View List</a>';
                                }
                                echo'
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-info rounded fs-3"><i class="ri-article-line text-info"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';