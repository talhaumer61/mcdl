<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
							 'select' 		=>	"id"
							,'where' 		=>	array( 
														 'is_deleted'			=>	0
														,'title'				=>	cleanvars($_POST['title'])
														,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'id_employee'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
													)
							,'return_type' 	=>	'count' 
						); 
	if($dblms->getRows(EMPLOYEE_PUBLICATIONS, $condition)) {		
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}else{
		$co_author 		= implode(',',$_POST['co_author']);
		$affiliation 	= implode(',',$_POST['affiliation']);
		$values 		= array(
									 'status'				=>	1
									,'id_type'				=>	cleanvars($_POST['id_type'])
									,'title'				=>	cleanvars($_POST['title'])
									,'sub_title'			=>	cleanvars($_POST['sub_title'])
								);
		if ($_POST['id_type'] == 1) {
			$values 		= array(
										 'journal'				=>	cleanvars($_POST['journal'])
										,'author'				=>	cleanvars($_POST['author'])
										,'co_author'			=>	cleanvars($co_author)
										,'affiliation'			=>	cleanvars($affiliation)
										,'id_country'			=>	cleanvars($_POST['id_country'])
										,'issn'					=>	cleanvars($_POST['issn'])
										,'doi'					=>	cleanvars($_POST['doi'])
										,'page'					=>	cleanvars($_POST['page'])
										,'vloume'				=>	cleanvars($_POST['vloume'])
										,'issue_num'			=>	cleanvars($_POST['issue_num'])
										,'id_language'			=>	cleanvars($_POST['id_language'])
										,'subject'				=>	cleanvars($_POST['subject'])
										,'keywords'				=>	cleanvars($_POST['keywords'])
										,'abstract'				=>	cleanvars($_POST['abstract'])
										,'year_date'			=>	cleanvars($_POST['year_date'])
										,'publisher_name'		=>	cleanvars($_POST['publisher_name'])
										,'url'					=>	cleanvars($_POST['url'])
										,'hec_category'			=>	cleanvars($_POST['hec_category'])
										,'hec_category_url'		=>	cleanvars($_POST['hec_category_url'])
										,'hec_medallion'		=>	cleanvars($_POST['hec_medallion'])
										,'hec_affiliation'		=>	cleanvars($_POST['hec_affiliation'])
										,'impact_factor'		=>	cleanvars($_POST['impact_factor'])
										,'indexed_on'			=>	cleanvars($_POST['indexed_on'])
										,'indexed_on_url'		=>	cleanvars($_POST['indexed_on_url'])
										,'id_employee'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
										,'id_employee'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
									);	
		} else if ($_POST['id_type'] == 2) {
			$values 		= array(
										 'page'					=>	cleanvars($_POST['page'])
										,'id_language'			=>	cleanvars($_POST['id_language'])
										,'id_dept'				=>	cleanvars($_POST['id_dept'])
										,'std_class'			=>	cleanvars($_POST['std_class'])
										,'material'				=>	cleanvars($_POST['material'])
										,'barcode'				=>	cleanvars($_POST['barcode'])
										,'session'				=>	cleanvars($_POST['session'])
										,'std_regno'			=>	cleanvars($_POST['std_regno'])
										,'submitted_by'			=>	cleanvars($_POST['submitted_by'])
										,'submitted_to'			=>	cleanvars($_POST['submitted_to'])
									);
		} else if ($_POST['id_type'] == 3) {
			$values 		= array(
										 'author'				=>	cleanvars($_POST['author'])
										,'corporate_name'		=>	cleanvars($_POST['corporate_name'])
										,'isbn'					=>	cleanvars($_POST['isbn'])
										,'issn'					=>	cleanvars($_POST['issn'])
										,'book_type'			=>	cleanvars($_POST['book_type'])
										,'page'					=>	cleanvars($_POST['page'])
										,'vloume'				=>	cleanvars($_POST['vloume'])
										,'id_language'			=>	cleanvars($_POST['id_language'])
										,'subject'				=>	cleanvars($_POST['subject'])
										,'keywords'				=>	cleanvars($_POST['keywords'])
										,'year_date'			=>	cleanvars($_POST['year_date'])
										,'publisher_name'		=>	cleanvars($_POST['publisher_name'])
										,'edition'				=>	cleanvars($_POST['edition'])
										,'editor'				=>	cleanvars($_POST['editor'])
										,'series_name'			=>	cleanvars($_POST['series_name'])
										,'series_num'			=>	cleanvars($_POST['series_num'])
										,'url'					=>	cleanvars($_POST['url'])
										,'id_dept'				=>	cleanvars($_POST['id_dept'])
									);
		}
		$values['id_campus']		=	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']);
		$values['id_employee']		=	cleanvars($_SESSION['userlogininfo']['EMPLYID']);

		$sqllms	= $dblms->insert(EMPLOYEE_PUBLICATIONS, $values);
		if($sqllms) { 
			// LATEST ID
			$latestID = $dblms->lastestid();
			// FILE UPLOAD
			if(!empty($_FILES['attachment']['name'])) {
				$path_parts 			= pathinfo($_FILES["attachment"]["name"]);
				$extension 				= strtolower($path_parts['extension']);
				if(in_array($extension , array('pdf', 'doc', 'docx', 'png', 'jpg', 'jpeg'))) {
					$img_dir 			= 'uploads/files/'.LMS_VIEW.'/';
					$originalImage		= $img_dir.to_seo_url(cleanvars($_POST['title'])).'-'.$latestID.".".($extension);
					$img_fileName		= to_seo_url(cleanvars($_POST['title'])).'-'.$latestID.".".($extension);
					$dataImage 			= array( 'attachment' => $img_fileName );
					$sqlUpdateImg 		= $dblms->Update(EMPLOYEE_PUBLICATIONS, $dataImage, "WHERE id = '".$latestID."'");
					if ($sqlUpdateImg) {
						move_uploaded_file($_FILES['attachment']['tmp_name'],$originalImage);
					}
				}
			}
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
														,'title'				=>	cleanvars($_POST['title'])
														,'id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'id_employee'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
													)
							,'not_equal' 	=>	array( 
														'id'					=>	cleanvars(LMS_EDIT_ID)
													)	
							,'return_type' 	=>	'count' 
						); 
	if($dblms->getRows(EMPLOYEE_PUBLICATIONS, $condition)) {		
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}else{
		$co_author 		= implode(',',$_POST['co_author']);
		$affiliation 	= implode(',',$_POST['affiliation']);
		$values 		= array(
									 'status'				=>	1
									,'id_type'				=>	cleanvars($_POST['id_type'])
									,'title'				=>	cleanvars($_POST['title'])
									,'sub_title'			=>	cleanvars($_POST['sub_title'])
								);
		if ($_POST['id_type'] == 1) {
			$values 		= array(
										 'journal'				=>	cleanvars($_POST['journal'])
										,'author'				=>	cleanvars($_POST['author'])
										,'co_author'			=>	cleanvars($co_author)
										,'affiliation'			=>	cleanvars($affiliation)
										,'id_country'			=>	cleanvars($_POST['id_country'])
										,'issn'					=>	cleanvars($_POST['issn'])
										,'doi'					=>	cleanvars($_POST['doi'])
										,'page'					=>	cleanvars($_POST['page'])
										,'vloume'				=>	cleanvars($_POST['vloume'])
										,'issue_num'			=>	cleanvars($_POST['issue_num'])
										,'id_language'			=>	cleanvars($_POST['id_language'])
										,'subject'				=>	cleanvars($_POST['subject'])
										,'keywords'				=>	cleanvars($_POST['keywords'])
										,'abstract'				=>	cleanvars($_POST['abstract'])
										,'year_date'			=>	cleanvars($_POST['year_date'])
										,'publisher_name'		=>	cleanvars($_POST['publisher_name'])
										,'url'					=>	cleanvars($_POST['url'])
										,'hec_category'			=>	cleanvars($_POST['hec_category'])
										,'hec_category_url'		=>	cleanvars($_POST['hec_category_url'])
										,'hec_medallion'		=>	cleanvars($_POST['hec_medallion'])
										,'hec_affiliation'		=>	cleanvars($_POST['hec_affiliation'])
										,'impact_factor'		=>	cleanvars($_POST['impact_factor'])
										,'indexed_on'			=>	cleanvars($_POST['indexed_on'])
										,'indexed_on_url'		=>	cleanvars($_POST['indexed_on_url'])
										,'id_employee'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
										,'id_employee'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
									);	
		} else if ($_POST['id_type'] == 2) {
			$values 		= array(
										 'page'					=>	cleanvars($_POST['page'])
										,'id_language'			=>	cleanvars($_POST['id_language'])
										,'id_dept'				=>	cleanvars($_POST['id_dept'])
										,'std_class'			=>	cleanvars($_POST['std_class'])
										,'material'				=>	cleanvars($_POST['material'])
										,'barcode'				=>	cleanvars($_POST['barcode'])
										,'session'				=>	cleanvars($_POST['session'])
										,'std_regno'			=>	cleanvars($_POST['std_regno'])
										,'submitted_by'			=>	cleanvars($_POST['submitted_by'])
										,'submitted_to'			=>	cleanvars($_POST['submitted_to'])
									);
		} else if ($_POST['id_type'] == 3) {
			$values 		= array(
										 'author'				=>	cleanvars($_POST['author'])
										,'corporate_name'		=>	cleanvars($_POST['corporate_name'])
										,'isbn'					=>	cleanvars($_POST['isbn'])
										,'issn'					=>	cleanvars($_POST['issn'])
										,'book_type'			=>	cleanvars($_POST['book_type'])
										,'page'					=>	cleanvars($_POST['page'])
										,'vloume'				=>	cleanvars($_POST['vloume'])
										,'id_language'			=>	cleanvars($_POST['id_language'])
										,'subject'				=>	cleanvars($_POST['subject'])
										,'keywords'				=>	cleanvars($_POST['keywords'])
										,'year_date'			=>	cleanvars($_POST['year_date'])
										,'publisher_name'		=>	cleanvars($_POST['publisher_name'])
										,'edition'				=>	cleanvars($_POST['edition'])
										,'editor'				=>	cleanvars($_POST['editor'])
										,'series_name'			=>	cleanvars($_POST['series_name'])
										,'series_num'			=>	cleanvars($_POST['series_num'])
										,'url'					=>	cleanvars($_POST['url'])
										,'id_dept'				=>	cleanvars($_POST['id_dept'])
									);
		}
		$values['id_campus']		=	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']);
		$values['id_employee']		=	cleanvars($_SESSION['userlogininfo']['EMPLYID']);
		
		$sqllms = $dblms->Update(EMPLOYEE_PUBLICATIONS, $values , "WHERE id  = '".cleanvars(LMS_EDIT_ID)."'");
		if($sqllms) { 
			// LATEST ID
			$latestID = LMS_EDIT_ID;
			// FILE UPLOAD
			if(!empty($_FILES['attachment']['name'])) {
				$path_parts 			= pathinfo($_FILES["attachment"]["name"]);
				$extension 				= strtolower($path_parts['extension']);
				if(in_array($extension , array('pdf', 'doc', 'docx', 'png', 'jpg', 'jpeg'))) {
					$img_dir 			= 'uploads/files/'.LMS_VIEW.'/';
					$originalImage		= $img_dir.to_seo_url(cleanvars($_POST['title'])).'-'.$latestID.".".($extension);
					$img_fileName		= to_seo_url(cleanvars($_POST['title'])).'-'.$latestID.".".($extension);
					$dataImage 			= array( 'attachment' => $img_fileName );
					$sqlUpdateImg 		= $dblms->Update(EMPLOYEE_PUBLICATIONS, $dataImage, "WHERE id = '".$latestID."'");
					if ($sqlUpdateImg) {
						move_uploaded_file($_FILES['attachment']['tmp_name'],$originalImage);
					}
				}
			}
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
	$values = array( 'is_deleted' => 1 );   
	$sqlDel = $dblms->Update(EMPLOYEE_PUBLICATIONS, $values , "WHERE id  = '".cleanvars($latestID)."'");
	if($sqlDel) { 
		sendRemark(moduleName(LMS_VIEW).' Deleted', '2', $latestID);
		sessionMsg('Success', 'Record Successfully Deleted.', 'success');
		header("Location: ".moduleName().".php?".$redirection."", true, 301);
		exit();
	}
}
?>