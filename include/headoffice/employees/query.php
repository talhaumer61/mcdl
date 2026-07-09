<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition		=	 array ( 
									 'select' 		=> 'emply_id'
									,'where' 		=> array( 
																 'is_deleted'	=> '0'	
																,'id_campus'	=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
																,'emply_email'  => cleanvars($_POST['emply_email'])
															)
									,'return_type' 	=> 'count'
								);
	if($dblms->getRows(EMPLOYEES, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "error");
		header("Location: employees.php", true, 301);
		exit();
	}else{
		$array 	= explode('|',$_POST['id_city']);
		$values = array(
							 'emply_status'					=>	cleanvars($_POST['emply_status'])
							,'emply_ordering'				=>	cleanvars($_POST['emply_ordering'])
							,'emply_request'				=> 1
							,'emply_name'					=>	cleanvars($_POST['emply_name'])
							,'emply_fathername'				=>	cleanvars($_POST['emply_fathername'])
							,'emply_dob'					=>	date("Y-m-d", strtotime($_POST['emply_dob']))
							,'emply_cnic'					=>	cleanvars($_POST['emply_cnic'])
							,'emply_marital'				=>	cleanvars($_POST['emply_marital'])
							,'emply_gender'					=>	cleanvars($_POST['emply_gender'])
							,'emply_blood'					=>	cleanvars($_POST['emply_blood'])
							,'emply_religion'				=>	cleanvars($_POST['emply_religion'])
							,'emply_joining_date'			=>	date("Y-m-d", strtotime($_POST['emply_joining_date']))
							,'id_type'						=>	cleanvars($_POST['id_type'])
							,'emply_permanentvisiting'		=>	cleanvars($_POST['emply_permanentvisiting'])
							,'id_dept'						=>	cleanvars($_POST['id_dept'])
							,'id_designation'				=>	cleanvars($_POST['id_designation'])
							,'id_city'						=>	cleanvars($array[0])
							,'id_country'					=>	cleanvars($array[1])
							,'emply_qualification'			=>	cleanvars($_POST['emply_qualification'])
							,'emply_university'				=>	cleanvars($_POST['emply_university'])
							,'emply_specialsubject'			=>	cleanvars($_POST['emply_specialsubject'])
							,'emply_passingyear'			=>	cleanvars($_POST['emply_passingyear'])
							,'emply_degreecountry'			=>	cleanvars($_POST['emply_degreecountry'])
							,'emply_experince'				=>	cleanvars($_POST['emply_experince'])
							,'emply_specialization'			=>	cleanvars($_POST['emply_specialization'])
							,'emply_phone'					=>	cleanvars($_POST['emply_phone'])
							,'emply_mobile'					=>	cleanvars($_POST['emply_mobile'])
							,'emply_email'					=>	cleanvars($_POST['emply_email'])
							,'emply_postal_address'			=>	cleanvars($_POST['emply_postal_address'])
							,'emply_permanent_address'		=>	cleanvars($_POST['emply_permanent_address'])
							,'emply_introduction'			=>	cleanvars($_POST['emply_introduction'])
							,'id_campus'					=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_added'						=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'					=>	date('Y-m-d G:i:s')
						); 
		$sqllms	=	$dblms->insert(EMPLOYEES, $values);

		if($sqllms){
			// LATEST ID
			$latestID = $dblms->lastestid();
			
			// HREF
			$sqlEmpHref = $dblms->Update(EMPLOYEES, ['emply_href' => to_seo_url($_POST['emply_name'].'-'.date('y').'-'.$latestID)], "WHERE emply_id = '".$latestID."'");

			// PHOTO
			if(!empty($_FILES['emply_photo']['name'])) {
				$path_parts 			= pathinfo($_FILES["emply_photo"]["name"]);
				$extension 				= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 			= 'uploads/images/employees/';
					$originalImage		= $img_dir.to_seo_url(cleanvars($_POST['emply_email'])).'-'.$latestID.".".($extension);
					$img_fileName		= to_seo_url(cleanvars($_POST['emply_email'])).'-'.$latestID.".".($extension);
					$dataImage 			= array( 'emply_photo' => $img_fileName );
					$sqlUpdateImg 		= $dblms->Update(EMPLOYEES, $dataImage, "WHERE emply_id = '".$latestID."'");
					if ($sqlUpdateImg) {
						move_uploaded_file($_FILES['emply_photo']['tmp_name'],$originalImage);
					}
				}
			}

			// REMARKS
			sendRemark("Employee Added ID: ".$latestID." Detail", '1');
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: employees.php", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {
	$condition	=	array ( 
							 'select' 		=>	'emply_id'
							,'where' 		=>	array( 
														 'is_deleted'	=> '0'
														,'emply_email'  => cleanvars($_POST['emply_email'])
													)
							,'not_equal'	=>	array(
														'emply_id'		=>	cleanvars($_POST['edit_id'])
													)
							,'return_type' 	=>	'count'
						);
	if($dblms->getRows(EMPLOYEES, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "error");
		header("Location: employees.php?id=".cleanvars($_POST['edit_id'])."", true, 301);
		exit();
	}else{	
		$array 	= explode('|',$_POST['id_city']);
		$values = array(
							 'emply_status'					=>	cleanvars($_POST['emply_status'])
							,'emply_request'				=> 1
							,'emply_name'					=>	cleanvars($_POST['emply_name'])
							,'emply_href'					=>	to_seo_url($_POST['emply_name'].'-'.date('y').'-'.$_POST['edit_id'])
							,'emply_fathername'				=>	cleanvars($_POST['emply_fathername'])
							,'emply_dob'					=>	date("Y-m-d", strtotime($_POST['emply_dob']))
							,'emply_cnic'					=>	cleanvars($_POST['emply_cnic'])
							,'emply_marital'				=>	cleanvars($_POST['emply_marital'])
							,'emply_gender'					=>	cleanvars($_POST['emply_gender'])
							,'emply_blood'					=>	cleanvars($_POST['emply_blood'])
							,'emply_religion'				=>	cleanvars($_POST['emply_religion'])
							,'emply_joining_date'			=>	date("Y-m-d", strtotime($_POST['emply_joining_date']))
							,'id_type'						=>	cleanvars($_POST['id_type'])
							,'emply_permanentvisiting'		=>	cleanvars($_POST['emply_permanentvisiting'])
							,'id_dept'						=>	cleanvars($_POST['id_dept'])
							,'id_designation'				=>	cleanvars($_POST['id_designation'])
							,'id_city'						=>	cleanvars($array[0])
							,'id_country'					=>	cleanvars($array[1])
							,'emply_qualification'			=>	cleanvars($_POST['emply_qualification'])
							,'emply_university'				=>	cleanvars($_POST['emply_university'])
							,'emply_specialsubject'			=>	cleanvars($_POST['emply_specialsubject'])
							,'emply_passingyear'			=>	cleanvars($_POST['emply_passingyear'])
							,'emply_degreecountry'			=>	cleanvars($_POST['emply_degreecountry'])
							,'emply_experince'				=>	cleanvars($_POST['emply_experince'])
							,'emply_specialization'			=>	cleanvars($_POST['emply_specialization'])
							,'emply_phone'					=>	cleanvars($_POST['emply_phone'])
							,'emply_mobile'					=>	cleanvars($_POST['emply_mobile'])
							,'emply_email'					=>	cleanvars($_POST['emply_email'])
							,'emply_postal_address'			=>	cleanvars($_POST['emply_postal_address'])
							,'emply_permanent_address'		=>	cleanvars($_POST['emply_permanent_address'])
							,'emply_introduction'			=>	cleanvars($_POST['emply_introduction'])
							,'id_modify'					=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'					=>	date('Y-m-d G:i:s')
						); 
		$sqllms = $dblms->Update(EMPLOYEES, $values , "WHERE emply_id  = '".cleanvars($_POST['edit_id'])."'");
		
		if($sqllms) {			
			// LATEST ID
			$latestID = $_POST['edit_id'];

			// ICON
			if(!empty($_FILES['emply_photo']['name'])) {
				$path_parts 			= pathinfo($_FILES["emply_photo"]["name"]);
				$extension 				= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 			= 'uploads/images/employees/';
					$originalImage		= $img_dir.to_seo_url(cleanvars($_POST['emply_email'])).'-'.$latestID.".".($extension);
					$img_fileName		= to_seo_url(cleanvars($_POST['emply_email'])).'-'.$latestID.".".($extension);
					$dataImage 			= array( 'emply_photo' => $img_fileName );
					$sqlUpdateImg 		= $dblms->Update(EMPLOYEES, $dataImage, "WHERE emply_id = '".$latestID."'");
					if ($sqlUpdateImg) {
						move_uploaded_file($_FILES['emply_photo']['tmp_name'],$originalImage);
					}
				}
			}

			// REMARKS
			sendRemark("Employee Updated ID: ".$latestID." Detail", '2');
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: employees.php", true, 301);
			exit();
		}
	}	
}

