<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
								 'select'		=> "id"
								,'where'		=> array( 
															 'fullname'			=> cleanvars($_POST['fullname'])
															,'dated'			=> cleanvars($_POST['dated'])
															,'is_deleted'		=> '0'
														)
								,'return_type'	=> 'count' 
							); 
	if($dblms->getRows(INQUIRIES, $condition)) {
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php", true, 301);
		exit();
	} else {
		
		$ym = date('ym');
		$prefix = "INQ-$ym-";
		$sqlQuery = $dblms->querylms("
			SELECT 
				CONCAT(
					'$prefix',
					IFNULL(
						LPAD(
							MAX(CAST(SUBSTRING(inquiry_no, -4) AS UNSIGNED)) + 1,
							4,
							'0'
						),
						'0001'
					)
				) AS next_inquiry_no
			FROM ".INQUIRIES."
			WHERE inquiry_no LIKE '$prefix%'
		");
		$valQuery = mysqli_fetch_array($sqlQuery);
		$inquiry_no = $valQuery['next_inquiry_no'];

		$values = array(
							 'status'			=> cleanvars($_POST['status'])
							,'inquiry_no'		=> cleanvars($_POST['inquiry_no'])
							,'fullname'			=> cleanvars($_POST['fullname'])
							,'fathername'		=> cleanvars($_POST['fathername'])
							,'phone'			=> cleanvars($_POST['phone'])
							,'type'				=> cleanvars($_POST['type'])
							,'source'			=> cleanvars($_POST['source'])
							,'dated'			=> cleanvars($_POST['dated'])
							,'remarks'			=> cleanvars($_POST['remarks'])
							,'id_added'			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'		=> date('Y-m-d G:i:s')
						);
		$sqllms = $dblms->insert(INQUIRIES, $values);
		if($sqllms) { 
			$latestID = $dblms->lastestid();			
			sendRemark(moduleName(false).' Added', '1', $latestID);
			sessionMsg('Successfully', 'Record Successfully Added.', 'success');
			header("Location: ".moduleName().".php", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {
	$condition	=	array ( 
								 'select'		=> "id"
								,'where'		=> array( 
															 'fullname'			=> cleanvars($_POST['fullname'])
															,'dated'			=> cleanvars($_POST['dated'])
															,'is_deleted'		=> '0'
														)
								,'not_equal' 	=>	array( 
															'id'				=>	cleanvars(LMS_EDIT_ID)
														)
								,'return_type'	=> 'count' 
							); 
	if($dblms->getRows(INQUIRIES, $condition)) {
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php", true, 301);
		exit();
	} else {
		$values = array(
							 'status'			=> cleanvars($_POST['status'])
							,'fullname'			=> cleanvars($_POST['fullname'])
							,'fathername'		=> cleanvars($_POST['fathername'])
							,'phone'			=> cleanvars($_POST['phone'])
							,'type'				=> cleanvars($_POST['type'])
							,'source'			=> cleanvars($_POST['source'])
							,'dated'			=> cleanvars($_POST['dated'])
							,'remarks'			=> cleanvars($_POST['remarks'])
							,'id_modify'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'		=> date('Y-m-d G:i:s')
						);
		$sqllms = $dblms->Update(INQUIRIES , $values , "WHERE id  = '".cleanvars(LMS_EDIT_ID)."'");
		if($sqllms) { 
			$latestID =	LMS_EDIT_ID;			
			sendRemark(moduleName(false).' Updated', '2', $latestID);
			sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
			header("Location: ".moduleName().".php", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])) {
	$latestID = $_GET['deleteid'];
	$values = array(
						 'id_deleted'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'		=>	'1'
						,'ip_deleted'		=>	cleanvars(LMS_IP)
						,'date_deleted'		=>	date('Y-m-d G:i:s')
					);   
	$sqlDel = $dblms->Update(INQUIRIES , $values , "WHERE id  = '".cleanvars($latestID)."'");
	if($sqlDel) { 
		sendRemark(moduleName(false).' Deleted', '3', $latestID);
		sessionMsg('Successfully', 'Record Successfully Deleted.', 'danger');
		exit();
		header("Location: ".moduleName().".php", true, 301);
	}
}
?>