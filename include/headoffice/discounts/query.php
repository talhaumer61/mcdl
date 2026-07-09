<?php
// INSERT RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
								 'select'		=>	'discount_id'
								,'where'		=>	array( 
															 'discount_name'	=>	cleanvars($_POST['discount_name'])
															,'is_deleted'		=>	'0'	
														)
								,'return_type' 	=>	'count' 
							); 
	if($dblms->getRows(DISCOUNT, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "danger");
		header("Location:".moduleName().".php", true, 301);
		exit();
	}else{
		$dateArray 		= explode(' to ', $_POST['discount_date']);
		$discount_from 	= $dateArray[0];
		$discount_to 	= $dateArray[1];
		$values = array(
							 'discount_status'		=> cleanvars($_POST['discount_status'])
							,'discount_name'		=> cleanvars($_POST['discount_name'])
							,'id_type'				=> cleanvars($_POST['id_type'])
							,'discount_description'	=> cleanvars($_POST['discount_description'])
							,'discount_from'		=> date('Y-m-d', strtotime($discount_from))
							,'discount_to'			=> date('Y-m-d', strtotime($discount_to))
							,'is_all'				=> ($_POST['on_all_courses'] == 1?'1':'2')
							,'id_added'				=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'			=> date('Y-m-d G:i:s')
		);
		$sqllms = $dblms->insert(DISCOUNT, $values);
		if($sqllms) { 
			$latestID = $dblms->lastestid();
			foreach ($_POST['which_curs'] AS $whichKey => $whichValue) {
				if (isset($_POST['which_curs'][$whichKey])) {
					$values = array(
									 'id_setup'		=> cleanvars($latestID)
					);
					if (isset($_POST['on_all_courses'])) {
						$values['id_curs']			= cleanvars($_POST['id_all_curs'][$whichKey]);
						$values['discount_type']	= cleanvars($_POST['discount_type']);
						$values['discount']			= cleanvars($_POST['discount']);
					} else {
						$values['id_curs']			= cleanvars($_POST['id_custom_curs'][$whichKey]);
						$values['discount_type']	= cleanvars($_POST['discount_type'][$whichKey]);
						$values['discount']			= cleanvars($_POST['discount'][$whichKey]);
					}					
					$dblms->insert(DISCOUNT_DETAIL, $values);
				}
			}
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
								 'select'		=>	'discount_id'
								,'where'		=>	array( 
															 'discount_name'	=>	cleanvars($_POST['discount_name'])
															,'is_deleted'		=>	'0'	
														)
								,'not_equal' 	=>	array( 
															'discount_id'		=>	cleanvars(LMS_EDIT_ID)
														)				
								,'return_type' 	=>	'count' 
							); 
	if($dblms->getRows(DISCOUNT, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "danger");
		header("Location: ".moduleName().".php", true, 301);
		exit();
	}else{
		
		$dateArray 		= explode(' to ', $_POST['discount_date']);
		$discount_from 	= $dateArray[0];
		$discount_to 	= $dateArray[1];
		$values = array(
							 'discount_status'		=> cleanvars($_POST['discount_status'])
							,'discount_name'		=> cleanvars($_POST['discount_name'])
							,'discount_description'	=> $_POST['discount_description']
							,'discount_from'		=> date('Y-m-d', strtotime($discount_from))
							,'discount_to'			=> date('Y-m-d', strtotime($discount_to))
							,'is_all'				=> ($_POST['on_all_courses'] == 1?'1':'2')
							,'id_modify'			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'			=> date('Y-m-d G:i:s')
						); 
		$sqllms = $dblms->Update(DISCOUNT , $values , "WHERE discount_id  = '".cleanvars(LMS_EDIT_ID)."'");
		if($sqllms) { 
			$latestID  =	LMS_EDIT_ID;
			// DELETE OLD RECORD
			$dblms->querylms("DELETE FROM ".DISCOUNT_DETAIL." WHERE id_setup = ".$latestID);

			foreach ($_POST['which_curs'] AS $whichKey => $whichValue) {
				if (isset($_POST['which_curs'][$whichKey])) {
					$values = array(
									 'id_setup'		=> cleanvars($latestID)
					);
					if (isset($_POST['on_all_courses'])) {
						$values['id_curs']			= cleanvars($_POST['id_all_curs'][$whichKey]);
						$values['discount_type']	= cleanvars($_POST['discount_type']);
						$values['discount']			= cleanvars($_POST['discount']);
					} else {
						$values['id_curs']			= cleanvars($_POST['id_custom_curs'][$whichKey]);
						$values['discount_type']	= cleanvars($_POST['discount_type'][$whichKey]);
						$values['discount']			= cleanvars($_POST['discount'][$whichKey]);
					}
					$dblms->insert(DISCOUNT_DETAIL, $values);
				}
			}
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
	$sqlDel = $dblms->Update(DISCOUNT, $values , "WHERE discount_id = '".cleanvars($latestID)."'");

	if($sqlDel) { 
		sendRemark(moduleName(false).' Deleted', '3', $latestID);
		sessionMsg("Success", "Record Successfully Deleted.", "Success");
		exit();
		header("Location: ".moduleName().".php", true, 301);
	}
}
?>