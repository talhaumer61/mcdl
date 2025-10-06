<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
							 'select' 		=>	'curs_id'
							,'where' 		=>	array( 
														 'curs_name'	=>	cleanvars($_POST['curs_name'])
														,'is_deleted'	=>	'0'	
													)
							,'return_type' 	=>	'count'
						); 
	if($dblms->getRows(COURSES, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "error");
		header("Location: ".moduleName().".php?$redirection", true, 301);
		exit();
	}else{
		$curs_credit_hours = $_POST['cur_credithours_theory'] + $_POST['cur_credithours_practical'];

		// SEQUENCING
		$array = array();
		$sequencing_category = '';
		foreach($_POST['sequencing_category'] as $key => $val):
			array_push($array, $val);
			$sequencing_category = implode(",", $array);
		endforeach;

		// WHAT YOU LEARN	
		$what_you_learn = json_encode($_POST['what_you_learn'], JSON_UNESCAPED_UNICODE);

		$values = array(
							 'curs_status'					=>	cleanvars($_POST['curs_status'])
							,'curs_wise'					=>	cleanvars($_POST['curs_wise'])
							,'duration'						=>	cleanvars($_POST['duration'])
							,'id_level'						=>	cleanvars($_POST['id_level'])
							,'id_cat'						=>	implode(",",$_POST['id_cat'])
							,'sequencing_category'			=>	cleanvars($sequencing_category)
							,'id_dept'						=>	cleanvars($_POST['id_dept'])
							,'id_faculty'					=>	cleanvars($_POST['id_faculty'])
							,'curs_name'			  		=>	cleanvars($_POST['curs_name'])
							,'curs_href'					=>	to_seo_url($_POST['curs_name'])
							,'curs_meta'					=>	cleanvars($_POST['curs_meta'])
							,'curs_keyword'					=>	cleanvars($_POST['curs_keyword'])
							,'cur_credithours_theory'		=>	cleanvars($_POST['cur_credithours_theory'])
							,'cur_credithours_practical'	=>	cleanvars($_POST['cur_credithours_practical'])
							,'curs_credit_hours'			=>	cleanvars($curs_credit_hours)							
							,'curs_pre_requisite'			=>	cleanvars($_POST['curs_pre_requisite'])
							,'curs_specialization'			=>	cleanvars($_POST['curs_specialization'])
							,'curs_type'					=>	cleanvars($_POST['curs_type'])
							,'curs_detail'					=>	cleanvars($_POST['curs_detail'])
							,'curs_about'					=>	cleanvars($_POST['curs_about'])
							,'what_you_learn'				=>	cleanvars($what_you_learn)
							,'curs_skills'					=>	cleanvars($_POST['curs_skills'])
							,'how_it_work'					=>	cleanvars($_POST['how_it_work'])
							,'curs_references'				=>	cleanvars($_POST['curs_references'])
							,'curs_video'					=>	cleanvars($_POST['curs_video'])
							,'curs_rating'					=>	cleanvars($_POST['curs_rating'])
							,'curs_hours'					=>	cleanvars($_POST['curs_hours'])
							,'id_lang'						=>	implode(",",$_POST['id_lang'])
							,'curs_type_status'				=>	cleanvars($_POST['curs_type_status'])
							,'id_campus'					=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_added'						=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'					=>	date('Y-m-d G:i:s')
						); 
		$sqllms	= $dblms->insert(COURSES, $values);

		if($sqllms){
			// LATEST ID
			$latestID = $dblms->lastestid();

			// ICON
			if(!empty($_FILES['curs_icon']['name'])) {
				$path_parts 	= pathinfo($_FILES["curs_icon"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/courses/icons/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['curs_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['curs_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'curs_icon'		=> $img_fileName, 
										);
					$sqllmsUpdateCNIC = $dblms->Update(COURSES, $dataImage, "WHERE curs_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['curs_icon']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}

			// IMAGE
			if(!empty($_FILES['curs_photo']['name'])) {
				$path_parts 	= pathinfo($_FILES["curs_photo"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/courses/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['curs_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['curs_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'curs_photo'		=> $img_fileName, 
										);
					$sqllmsUpdateCNIC = $dblms->Update(COURSES, $dataImage, "WHERE curs_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['curs_photo']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}

			// REMARKS
			sendRemark(moduleName(false).' Added', '1', $latestID);
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: ".moduleName().".php?$redirection", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {
	$condition	=	array ( 
							 'select' 		=> 'curs_id'
							,'where' 		=>	array( 
														 'curs_name'	=>	cleanvars($_POST['curs_name'])
														,'is_deleted'	=>	'0'	
													)
							,'not_equal'	=>	array(
														'curs_id'		=>	cleanvars($_POST['curs_id'])
													)
							,'return_type' 	=> 'count'
						); 
	if($dblms->getRows(COURSES, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "error");
		header("Location: ".moduleName().".php?$redirection", true, 301);
		exit();
	}else{
		$curs_credit_hours = $_POST['cur_credithours_theory'] + $_POST['cur_credithours_practical'];

		// SEQUENCING
		$array = array();
		$sequencing_category = '';
		foreach($_POST['sequencing_category'] as $key => $val):
			array_push($array, $val);
			$sequencing_category = implode(",", $array);	
		endforeach;

		// WHAT YOU LEARN
		$what_you_learn = json_encode($_POST['what_you_learn'], JSON_UNESCAPED_UNICODE);

		$values = array(
							 'curs_status'					=>	cleanvars($_POST['curs_status'])
							,'curs_wise'					=>	cleanvars($_POST['curs_wise'])
							,'duration'						=>	cleanvars($_POST['duration'])
							,'id_level'						=>	cleanvars($_POST['id_level'])
							,'id_cat'						=>	implode(",",$_POST['id_cat'])
							,'sequencing_category'			=>	cleanvars($sequencing_category)
							,'id_dept'						=>	cleanvars($_POST['id_dept'])
							,'id_faculty'					=>	cleanvars($_POST['id_faculty'])
							,'curs_name'					=>	cleanvars($_POST['curs_name'])
							,'curs_href'					=>	to_seo_url($_POST['curs_name'])
							,'curs_meta'					=>	cleanvars($_POST['curs_meta'])
							,'curs_keyword'					=>	cleanvars($_POST['curs_keyword'])
							,'cur_credithours_theory'		=>	cleanvars($_POST['cur_credithours_theory'])
							,'cur_credithours_practical'	=>	cleanvars($_POST['cur_credithours_practical'])
							,'curs_credit_hours'			=>	cleanvars($curs_credit_hours)							
							,'curs_pre_requisite'			=>	cleanvars($_POST['curs_pre_requisite'])
							,'curs_specialization'			=>	cleanvars($_POST['curs_specialization'])
							,'curs_type'					=>	cleanvars($_POST['curs_type'])
							,'curs_detail'					=>	cleanvars($_POST['curs_detail'])
							,'curs_about'					=>	cleanvars($_POST['curs_about'])
							,'what_you_learn'				=>	cleanvars($what_you_learn)
							,'curs_skills'					=>	cleanvars($_POST['curs_skills'])
							,'how_it_work'					=>	cleanvars($_POST['how_it_work'])
							,'curs_references'				=>	cleanvars($_POST['curs_references'])
							,'curs_video'					=>	cleanvars($_POST['curs_video'])
							,'curs_rating'					=>	cleanvars($_POST['curs_rating'])
							,'curs_hours'					=>	cleanvars($_POST['curs_hours'])
							,'id_lang'						=>	implode(",",$_POST['id_lang'])
							,'curs_type_status'				=>	cleanvars($_POST['curs_type_status'])
							,'id_campus'					=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_modify'					=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'					=>	date('Y-m-d G:i:s')
						);
		$sqllms = $dblms->Update(COURSES, $values , "WHERE curs_id  = '".cleanvars($_POST['curs_id'])."'");
		if($sqllms) {
			// LATEST ID
			$latestID = $_POST['curs_id'];

			// ICON
			if(!empty($_FILES['curs_icon']['name'])) {
				$path_parts 	= pathinfo($_FILES["curs_icon"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/courses/icons/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['curs_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['curs_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'curs_icon'		=> $img_fileName, 
										);
					$sqllmsUpdateCNIC = $dblms->Update(COURSES, $dataImage, "WHERE curs_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['curs_icon']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}

			// IMAGE
			if(!empty($_FILES['curs_photo']['name'])) {
				$path_parts 	= pathinfo($_FILES["curs_photo"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/courses/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['curs_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['curs_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'curs_photo'		=> $img_fileName, 
										);
					$sqllmsUpdateCNIC = $dblms->Update(COURSES, $dataImage, "WHERE curs_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['curs_photo']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}

			// REMARKS
			sendRemark(moduleName(false).'Updated', '2', $latestID);
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: ".moduleName().".php?$redirection", true, 301);
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
	$sqlDel = $dblms->Update(COURSES, $values , "WHERE curs_id  = '".cleanvars($_GET['deleteid'])."'");

	if($sqlDel) {
		sendRemark(moduleName(false).' Deleted', '3', $latestID);
		sessionMsg("Success", "Record Successfully Deleted.", "warning");
		header("Location: ".moduleName().".php?$redirection", true, 301);
		exit();
	}
}

// ALLOCATE TEACHERS
if(isset($_POST['allocate_teachers'])) {
	$values = array(
						 'id_curs'			=>	cleanvars($_POST['id_curs'])
						,'id_teacher'		=>	implode(",", $_POST['id_teacher'])
						,'remarks'			=>	cleanvars($_POST['remarks'])
						,'id_added'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'date_added'		=>	date('Y-m-d G:i:s')
					);
	if(empty($_POST['id'])){
		$values['id_added']		= cleanvars($_SESSION['userlogininfo']['LOGINIDA']);
		$values['date_added']	= date('Y-m-d G:i:s');
		$sqllms	= $dblms->insert(ALLOCATE_TEACHERS, $values);
		// LATEST ID
		$latestID = $dblms->lastestid();
		$action = '1';
	}else{
		$values['id_modify']	= cleanvars($_SESSION['userlogininfo']['LOGINIDA']);
		$values['date_modify']	= date('Y-m-d G:i:s');
		$sqllms = $dblms->Update(ALLOCATE_TEACHERS, $values, "WHERE id = '".$_POST['id']."' ");
		// LATEST ID
		$latestID = $_POST['id'];
		$action = '2';
	}

	if($sqllms){
		// REMARKS
		sendRemark("Teachers Allocated to Course", $action, $latestID);
		sessionMsg("Success", "Record Successfully Updated.", "success");
		header("Location: ".moduleName().".php?$redirection", true, 301);
		exit();
	}
}
?>