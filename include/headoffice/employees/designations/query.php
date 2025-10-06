<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {

	$condition	=	array ( 
								 'select'		=> "d.designation_id"
								,'where' 		=> array( 
															 'd.designation_name'	=>	cleanvars($_POST['designation_name'])
															, 'd.designation_code'	=>	cleanvars($_POST['designation_code'])
															, 'd.id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
															, 'd.is_deleted'		=>	'0'	
														)
								,'return_type' 	=> 'count' 
							); 
	if($dblms->getRows(DESIGNATIONS.' d', $condition)) {
		sessionMsg('Error','Record Already Exists.','error');
		header("Location: designations.php", true, 301);
		exit();
	}else{

		$values = array(
							 'designation_status'	=> cleanvars($_POST['designation_status'])
							,'designation_name'		=> cleanvars($_POST['designation_name'])
							,'designation_code'		=> cleanvars($_POST['designation_code'])
							,'designation_ordering'	=> cleanvars($_POST['designation_ordering'])
							,'id_campus'			=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_added'				=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'			=> date('Y-m-d G:i:s')
							,'is_deleted'			=> '0'
						);
		$sqllms		=	$dblms->insert(DESIGNATIONS, $values);
		if($sqllms) { 
			$latestID = $dblms->lastestid();
			sendRemark('Designation Added ID:'.$latestID, '1');
			sessionMsg('Successfully', 'Record Successfully Added.', 'success');
			header("Location: designations.php", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {

	$condition	=	array ( 
								 'select'		=> "d.designation_id"
								,'where' 		=> array( 
															 'd.designation_name'		=>	cleanvars($_POST['designation_name'])
															, 'd.designation_code'		=>	cleanvars($_POST['designation_code'])
															, 'd.id_campus'				=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
															, 'd.is_deleted'			=>	'0'	
														)
								,'not_equal' 	=> array( 
															'd.designation_id'		=>	cleanvars($_POST['designation_id'])
														)
								,'return_type' 	=> 'count'  
							); 
	if($dblms->getRows(DESIGNATIONS.' d', $condition)) {
		sessionMsg('Error', 'Record Already Exists.', 'error');
		header("Location: designations.php", true, 301);
		exit();
	}else{
	
		$values = array(
							 'designation_status'	=> cleanvars($_POST['designation_status'])
							,'designation_name'		=> cleanvars($_POST['designation_name'])
							,'designation_code'		=> cleanvars($_POST['designation_code'])
							,'id_modify'			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'			=> date('Y-m-d G:i:s')
						); 
		$sqllms = $dblms->Update(DESIGNATIONS , $values , "WHERE designation_id  = '".cleanvars($_POST['designation_id'])."'");
		if($sqllms) { 
			sendRemark('Designation Updated ID:'.cleanvars($_POST['designation_id']), '2');
			sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
			header("Location: designations.php", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])) {
	$values = array(
						 'id_deleted'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'		=>	'1'
						,'ip_deleted'		=>	cleanvars($ip)
						,'date_deleted'		=>	date('Y-m-d G:i:s')
					);   
	$sqlDel = $dblms->Update(DESIGNATIONS , $values , "WHERE designation_id  = '".cleanvars($_GET['deleteid'])."'");
	if($sqlDel) { 
		sendRemark('Designation Deleted ID:'.cleanvars($_GET['deleteid']), '3');
		sessionMsg('Warning', 'Record Successfully Deleted.', 'warning');
		header("Location: designations.php", true, 301);
		exit();
	}
}
?>