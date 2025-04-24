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
	if($dblms->getRows(EMPLOYEE_EXPERIENCE, $condition)) {		
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}else{	
		$values 		= array(
									 'status'				=>	1
									,'organization'			=>	cleanvars($_POST['organization'])
									,'jobfield'				=>	cleanvars($_POST['jobfield'])
									,'designation'			=>	cleanvars($_POST['designation'])
									,'jobdetail'			=>	cleanvars($_POST['jobdetail'])
									,'date_start'			=>	date('Y-m-d',strtotime($_POST['date_start']))
									,'date_end'				=>	date('Y-m-d',strtotime($_POST['date_end']))
									,'salary_start'			=>	cleanvars($_POST['salary_start'])
									,'salary_end'			=>	cleanvars($_POST['salary_end'])
									,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
									,'id_employee'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
									,'id_added'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
									,'date_added'			=>	date('Y-m-d H:i:s')
								);
		$sqllms	= $dblms->insert(EMPLOYEE_EXPERIENCE, $values);
		if($sqllms) { 
			// LATEST ID
			$latestID = $dblms->lastestid();
			// REMARKS
			sendRemark(moduleName(LMS_VIEW).' Added', '1', $latestID);
			sessionMsg('Successfully', 'Record Successfully Added.', 'success');
			header("Location: ".moduleName().".php?".$redirection."", true, 301);
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
														'id'					=>	cleanvars(LMS_EDIT_ID)
													)				
							,'return_type' 	=>	'count' 
						); 
	if($dblms->getRows(EMPLOYEE_EXPERIENCE, $condition)) {	 
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}else{
		$values 		= array(
									 'organization'			=>	cleanvars($_POST['organization'])
									,'jobfield'				=>	cleanvars($_POST['jobfield'])
									,'designation'			=>	cleanvars($_POST['designation'])
									,'jobdetail'			=>	cleanvars($_POST['jobdetail'])
									,'date_start'			=>	date('Y-m-d',strtotime($_POST['date_start']))
									,'date_end'				=>	date('Y-m-d',strtotime($_POST['date_end']))
									,'salary_start'			=>	cleanvars($_POST['salary_start'])
									,'salary_end'			=>	cleanvars($_POST['salary_end'])
									,'id_modify'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
									,'date_modify'			=>	date('Y-m-d H:i:s')
								);
		$sqllms = $dblms->Update(EMPLOYEE_EXPERIENCE, $values , "WHERE id  = '".cleanvars(LMS_EDIT_ID)."'");
		if($sqllms) { 
			// LATEST ID
			$latestID = LMS_EDIT_ID;
			// REMARKS
			sendRemark(moduleName(LMS_VIEW).' Updated', '2', $latestID);
			sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
			header("Location: ".moduleName().".php?".$redirection."", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])) {
	$latestID = $_GET['deleteid'];
	$values = array(
						 'id_deleted'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'		=>	1
						,'ip_deleted'		=>	cleanvars(LMS_IP)
						,'date_deleted'		=>	date('Y-m-d G:i:s')
					);   
	$sqlDel = $dblms->Update(EMPLOYEE_EXPERIENCE , $values , "WHERE id  = '".cleanvars($latestID)."'");
	if($sqlDel) { 
		sendRemark(moduleName(LMS_VIEW).' Deleted', '3', $latestID);
		sessionMsg('Success', 'Record Successfully Deleted.', 'success');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}
}
?>