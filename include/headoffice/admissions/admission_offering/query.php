<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
								 'select' 	=> "admoff_id"
								,'where' 	=> array( 
														 'id_session'		=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'is_deleted'		=>	'0'	
														,'admoff_type'		=>	cleanvars($_POST['admoff_type'])
														,'admoff_degree'	=>	cleanvars($_POST['admoff_degree'])
													)
								,'return_type' 	=> 'count' 
							);
	if($dblms->getRows(ADMISSION_OFFERING, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "danger");
		header("Location:".moduleName().".php", true, 301);
		exit();
	}else{
		$values = array(
							 'admoff_status'				=>	cleanvars($_POST['admoff_status'])
							,'admoff_type'					=>	cleanvars($_POST['admoff_type'])
							,'id_type'						=>	cleanvars($_POST['learner_type'])
							,'admoff_startdate'				=>	cleanvars($_POST['admoff_startdate'])
							,'admoff_enddate'				=>	cleanvars($_POST['admoff_enddate'])
							,'admoff_amount'				=>	cleanvars($_POST['admoff_amount'])
							,'admoff_amount_in_usd'			=>	cleanvars($_POST['admoff_amount_in_usd'])
							,'admoff_ip'					=>	cleanvars(__IP__)
							,'id_cat'						=>	cleanvars($_POST['id_cat'])
							,'admoff_degree'				=>	cleanvars($_POST['admoff_degree'])
							,'id_session'					=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_added'						=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'					=>	date('Y-m-d G:i:s')
		);
		$sqllms		=	$dblms->insert(ADMISSION_OFFERING, $values);
		if($sqllms) { 
			$latestID = $dblms->lastestid();
			// REMARKS
			sendRemark(moduleName(false)." Added ", '1', $latestID);
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location:".moduleName().".php", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {
	$condition	=	array ( 
								 'select' 	=> "admoff_id"
								,'where' 	=> array( 
														 'id_session'		=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'is_deleted'		=>	'0'	
														,'admoff_type'		=>	cleanvars($_POST['admoff_type'])
														,'admoff_degree'	=>	cleanvars($_POST['admoff_degree'])
													)
								,'not_equal' 	=> array( 
														'admoff_id'			=>	cleanvars(LMS_EDIT_ID)
													)					
								,'return_type' 	=> 'count' 
							); 
	if($dblms->getRows(ADMISSION_OFFERING, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "danger");
		header("Location:".moduleName().".php", true, 301);
		exit();
	}else{	
		$values = array(
							 'admoff_status'				=>	cleanvars($_POST['admoff_status'])
							,'admoff_type'					=>	cleanvars($_POST['admoff_type'])
							,'id_type'						=>	cleanvars($_POST['learner_type'])
							,'admoff_startdate'				=>	cleanvars($_POST['admoff_startdate'])
							,'admoff_enddate'				=>	cleanvars($_POST['admoff_enddate'])
							,'admoff_amount'				=>	cleanvars($_POST['admoff_amount'])
							,'admoff_amount_in_usd'			=>	cleanvars($_POST['admoff_amount_in_usd'])
							,'admoff_ip'					=>	cleanvars(__IP__)
							,'id_cat'						=>	cleanvars($_POST['id_cat'])
							,'admoff_degree'				=>	cleanvars($_POST['admoff_degree'])
							,'id_session'					=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_modify'					=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'					=>	date('Y-m-d G:i:s')
						); 
		$sqllms = $dblms->Update(ADMISSION_OFFERING , $values , "WHERE admoff_id  = '".cleanvars(LMS_EDIT_ID)."'");
		if($sqllms) { 
			// LATEST ID
			$latestID = LMS_EDIT_ID;

			// REMARKS
			sendRemark(moduleName(false)." Updated ", '2', $latestID);
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location:".moduleName().".php", true, 301);
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
	$sqlDel = $dblms->Update(ADMISSION_OFFERING, $values , "WHERE admoff_id  = '".cleanvars($latestID)."'");

	if($sqlDel) { 
		sendRemark(moduleName(false)." Deleted", '3', $latestID);
		sessionMsg("Warning", "Record Successfully Deleted.", "danger");
		exit();
		header("Location: ".moduleName().".php", true, 301);
	}
}
?>