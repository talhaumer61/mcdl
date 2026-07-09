<?php
if ($data_arr['method_name'] == "get_enrollments") {

    $enrollments = [];
	if(isset($data_arr['std_id']) && $data_arr['std_id'] != '') {
		// Valid Student ID
		$std_id = $data_arr['std_id'];

        // 🔹 Query
        $conditions = array ( 
                         'select'       =>	'ch.issue_date, ch.total_amount, ch.currency_code, c.curs_name, c.curs_photo, mt.mas_name, mt.mas_photo, p.prg_photo, ap.program, ec.id_type, ec.secs_status, ec.id_mas, ec.id_ad_prg'
                        ,'join'         =>	'LEFT JOIN '.CHALLANS.' ch ON FIND_IN_SET(ec.secs_id,ch.id_enroll) AND ch.is_deleted = 0
                                             LEFT JOIN '.COURSES.' c ON c.curs_id = ec.id_curs AND c.curs_status = 1 AND c.is_deleted = 0
                                             LEFT JOIN '.MASTER_TRACK.' mt ON mt.mas_id = ec.id_mas AND mt.mas_status = 1 AND mt.is_deleted = 0
                                             LEFT JOIN '.ADMISSION_PROGRAMS.' ap ON ap.id = ec.id_ad_prg
                                             LEFT JOIN '.PROGRAMS.' p ON p.prg_id = ap.id_prg'
                        ,'where' 		=>	array( 
                                                     'ec.is_deleted'    => '0'
                                                    ,'ec.id_std' 	    => cleanvars($std_id) 
                                                )
                        ,'order_by'     =>	'ec.secs_id DESC'
                        ,'return_type'	=>	'all'
                    ); 
        $ENROLLED_COURSES = $dblms->getRows(ENROLLED_COURSES.' ec', $conditions, $sql);

        if ($ENROLLED_COURSES) {
            foreach ($ENROLLED_COURSES as $row) {
                $name = '';
                $file_url = '';
                $photo = SITE_URL . 'uploads/images/default_curs.jpg';

                // 🔹 Detect enrollment type
                if ($row['id_type'] == 1) {
                    $name     = $row['program'];
                    if(!empty($row['prg_photo'])){
                        $photo = SITE_URL . 'uploads/images/programs/' . $row['prg_photo'];
                    }
                } elseif ($row['id_type'] == 2) {
                    $name     = $row['mas_name'];
                    if(!empty($row['mas_photo'])){
                        $photo = SITE_URL . 'uploads/images/admissions/master_track/' . $row['mas_photo'];
                    }
                } elseif ($row['id_type'] == 3 || $row['id_type'] == 4) {
                    $name     = $row['curs_name'];
                    if(!empty($row['curs_photo'])){
                        $photo = SITE_URL . 'uploads/images/courses/' . $row['curs_photo'];
                    }
                } 

                foreach ($statusLeave as $secs_status) {
                    if ($secs_status['id'] == $row['secs_status']) {
                        $row['secs_status'] = $secs_status['name'];
                        break;
                    }
                } 

                $enrollments[] = array(
                    'name'          => $name,
                    'photo'         => $photo,
                    'type'          => get_offering_type($row['id_type']), // 🔹 readable type
                    'status'        => $row['secs_status'], // 🔹 readable status
                    'request_date'  => $row['issue_date'] ?? '',
                    'total_amount'  => $row['total_amount'] ?? '',
                    'currency_code' => $row['currency_code'] ?? ''
                );
            }
            $rowjson['success'] = 1;
            $rowjson['MSG'] = 'Enrollments fetched successfully.';
        } else {
            $rowjson['success'] = 0;
            $rowjson['MSG'] = 'No enrollments found.';
        }
	} else {
		$rowjson['success'] = 0;
		$rowjson['MSG'] = 'Student ID is required.';
	}
	$rowjson['enrollments_list'] = $enrollments;

	
}
