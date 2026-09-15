<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
							'select' 	=> "faq_id",
							'where' 	=> array( 
													 'question'	=>	cleanvars($_POST['question'])
													,'is_deleted'	=>	'0'	
												),
							'return_type' 	=> 'count' 
						  ); 
	if($dblms->getRows(FAQS, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "danger");
		header("Location:".moduleName().".php", true, 301);
		exit();
	}else{

		$values = array(
						 'faq_status'			=>	cleanvars($_POST['faq_status'])
						,'question'				=>	cleanvars($_POST['question'])
						,'answer'				=>	cleanvars($_POST['answer'])
						,'id_added'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'date_added'			=>	date('Y-m-d G:i:s')
					   ); 
		$sqllms = $dblms->insert(FAQS, $values);

		if($sqllms) { 
			$latestID  =	$dblms->lastestid();
			// REMARKS
			sendRemark(moduleName(false)." Added", '1', $latestID);
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location:".moduleName().".php", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {

	$condition	=	array ( 
							'select' 	=> "faq_id",
							'where' 	=> array( 
													 'question'		=>	cleanvars($_POST['question'])
													,'is_deleted'	=>	'0'	
												),
							'not_equal' 	=> array( 
													'faq_id'		=>	cleanvars($_POST['faq_id'])
												),					
							'return_type' 	=> 'count' 
						  ); 
	if($dblms->getRows(FAQS, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "danger");
		header("Location:".moduleName().".php", true, 301);
		exit();
	}else{
		$latestID  = $_POST['faq_id'];
		$values = array(
						 'faq_status'			=>	cleanvars($_POST['faq_status'])
						,'question'			=>	cleanvars($_POST['question'])
						,'answer'			=>	cleanvars($_POST['answer'])
						,'id_modify'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'date_modify'			=>	date('Y-m-d G:i:s')
					   ); 
		$sqllms = $dblms->Update(FAQS , $values , "WHERE faq_id  = '".cleanvars($latestID)."'");
		if($sqllms) { 

			// REMARKS			
			sendRemark(moduleName(false)." Updated", '2', $latestID);
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location:".moduleName().".php", true, 301);
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

	$sqlDel = $dblms->Update(FAQS , $values , "WHERE faq_id  = '".cleanvars($_GET['deleteid'])."'");

	if($sqlDel) { 		
		sendRemark(moduleName(false)." Deleted", '3', $latestID);
		sessionMsg("Warning", "Record Successfully Deleted.", "warning");
		header("Location:".moduleName().".php", true, 301);
		exit();
	}
}