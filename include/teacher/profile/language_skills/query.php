<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
							'select' 		=>	"id"
							,'where' 		=>	array( 
														 'is_deleted'			=>	0
														,'language_name'		=>	cleanvars($_POST['language_name'])
														,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'id_employee'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
													)
							,'return_type' 	=>	'count' 
						); 
	if($dblms->getRows(EMPLOYEE_LANGUAGE_SKILLS, $condition)) {		
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php?view=".LMS_VIEW."", true, 301);
		exit();
	}else{	
		$values 		= array(
									 'status'				=>	1
									,'language_name'		=>	cleanvars($_POST['language_name'])
									,'speaking'				=>	cleanvars($_POST['speaking'])
									,'listenting'			=>	cleanvars($_POST['listenting'])
									,'writing'				=>	cleanvars($_POST['writing'])
									,'reading'				=>	cleanvars($_POST['reading'])
									,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
									,'id_employee'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
								);
		$sqllms	= $dblms->insert(EMPLOYEE_LANGUAGE_SKILLS, $values);
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
														,'language_name'		=>	cleanvars($_POST['language_name'])
														,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'id_employee'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
													)
							,'not_equal' 	=>	array( 
														'id'					=>	cleanvars($_POST['lng_skill_id'])
													)				
							,'return_type' 	=>	'count' 
						); 
	if($dblms->getRows(EMPLOYEE_LANGUAGE_SKILLS, $condition)) {	 
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php?view=".LMS_VIEW."", true, 301);
		exit();
	}else{
		$values 		= array(
									 'language_name'		=>	cleanvars($_POST['language_name'])
									,'speaking'				=>	cleanvars($_POST['speaking'])
									,'listenting'			=>	cleanvars($_POST['listenting'])
									,'writing'				=>	cleanvars($_POST['writing'])
									,'reading'				=>	cleanvars($_POST['reading'])
								);
		$sqllms = $dblms->Update(EMPLOYEE_LANGUAGE_SKILLS, $values , "WHERE id  = '".cleanvars($_POST['lng_skill_id'])."'");
		if($sqllms) { 
			// LATEST ID
			$latestID = $_POST['lng_skill_id'];
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
	$sqlDel = $dblms->Update(EMPLOYEE_LANGUAGE_SKILLS, $values , "WHERE id  = '".cleanvars($_GET['deleteid'])."'");
	if($sqlDel) { 
		sendRemark(''.moduleName(false).' Deleted ID:'.cleanvars($_GET['deleteid']), '3');
		sessionMsg('Success', 'Record Successfully Deleted.', 'success');
		header("Location: ".moduleName().".php?view=".LMS_VIEW."", true, 301);
		exit();
	}
}
?>