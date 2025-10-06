<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
							 'select' 		=>	"id"
							,'where' 		=>	array( 
														 'is_deleted'			=>	0
														,'memno'				=>	cleanvars($_POST['memno'])
														,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'id_employee'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
													)
							,'return_type' 	=>	'count' 
						); 
	if($dblms->getRows(EMPLOYEE_MEMBERSHIPS, $condition)) {		
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php?view=".LMS_VIEW."", true, 301);
		exit();
	}else{	
		$values 		= array(
									 'status'				=>	1
									,'organization'			=>	cleanvars($_POST['organization'])
									,'designation'			=>	cleanvars($_POST['designation'])
									,'memno'				=>	cleanvars($_POST['memno'])
									,'startdate'			=>	date("Y-m-d",strtotime(cleanvars($_POST['startdate'])))
									,'enddate'				=>	date("Y-m-d",strtotime(cleanvars($_POST['enddate'])))
									,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
									,'id_employee'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
								);
		$sqllms	= $dblms->insert(EMPLOYEE_MEMBERSHIPS, $values);
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
														,'memno'				=>	cleanvars($_POST['memno'])
														,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'id_employee'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
													)
							,'not_equal' 	=>	array( 
														'id'					=>	cleanvars($_POST['mem_id'])
													)				
							,'return_type' 	=>	'count' 
						); 
	if($dblms->getRows(EMPLOYEE_MEMBERSHIPS, $condition)) {	 
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php?view=".LMS_VIEW."", true, 301);
		exit();
	}else{
		$values 		= array(
									 'organization'			=>	cleanvars($_POST['organization'])
									,'designation'			=>	cleanvars($_POST['designation'])
									,'memno'				=>	cleanvars($_POST['memno'])
									,'startdate'			=>	date("Y-m-d",strtotime(cleanvars($_POST['startdate'])))
									,'enddate'				=>	date("Y-m-d",strtotime(cleanvars($_POST['enddate'])))
								);
		$sqllms = $dblms->Update(EMPLOYEE_MEMBERSHIPS, $values , "WHERE id  = '".cleanvars($_POST['mem_id'])."'");
		if($sqllms) { 
			// LATEST ID
			$latestID = $_POST['mem_id'];
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
	$sqlDel = $dblms->Update(EMPLOYEE_MEMBERSHIPS, $values , "WHERE id  = '".cleanvars($_GET['deleteid'])."'");
	if($sqlDel) { 
		sendRemark(''.moduleName(false).' Deleted ID:'.cleanvars($_GET['deleteid']), '3');
		sessionMsg('Success', 'Record Successfully Deleted.', 'success');
		header("Location: ".moduleName().".php?view=".LMS_VIEW."", true, 301);
		exit();
	}
}
?>