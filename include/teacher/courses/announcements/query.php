<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
							 'select' 		=>	"announcement_id "
							,'where' 		=>	array( 
														 'is_deleted'			=>	'0'	
														,'announcement_topic'	=>	cleanvars($_POST['announcement_topic'])
														,'id_session'			=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'id_curs'				=>	cleanvars(CURS_ID)
													)
							,'return_type' 	=>	'count' 
						); 
	if($dblms->getRows(COURSES_ANNOUNCEMENTS, $condition)) {
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}else{
		$id_lecture		= implode(',',$_POST['id_lecture']);
		$values 		= array(
									 'announcement_status'		=>	cleanvars($_POST['announcement_status'])
									,'announcement_topic'		=>	cleanvars($_POST['announcement_topic'])
									,'announcement_detail'		=>	cleanvars($_POST['announcement_detail'])
									,'id_lecture'				=>	cleanvars($id_lecture)
									,'id_session'				=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
									,'id_campus'				=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
									,'id_teacher'				=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
									,'id_curs'					=>	cleanvars(CURS_ID)
									,'id_added'					=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
									,'date_added'				=>	date('Y-m-d G:i:s')
								);
		$sqllms	= $dblms->insert(COURSES_ANNOUNCEMENTS, $values);
		if($sqllms) { 
			// LATEST ID
			$latestID = $dblms->lastestid();
			// REMARKS
			sendRemark(moduleName(false).' Added', '1', $latestID);
			sessionMsg('Successfully', 'Record Successfully Added.', 'success');
			header("Location: ".moduleName().".php?".$redirection."", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {
	$condition	=	array ( 
							 'select' 		=>	"announcement_id "
							,'where' 		=>	array( 
														 'is_deleted'			=>	'0'	
														,'announcement_topic'	=>	cleanvars($_POST['announcement_topic'])
														,'id_session'			=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'id_curs'				=>	cleanvars(CURS_ID)
													)
							,'not_equal'	=>	array(
														'announcement_id'		=>	cleanvars(LMS_EDIT_ID)
													)
							,'return_type' 	=>	'count' 
						); 
	if($dblms->getRows(COURSES_ANNOUNCEMENTS, $condition)) {
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}else{
		$id_lecture		= implode(',',$_POST['id_lecture']);
		$values 		= array(
									'announcement_status'	=>	cleanvars($_POST['announcement_status'])
									,'announcement_topic'	=>	cleanvars($_POST['announcement_topic'])
									,'announcement_detail'	=>	cleanvars($_POST['announcement_detail'])
									,'id_lecture'			=>	cleanvars($id_lecture)
									,'id_modify'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
									,'date_modify'			=>	date('Y-m-d G:i:s')
								);
		$sqllms = $dblms->Update(COURSES_ANNOUNCEMENTS, $values , "WHERE announcement_id = '".cleanvars(LMS_EDIT_ID)."'");
		if($sqllms) { 
			// LATEST ID
			$latestID = $_POST['announcement_id'];
			// REMARKS
			sendRemark(moduleName(false).' Updated', '2', $latestID);
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
	$sqlDel = $dblms->Update(COURSES_ANNOUNCEMENTS , $values , "WHERE announcement_id  = '".cleanvars($latestID)."'");
	if($sqlDel) { 
        sendRemark(moduleName(false).' Deleted', '3', $latestID);
		sessionMsg('Success', 'Record Successfully Deleted.', 'success');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}
}
?>