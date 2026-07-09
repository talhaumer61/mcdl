<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$programArray	= explode('|',$_POST['program']);
	$id_prg			= cleanvars($programArray[0]);
	$program		= cleanvars($programArray[1]);
	$eligibility	= cleanvars($programArray[2]);		

	$condition	=	array ( 
							 'select'		=>	'id'
							,'where'		=>	array( 
														 'id_prg'				=> $id_prg
														,'academic_sess'		=> cleanvars($_POST['academic_sess'])
														,'id_campus'			=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'is_deleted'			=> '0'	
													)
							,'return_type' 	=>	'count' 
							); 
	if($dblms->getRows(ADMISSION_PROGRAMS, $condition)) {
		sessionMsg('Error', 'Record Already Exists', 'error');
		header("Location: ".moduleName().".php", true, 301);
		exit();
	}else{
		$morning	= (isset($_POST['morning']))? '1': '2';
		$evening	= (isset($_POST['evening']))? '1': '2';
		$weekend	= (isset($_POST['weekend']))? '1': '2';

		$values = array(
							 'status'					=> cleanvars($_POST['status'])
							,'id_prg'					=> $id_prg
							,'program'					=> $program
							,'eligibility_criteria'		=> cleanvars($_POST['eligibility_criteria'])
							,'totalseats'				=> cleanvars($_POST['totalseats'])
							,'academic_sess'			=> cleanvars($_POST['academic_sess'])
							,'study_mode'				=> cleanvars($_POST['study_mode'])
							,'entrytest'				=> cleanvars($_POST['entrytest'])
							,'deposit'					=> cleanvars($_POST['deposit'])
							,'payment_per_smester'		=> cleanvars($_POST['payment_per_smester'])
							,'total_payments'			=> cleanvars($_POST['total_payments'])
							,'total_package'			=> cleanvars($_POST['total_package'])
							,'examination_fee'			=> cleanvars($_POST['examination_fee'])
							,'portal_fee'				=> cleanvars($_POST['portal_fee'])
							,'library_fee'				=> cleanvars($_POST['library_fee'])
							,'student_card_fee'			=> cleanvars($_POST['student_card_fee'])
							,'library_mag_fee'			=> cleanvars($_POST['library_mag_fee'])
							,'classdays'				=> cleanvars($_POST['classdays'])
							,'shortdetail'				=> cleanvars($_POST['shortdetail'])
							,'detail'					=> cleanvars($_POST['detail'])
							,'intro_video'				=> cleanvars($_POST['intro_video'])
							,'prg_for'					=> cleanvars($_POST['prg_for'])
							,'apply_enroll'				=> cleanvars($_POST['apply_enroll'])
							,'cohorts_deadlines'		=> cleanvars($_POST['cohorts_deadlines'])
							,'class_profile'			=> cleanvars($_POST['class_profile'])
							,'programme_length'			=> cleanvars($_POST['programme_length'])
							,'career_outcomes'			=> cleanvars($_POST['career_outcomes'])
							,'alumni_benefits'			=> cleanvars($_POST['alumni_benefits'])
							,'id_language'				=> cleanvars($_POST['id_language'])
							,'metakeyword'				=> cleanvars($_POST['metakeyword'])
							,'metadescription'			=> cleanvars($_POST['metadescription'])
							,'id_campus'				=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'morning'					=> $morning
							,'evening'					=> $evening
							,'weekend'					=> $weekend
							,'id_added'					=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'				=> date('Y-m-d G:i:s')
						); 		
		$sqllms = $dblms->insert(ADMISSION_PROGRAMS, $values);
		if($sqllms) { 
			// LATEST ID
			$lastestID = $dblms->lastestid();
			// REMARKS
			sendRemark(moduleName(false).' Added', '1', $lastestID);
			sessionMsg('Successfully', 'Record Successfully Added.', 'success');
			header("Location: ".moduleName().".php", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {
	$programArray	= explode('|',$_POST['program']);
	$id_prg			= cleanvars($programArray[0]);

	$condition	=	array ( 
								 'select' 	=> "id"
								,'where' 	=> array( 
														 'id_prg'				=> $id_prg
														,'academic_sess'		=> cleanvars($_POST['academic_sess'])
														,'id_campus'			=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'is_deleted'			=> '0'	
													)
								,'not_equal' 	=> array( 
															'id'				=>	cleanvars(LMS_EDIT_ID)
														)
								,'return_type' 	=> 'count' 
							);
	if($dblms->getRows(ADMISSION_PROGRAMS, $condition)) {
		sessionMsg('Error', 'Record Already Exists', 'danger');
		header("Location: ".moduleName().".php", true, 301);
		exit();
	}else{
		$program		= cleanvars($programArray[1]);
		$eligibility	= cleanvars($programArray[2]);	
		$morning		= (isset($_POST['morning']))? '1': '2';
		$evening		= (isset($_POST['evening']))? '1': '2';
		$weekend		= (isset($_POST['weekend']))? '1': '2';

		$values = array(
							 'status'					=> cleanvars($_POST['status'])
							,'id_prg'					=> $id_prg
							,'program'					=> $program
							,'eligibility_criteria'		=> cleanvars($_POST['eligibility_criteria'])
							,'totalseats'				=> cleanvars($_POST['totalseats'])
							,'academic_sess'			=> cleanvars($_POST['academic_sess'])
							,'study_mode'				=> cleanvars($_POST['study_mode'])
							,'entrytest'				=> cleanvars($_POST['entrytest'])
							,'deposit'					=> cleanvars($_POST['deposit'])
							,'payment_per_smester'		=> cleanvars($_POST['payment_per_smester'])
							,'total_payments'			=> cleanvars($_POST['total_payments'])
							,'total_package'			=> cleanvars($_POST['total_package'])
							,'examination_fee'			=> cleanvars($_POST['examination_fee'])
							,'portal_fee'				=> cleanvars($_POST['portal_fee'])
							,'library_fee'				=> cleanvars($_POST['library_fee'])
							,'student_card_fee'			=> cleanvars($_POST['student_card_fee'])
							,'library_mag_fee'			=> cleanvars($_POST['library_mag_fee'])
							,'classdays'				=> cleanvars($_POST['classdays'])
							,'shortdetail'				=> cleanvars($_POST['shortdetail'])
							,'detail'					=> cleanvars($_POST['detail'])
							,'intro_video'				=> cleanvars($_POST['intro_video'])
							,'prg_for'					=> cleanvars($_POST['prg_for'])
							,'apply_enroll'				=> cleanvars($_POST['apply_enroll'])
							,'cohorts_deadlines'		=> cleanvars($_POST['cohorts_deadlines'])
							,'class_profile'			=> cleanvars($_POST['class_profile'])
							,'programme_length'			=> cleanvars($_POST['programme_length'])
							,'career_outcomes'			=> cleanvars($_POST['career_outcomes'])
							,'alumni_benefits'			=> cleanvars($_POST['alumni_benefits'])
							,'id_language'				=> cleanvars($_POST['id_language'])
							,'metakeyword'				=> cleanvars($_POST['metakeyword'])
							,'metadescription'			=> cleanvars($_POST['metadescription'])
							,'id_campus'				=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'morning'					=> $morning
							,'evening'					=> $evening
							,'weekend'					=> $weekend
							,'id_modify'				=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'				=> date('Y-m-d G:i:s')
						);
		$sqllms = $dblms->Update(ADMISSION_PROGRAMS , $values , "WHERE id = '".cleanvars(LMS_EDIT_ID)."'");
		if($sqllms) { 
			// LASTEST ID
			$lastestID = LMS_EDIT_ID;
			// REMARKS
			sendRemark(moduleName(false).' Updated', '2', $lastestID);
			sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
			header("Location: ".moduleName().".php", true, 301);
			exit();
		}
	}	
}

// DELETE RECORD
if(isset($_GET['deleteid'])) {
	$lastestID = $_GET['deleteid'];
	
	$values = array(
						 'id_deleted'	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'	=>	'1'
						,'ip_deleted'	=>	cleanvars($ip)
						,'date_deleted'	=>	date('Y-m-d G:i:s')
					);
	$sqlDel = $dblms->Update(ADMISSION_PROGRAMS , $values , "WHERE id  = '".cleanvars($lastestID)."'");

	if($sqlDel) { 
		sendRemark(moduleName(false).' Deleted.', '3', $lastestID);
		sessionMsg('Success', 'Record Successfully Deleted.', 'success');
		exit();
		header("Location: ".moduleName().".php", true, 301);
	}
}

// MAKE STUDY SCHEME
if(isset($_POST['make_study_scheme'])) {
	$latestID = $_POST['id'];

	// DELETE OLD RECORD
	$dblms->querylms("DELETE FROM ".PROGRAMS_STUDY_SCHEME." WHERE id_ad_prg = ".$latestID);

	// INSERT NEW RECORD
	foreach ($_POST['id_curs'] as $curstype => $value):
		foreach ($value as $cat => $courses) {
			$values = array(
								 'id_ad_prg'	=>	$latestID
								,'id_curstype'	=>	$curstype
								,'id_cat'		=>	$cat
								,'id_curs'		=>	implode(',',$courses)
							); 
			$sqllms = $dblms->insert(PROGRAMS_STUDY_SCHEME, $values);
		}
	endforeach;

	// REMARKS
	sendRemark(moduleName(LMS_VIEW).' Updated', '2', $lastestID);
	sessionMsg("Success", "Record Successfully Updated.", "info");
	header("Location:".moduleName().".php", true, 301);
	exit();
}
?>