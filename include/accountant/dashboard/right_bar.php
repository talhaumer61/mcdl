<?php
echo'
<div class="card h-100 rounded-0">
    <div class="card-body p-0">
        <div class="p-3 mt-2">
            <h6 class="text-muted mb-3 text-uppercase">Top Enrollments</h6>
            <ol class="ps-3 text-muted">';
                foreach ($ENROLLED_COURSES as $key => $value) {
                    echo'
                    <li class="py-1">
                        <a href="#" class="text-muted">'.$value['curs_name'].'</a>
                        <br>
                        '.get_enroll_type($value['id_type']).'
                        <span class="badge rounded-pill bg-success float-end">'.number_format($value['std_count']).'</span>
                    </li>';
                }
                echo'
            </ol>
            <div class="mt-3 text-center">
                <a href="'.SITE_URL.'courses.php?type=1" class="text-muted text-decoration-underline">View all</a>
            </div>
        </div>
        <div class="p-3">
            <h6 class="text-muted mb-0 text-uppercase">Recent Challans</h6>
        </div>
        <div data-simplebar class="p-3 pt-0">
            <div class="acitivity-timeline acitivity-main">';
                $condition = array(
                                    'select'       =>  'ch.challan_no, ch.status, ch.paid_date, s.std_name'
                                    ,'join'         =>  'INNER JOIN '.STUDENTS.' s ON s.std_id = ch.id_std'
                                    ,'where'        =>  array(
                                                                'ch.is_deleted'  => 0
                                                            )
                                    ,'limit'        =>  ' 15 '
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
                            <h6 class="mb-1 lh-base">'.$value['challan_no'].' <span class="float-end">'.get_payments($value['status']).'<span></h6>
                            <p class="text-muted mb-1">'.$value['std_name'].'</p>
                            <small class="mb-0 text-muted">'.($value['status'] == 1?timeAgo($value['paid_date']):'').'</small>
                        </div>
                    </div>';
                }
                echo'                
                <div class="mt-3 text-center">
                    <a href="'.SITE_URL.'challans.php" class="text-muted text-decoration-underline">View all</a>
                </div>
            </div>
        </div>
    </div>
</div>';