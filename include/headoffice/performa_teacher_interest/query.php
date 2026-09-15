<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
								 'select'		=>	'id'
								,'where'		=>	array( 
															 'question'		=> cleanvars($_POST['question'])
															,'is_deleted'	=> '0'
														)
								,'return_type'	=>	'count'
							);
	if($dblms->getRows(TEACHER_INTEREST_QUESTIONS, $condition, $sql)) {
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php", true, 301);
		exit();
	} else {
		$options = json_encode($_POST['options'], JSON_UNESCAPED_UNICODE);
		$values = array(
							 'status'			=> cleanvars($_POST['status'])
							,'type'				=> cleanvars($_POST['type'])
							,'id_section'		=> cleanvars($_POST['id_section'])
							,'question'			=> cleanvars($_POST['question'])
							,'options'			=> ''
							,'id_added'			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'		=> date('Y-m-d G:i:s')
						);
		if($_POST['type'] == 3){
			$values['options'] = $options;
		}
		$sqllms = $dblms->insert(TEACHER_INTEREST_QUESTIONS, $values);
		if($sqllms) { 
			$latestID =	$dblms->lastestid();
			sendRemark(moduleName(false).' Added', '1', $latestID);
			sessionMsg('Successfully', 'Record Successfully Added.', 'success');
			header("Location: ".moduleName().".php", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {
	$condition	=	array ( 
								 'select'		=>	'id'
								,'where'		=>	array( 
															 'question'		=> cleanvars($_POST['question'])
															,'is_deleted'	=> '0'
														)
								,'not_equal'	=>	array( 
															'id'			=> LMS_EDIT_ID
														)
								,'return_type'	=>	'count' 
							);
	if($dblms->getRows(TEACHER_INTEREST_QUESTIONS, $condition, $sql)) {
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php", true, 301);
		exit();
	} else {
		$options = json_encode($_POST['options'], JSON_UNESCAPED_UNICODE);
		$values = array(
							 'status'			=> cleanvars($_POST['status'])
							,'type'				=> cleanvars($_POST['type'])
							,'id_section'		=> cleanvars($_POST['id_section'])
							,'question'			=> cleanvars($_POST['question'])
							,'options'			=> ''
							,'id_modify'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'		=> date('Y-m-d G:i:s')
						);
		if($_POST['type'] == 3){
			$values['options'] = $options;
		}
		$sqllms = $dblms->Update(TEACHER_INTEREST_QUESTIONS, $values , "WHERE id  = '".cleanvars(LMS_EDIT_ID)."' ");
		if($sqllms) { 
			$latestID =	LMS_EDIT_ID;
			sendRemark(moduleName(false).' Updated', '2', $latestID);
			sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
			header("Location: ".moduleName().".php", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])) {
	$latestID = $_GET['deleteid'];
	$values = array(
						 'id_deleted'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'		=>	'1'
						,'ip_deleted'		=>	cleanvars(LMS_IP)
						,'date_deleted'		=>	date('Y-m-d G:i:s')
					);   
	$sqlDel = $dblms->Update(TEACHER_INTEREST_QUESTIONS, $values , "WHERE id  = '".cleanvars($_GET['deleteid'])."'");
	if($sqlDel) { 
		sendRemark(moduleName(false).' Deleted', '3', $latestID);
		sessionMsg('Warning', 'Record Successfully Deleted.', 'warning');
		header("Location: ".moduleName().".php", true, 301);
		exit();
	}
}
?>