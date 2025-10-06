<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$date		= explode('to',$_POST['date']);
	$start_date	= $date[0];
	$end_date	= $date[1];
	
	$condition	=	array ( 
								 'select'		=>	'n.not_id'
								,'where'		=>	array( 
															 'n.not_title'		=> cleanvars($_POST['not_title'])
															,'n.is_deleted'		=> '0'
														)
								// ,'search_by'	=>	' AND (n.start_date BETWEEN "'.$start_date.'" AND "'.$end_date.'") AND (n.end_date BETWEEN "'.$start_date.'" AND "'.$end_date.'")'
								,'return_type'	=>	'count' 
							);
	if($dblms->getRows(NOTIFICATIONS.' n', $condition, $sql)) {
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php", true, 301);
		exit();
	} else {
		// Convert checkbox values to comma-separated strings
        $display_location = isset($_POST['display_location']) ? implode(',', $_POST['display_location']) : '';
        $display_audience = isset($_POST['display_audience']) ? implode(',', $_POST['display_audience']) : '';

		$values = array(
							 'id_type'			=> 1
							,'not_status'		=> cleanvars($_POST['not_status'])
							,'not_title'		=> cleanvars($_POST['not_title'])
							,'not_description'	=> cleanvars($_POST['not_description'])
							,'start_date'		=> cleanvars($start_date)
							,'end_date'			=> cleanvars($end_date)
							,'dated'			=> date('Y-m-d G:i:s')
							,'display_location' => $display_location
							,'display_audience' => $display_audience
							,'id_added'			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'		=> date('Y-m-d G:i:s')
						);
		$sqllms = $dblms->insert(NOTIFICATIONS, $values);
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
								 'select'		=>	'n.not_id'
								,'where'		=>	array( 
															 'n.not_title'		=>	cleanvars($_POST['not_title'])
															,'n.is_deleted'		=>	'0'
														)
								,'not_equal'	=>	array(
															'n.not_id'			=>	LMS_EDIT_ID
														)
								// ,'search_by'	=>	' AND (n.start_date BETWEEN "'.$start_date.'" AND "'.$end_date.'") AND (n.end_date BETWEEN "'.$start_date.'" AND "'.$end_date.'")'
								,'return_type'	=>	'count' 
							);
	if($dblms->getRows(NOTIFICATIONS.' n', $condition, $sql)) {
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php", true, 301);
		exit();
	} else {
		// Convert checkbox values to comma-separated strings
        $display_location = isset($_POST['display_location']) ? implode(',', $_POST['display_location']) : '';
        $display_audience = isset($_POST['display_audience']) ? implode(',', $_POST['display_audience']) : '';

		$values = array(
							 'id_type'			=> 1
							,'not_status'		=> cleanvars($_POST['not_status'])
							,'not_title'		=> cleanvars($_POST['not_title'])
							,'not_description'	=> cleanvars($_POST['not_description'])
							,'start_date'		=> cleanvars($start_date)
							,'end_date'			=> cleanvars($end_date)
							,'dated'			=> date('Y-m-d G:i:s')
							,'display_location' => $display_location
							,'display_audience' => $display_audience
							,'id_modify'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'		=> date('Y-m-d G:i:s')
						);
		$sqllms = $dblms->Update(NOTIFICATIONS , $values , "WHERE not_id  = '".cleanvars(LMS_EDIT_ID)."' ");
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
	$sqlDel = $dblms->Update(NOTIFICATIONS , $values , "WHERE not_id  = '".cleanvars($_GET['deleteid'])."'");
	if($sqlDel) { 
		sendRemark(moduleName(false).' Deleted', '3', $latestID);
		sessionMsg('Warning', 'Record Successfully Deleted.', 'warning');
		header("Location: ".moduleName().".php", true, 301);
		exit();
	}
}
?>