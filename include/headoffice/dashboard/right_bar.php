<?php
echo'
<div class="card h-100 rounded-0">
    <div class="card-body p-0">
        <div class="p-3 mt-2">
            <h6 class="text-muted mb-3 text-uppercase">Top 10 Categories</h6>
            <ol class="ps-3 text-muted">';
                foreach ($ENROLLED_COURSES as $key => $value) {
                    echo'
                    <li class="py-1">
                        <a href="#" class="text-muted">'.$value['curs_name'].' <span class="float-end">('.number_format($value['std_count']).')</span></a>
                    </li>';
                }
                echo'
            </ol>
            <div class="mt-3 text-center">
                <a href="javascript:void(0);" class="text-muted text-decoration-underline">View all Categories</a>
            </div>
        </div>
        <div class="p-3">
            <h6 class="text-muted mb-0 text-uppercase">Recent Challans</h6>
        </div>
        <div data-simplebar style="max-height: 410px;" class="p-3 pt-0">
            <div class="acitivity-timeline acitivity-main">';
                $condition = array(
                                    'select'       =>  'ch.challan_no, ch.status, ch.paid_date, s.std_name'
                                    ,'join'         =>  'INNER JOIN '.STUDENTS.' s ON s.std_id = ch.id_std'
                                    ,'where'        =>  array(
                                                                'ch.is_deleted'  => 0
                                                            )
                                    ,'limit'        =>  ' 10 '
                                    ,'order_by'     =>  ' ch.challan_id DESC '
                                    ,'return_type'  =>  'all'
                );
                $CHALLANS = $dblms->getRows(CHALLANS.' ch', $condition);
                foreach ($CHALLANS as $key => $value) {
                    echo'
                    <div class="acitivity-item py-1 d-flex">
                        <div class="flex-shrink-0 avatar-xs acitivity-avatar">';
                            $badgeColor = ($value['status'] == 1?'success':($value['status'] == 2?'warning':($value['status'] == 3?'danger':($value['status'] == 4?'info':''))));
                            echo'
                            <div class="avatar-title bg-soft-'.$badgeColor.' text-'.$badgeColor.' rounded-circle">
                                <i class="ri-visa-fill"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 lh-base">'.$value['challan_no'].' '.get_payments($value['status']).'</h6>
                            <p class="text-muted mb-1">'.$value['std_name'].'</p>
                            <small class="mb-0 text-muted">'.($value['status'] == 1?timeAgo($value['paid_date']):'').'</small>
                        </div>
                    </div>';
                }
                echo'
            </div>
        </div>
        <!--
        <div class="p-3">
            <h6 class="text-muted mb-3 text-uppercase">Products Reviews</h6>
            <div class="swiper vertical-swiper" style="height: 250px;">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="card border border-dashed shadow-none">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 avatar-sm">
                                        <div class="avatar-title bg-light rounded">
                                            <img src="assets/images/companies/img-1.png" alt="" height="30">
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div>
                                            <p class="text-muted mb-1 fst-italic text-truncate-two-lines">
                                                " Great product and looks great, lots of
                                                features. "</p>
                                            <div class="fs-11 align-middle text-warning">
                                                <i class="ri-star-fill"></i>
                                                <i class="ri-star-fill"></i>
                                                <i class="ri-star-fill"></i>
                                                <i class="ri-star-fill"></i>
                                                <i class="ri-star-fill"></i>
                                            </div>
                                        </div>
                                        <div class="text-end mb-0 text-muted">
                                            - by <cite title="Source Title">Force
                                                Medicines</cite>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card border border-dashed shadow-none">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="assets/images/users/avatar-3.jpg" alt="" class="avatar-sm rounded">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div>
                                            <p class="text-muted mb-1 fst-italic text-truncate-two-lines">
                                                " Amazing template, very easy to
                                                understand and manipulate. "</p>
                                            <div class="fs-11 align-middle text-warning">
                                                <i class="ri-star-fill"></i>
                                                <i class="ri-star-fill"></i>
                                                <i class="ri-star-fill"></i>
                                                <i class="ri-star-fill"></i>
                                                <i class="ri-star-half-fill"></i>
                                            </div>
                                        </div>
                                        <div class="text-end mb-0 text-muted">
                                            - by <cite title="Source Title">Henry
                                                Baird</cite>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card border border-dashed shadow-none">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 avatar-sm">
                                        <div class="avatar-title bg-light rounded">
                                            <img src="assets/images/companies/img-8.png" alt="" height="30">
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div>
                                            <p class="text-muted mb-1 fst-italic text-truncate-two-lines">
                                                "Very beautiful product and Very helpful
                                                customer service."</p>
                                            <div class="fs-11 align-middle text-warning">
                                                <i class="ri-star-fill"></i>
                                                <i class="ri-star-fill"></i>
                                                <i class="ri-star-fill"></i>
                                                <i class="ri-star-line"></i>
                                                <i class="ri-star-line"></i>
                                            </div>
                                        </div>
                                        <div class="text-end mb-0 text-muted">
                                            - by <cite title="Source Title">Zoetic
                                                Fashion</cite>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card border border-dashed shadow-none">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="assets/images/users/avatar-2.jpg" alt="" class="avatar-sm rounded">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div>
                                            <p class="text-muted mb-1 fst-italic text-truncate-two-lines">
                                                " The product is very beautiful. I like
                                                it. "</p>
                                            <div class="fs-11 align-middle text-warning">
                                                <i class="ri-star-fill"></i>
                                                <i class="ri-star-fill"></i>
                                                <i class="ri-star-fill"></i>
                                                <i class="ri-star-half-fill"></i>
                                                <i class="ri-star-line"></i>
                                            </div>
                                        </div>
                                        <div class="text-end mb-0 text-muted">
                                            - by <cite title="Source Title">Nancy
                                                Martino</cite>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        -->
    </div>
</div>';