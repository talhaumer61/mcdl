<?php
// ADD RECORD
if(isset($_POST['submit_add'])) {
	$condition	=	array ( 
								 'select'		=> "id"
								,'where'		=> array( 
															 'is_deleted'		=> '0'
															,'book_name'		=>	cleanvars($_POST['book_name'])
															,'id_session'		=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
															,'id_campus'		=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
															,'id_teacher'		=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
															,'id_curs'			=>	cleanvars($_POST['id'])
														)
								,'return_type'	=> 'count' 
							); 
	if($dblms->getRows(COURSES_BOOKS, $condition)) {
		sessionMsg('Error','Record Already Exists.','danger');
		header("Location: courses.php?id=".cleanvars($_POST['id'])."&view=".cleanvars($_POST['view'])."", true, 301);
		exit();
	}else{
		$id_lecture		= implode(',',$_POST['id_lecture']);
		$values 		= array(
									'status'			=>	cleanvars($_POST['status'])
									,'book_name'		=>	cleanvars($_POST['book_name'])
									,'author_name'		=>	cleanvars($_POST['author_name'])
									,'edition'			=>	cleanvars($_POST['edition'])
									,'isbn'				=>	cleanvars($_POST['isbn'])
									,'publisher'		=>	cleanvars($_POST['publisher'])
									,'url'				=>	cleanvars($_POST['url'])
									,'id_session'		=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
									,'id_campus'		=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
									,'id_teacher'		=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
									,'id_curs'			=>	cleanvars($_POST['id'])
									,'id_added'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
									,'date_added'		=>	date('Y-m-d G:i:s')
								);
		$sqllms	= $dblms->insert(COURSES_BOOKS, $values);
		if($sqllms) { 
			// LATEST ID
			$latestID = $dblms->lastestid();
			// REMARKS
			sendRemark('Books Added ID:'.$latestID, '1');
			sessionMsg('Successfully', 'Record Successfully Added.', 'success');
			header("Location: courses.php?id=".cleanvars($_POST['id'])."&view=".cleanvars($_POST['view'])."", true, 301);
			exit();
		}
	}
}

// EDIT RECORD
if(isset($_POST['submit_edit'])) {
	$condition	=	array ( 
								 'select'		=> "id"
								,'where'		=>	array( 
															 'is_deleted'		=> '0'
															,'book_name'		=>	cleanvars($_POST['book_name'])
															,'id_session'		=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
															,'id_campus'		=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
															,'id_teacher'		=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
														)
								,'not_equal' 	=>	array( 
															'id'				=>	cleanvars($_POST['book_id'])
														)
								,'return_type' 	=>	'count'  
							); 
	if($dblms->getRows(COURSES_BOOKS, $condition)) {
		sessionMsg('Error', 'Record Already Exists.', 'danger');
		header("Location: courses.php?id=".cleanvars($_POST['id'])."&view=".cleanvars($_POST['view'])."", true, 301);
		exit();
	}else{	
		$values 		= array(
									 'status'			=>	cleanvars($_POST['status'])
									,'book_name'		=>	cleanvars($_POST['book_name'])
									,'author_name'		=>	cleanvars($_POST['author_name'])
									,'edition'			=>	cleanvars($_POST['edition'])
									,'isbn'				=>	cleanvars($_POST['isbn'])
									,'publisher'		=>	cleanvars($_POST['publisher'])
									,'url'				=>	cleanvars($_POST['url'])
									,'id_modify'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
									,'date_modify'		=>	date('Y-m-d G:i:s')
								);
		$sqllms = $dblms->Update(COURSES_BOOKS, $values , "WHERE id  = '".cleanvars($_POST['book_id'])."'");
		if($sqllms) { 
			// LATEST ID
			$latestID = $_POST['book_id'];
			// REMARKS
			sendRemark('Books Updated ID:'.$latestID, '2');
			sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
			header("Location: courses.php?id=".cleanvars($_POST['id'])."&view=".cleanvars($_POST['view'])."", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])) {
	$values = array(
						 'id_deleted'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'is_deleted'		=>	1
						,'ip_deleted'		=>	cleanvars($ip)
						,'date_deleted'		=>	date('Y-m-d G:i:s')
					);   
	$sqlDel = $dblms->Update(COURSES_BOOKS , $values , "WHERE id  = '".cleanvars($_GET['deleteid'])."'");
	if($sqlDel) { 
		sendRemark('Books Deleted ID:'.cleanvars($_GET['deleteid']), '3');
		sessionMsg('Success', 'Record Successfully Deleted.', 'success');
		header("Location: courses.php?id=".cleanvars($_POST['id'])."&view=".cleanvars($_POST['view'])."", true, 301);
		exit();
	}
}
?>