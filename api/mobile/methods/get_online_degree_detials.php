<?php
if($data_arr['method_name'] == "get_online_degree_detials") { 
		$allacademics 		= array();
		$academics 			= array();
		$careers 			= array();
		$core_courses		= array();
		$foundation_courses	= array();
		$elective_courses	= array();
		$admissions 		= array();
		// PROGRAM DETAIL
		$condition = array ( 
								 'select'       =>	'ap.id, p.prg_name, p.prg_photo,ap.programme_length , ap.detail, ap.prg_for, ap.apply_enroll, ap.cohorts_deadlines, ap.eligibility_criteria, ap.class_profile, p.prg_semesters, ap.payment_per_smester, p.prg_payments_per_semester, ap.total_payments, ap.total_package, p.prg_admission_fee, ap.examination_fee, ap.portal_fee, ap.library_fee, ap.library_mag_fee, ap.alumni_benefits, ap.career_outcomes'
								,'join'         =>  'INNER JOIN '.PROGRAMS.' p ON p.prg_id = ap.id_prg'
								,'where' 		    =>	array( 
																 'ap.is_deleted'    =>  0  
																,'ap.id_prg'    	=>  cleanvars($data_arr['degree_id'])
														)
								,'return_type'	=>	'single'
						); 
		$DEGREE = $dblms->getRows(ADMISSION_PROGRAMS.' ap', $condition);	

		$admissions['Who_is_this_degree_for']		= html_entity_decode(html_entity_decode($DEGREE['prg_for']));
		$admissions['how_to_apply_and_enrol']		= html_entity_decode(html_entity_decode($DEGREE['apply_enroll']));
		$admissions['cohorts_and_deadlines']		= html_entity_decode(html_entity_decode($DEGREE['cohorts_deadlines']));
		$admissions['eligibility_criteria']			= html_entity_decode(html_entity_decode($DEGREE['eligibility_criteria']));
		$admissions['class_profile']				= html_entity_decode(html_entity_decode($DEGREE['class_profile']));

		// ACADEMICS
		$academics['programme_length'] = html_entity_decode(html_entity_decode($DEGREE['programme_length']));
        $condition = array ( 
								 'select'       =>	'id_curs, id_curstype'
								,'where'        =>	array( 
															'id_ad_prg'   =>  cleanvars($DEGREE['id'])
													)
								,'return_type'	=>	'all'
							); 
		$scheme = $dblms->getRows(PROGRAMS_STUDY_SCHEME, $condition);
		foreach ($scheme as $key => $value) {
			$allCurs 	= array();
			$condition 	= array ( 
									 'select' 		=>	'curs_code, curs_name'
									,'where' 		=>	array( 
																 'curs_status' 	=> 1
																,'is_deleted' 	=> 0
															)
									,'search_by'	=>	' AND curs_id IN ('.$value['id_curs'].')'
									,'return_type'	=>	'all'
								); 
			$COURSES 	= $dblms->getRows(COURSES, $condition, $sql);
			foreach ($COURSES as $inkey => $invalue) {
				$curs['curs_code'] 	= $invalue['curs_code'];
				$curs['curs_name'] 	= $invalue['curs_name'];

				if ($value['id_curstype']==1) {
					array_push($core_courses, $curs);
				} else if ($value['id_curstype']==2) {
					array_push($foundation_courses, $curs);
				} else {
					array_push($elective_courses, $curs);
				}
			}
		}
		$academics['core_courses'] 					= $core_courses;
		$academics['foundation_courses'] 			= $foundation_courses;
		$academics['elective_courses'] 				= $elective_courses;

		// FINANCE
		$tuition_fee['semesters'] 					= $DEGREE['prg_semesters'];
		$tuition_fee['fee_semester'] 				= $DEGREE['payment_per_smester'];
		$tuition_fee['payments_semester'] 			= $DEGREE['prg_payments_per_semester'];
		$tuition_fee['total_no_of_payments'] 		= $DEGREE['total_payments'].' x '.$DEGREE['payment_per_smester']/$DEGREE['prg_payments_per_semester'];
		$tuition_fee['total_package'] 				= $DEGREE['total_package'];
		$allfinance['tuition_fee'] 					= $tuition_fee;

		$additional_charges['addmission_fee'] 		= $DEGREE['prg_admission_fee'];
		$additional_charges['examination_fee'] 		= $DEGREE['examination_fee'];
		$additional_charges['portal_fee'] 			= $DEGREE['portal_fee'];
		$additional_charges['library_fee'] 			= $DEGREE['library_fee'];
		$additional_charges['student_crad_fee'] 	= $DEGREE['library_mag_fee'];
		$additional_charges['library_magzine_fee'] 	= '';
		$allfinance['additional_charges'] 			= $additional_charges;

		// CAREERS
		$allcareers['title'] 	= 'Career Outcomes';
		$allcareers['detail'] 	= html_entity_decode(html_entity_decode($DEGREE['career_outcomes']));
		array_push($careers, $allcareers);
		$allcareers['title'] 	= 'Aliumni Benefits';
		$allcareers['detail'] 	= html_entity_decode(html_entity_decode($DEGREE['alumni_benefits']));
		array_push($careers, $allcareers);


		// CHECK FILE EXIST
		$photo      = SITE_URL.'uploads/images/default_curs.jpg';
		$file_url   = SITE_URL.'uploads/images/programs/'.$DEGREE['prg_photo'];
		if (check_file_exists($file_url)) {
			$photo = $file_url;
		}

		$degreedetail['id'] 				= intval($data_arr['degree_id']);
		$degreedetail['deg_photo'] 			= $photo;
		$degreedetail['org_name'] 			= 'Minhaj University Lahore';
		$degreedetail['prg_name'] 			= $DEGREE['prg_name'];
		$degreedetail['overview'] 			= html_entity_decode(html_entity_decode($DEGREE['detail']));
		$degreedetail['admissions'] 		= $admissions;
		$degreedetail['academics'] 			= $academics;
		$degreedetail['financing'] 			= $allfinance;
		$degreedetail['careers'] 			= $careers;
		$degreedetail['careers'] 			= $careers;
		$degreedetail['reviews'] 			= $data['reviewslist'];


		$rowjson['degree_detial']		= $degreedetail;
	} 