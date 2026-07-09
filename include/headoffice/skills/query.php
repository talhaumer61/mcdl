<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
								 'select'		=> "s.skill_id"
								,'where'		=> array( 
															 's.skill_name'		=> cleanvars($_POST['skill_name'])
															,'s.id_campus'		=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
															,'s.is_deleted'		=> '0'
														)
								,'return_type'	=> 'count' 
							); 
	if($dblms->getRows(SKILLS.' s', $condition)) {
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: skills.php", true, 301);
		exit();
	}else{

		$values = array(
							 'skill_status'		=> cleanvars($_POST['skill_status'])
							,'skill_name'		=> cleanvars($_POST['skill_name'])
							,'skill_detail'		=> cleanvars($_POST['skill_detail'])
							,'skill_ordering'	=> cleanvars($_POST['skill_ordering'])
							,'id_campus'		=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_added'			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'		=> date('Y-m-d G:i:s')
							,'is_deleted'		=> '0'
						);
		$sqllms = $dblms->insert(SKILLS, $values);
		if($sqllms) { 
			$latestID   =	$dblms->lastestid();
			sendRemark('Skills Added ID:'.$latestID, '1');
			sessionMsg('Successfully', 'Record Successfully Added.', 'success');
			header("Location: skills.php", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {
	$condition	=	array ( 
								 'select'		=>	's.skill_id'
								,'where'		=>	array( 
															 's.skill_name'		=>	cleanvars($_POST['skill_name'])
															,'s.id_campus'		=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
															,'s.is_deleted'		=>	'0'	
														)
								,'not_equal' 	=>	array( 
															's.skill_id'		=>	cleanvars($_POST['skill_id'])
														)
								,'return_type' 	=>	'count'  
							); 
	if($dblms->getRows(SKILLS.' s', $condition)) {
		sessionMsg('Error', 'Record Already Exists.', 'danger');
		header("Location: skills.php", true, 301);
		exit();
	}else{	
		$values = array(
							 'skill_status'		=> cleanvars($_POST['skill_status'])
							,'skill_name'		=> cleanvars($_POST['skill_name'])
							,'skill_detail'		=> cleanvars($_POST['skill_detail'])
							,'id_modify'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'		=> date('Y-m-d G:i:s')
						); 
		$sqllms = $dblms->Update(SKILLS , $values , "WHERE skill_id  = '".cleanvars($_POST['skill_id'])."'");
		if($sqllms) { 
			$latestID   =	$_POST['skill_id'];
			sendRemark('Skills Updated ID:'.$latestID, '2');
			sessionMsg('Successfully', 'Record Successfully Added.', 'success');
			header("Location: skills.php", true, 301);
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
	$sqlDel = $dblms->Update(SKILLS , $values , "WHERE skill_id  = '".cleanvars($_GET['deleteid'])."'");
	if($sqlDel) { 
		sendRemark('Deleted SKILLS #:'.cleanvars($_GET['deleteid']), '3');
		sessionMsg('Warning', 'Record Successfully Deleted.', 'warning');
		header("Location: skills.php", true, 301);
		exit();
	}
}
?>