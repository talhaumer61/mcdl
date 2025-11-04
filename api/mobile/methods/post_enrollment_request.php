<?php

if ($data_arr['method_name'] == "post_enrollment_request") {

    // --- Input Validation and Setup ---
    if (
        empty($data_arr['std_id']) ||
        empty($data_arr['std_org']) ||
        empty($data_arr['user_id']) ||
        empty($data_arr['user_fullname']) ||
        empty($data_arr['user_email']) ||
        !isset($data_arr['items_list']) || !is_array($data_arr['items_list']) || empty($data_arr['items_list'])
    ) {
        $rowjson['success'] = 0;
        $rowjson['MSG'] = 'Required student, organization, user ID, user info, or items_list are missing or invalid.';
    }

    $std_id        = cleanvars($data_arr['std_id']);
    $std_org       = cleanvars($data_arr['std_org']);
    $user_id       = cleanvars($data_arr['user_id']);
    $user_fullname = cleanvars($data_arr['user_fullname']);
    $user_email    = cleanvars($data_arr['user_email']);

    $enrolledFreeAndPaid    = false;
    $enrolledLearnFree      = false;
    $total_amount  = 0;
    $currency_code = ( $data_arr['country'] == 'pk' ? 'PKR' : 'USD');

    $enroll_id      = [];
    $id_curs        = [];
    $id_type        = [];
    $curs_amount    = [];
    $lrn_type       = [];

    // --- Iterate Over Each Item in items_list ---
    foreach ($data_arr['items_list'] as $item) {
        $value = cleanvars($item['item_id']);
        $type = cleanvars($item['enroll_type']);
        $fee_type = cleanvars($item['fee_type']);
        $name = cleanvars($item['item_name']);
        $amount = cleanvars($item['item_amount']);

        // === PROGRAM ===
        if ($type == 1) {
            $condition = [
                'select' => 'secs_id',
                'where' => [
                    'id_ad_prg' => $value,
                    'id_type' => $type,
                    'id_std' => $std_id,
                    'is_deleted' => '0'
                ],
                'search_by' => ' AND secs_status IN (1,2)',
                'return_type' => 'count'
            ];
            if (!($dblms->getRows(ENROLLED_COURSES, $condition))) {

                // GET COURSES
                $condition = [
                    'select' => 'GROUP_CONCAT(id_curs) courses',
                    'where' => ['id_ad_prg' => $value],
                    'return_type' => 'single'
                ];
                $PROGRAMS_STUDY_SCHEME = $dblms->getRows(PROGRAMS_STUDY_SCHEME, $condition);

                $values = [
                    'secs_status' => (string)($fee_type == 3 ? 1 : 2),
                    'id_std' => $std_id,
                    'id_org' => $std_org,
                    'id_curs' => cleanvars($PROGRAMS_STUDY_SCHEME['courses']),
                    'id_ad_prg' => $value,
                    'id_type' => $type,
                    'id_added' => $user_id,
                    'date_added' => date('Y-m-d G:i:s')
                ];
                $sqllms = $dblms->insert(ENROLLED_COURSES, $values);

                if ($sqllms) {
                    if ($fee_type != 3) {
                        $enroll_id[] = $dblms->lastestid();
                        $id_curs[] = $value;
                        $id_type[] = $type;
                        $curs_amount[] = $amount;
                        $lrn_type[] = $fee_type;
                        $enrolledFreeAndPaid = true;
                        $enrolledLearnFree = false;
                        
                    } else if ($fee_type == 3 && $enrolledFreeAndPaid == false) {
                        $enrolledLearnFree = true;
                    }

                    get_SendMail([
                        'sender' => SMTP_EMAIL,
                        'senderName' => SITE_NAME_WEB,
                        'receiver' => $user_email,
                        'receiverName' => $user_fullname,
                        'subject' => "You enrolled in a course from " . TITLE_HEADER_WEB,
                        'body' => '
                            <p>
                                Congratulations on enrolling in the Degree (' . $name . ')! Here are the details and next steps to begin your learning journey.
                                <br><b>Orientation for New Learner</b><br>
                                Join course orientation to learn more about LMS, and get tips for success.
                                <br><b>Click here for orientation</b><br>
                                <a href="https://youtu.be/5ZEUEok9Mig" target="_blank">Orientation video!</a><br><br>
                                <b>Warm regards,</b><br>
                                Support Team<br><br>
                                ' . SMTP_EMAIL . '<br>' . SITE_NAME_WEB . ' <b>(' . TITLE_HEADER_WEB . ')</b><br>
                                <b>Minhaj University Lahore</b>
                            </p>
                        ',
                        'tokken' => SMTP_TOKEN,
                    ], 'send-mail');

                    $dblms->querylms('DELETE FROM ' . WISHLIST . ' WHERE id_ad_prg = "' . $value . '" AND id_type = "' . $type . '" AND id_std = "' . $std_id . '" ');
                }
            }
        }

        // === MASTER TRACK ===
        elseif ($type == 2) {
            $condition = [
                'select' => 'secs_id',
                'where' => [
                    'id_mas' => $value,
                    'id_std' => $std_id,
                    'id_type' => $type,
                    'is_deleted' => '0'
                ],
                'search_by' => ' AND secs_status IN (1,2)',
                'return_type' => 'count'
            ];
            if (!($dblms->getRows(ENROLLED_COURSES, $condition))) {

                $condition = [
                    'select' => 'GROUP_CONCAT(id_curs) courses',
                    'where' => ['id_mas' => $value],
                    'return_type' => 'single'
                ];
                $MASTER_TRACK_DETAIL = $dblms->getRows(MASTER_TRACK_DETAIL, $condition);

                $values = [
                    'secs_status' => (string)($fee_type == 3 ? 1 : 2),
                    'id_std' => $std_id,
                    'id_org' => $std_org,
                    'id_type' => $type,
                    'id_curs' => cleanvars($MASTER_TRACK_DETAIL['courses']),
                    'id_mas' => $value,
                    'id_added' => $user_id,
                    'date_added' => date('Y-m-d G:i:s')
                ];
                $sqllms = $dblms->insert(ENROLLED_COURSES, $values);

                if ($sqllms) {
                    if ($fee_type != 3) {
                        $enroll_id[] = $dblms->lastestid();
                        $id_curs[] = $value;
                        $id_type[] = $type;
                        $curs_amount[] = $amount;
                        $lrn_type[] = $fee_type;
                        $enrolledFreeAndPaid = true;
                        $enrolledLearnFree = false;
                        
                    } else if ($fee_type == 3 && $enrolledFreeAndPaid == false) {
                        $enrolledLearnFree = true;
                    }

                    get_SendMail([
                        'sender' => SMTP_EMAIL,
                        'senderName' => SITE_NAME_WEB,
                        'receiver' => $user_email,
                        'receiverName' => $user_fullname,
                        'subject' => "You enrolled in a course from " . TITLE_HEADER_WEB,
                        'body' => '
                            <p>
                                Congratulations on enrolling in the Master Track (' . $name . ')! Here are the details and next steps to begin your learning journey.
                                <br><b>Orientation for New Learner</b><br>
                                Join course orientation to learn more about LMS, and get tips for success.
                                <br><b>Click here for orientation</b><br>
                                <a href="https://youtu.be/5ZEUEok9Mig" target="_blank">Orientation video!</a><br><br>
                                <b>Warm regards,</b><br>
                                Support Team<br><br>
                                ' . SMTP_EMAIL . '<br>' . SITE_NAME_WEB . ' <b>(' . TITLE_HEADER_WEB . ')</b><br>
                                <b>Minhaj University Lahore</b>
                            </p>
                        ',
                        'tokken' => SMTP_TOKEN,
                    ], 'send-mail');

                    $dblms->querylms('DELETE FROM ' . WISHLIST . ' WHERE id_mas = "' . $value . '" AND id_type = "' . $type . '" AND id_std = "' . $std_id . '" ');
                }
            }
        }

        // === COURSE / e-Training ===
        elseif ($type == 3 || $type == 4) {
            $condition = [
                'select' => 'secs_id',
                'where' => [
                    'id_curs' => $value,
                    'id_std' => $std_id,
                    'id_type' => $type,
                    'is_deleted' => '0'
                ],
                'search_by' => ' AND secs_status IN (1,2)',
                'return_type' => 'count'
            ];
            if (!($dblms->getRows(ENROLLED_COURSES, $condition))) {
                $values = [
                    'secs_status' => (string)($fee_type == 3 ? 1 : 2),
                    'id_std' => $std_id,
                    'id_org' => $std_org,
                    'id_curs' => $value,
                    'id_type' => $type,
                    'id_added' => $user_id,
                    'date_added' => date('Y-m-d G:i:s')
                ];
                $sqllms = $dblms->insert(ENROLLED_COURSES, $values);

                if ($sqllms) {
                    if ($fee_type != 3) {
                        $enroll_id[] = $dblms->lastestid();
                        $id_curs[] = $value;
                        $id_type[] = $type;
                        $curs_amount[] = $amount;
                        $lrn_type[] = $fee_type;
                        $enrolledFreeAndPaid = true;
                        $enrolledLearnFree = false;
                        
                    } else if ($fee_type == 3 && $enrolledFreeAndPaid == false) {
                        $enrolledLearnFree = true;
                    }

                    get_SendMail([
                        'sender' => SMTP_EMAIL,
                        'senderName' => SITE_NAME_WEB,
                        'receiver' => $user_email,
                        'receiverName' => $user_fullname,
                        'subject' => "You enrolled in a course from " . TITLE_HEADER_WEB,
                        'body' => '
                            <p>
                                Congratulations on enrolling in the Course (' . $name . ')! Here are the details and next steps to begin your learning journey.
                                <br><b>Orientation for New Learner</b><br>
                                Join course orientation to learn more about LMS, and get tips for success.
                                <br><b>Click here for orientation</b><br>
                                <a href="https://youtu.be/5ZEUEok9Mig" target="_blank">Orientation video!</a><br><br>
                                <b>Warm regards,</b><br>
                                Support Team<br><br>
                                ' . SMTP_EMAIL . '<br>' . SITE_NAME_WEB . ' <b>(' . TITLE_HEADER_WEB . ')</b><br>
                                <b>Minhaj University Lahore</b>
                            </p>
                        ',
                        'tokken' => SMTP_TOKEN,
                    ], 'send-mail');

                    $dblms->querylms('DELETE FROM ' . WISHLIST . ' WHERE id_curs = "' . $value . '" AND id_type = "' . $type . '" AND id_std = "' . $std_id . '" ');
                }
            }
        }

        if ($fee_type != 3) {
            $total_amount += $amount;
        }
    }

    // --- Challan Creation and Processing ---
    if ($enrolledFreeAndPaid) {
        do {
            $trans_no   = date('ym').mt_rand(10000,99999);
            $sqlChallan = "SELECT challan_no FROM " . CHALLANS . " WHERE challan_no = '$trans_no'";
            $sqlCheck   = $dblms->querylms($sqlChallan);
        } while (mysqli_num_rows($sqlCheck) > 0);

        $issue_date = date('Y-m-d');
        $due_date   = date('Y-m-d', strtotime($issue_date . ' +15 days'));

        $values = array(
            'status'                    => '2', // Pending
            'id_std'                    => $std_id,
            'challan_no'                => $trans_no,
            'id_enroll'                 => cleanvars(implode(",", $enroll_id)),
            'without_discount_amount'   => cleanvars($total_amount), // This should be the total from the form
            'discount'                  => cleanvars($data_arr['discount'] ?? 0),
            'total_amount'              => $total_amount,
            'currency_code'             => $currency_code,
            'issue_date'                => $issue_date,
            'due_date'                  => $due_date,
            'id_added'                  => $user_id,
            'date_added'                => date('Y-m-d G:i:s')
        );
        $sqllms = $dblms->insert(CHALLANS, $values);
        if($sqllms){
            $challanID = $dblms->lastestid();
            $j=0; // Use a different index variable for the foreach loop
            foreach ($enroll_id as $value_enroll_id) { // Use a distinct variable name here
                $values = array(
                    'status'            => '1',
                    'id_curs'           => cleanvars($id_curs[$j]),
                    'id_type'           => cleanvars($id_type[$j]), // 1=prg, 2=mt, 3=curs, 4=trainging
                    'learn_type'        => cleanvars($lrn_type[$j]), // 1=paid, 2=free, 3=learn free
                    'amount'            => cleanvars($curs_amount[$j]),
                    'id_challan'        => $challanID,
                    'id_enroll'         => $value_enroll_id,
                    'id_added'          => $user_id,
                    'date_added'        => date('Y-m-d G:i:s')
                );
                $dblms->insert(CHALLAN_DETAIL, $values);
                $j++;
            }

            $challanRemarks = 'Challan Created of enrollment IDs : '.cleanvars(implode(",",$enroll_id)).', Request From Mobile App.';
            $values = array (
							 'id_user'		=>	cleanvars($user_id)
							,'id_record'	=>	cleanvars($challanID)
							,'filename'		=>	'post_enrollment_request.php'
							,'action'		=>	1
							,'dated'		=>	date('Y-m-d G:i:s')
							,'ip'			=>	cleanvars(LMS_IP)
							,'remarks'		=>	cleanvars($challanRemarks)
						);
		    $sqlRemarks = $dblms->insert(LOGS, $values);

            if($total_amount <= 0){ // Using the internal calculated total here, as per original logic for paid status
                $values = array(
                    'status'        => 1, // Paid
                    'paid_amount'   => $total_amount,
                    'paid_date'     => date('Y-m-d'),
                    'id_modify'     => $user_id,
                    'date_modify'   => date('Y-m-d G:i:s')
                );
                $sqllms = $dblms->Update(CHALLANS, $values , "WHERE challan_id = '".$challanID."' ");
                if($sqllms){
                    // enroll courses
                    $values = array(
                        'secs_status'   => '1',
                        'id_modify'     => $user_id,
                        'date_modify'   => date('Y-m-d G:i:s')
                    ); 
                    $sqllms = $dblms->Update(ENROLLED_COURSES, $values , "WHERE secs_id IN (".cleanvars(implode(",",$enroll_id)).") ");
        
                    // send in transactions
                    $values = array(
                        'trans_status'  => 1,
                        'trans_no'      => $trans_no,
                        'trans_amount'  => $total_amount,
                        'currency_code' => $currency_code,
                        'id_enroll'     => cleanvars(implode(",",$enroll_id)),
                        'id_std'        => $std_id,
                        'date'          => date('Y-m-d'),
                        'id_added'      => $user_id,
                        'date_added'    => date('Y-m-d G:i:s')
                    ); 
                    $sqllms = $dblms->insert(TRANSACTION, $values);
        
                    // REMARKS
                    $values = array (
							 'id_user'		=>	cleanvars($user_id)
							,'id_record'	=>	cleanvars($challanID)
							,'filename'		=>	'post_enrollment_request.php'
							,'action'		=>	2
							,'dated'		=>	date('Y-m-d G:i:s')
							,'ip'			=>	cleanvars(LMS_IP)
							,'remarks'		=>	'Challan marked as paid via Mobile App.'
						);
		            $sqlRemarks = $dblms->insert(LOGS, $values);
                    
                    $rowjson['success'] = 1;
                    $rowjson['MSG'] = 'Successfully Enrolled (Free of Cost).';

                } else {
                    $rowjson['success'] = 0;
                    $rowjson['MSG'] = 'Failed to update challan as paid.';
                }
            } else {
                $rowjson['success'] = 1;
                $rowjson['MSG']     = 'Enrollment Request Sent. Challan created.';
            }
        } else {
            $rowjson['success'] = 0;
            $rowjson['MSG']     = 'Error during challan creation.';
        }
    } elseif ($enrolledFreeAndPaid == false && $enrolledLearnFree) {
        $rowjson['success'] = 1;
        $rowjson['MSG'] = 'Successfully Enrolled (Learn Free Course).';
    } else {
        $rowjson['success'] = 0;
        $rowjson['MSG'] = 'Error during enrollment: No items were processed for enrollment.';
    }
}
?>