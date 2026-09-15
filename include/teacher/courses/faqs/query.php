<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
							 'select' 		=>	"question"
							,'where' 		=>	array( 
														 'is_deleted'		=>	'0'
														,'question'			=>	cleanvars($_POST['question'])
														,'id_session'		=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'id_campus'		=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'id_curs'			=>	cleanvars(CURS_ID)
													)
							,'return_type' 	=>	'count'
						); 
	if($dblms->getRows(COURSES_FAQS, $condition)) {		
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}else{
		$values = array(
							 'status'			=>	cleanvars($_POST['status'])
							,'question'			=>	cleanvars($_POST['question'])
							,'answer'			=>	cleanvars($_POST['answer'])
							,'id_lesson'		=>	cleanvars(implode(',',$_POST['id_lesson']))
							,'id_session'		=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_campus'		=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_teacher'		=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
							,'id_curs'			=>	cleanvars(CURS_ID)
							,'id_added'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'		=>	date('Y-m-d G:i:s')
						);
		$sqllms	= $dblms->insert(COURSES_FAQS, $values);
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
							 'select' 		=>	"question"
							,'where' 		=>	array( 
														 'is_deleted'		=>	'0'
														,'question'			=>	cleanvars($_POST['question'])
														,'id_session'		=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'id_campus'		=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'id_curs'			=>	cleanvars(CURS_ID)
													)
							,'not_equal'	=>	array(
														'id'				=>	cleanvars(LMS_EDIT_ID)
													)
							,'return_type' 	=>	'count'
						); 
	if($dblms->getRows(COURSES_FAQS, $condition)) {		
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}else{
		$values 		= array(
									 'status'			=>	cleanvars($_POST['status'])
									,'question'			=>	cleanvars($_POST['question'])
									,'answer'			=>	cleanvars($_POST['answer'])
									,'id_lesson'		=>	cleanvars(implode(',',$_POST['id_lesson']))
									,'id_modify'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
									,'date_modify'		=>	date('Y-m-d G:i:s')
								);
		$sqllms = $dblms->Update(COURSES_FAQS, $values , "WHERE id  = '".cleanvars(LMS_EDIT_ID)."'");
		// endforeach;
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
	$sqlDel = $dblms->Update(COURSES_FAQS , $values , "WHERE id  = '".cleanvars($latestID)."'");
	if($sqlDel) { 
		sendRemark(moduleName(LMS_VIEW).' Deleted', '3', $latestID);
		sessionMsg('Success', 'Record Successfully Deleted.', 'success');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}
}
?>