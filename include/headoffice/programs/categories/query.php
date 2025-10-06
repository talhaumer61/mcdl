<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {

	$condition	=	array ( 
							'select' 	=> "cat_id",
							'where' 	=> array( 
													 'cat_name'		=>	cleanvars($_POST['cat_name'])
													,'is_deleted'	=>	'0'	
												),
							'return_type' 	=> 'count' 
						  ); 
	if($dblms->getRows(PROGRAMS_CATEGORIES, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "danger");
		header("Location:program_categories.php", true, 301);
		exit();
	}else{

		$values = array(
						 'cat_status'			=>	cleanvars($_POST['cat_status'])
						,'cat_code'				=>	cleanvars($_POST['cat_code'])
						,'cat_href'				=>	to_seo_url($_POST['cat_name'])
						,'cat_ordering'			=>	cleanvars($_POST['cat_ordering'])
						,'cat_name'				=>	cleanvars($_POST['cat_name'])
						,'cat_description'		=>	cleanvars($_POST['cat_description'])
						,'cat_meta_keywords'	=>	cleanvars($_POST['cat_meta_keywords'])
						,'cat_meta_description'	=>	cleanvars($_POST['cat_meta_description'])
						,'id_added'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'date_added'			=>	date('Y-m-d G:i:s')
					   ); 

		$sqllms		=	$dblms->insert(PROGRAMS_CATEGORIES, $values);

		if($sqllms) { 
			$latestID  =	$dblms->lastestid();

			// CATEGORY ICON
			if(!empty($_FILES['cat_icon']['name'])) {

				$path_parts 	= pathinfo($_FILES["cat_icon"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/programs/categories/icons/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['cat_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['cat_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'cat_icon'		=> $img_fileName, 
									  );
					$sqllmsUpdateCNIC = $dblms->Update(PROGRAMS_CATEGORIES, $dataImage, "WHERE cat_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['cat_icon']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}

			}
			// CATEGORY IMAGE
			if(!empty($_FILES['cat_image']['name'])) {

				$path_parts 	= pathinfo($_FILES["cat_image"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/programs/categories/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['cat_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['cat_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'cat_image'		=> $img_fileName, 
									  );
					$sqllmsUpdateCNIC = $dblms->Update(PROGRAMS_CATEGORIES, $dataImage, "WHERE cat_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['cat_image']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}

			}

			// REMARKS
			sendRemark("Program Category Added ID: ".$latestID." Detail", '1');
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location:program_categories.php", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {

	$condition	=	array ( 
							'select' 	=> "cat_id",
							'where' 	=> array( 
													 'cat_name'		=>	cleanvars($_POST['cat_name'])
													,'is_deleted'	=>	'0'	
												),
							'not_equal' 	=> array( 
													'cat_id'		=>	cleanvars($_POST['cat_id'])
												),					
							'return_type' 	=> 'count' 
						  ); 
	if($dblms->getRows(PROGRAMS_CATEGORIES, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "danger");
		header("Location:program_categories.php", true, 301);
		exit();
	}else{
		$latestID  =	$_POST['cat_id'];
		$values = array(
						'cat_status'			=>	cleanvars($_POST['cat_status'])
						,'cat_code'				=>	cleanvars($_POST['cat_code'])
						,'cat_href'				=>	to_seo_url($_POST['cat_name'])
						,'cat_ordering'			=>	cleanvars($_POST['cat_ordering'])
						,'cat_name'				=>	cleanvars($_POST['cat_name'])
						,'cat_description'		=>	cleanvars($_POST['cat_description'])
						,'cat_meta_keywords'	=>	cleanvars($_POST['cat_meta_keywords'])
						,'cat_meta_description'	=>	cleanvars($_POST['cat_meta_description'])
						,'id_modify'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'date_modify'			=>	date('Y-m-d G:i:s')

					   ); 
		$sqllms = $dblms->Update(PROGRAMS_CATEGORIES , $values , "WHERE cat_id  = '".cleanvars($latestID)."'");
		if($sqllms) { 

			// CATEGORY ICON
			if(!empty($_FILES['cat_icon']['name'])) {

				$path_parts 	= pathinfo($_FILES["cat_icon"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/programs/categories/icons/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['cat_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['cat_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'cat_icon'		=> $img_fileName, 
									  );
					$sqllmsUpdateCNIC = $dblms->Update(PROGRAMS_CATEGORIES, $dataImage, "WHERE cat_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['cat_icon']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}

			}
			// CATEGORY IMAGE
			if(!empty($_FILES['cat_image']['name'])) {

				$path_parts 	= pathinfo($_FILES["cat_image"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				if(in_array($extension , array('jpeg','jpg', 'png', 'JPEG', 'JPG', 'PNG', 'svg'))) {
					$img_dir 		= 'uploads/images/programs/categories/';
					$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['cat_name'])).'-'.$latestID.".".($extension);
					$img_fileName	= to_seo_url(cleanvars($_POST['cat_name'])).'-'.$latestID.".".($extension);
					$dataImage = array(
										'cat_image'		=> $img_fileName, 
									  );
					$sqllmsUpdateCNIC = $dblms->Update(PROGRAMS_CATEGORIES, $dataImage, "WHERE cat_id = '".$latestID."'");
					unset($sqllmsUpdateCNIC);
					$mode = '0644';
					move_uploaded_file($_FILES['cat_image']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}

			}
			// REMARKS
			sendRemark("Program Category Updated ID: ".$latestID." Detail", '2');
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location:program_categories.php", true, 301);
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

	$sqlDel = $dblms->Update(PROGRAMS_CATEGORIES , $values , "WHERE cat_id  = '".cleanvars($_GET['deleteid'])."'");

	if($sqlDel) { 
		sendRemark("Program Category Deleted ID: ".$_GET['deleteid']." Detail", '3');
		sessionMsg("Warning", "Record Successfully Deleted.", "warning");
		header("Location: program_categories.php", true, 301);
		exit();
	}
}