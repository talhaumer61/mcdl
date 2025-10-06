<?php
// INSERT RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
								 'select'		=>	'ref_id'
								,'where'		=>	array( 
															 'ref_remarks'		=>	cleanvars($_POST['ref_remarks'])
															,'is_deleted'		=>	'0'	
														)
								,'return_type' 	=>	'count' 
							); 
	if($dblms->getRows(REFERRAL_CONTROL, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "danger");
		header("Location:".moduleName().".php", true, 301);
		exit();
	}else{
		$id_user 	= (isset($_POST['id_user']))?$_POST['id_user']:array();
		$id_curs 	= (isset($_POST['id_curs']))?$_POST['id_curs']:array();
		$values = array(
							 'ref_status'			=> cleanvars($_POST['ref_status'])
							,'ref_remarks'			=> cleanvars($_POST['ref_remarks'])
							,'ref_percentage'		=> cleanvars($_POST['ref_percentage'])
							,'id_user'				=> cleanvars(implode(',',$id_user))
							,'id_curs'				=> cleanvars(implode(',',$id_curs))
							,'ref_date_time_from'	=> date('Y-m-d G:i:s', strtotime(cleanvars($_POST['ref_date_time_from'])))
							,'ref_date_time_to'		=> date('Y-m-d G:i:s', strtotime(cleanvars($_POST['ref_date_time_to'])))
							,'id_added'				=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'			=> date('Y-m-d G:i:s')
		);
		$sqllms = $dblms->insert(REFERRAL_CONTROL, $values);
		if($sqllms) { 
			$latestID = $dblms->lastestid();

			// REMARKS
			sendRemark(moduleName(false).' Added', '1', $latestID);
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: ".moduleName().".php", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['submit_edit'])) {
	$condition	=	array ( 
								 'select'		=>	'ref_id'
								,'where'		=>	array( 
															 'ref_remarks'	=>	cleanvars($_POST['ref_remarks'])
															,'is_deleted'		=>	'0'	
														)
								,'not_equal' 	=>	array( 
															'ref_id'		=>	cleanvars(LMS_EDIT_ID)
														)				
								,'return_type' 	=>	'count' 
							); 
	if($dblms->getRows(REFERRAL_CONTROL, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "danger");
		header("Location: ".moduleName().".php", true, 301);
		exit();
	}else{
		$id_user 	= (isset($_POST['id_user']))?$_POST['id_user']:array();
		$id_curs 	= (isset($_POST['id_curs']))?$_POST['id_curs']:'';
		$values = array(
							 'ref_status'			=> cleanvars($_POST['ref_status'])
							,'ref_remarks'			=> cleanvars($_POST['ref_remarks'])
							,'ref_percentage'		=> cleanvars($_POST['ref_percentage'])
							,'id_user'				=> cleanvars(implode(',',$id_user))
							,'id_curs'				=> cleanvars(implode(',',$id_curs))
							,'ref_date_time_from'	=> date('Y-m-d G:i:s', strtotime(cleanvars($_POST['ref_date_time_from'])))
							,'ref_date_time_to'		=> date('Y-m-d G:i:s', strtotime(cleanvars($_POST['ref_date_time_to'])))
							,'id_modify'			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'			=> date('Y-m-d G:i:s')
						); 
		$sqllms = $dblms->Update(REFERRAL_CONTROL , $values , "WHERE ref_id  = '".cleanvars(LMS_EDIT_ID)."'");
		if($sqllms) { 
			$latestID  =	LMS_EDIT_ID;
			// REMARKS
			sendRemark(moduleName(false).' Updated', '2', $latestID);
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: ".moduleName().".php", true, 301);
			exit();
		}
	}	
}

// DELETE RECORD
if(isset($_GET['deleteid'])) {
	$latestID = $_GET['deleteid'];
	
	$values = array(
						 'id_deleted'	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'	=>	'1'
						,'ip_deleted'	=>	cleanvars($ip)
						,'date_deleted'	=>	date('Y-m-d G:i:s')
					);
	$sqlDel = $dblms->Update(REFERRAL_CONTROL, $values , "WHERE ref_id = '".cleanvars($latestID)."'");

	if($sqlDel) { 
		sendRemark(moduleName(false).' Deleted', '3', $latestID);
		sessionMsg("Success", "Record Successfully Deleted.", "Success");
		exit();
		header("Location: ".moduleName().".php", true, 301);
	}
}
?>