// APPROVE OR REJECT
if(isset($_POST['approve_reject'])) {
	// APPROVE
	if($_POST['emply_request'] == '1'){
		$values = array(
							 'is_teacher'		=> 3
							,'adm_logintype'	=> 3
							,'date_modify'		=> date('Y-m-d G:i:s')
							,'id_modify'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						);
		$sqllms = $dblms->Update(ADMINS, $values , "WHERE adm_id  = '".cleanvars($_POST['adm_id'])."'");

		if($sqllms){
			$empValues = array(
								 'emply_loginid'		=> $_POST['adm_id']
								,'emply_request'		=> 1
								,'remarks'				=> cleanvars($_POST['remarks'])
								,'date_modify'			=> date('Y-m-d G:i:s')
								,'id_modify'			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
			);
			$empsqllms = $dblms->Update(EMPLOYEES, $empValues , "WHERE emply_id  = '".cleanvars($_POST['emply_id'])."'");

			// REMAKRS
			sendRemark("Teacher Approved", '2', $_POST['emply_id']);
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: employees.php", true, 301);
			exit();
		}

	}	
	// REJECT
	elseif($_POST['emply_request'] == '3'){
		$values = array(
							 'emply_request'		=> 3
							,'remarks'				=> cleanvars($_POST['remarks'])
							,'date_modify'			=> date('Y-m-d G:i:s')
							,'id_modify'			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
		);
		$sqllms = $dblms->Update(EMPLOYEES, $values , "WHERE emply_id  = '".cleanvars($_POST['emply_id'])."'");

		if($sqllms){
			sendRemark("Teacher Rejected", '2', $_POST['emply_id']);
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: employees.php", true, 301);
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
	$sqlDel = $dblms->Update(EMPLOYEES, $values , "WHERE emply_id = '".cleanvars($_GET['deleteid'])."'");

	if($sqlDel) {
		sendRemark("Employee Deleted ID: ".$_GET['deleteid']." Detail", '3');
		sessionMsg("Warning", "Record Successfully Deleted.", "warning");
		header("Location: employees.php", true, 301);
		exit();
	}
}
?>