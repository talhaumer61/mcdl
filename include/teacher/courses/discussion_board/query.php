<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	// $array			= explode(' to ',$_POST['discussion_date']);
	// $startdate		= date("Y-m-d",strtotime($array[0]));
	// $enddate		= date("Y-m-d",strtotime($array[1]));
	$id_lecture		= implode(',',$_POST['id_lecture']);

	$condition	=	array ( 
							 'select' 		=>	"discussion_id"
							,'where' 		=>	array( 
														 'is_deleted'			=>	'0'
														,'discussion_subject'	=>	cleanvars($_POST['discussion_subject'])
														// ,'discussion_startdate'	=>	cleanvars($startdate)
														// ,'discussion_enddate'	=>	cleanvars($enddate)
														,'id_curs'				=>	cleanvars(CURS_ID)
													//	,'id_session'			=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
													)
							,'return_type' 	=>	'count'
						); 
	if($dblms->getRows(COURSES_DISCUSSION, $condition)) {		
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}else{
		$values 		= array(
									 'discussion_status'	=>	cleanvars($_POST['discussion_status'])
									,'discussion_subject'	=>	cleanvars($_POST['discussion_subject'])
									,'discussion_detail'	=>	cleanvars($_POST['discussion_detail'])
									// ,'discussion_startdate'	=>	cleanvars($startdate)
									// ,'discussion_enddate'	=>	cleanvars($enddate)
									,'id_lecture'			=>	cleanvars($id_lecture)
									//,'id_session'			=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
									,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
									,'id_teacher'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
									,'id_curs'				=>	cleanvars(CURS_ID)
									,'id_added'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
									,'date_added'			=>	date('Y-m-d G:i:s')
								);
		$sqllms	= $dblms->insert(COURSES_DISCUSSION, $values);
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
	// $array			= explode(' to ',$_POST['discussion_date']);
	// $startdate		= date("Y-m-d",strtotime($array[0]));
	// $enddate		= date("Y-m-d",strtotime($array[1]));
	$id_lecture		= implode(',',$_POST['id_lecture']);

	$condition	=	array ( 
							 'select' 		=>	"discussion_id"
							,'where' 		=>	array( 
														 'is_deleted'			=>	'0'
														,'discussion_subject'	=>	cleanvars($_POST['discussion_subject'])
														// ,'discussion_startdate'	=>	cleanvars($startdate)
														// ,'discussion_enddate'	=>	cleanvars($enddate)
														,'id_curs'				=>	cleanvars(CURS_ID)
													//	,'id_session'			=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
													)
							,'search_by' 	=>	" AND discussion_id != '".cleanvars(LMS_EDIT_ID)."' "
							,'return_type' 	=>	'count'
						);
	if($dblms->getRows(COURSES_DISCUSSION, $condition)) {		
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}else{
		$values 		= array(
									 'discussion_status'	=>	cleanvars($_POST['discussion_status'])
									,'discussion_subject'	=>	cleanvars($_POST['discussion_subject'])
									,'discussion_detail'	=>	cleanvars($_POST['discussion_detail'])
									// ,'discussion_startdate'	=>	cleanvars($startdate)
									// ,'discussion_enddate'	=>	cleanvars($enddate)
									,'id_lecture'			=>	cleanvars($id_lecture)
									,'id_modify'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
									,'date_modify'			=>	date('Y-m-d G:i:s')
								);
		$sqllms = $dblms->Update(COURSES_DISCUSSION, $values , "WHERE discussion_id = '".cleanvars(LMS_EDIT_ID)."'");
		if($sqllms) { 
			// LATEST ID
			$latestID = LMS_EDIT_ID;
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
	$sqlDel = $dblms->Update(COURSES_DISCUSSION , $values , "WHERE discussion_id  = '".cleanvars($latestID)."'");
	if($sqlDel) {
		sendRemark(moduleName(LMS_VIEW).' Deleted', '3', $latestID);
		sessionMsg('Successfully', 'Record Successfully Deleted.', 'warning');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}
}
