<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
							 'select' 		=>	"id"
							,'where' 		=>	array( 
														 'is_deleted'			=>	0
														,'jobfield'				=>	cleanvars($_POST['jobfield'])
														,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'id_employee'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
													)
							,'return_type' 	=>	'count' 
						); 
	if($dblms->getRows(EMPLOYEE_TRAININGS, $condition)) {		
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php?view=".LMS_VIEW."", true, 301);
		exit();
	}else{	
		$values 		= array(
									 'status'				=>	1
									,'jobfield'				=>	cleanvars($_POST['jobfield'])
									,'course'				=>	cleanvars($_POST['course'])
									,'date_start'			=>	date("Y-m-d",strtotime(cleanvars($_POST['date_start'])))
									,'date_end'				=>	date("Y-m-d",strtotime(cleanvars($_POST['date_end'])))
									,'organization'			=>	cleanvars($_POST['organization'])
									,'address'				=>	cleanvars($_POST['address'])
									,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
									,'id_employee'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
								);
		$sqllms	= $dblms->insert(EMPLOYEE_TRAININGS, $values);
		if($sqllms) { 
			// LATEST ID
			$latestID = $dblms->lastestid();
			// REMARKS
			sendRemark(''.moduleName(false).' Added ID:'.$latestID, '1');
			sessionMsg('Successfully', 'Record Successfully Added.', 'success');
			header("Location: ".moduleName().".php?view=".LMS_VIEW."", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {
	$condition	=	array ( 
							'select' 		=>	"id"
							,'where' 		=>	array( 
														 'is_deleted'			=>	0
														,'jobfield'				=>	cleanvars($_POST['jobfield'])
														,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'id_employee'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
													)
							,'not_equal' 	=>	array( 
														'id'					=>	cleanvars($_POST['training_id'])
													)				
							,'return_type' 	=>	'count' 
						); 
	if($dblms->getRows(EMPLOYEE_TRAININGS, $condition)) {	 
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php?view=".LMS_VIEW."", true, 301);
		exit();
	}else{
		$values 		= array(
									 'jobfield'				=>	cleanvars($_POST['jobfield'])
									,'course'				=>	cleanvars($_POST['course'])
									,'date_start'			=>	date("Y-m-d",strtotime(cleanvars($_POST['date_start'])))
									,'date_end'				=>	date("Y-m-d",strtotime(cleanvars($_POST['date_end'])))
									,'organization'			=>	cleanvars($_POST['organization'])
									,'address'				=>	cleanvars($_POST['address'])
								);
		$sqllms = $dblms->Update(EMPLOYEE_TRAININGS, $values , "WHERE id  = '".cleanvars($_POST['training_id'])."'");
		if($sqllms) { 
			// LATEST ID
			$latestID = $_POST['training_id'];
			// REMARKS
			sendRemark(''.moduleName(false).' Updated ID:'.$latestID, '2');
			sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
			header("Location: ".moduleName().".php?view=".LMS_VIEW."", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])) {
	$values = array( 'is_deleted' => 1 );   
	$sqlDel = $dblms->Update(EMPLOYEE_TRAININGS, $values , "WHERE id  = '".cleanvars($_GET['deleteid'])."'");
	if($sqlDel) { 
		sendRemark(''.moduleName(false).' Deleted ID:'.cleanvars($_GET['deleteid']), '3');
		sessionMsg('Success', 'Record Successfully Deleted.', 'success');
		header("Location: ".moduleName().".php?view=".LMS_VIEW."", true, 301);
		exit();
	}
}
?>