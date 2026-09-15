<?php
// Assumes 'dblms', 'cleanvars', table constants, and helper functions are available in this scope.

if ($data_arr['method_name'] == "enrollment_request") {

    // --- Validation ---
    if (!isset($data_arr['std_id']) || empty($data_arr['std_id'])) {
        $rowjson['success'] = 0;
        $rowjson['MSG'] = 'User ID is required.';
    } else {
        // --- Initialization ---
        $userId = cleanvars($data_arr['std_id']);
        $country = isset($data_arr['country']) ? cleanvars($data_arr['country']) : 'pk';
        $invoice_items = [];
        $total = 0;
        $found = false; // To track if the item from request is already in the wishlist

        // --- Fetch User's Organization ID (Replaces Session) ---
        // This query is added to get the organization ID which was previously in the session.
        $userCondition = [
            'select' => 'id_org',
            'where' => ['std_id' => $userId],
            'return_type' => 'single'
        ];
        $user_data = $dblms->getRows(STUDENTS, $userCondition);
        $organizationId = $user_data['id_organization'] ?? null;


        // --- 1. Fetch Wishlist Items (Same as original) ---
        $wishlistCondition = [
            'select' => 'w.wl_id ,w.id_type ,w.id_curs, w.id_mas, w.id_ad_prg, c.curs_href, 
                        c.curs_name, c.curs_photo ,m.mas_href, m.mas_name, m.mas_photo ,p.prg_href, 
                        p.prg_name, p.prg_photo',
            'join' => 'LEFT JOIN ' . COURSES . ' c ON c.curs_id = w.id_curs
                    LEFT JOIN ' . MASTER_TRACK . ' m ON m.mas_id = w.id_mas
                    LEFT JOIN ' . ADMISSION_PROGRAMS . ' ap ON ap.id = w.id_ad_prg
                    LEFT JOIN ' . PROGRAMS . ' p ON p.prg_id = ap.id_prg',
            'where' => ['w.id_std' => $userId],
            'order_by' => "w.wl_id DESC",
            'return_type' => 'all'
        ];

        // This block is a special case from the original file, handle it similarly.
        if (isset($data_arr['item_type']) && ($data_arr['item_type'] == 5)) {
            $wishlistCondition['where']['w.id_curs'] = cleanvars($data_arr['id']);
            $wishlistCondition['where']['w.id_mas'] = 0;
            $wishlistCondition['where']['w.id_ad_prg'] = 0;
        }
        $WISHLIST = $dblms->getRows(WISHLIST . ' w', $wishlistCondition);

        // --- 2. Fetch Organization Discount (Same as original) ---
        $orgCondition = [
            'select' => 'o.org_link_to, o.org_percentage',
            'join' => 'INNER JOIN ' . ADMINS . ' AS a ON a.adm_id = o.id_loginid',
            'where' => [
                'o.org_status' => 1,
                'o.is_deleted' => 0,
                'o.org_id' => $organizationId,
            ],
            'return_type' => 'single',
        ];
        $ORGANIZATIONS = $dblms->getRows(SKILL_AMBASSADOR . ' AS o', $orgCondition);
        $currency_code = ''; // Initialize currency code

        // --- 3. Process Wishlist Items ---
        if ($WISHLIST) {
            foreach ($WISHLIST as $row) {
                $idWish = '';
                $name = '';

                // Determine item details based on type
                $photo = SITE_URL. 'uploads/images/default_curs.jpg';
                if ($row['id_type'] == 1) {
                    $idWish = $row['id_ad_prg'];
                    $name = $row['prg_name'];
                    $photo = SITE_URL. 'uploads/images/programs/' . $row['prg_photo'];
                } elseif ($row['id_type'] == 2) {
                    $idWish = $row['id_mas'];
                    $name = $row['mas_name'];
                    $photo = SITE_URL. 'uploads/images/admissions/master_track/' . $row['mas_photo'];
                } elseif ($row['id_type'] == 3 || $row['id_type'] == 4) {
                    $idWish = $row['id_curs'];
                    $name = $row['curs_name'];
                    $photo = SITE_URL. 'uploads/images/courses/' . $row['curs_photo'];
                }

                if (isset($data_arr['type']) && ($data_arr['type'] == $row['id_type']) && $idWish == $data_arr['id']) {
                    $found = true;
                }

                // Get Price (ADMISSION_OFFERING)
                $priceCondition = [
                    'select' => 'ao.admoff_amount, ao.admoff_amount_in_usd, ao.id_type',
                    'where' => [
                        'ao.admoff_status' => 1,
                        'ao.admoff_type' => cleanvars($row['id_type']),
                        'ao.admoff_degree' => cleanvars($idWish)
                    ],
                    'return_type' => 'single'
                ];
                $ADMISSION_OFFERING = $dblms->getRows(ADMISSION_OFFERING . ' AS ao', $priceCondition);

                // Get Website Discount
                $discountCondition = [
                    'select' => 'd.discount_id, d.discount_from, d.discount_to, dd.discount, dd.discount_type',
                    'join' => 'INNER JOIN ' . DISCOUNT_DETAIL . ' AS dd ON d.discount_id = dd.id_setup AND dd.id_curs = "' . $idWish . '"',
                    'where' => [
                        'd.discount_status' => '1',
                        'd.is_deleted' => '0'
                    ],
                    'search_by' => ' AND d.discount_from <= CURRENT_DATE AND d.discount_to >= CURRENT_DATE ',
                    'return_type' => 'single'
                ];
                $DISCOUNT = $dblms->getRows(DISCOUNT . ' AS d ', $discountCondition);

                // Calculate Final Price
                $currentDate = date('Y-m-d');
                $admoff_amount = ( $country = 'pk' ? $ADMISSION_OFFERING['admoff_amount'] : $ADMISSION_OFFERING['admoff_amount_in_usd']);
                $currency_code = ( $country = 'pk' ? 'PKR' : 'USD');
                $discount_web = 0;
                $discount_org = 0;

                if ($DISCOUNT && ($currentDate >= $DISCOUNT['discount_from'] && $currentDate <= $DISCOUNT['discount_to'])) {
                    $discount_web = ($DISCOUNT['discount_type'] == 1) ? $DISCOUNT['discount'] : ($admoff_amount * ($DISCOUNT['discount'] / 100));
                }

                if ($ORGANIZATIONS && (date('Y-m-d', strtotime($ORGANIZATIONS['org_link_to'])) >= $currentDate)) {
                    $discount_org = (($ORGANIZATIONS['org_percentage'] / 100) * $admoff_amount);
                }

                $admoff_amount -= max($discount_org, $discount_web);
                $final_price = ($ADMISSION_OFFERING['id_type'] != 3 ? $admoff_amount : 0);
                $total += $final_price;

                // Add item to the response array
                $invoice_items[] = [
                    'item_id'           => $idWish,
                    'item_name'         => $name,
                    'item_photo'        => $photo,
                    'item_amount'       => "$final_price",
                    'enroll_type'       => $row['id_type'],
                    'enroll_type_name'  => get_offering_type($row['id_type']),
                    'fee_type'          => ''.$ADMISSION_OFFERING['id_type'].'',
                    'fee_type_name'     => get_fee_type($ADMISSION_OFFERING['id_type'])
                ];
            }
        }

        // --- 4. Process Item from Request if not in Wishlist (Same logic as original) ---
        if (!$found && isset($data_arr['id'])) {
            $item_id = cleanvars($data_arr['id']);
            $item_type = cleanvars($data_arr['type']);
        
            // Get item details and price
            $condition = [
                'where' => [
                    'ao.admoff_status' => 1,
                    'ao.is_deleted' => 0,
                    'ao.admoff_degree' => $item_id,
                    'ao.admoff_type' => $item_type
                ],
                'return_type' => 'single'
            ];
        
            if ($item_type == 1) {
                $condition['select'] = 'ao.admoff_degree, p.prg_name as name, p.prg_photo as photo, ao.admoff_amount, ao.admoff_amount_in_usd, ao.id_type';
                $condition['join'] = 'LEFT JOIN ' . ADMISSION_PROGRAMS . ' ap ON ap.id = ao.admoff_degree
                                    LEFT JOIN ' . PROGRAMS . ' p ON p.prg_id = ap.id_prg';
            } elseif ($item_type == 2) {
                $condition['select'] = 'ao.admoff_degree, m.mas_name as name, m.mas_photo as photo, ao.admoff_amount, ao.admoff_amount_in_usd, ao.id_type';
                $condition['join'] = 'LEFT JOIN ' . MASTER_TRACK . ' m ON m.mas_id = ao.admoff_degree';
            } elseif ($item_type == 3 || $item_type == 4) {
                $condition['select'] = 'ao.admoff_degree, c.curs_name as name, c.curs_photo as photo, ao.admoff_amount, ao.admoff_amount_in_usd, ao.id_type';
                $condition['join'] = 'LEFT JOIN ' . COURSES . ' c ON c.curs_id = ao.admoff_degree';
            }
        
            $ADMISSION_OFFERING = $dblms->getRows(ADMISSION_OFFERING . ' ao', $condition);
        
            if($ADMISSION_OFFERING) {
                // Get Website Discount
                $discountCondition = [
                    'select'      => 'd.discount_id, d.discount_from, d.discount_to, dd.discount, dd.discount_type',
                    'join'        => 'INNER JOIN ' . DISCOUNT_DETAIL . ' AS dd ON d.discount_id = dd.id_setup AND dd.id_curs = "' . $item_id . '"',
                    'where'       => ['d.discount_status' => '1', 'd.is_deleted' => '0'],
                    'search_by'   => ' AND d.discount_from <= CURRENT_DATE AND d.discount_to >= CURRENT_DATE ',
                    'return_type' => 'single'
                ];
                $DISCOUNT = $dblms->getRows(DISCOUNT . ' AS d ', $discountCondition);
        
                // Calculate final price
                $currentDate = date('Y-m-d');
                $admoff_amount = ( $country = 'PKR' ? $ADMISSION_OFFERING['admoff_amount'] : $ADMISSION_OFFERING['admoff_amount_in_usd']);
                $currency_code = ( $country = 'PKR' ? 'PKR' : 'USD');
                $discount_web = 0;
                $discount_org = 0;
        
                if ($DISCOUNT && ($currentDate >= $DISCOUNT['discount_from'] && $currentDate <= $DISCOUNT['discount_to'])) {
                    $discount_web = ($DISCOUNT['discount_type'] == 1) ? $DISCOUNT['discount'] : ($admoff_amount * ($DISCOUNT['discount'] / 100));
                }
        
                if ($ORGANIZATIONS && (date('Y-m-d', strtotime($ORGANIZATIONS['org_link_to'])) >= $currentDate)) {
                    $discount_org = (($ORGANIZATIONS['org_percentage'] / 100) * $admoff_amount);
                }
        
                $admoff_amount -= max($discount_org, $discount_web);
        
                // Apply Referral Discount if ref_hash is provided
                if (isset($data_arr['ref_hash']) && !empty($data_arr['ref_hash'])) {
                    $ref_array = explode(',', get_dataHashingOnlyExp($data_arr['ref_hash'], false));
                    $ref_id = cleanvars($ref_array[2]);
                    $adm_email = cleanvars($ref_array[3]);
        
                    $refCondition = [
                        'select' => 'r.ref_percentage',
                        'join' => 'INNER JOIN ' . COURSES . ' AS c ON c.curs_id = "' . $ADMISSION_OFFERING['admoff_degree'] . '"
                                INNER JOIN ' . REFERRAL_TEACHER_SHARING . ' AS rt ON rt.id_ref = r.ref_id AND FIND_IN_SET("' . $adm_email . '", rt.std_emails) AND rt.ref_shr_status = "1" AND rt.is_deleted = "0"
                                LEFT JOIN ' . ADMINS . ' AS a ON FIND_IN_SET(a.adm_email, rt.std_emails) AND a.adm_status = "1" AND a.is_deleted = "0"',
                        'where' => ['r.is_deleted' => 0, 'r.ref_status' => 1, 'r.ref_id' => $ref_id],
                        'group_by' => ' a.adm_id ',
                        'search_by' => ' AND r.ref_date_time_from < "' . date('Y-m-d H:i:s') . '" AND r.ref_date_time_to > "' . date('Y-m-d H:i:s') . '" AND FIND_IN_SET("' . cleanvars($ADMISSION_OFFERING['admoff_degree']) . '", r.id_curs)',
                        'return_type' => 'single'
                    ];
                    $REFERRAL_CONTROL = $dblms->getRows(REFERRAL_CONTROL . ' AS r', $refCondition);
        
                    if ($REFERRAL_CONTROL) {
                        $admoff_amount -= ($admoff_amount * ($REFERRAL_CONTROL['ref_percentage'] / 100));
                    }
                }
        
                $final_price = ($ADMISSION_OFFERING['id_type'] != 3 ? $admoff_amount : 0);
                $total += $final_price;
        
                // Build Photo URL
                $photo = SITE_URL. 'uploads/images/default_curs.jpg';
                $photo_path = '';
                if ($item_type == 1) {
                    $photo_path = SITE_URL. 'uploads/images/programs/' . $ADMISSION_OFFERING['photo'];
                } elseif ($item_type == 2) {
                    $photo_path = SITE_URL . 'uploads/images/admissions/master_track/' . $ADMISSION_OFFERING['photo'];
                } elseif ($item_type == 3 || $item_type == 4) {
                    $photo_path = SITE_URL . 'uploads/images/courses/' . $ADMISSION_OFFERING['photo'];
                }
                if(check_file_exists($photo_path)) {
                    $photo = $photo_path;
                }
        
                // Add item to response array
                $invoice_items[] = [
                    'item_id'           =>  $ADMISSION_OFFERING['admoff_degree'],
                    'item_name'         =>  $ADMISSION_OFFERING['name'],
                    'item_photo'        =>  $photo,
                    'item_amount'       =>  "$final_price",
                    'enroll_type'       =>  $item_type,
                    'enroll_type_name'  =>  get_offering_type($item_type),
                    'fee_type'          =>  $ADMISSION_OFFERING['id_type'],
                    'fee_type_name'     =>  get_fee_type($ADMISSION_OFFERING['id_type'])
                ];
            }
        }
        
        // --- 5. Final API Response ---
        if (!empty($invoice_items)) {
            $rowjson['success'] = 1;
            $rowjson['MSG'] = 'Invoice data found.';
            $rowjson['invoice_items'] = [
                'items' => $invoice_items,
                'grand_total' => "$total",
                'currency_code' => $currency_code,
            ];
        } else {
            $rowjson['invoice_items'] = [];
            $rowjson['success'] = 0;
            $rowjson['MSG'] = 'No items found for enrollment.';
        }
    }
}

?>