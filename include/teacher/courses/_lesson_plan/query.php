<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
							 'select' 		=>	"lesson_id"
							,'where' 		=>	array( 
														 'is_deleted'			=>	'0'
														,'lesson_topic'			=>	cleanvars($_POST['lesson_topic'])
														,'id_session'			=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'id_teacher'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
														,'id_curs'				=>	cleanvars(CURS_ID)
														,'id_lecture'			=>	cleanvars($_POST['id_lecture'])
													)
							,'return_type' 	=>	'count' 
						); 
	if($dblms->getRows(COURSES_LESSONS, $condition)) {		
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}else{		
		$values = array(
							 'lesson_status'			=>	cleanvars($_POST['lesson_status'])
							,'lesson_topic'				=>	cleanvars($_POST['lesson_topic'])
							,'lesson_content'			=>	cleanvars($_POST['lesson_content'])
							,'lesson_detail'			=>	cleanvars($_POST['lesson_detail'])
							,'lesson_video_code'		=>	cleanvars($_POST['lesson_video_code'])
							,'lesson_reading_detail'	=>	cleanvars($_POST['lesson_reading_detail'])
							,'id_week'					=>	cleanvars($_POST['id_week'])
							,'id_lecture'				=>	cleanvars($_POST['id_lecture'])
							,'id_parent_topic'			=>	cleanvars($_POST['id_parent_topic'])
							,'id_session'				=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_campus'				=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_teacher'				=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
							,'id_curs'					=>	cleanvars(CURS_ID)
							,'id_added'					=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'				=>	date('Y-m-d G:i:s')
						);
        $sqllms	= $dblms->insert(COURSES_LESSONS, $values);
		if($sqllms) { 
			// LATEST ID
			$latestID = $dblms->lastestid();
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
							'select' 		=>	"lesson_id"
							,'where' 		=>	array( 
														 'is_deleted'			=>	'0'	
														,'lesson_topic'			=>	cleanvars($_POST['lesson_topic'])
														,'id_session'			=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'id_teacher'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
														,'id_curs'				=>	cleanvars(CURS_ID)
														,'id_lecture'			=>	cleanvars($_POST['id_lecture'])
													)
							,'not_equal' 	=>	array( 
														'lesson_id'				=>	cleanvars($_POST['lesson_id'])
													)				
							,'return_type' 	=>	'count' 
						); 
	if($dblms->getRows(COURSES_LESSONS, $condition)) {	 
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}else{
		$values = array(
							 'lesson_status'			=>	cleanvars($_POST['lesson_status'])
						   	,'lesson_topic'				=>	cleanvars($_POST['lesson_topic'])
							,'lesson_content'			=>	cleanvars($_POST['lesson_content'])
						   	,'lesson_detail'			=>	cleanvars($_POST['lesson_detail'])
						   	,'lesson_video_code'		=>	cleanvars($_POST['lesson_video_code'])
						   	,'lesson_reading_detail'	=>	cleanvars($_POST['lesson_reading_detail'])
						   	,'id_week'					=>	cleanvars($_POST['id_week'])
						   	,'id_lecture'				=>	cleanvars($_POST['id_lecture'])
						   	,'id_parent_topic'			=>	cleanvars($_POST['id_parent_topic'])
						   	,'id_session'				=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
						   	,'id_campus'				=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
						   	,'id_teacher'				=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
						   	,'id_curs'					=>	cleanvars(CURS_ID)
						   	,'id_modify'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'				=>	date('Y-m-d G:i:s')
					   );
		$sqllms = $dblms->Update(COURSES_LESSONS, $values , "WHERE lesson_id  = '".cleanvars($_POST['lesson_id'])."'");
		if($sqllms) { 
			// LATEST ID
			$latestID = $_POST['lesson_id'];
			// REMARKS
        	sendRemark(moduleName(LMS_VIEW).' Updated', '2', $latestID);
			sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
			header("Location: ".moduleName().".php?".$redirection."", true, 301);
			exit();
		}
	}	
}

// DELETE RECORD
if(isset($_GET['deleteid'])) {
	$latestID = $_GET['deleteid'];
	$values = array(
						 'id_deleted'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'		=>	1
						,'ip_deleted'		=>	cleanvars($ip)
						,'date_deleted'		=>	date('Y-m-d G:i:s')
					);   
	$sqlDel = $dblms->Update(COURSES_LESSONS , $values , "WHERE lesson_id  = '".cleanvars($latestID)."'");
	if($sqlDel) { 
        sendRemark(moduleName(LMS_VIEW).' Deleted', '3', $latestID);
		sessionMsg('Success', 'Record Successfully Deleted.', 'success');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}
}
?>