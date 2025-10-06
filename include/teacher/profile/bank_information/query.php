<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
							 'select' 		=>	"bank_id"
							,'where' 		=>	array( 
														 'is_deleted'			=>	0
														,'bank_name'			=>	cleanvars($_POST['bank_name'])
														,'bank_account_no'		=>	cleanvars($_POST['bank_account_no'])
														,'id_emply'				=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
													)
							,'return_type' 	=>	'count' 
						); 
	if($dblms->getRows(BANK_INFORMATION, $condition)) {		
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php?view=".LMS_VIEW."", true, 301);
		exit();
	}else{	
		if ($_POST['bank_status'] == 1) {
			$values	= array( 'bank_status'	=>	2 );
			$sqllms = $dblms->Update(BANK_INFORMATION,$values,'WHERE id_emply = '.cleanvars($_SESSION['userlogininfo']['EMPLYID']).'');
		}
		$values 		= array(
									 'bank_status'			=>	cleanvars($_POST['bank_status'])
									,'bank_account_name'	=>	cleanvars($_POST['bank_account_name'])
									,'bank_account_detail'	=>	cleanvars($_POST['bank_account_detail'])
									,'bank_account_no'		=>	cleanvars($_POST['bank_account_no'])
									,'bank_account_iban_no'	=>	cleanvars($_POST['bank_account_iban_no'])
									,'bank_name'			=>	cleanvars($_POST['bank_name'])
									,'bank_branch_name'		=>	cleanvars($_POST['bank_branch_name'])
									,'bank_branch_code'		=>	cleanvars($_POST['bank_branch_code'])
									,'id_emply'				=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
									,'id_added'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
									,'date_added'			=>	date('Y-m-d G:i:s')
								);					
		$sqllms	= $dblms->insert(BANK_INFORMATION, $values);
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
							 'select' 		=>	"bank_id"
							,'where' 		=>	array( 
														 'is_deleted'			=>	0
														,'bank_name'			=>	cleanvars($_POST['bank_name'])
														,'bank_account_no'		=>	cleanvars($_POST['bank_account_no'])
														,'id_emply'				=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
													)
							,'not_equal' 	=>	array( 
														'bank_id'				=>	cleanvars($_POST['bank_id'])
													)				
							,'return_type' 	=>	'count' 
						); 
	if($dblms->getRows(BANK_INFORMATION, $condition)) {	 
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php?view=".LMS_VIEW."", true, 301);
		exit();
	}else{
		if ($_POST['bank_status'] == 1) {
			$values	= array( 'bank_status'	=>	2 );
			$sqllms = $dblms->Update(BANK_INFORMATION,$values,'WHERE id_emply = '.cleanvars($_SESSION['userlogininfo']['EMPLYID']).'');
		}
		$values 		= array(
									 'bank_status'			=>	cleanvars($_POST['bank_status'])
									,'bank_account_name'	=>	cleanvars($_POST['bank_account_name'])
									,'bank_account_detail'	=>	cleanvars($_POST['bank_account_detail'])
									,'bank_account_no'		=>	cleanvars($_POST['bank_account_no'])
									,'bank_account_iban_no'	=>	cleanvars($_POST['bank_account_iban_no'])
									,'bank_name'			=>	cleanvars($_POST['bank_name'])
									,'bank_branch_name'		=>	cleanvars($_POST['bank_branch_name'])
									,'bank_branch_code'		=>	cleanvars($_POST['bank_branch_code'])
									,'id_emply'				=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
									,'id_added'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
									,'date_added'			=>	date('Y-m-d G:i:s')
								);
		$sqllms = $dblms->Update(BANK_INFORMATION, $values , "WHERE bank_id  = '".cleanvars($_POST['bank_id'])."'");
		if($sqllms) { 
			// LATEST ID
			$latestID = $_POST['bank_id'];
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
	$values 		= array(
								 'is_deleted'	=>	1
								,'id_deleted'	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
								,'date_deleted'	=>	date('Y-m-d G:i:s')
								,'ip_deleted'	=>	cleanvars(LMS_IP)
	);
	$sqlDel = $dblms->Update(BANK_INFORMATION, $values , "WHERE bank_id  = '".cleanvars($_GET['deleteid'])."'");
	if($sqlDel) { 
		sendRemark(''.moduleName(false).' Deleted ID:'.cleanvars($_GET['deleteid']), '3');
		sessionMsg('Success', 'Record Successfully Deleted.', 'success');
		exit();
		header("Location: ".moduleName().".php?view=".LMS_VIEW."", true, 301);
	}
}
?>	