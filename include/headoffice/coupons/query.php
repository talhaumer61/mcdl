<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$date		= explode('to',$_POST['date']);
	$start_date	= $date[0];
	$end_date	= $date[1];
	
	$condition	=	array ( 
								 'select'		=>	'cpn_id'
								,'where'		=>	array( 
															 'BINARY cpn_code'	=> cleanvars($_POST['cpn_code'])
															,'is_deleted'		=> '0'
														)
								,'return_type'	=>	'count' 
							);
	if($dblms->getRows(COUPONS, $condition, $sql)) {
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php", true, 301);
		exit();
	} else {
		$values = array(
							 'is_applied'			=> 2
							,'cpn_status'			=> cleanvars($_POST['cpn_status'])
							,'cpn_start_date'		=> cleanvars($start_date)
							,'cpn_end_date'			=> cleanvars($end_date)
							,'cpn_name'				=> cleanvars($_POST['cpn_name'])
							,'cpn_code'				=> cleanvars($_POST['cpn_code'])
							,'cpn_type'				=> cleanvars($_POST['cpn_type'])
							,'cpn_percent_amount'	=> cleanvars($_POST['cpn_percent_amount'])
							,'cpn_detail'			=> cleanvars($_POST['cpn_detail'])
							,'id_added'				=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'			=> date('Y-m-d G:i:s')
						);
		$sqllms = $dblms->insert(COUPONS, $values);
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
	$date		= explode('to',$_POST['date']);
	$start_date	= $date[0];
	$end_date	= $date[1];
	
	$condition	=	array ( 
								 'select'		=>	'cpn_id'
								,'where'		=>	array( 
															 'BINARY cpn_code'	=> cleanvars($_POST['cpn_code'])
															,'is_deleted'		=> '0'
														)
								,'not_equal'	=>	array( 
															'cpn_id'			=> LMS_EDIT_ID
														)
								,'return_type'	=>	'count' 
							);
	if($dblms->getRows(COUPONS, $condition, $sql)) {
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php", true, 301);
		exit();
	} else {
		$values = array(
							 'is_applied'			=> 2
							,'cpn_status'			=> cleanvars($_POST['cpn_status'])
							,'cpn_start_date'		=> cleanvars($start_date)
							,'cpn_end_date'			=> cleanvars($end_date)
							,'cpn_name'				=> cleanvars($_POST['cpn_name'])
							,'cpn_type'				=> cleanvars($_POST['cpn_type'])
							,'cpn_percent_amount'	=> cleanvars($_POST['cpn_percent_amount'])
							,'cpn_detail'			=> cleanvars($_POST['cpn_detail'])
							,'id_added'				=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'			=> date('Y-m-d G:i:s')
						);
		$sqllms = $dblms->Update(COUPONS , $values , "WHERE cpn_id  = '".cleanvars(LMS_EDIT_ID)."' ");
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
	$sqlDel = $dblms->Update(COUPONS , $values , "WHERE cpn_id  = '".cleanvars($latestID)."'");
	if($sqlDel) { 
		sendRemark(moduleName(false).' Deleted', '3', $latestID);
		sessionMsg('Warning', 'Record Successfully Deleted.', 'warning');
		header("Location: ".moduleName().".php", true, 301);
		exit();
	}
}
?>