<?php
 // ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
							'select' 		=>	"quiz_id "
							,'where' 		=>	array( 
														 'is_deleted'			=>	'0'	
														,'quiz_title'			=>	cleanvars($_POST['quiz_title'])
														,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'id_curs'				=>	cleanvars($_POST['id'])
													)
							,'return_type' 	=>	'count' 
						); 
	if($dblms->getRows(QUIZ, $condition)) {
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: courses.php?".$redirection."", true, 301);
		exit();
	}else{
		$totalMarks	 	= ($_POST['quiz_totalmarks_m'] + $_POST['quiz_totalmarks_s']);
		$passingMarks 	= intval((intval($_POST['quiz_pass_percentage']) / 100) * $totalMarks);
		$values = array(
							 'quiz_status'				=>	cleanvars($_POST['quiz_status'])
							,'is_publish'				=>	cleanvars($_POST['is_publish'])
							,'quiz_no_qns'				=>	cleanvars(count($_POST['qns_question']))
							,'quiz_title'				=>	cleanvars($_POST['quiz_title'])
							,'quiz_instruction'			=>	cleanvars($_POST['quiz_instruction'])
							,'id_week'					=>	cleanvars($_POST['id_week'])
							,'quiz_time'				=>	cleanvars($_POST['quiz_time'])
							,'quiz_pass_percentage'		=>	cleanvars($_POST['quiz_pass_percentage'])
							,'quiz_totalmarks'			=>	cleanvars($totalMarks)
							,'quiz_passingmarks'		=>	cleanvars($passingMarks)
							,'id_curs'					=>	cleanvars($_POST['id'])
							,'id_teacher'				=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
							,'academic_session'			=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_campus'				=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_added'					=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'				=>	date('Y-m-d G:i:s')
		);
        $sqllms	= $dblms->insert(QUIZ, $values);
		if($sqllms) { 
			// LATEST ID
			$latestID = $dblms->lastestid();

			foreach ($_POST['qns_question'] AS $key => $val) {
				$values = array(
								 'id_quiz'				=>	cleanvars($latestID)
								,'quiz_qns_level'		=>	cleanvars($_POST['qns_level'][$key])
								,'quiz_qns_type'		=>	cleanvars($_POST['qns_type'][$key])
								,'quiz_qns_question'	=>	cleanvars($val)
								,'quiz_qns_marks'		=>	cleanvars($_POST['qns_marks'][$key])
				);
				if ($_POST['qns_type'][$key] == 3) {
					$values['quiz_qns_option'] 			= $_POST['qns_options'][$key];
				}
				$sqllms	= $dblms->insert(QUIZ_QUESTIONS, $values);
			}
			// REMARKS
			sendRemark(moduleName(LMS_VIEW).' Added', '1', $latestID);
			sessionMsg('Successfully', 'Record Successfully Added.', 'success');
			header("Location: ".moduleName().".php?".$redirection."", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {
	$condition	=	array ( 
							'select' 		=>	"quiz_id"
							,'where' 		=>	array( 
														 'is_deleted'			=>	'0'	
														,'quiz_title'			=>	cleanvars($_POST['quiz_title'])
														,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'id_curs'				=>	cleanvars($_POST['id'])
													)
							,'not_equal' 	=>	array( 
														'quiz_id'				=>	cleanvars(LMS_EDIT_ID)
													)				
							,'return_type' 	=>	'count'
						); 
	if($dblms->getRows(QUIZ, $condition, $sql)) {
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: courses.php?".$redirection."", true, 301);
		exit();
	}else{	
		$totalMarks	 	= ($_POST['quiz_totalmarks_m'] + $_POST['quiz_totalmarks_s']);
		$passingMarks 	= intval((intval($_POST['quiz_pass_percentage']) / 100) * $totalMarks);
		$values = array(
							 'quiz_status'				=>	cleanvars($_POST['quiz_status'])
							,'is_publish'				=>	cleanvars($_POST['is_publish'])
							,'quiz_no_qns'				=>	cleanvars(count($_POST['qns_question']))
							,'quiz_title'				=>	cleanvars($_POST['quiz_title'])
							,'quiz_instruction'			=>	cleanvars($_POST['quiz_instruction'])
							,'id_week'					=>	cleanvars($_POST['id_week'])
							,'quiz_time'				=>	cleanvars($_POST['quiz_time'])
							,'quiz_pass_percentage'		=>	cleanvars($_POST['quiz_pass_percentage'])
							,'quiz_totalmarks'			=>	cleanvars($totalMarks)
							,'quiz_passingmarks'		=>	cleanvars($passingMarks)
							,'id_curs'					=>	cleanvars($_POST['id'])
							,'id_teacher'				=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
							,'academic_session'			=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_campus'				=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_added'					=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'				=>	date('Y-m-d G:i:s')
					   );
		$sqllms = $dblms->Update(QUIZ, $values , "WHERE quiz_id  = '".cleanvars(LMS_EDIT_ID)."'");
		if($sqllms) { 
			// LATEST ID
			$latestID = LMS_EDIT_ID;

			$sqllms	= $dblms->querylms('DELETE FROM '.QUIZ_QUESTIONS.' WHERE id_quiz = '.cleanvars($latestID).'');

			foreach ($_POST['qns_question'] AS $key => $val) {
				$values = array(
								 'id_quiz'				=>	cleanvars($latestID)
								,'quiz_qns_level'		=>	cleanvars($_POST['qns_level'][$key])
								,'quiz_qns_type'		=>	cleanvars($_POST['qns_type'][$key])
								,'quiz_qns_question'	=>	cleanvars($val)
								,'quiz_qns_marks'		=>	cleanvars($_POST['qns_marks'][$key])
				);
				if ($_POST['qns_type'][$key] == 3) {
					$values['quiz_qns_option'] 			= $_POST['qns_options'][$key];
				}
				$sqllms	= $dblms->insert(QUIZ_QUESTIONS, $values);
			}

			// REMARKS
			sendRemark(moduleName(LMS_VIEW).' Updated', '2', $latestID);
			sessionMsg('Successfully', 'Record Successfully Added.', 'success');
			header("Location: ".moduleName().".php?".$redirection."", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])) {
	$values = array(
						 'id_deleted'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'		=>	1
						,'ip_deleted'		=>	cleanvars($ip)
						,'date_deleted'		=>	date('Y-m-d H:i:s')
					);   
	$sqlDel = $dblms->Update(QUIZ , $values , "WHERE quiz_id  = '".cleanvars($_GET['deleteid'])."'");
	if($sqlDel) { 
		sendRemark(moduleName(LMS_VIEW).' Added', '1', $latestID);
		sessionMsg('Successfully', 'Record Successfully Added.', 'success');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}
}
?>