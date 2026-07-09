<?php
// EDIT RECORD
if(isset($_POST['submit_edit'])) {	
	$values = array(
						 'status'				=>	2
						,'read_status'			=>	2
						,'message'				=>	cleanvars($_POST['message'])
						,'id_user'				=>  cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'ip_user'				=>	cleanvars(LMS_IP)
						,'id_modify'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'date_modify'			=>	date('Y-m-d G:i:s')
					);
	$sqllms = $dblms->Update(QUESTION_ANSWERS, $values, "WHERE id = '".LMS_EDIT_ID."'");

	if($sqllms) { 
		// LATEST ID
		$latestID = LMS_EDIT_ID;
		// REMARKS
		sendRemark(moduleName(LMS_VIEW).' Updated', '2', $latestID);
		sessionMsg('Successfully', 'Message Reply Sent.', 'success');
		header("Location: ".moduleName().".php?chat&id_std=".$_POST['id_std']."&".$redirection."", true, 301);
		exit();
	} else {		
		sessionMsg('Error', 'Something went wrong.', 'danger');
		header("Location: ".moduleName().".php?chat&id_std=".$_POST['id_std']."&".$redirection."", true, 301);
		exit();
	}
}

// DELETE RECORD QNA
if(isset($_GET['deleteidMsj'])) {
	$latestID = $_GET['deleteidMsj'];
	$values = array(
						 'id_deleted'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'		=>	'1'
						,'ip_deleted'		=>	cleanvars(LMS_IP)
						,'date_deleted'		=>	date('Y-m-d G:i:s')
					);   
	$sqlDel = $dblms->Update(QUESTION_ANSWERS , $values , "WHERE id = '".cleanvars($latestID)."'");
	if($sqlDel) { 
		sendRemark(moduleName(LMS_VIEW).' Deleted', '3', $latestID);
		sessionMsg('Success', 'Record Successfully Deleted.', 'success');
		header("Location: ".moduleName().".php?chat&id_std=".$_GET['id_std']."&".$redirection."", true, 301);
		exit();
	}
}

// DELETE RECORD THREAD
if(isset($_GET['deleteidCurs'])) {
	$latestID = $_GET['deleteidCurs'];
	$values = array(
						 'id_deleted'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'		=>	'1'
						,'ip_deleted'		=>	cleanvars(LMS_IP)
						,'date_deleted'		=>	date('Y-m-d G:i:s')
					);   
	$sqlDel = $dblms->Update(QUESTION_ANSWERS , $values , "WHERE id_curs = '".cleanvars($latestID)."' AND (id_user = '".$_GET['id_std']." OR reply_to = '".$_GET['id_std']."'')  ");
	if($sqlDel) { 
		sendRemark(moduleName(LMS_VIEW).' Deleted', '3', $latestID);
		sessionMsg('Success', 'Record Successfully Deleted.', 'success');
		header("Location: ".moduleName().".php?chat&".$redirection."", true, 301);
		exit();
	}
}
?>