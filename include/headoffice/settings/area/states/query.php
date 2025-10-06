<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {

	$condition	=	array ( 
							'select' 	=>	'state_id',
							'where' 	=>	array( 
													'state_name'	=>	cleanvars($_POST['state_name'])
													,'is_deleted'	=>	'0'
												),
							'return_type' 	=> 'count'
							); 
	if($dblms->getRows(STATES, $condition)) {
		sessionMsg('Error', 'Record Already Exists.', 'danger');
		header("Location: states.php", true, 301);
		exit();
	}else{
		$values = array(
						'state_name'		=>	cleanvars($_POST['state_name'])
						,'state_codedigit'	=>	cleanvars($_POST['state_codedigit'])
						,'state_codealpha'	=>	cleanvars($_POST['state_codealpha'])
						,'state_latitude'	=>	cleanvars($_POST['state_latitude'])
						,'state_longitude'	=>	cleanvars($_POST['state_longitude'])
						,'id_country'		=>	cleanvars($_POST['id_country'])
						,'state_status' 	=>	cleanvars($_POST['state_status'])
						,'state_ordering' 	=>	cleanvars($_POST['state_ordering'])
						,'id_added'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'date_added'		=>	date('Y-m-d G:i:s')
						); 
		$sqllms		=	$dblms->insert(STATES, $values);

		if($sqllms) { 
			$latestID   =	$dblms->lastestid();
			sendRemark('State Added ID:'.$latestID, '1');
			sessionMsg('Successfully', 'Record Successfully Added.', 'success');
			header("Location: states.php", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {

	$condition	=	array ( 
							'select' 	=> "state_id",
							'where' 	=> array( 
													'state_name'	=>	cleanvars($_POST['state_name'])
													,'is_deleted'	=>	'0'	
												),
							'not_equal' 	=> array( 
													'state_id'		=>	cleanvars($_POST['state_id'])
												),					
							'return_type' 	=> 'count' 
							); 
	if($dblms->getRows(STATES, $condition)) {
		sessionMsg('Error', 'Record Already Exists.', 'danger');
		header("Location: states.php", true, 301);
		exit();
	}else{
		$values = array(
						'state_name'		=>	cleanvars($_POST['state_name'])
						,'state_codedigit'	=>	cleanvars($_POST['state_codedigit'])
						,'state_codealpha'	=>	cleanvars($_POST['state_codealpha'])
						,'state_latitude'	=>	cleanvars($_POST['state_latitude'])
						,'state_longitude'	=>	cleanvars($_POST['state_longitude'])
						,'id_country'		=>	cleanvars($_POST['id_country'])
						,'state_status'		=>	cleanvars($_POST['state_status'])
						,'state_ordering' 	=>	cleanvars($_POST['state_ordering'])
						,'id_modify'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'date_modify'		=>	date('Y-m-d G:i:s')
					);  
		$sqllms = $dblms->Update(STATES , $values , "WHERE state_id  = '".cleanvars($_POST['state_id'])."'");

		if($sqllms) { 
			$latestID = $_POST['state_id'];
			sendRemark('State Updates ID:'.cleanvars($latestID), '2');
			sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
			header("Location: states.php", true, 301);
			exit();
		}
	}

	
}

// DELETE RECORD
if(isset($_GET['deleteid'])) {
	
	$values = array(
					'id_deleted'	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
					,'is_deleted'	=>	'1'
					,'ip_deleted'	=>	cleanvars($ip)
					,'date_deleted'	=>	date('Y-m-d G:i:s')

					);   
	$sqlDel = $dblms->Update(STATES , $values , "WHERE state_id  = '".cleanvars($_GET['deleteid'])."'");

	if($sqlDel) { 
		sendRemark('Deleted State #:'.cleanvars($_GET['deleteid']), '3');
		sessionMsg('Warning', 'Record Successfully Deleted.', 'warning');
		header("Location: states.php", true, 301);
		exit();
	}
}
?>