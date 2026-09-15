<?php
// ADD, UPDATE -- INTRODUCTION
if(isset($_POST['submit_introduction'])) {
	if(empty($_POST['info_id'])){
		$values = array(
							 'status'				=>	'1'
							,'introduction'			=>	cleanvars($_POST['introduction'])
							,'introduction_date'	=>	date('Y-m-d G:i:s')
							,'id_curs'				=>	cleanvars($_POST['id'])
							,'id_teacher'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
							,'academic_session'		=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_added'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'			=>	date('Y-m-d G:i:s')
						);
		$sqllms	= $dblms->insert(COURSES_INFO, $values);

		// REMARKS
		if($sqllms) {
			$latestID = $dblms->lastestid();
			sendRemark('Course Info Introduction Added', '1', $latestID);
			sessionMsg('Successfully', 'Record Successfully Added.', 'success');
			header("Location: ".moduleName().".php?".$redirection."", true, 301);
			exit();
		}
	}else{
		$values = array(
							 'introduction'			=>	cleanvars($_POST['introduction'])
							,'introduction_date'	=>	date('Y-m-d G:i:s')
							,'id_modify'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'			=>	date('Y-m-d G:i:s')
						);
		$sqllms = $dblms->Update(COURSES_INFO , $values , "WHERE id  = '".cleanvars($_POST['info_id'])."'");

		// REMARKS
		if($sqllms) { 
			$latestID = $_POST['info_id'];
			sendRemark('Course Info Introduction Updated', '2', $latestID);
			sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
			header("Location: ".moduleName().".php?".$redirection."", true, 301);
			exit();
		}
	}
}

// ADD, UPDATE -- OBJECTIVES
if(isset($_POST['submit_objectives'])) {
	if(empty($_POST['info_id'])){
		$values = array(
							 'status'				=>	'1'
							,'objectives'			=>	cleanvars($_POST['objectives'])
							,'objectives_date'		=>	date('Y-m-d G:i:s')
							,'id_curs'				=>	cleanvars($_POST['id'])
							,'id_teacher'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
							,'academic_session'		=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_added'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'			=>	date('Y-m-d G:i:s')
						);
		$sqllms	= $dblms->insert(COURSES_INFO, $values);

		// REMARKS
		if($sqllms) {
			$latestID = $dblms->lastestid();
			sendRemark('Course Info Objectives Added', '1', $latestID);
			sessionMsg('Successfully', 'Record Successfully Added.', 'success');
			header("Location: ".moduleName().".php?".$redirection."", true, 301);
			exit();
		}
	}else{
		$values = array(
							 'objectives'			=>	cleanvars($_POST['objectives'])
							,'objectives_date'		=>	date('Y-m-d G:i:s')
							,'id_modify'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'			=>	date('Y-m-d G:i:s')
						);
		$sqllms = $dblms->Update(COURSES_INFO , $values , "WHERE id  = '".cleanvars($_POST['info_id'])."'");

		// REMARKS
		if($sqllms) { 
			$latestID = $_POST['info_id'];
			sendRemark('Course Info Objectives Updated', '2', $latestID);
			sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
			header("Location: ".moduleName().".php?".$redirection."", true, 301);
			exit();
		}
	}
}

// ADD, UPDATE -- OUTCOMES
if(isset($_POST['submit_outcomes'])) {
	if(empty($_POST['info_id'])){
		$values = array(
							 'status'				=>	'1'
							,'outcomes'				=>	cleanvars($_POST['outcomes'])
							,'outcomes_date'		=>	date('Y-m-d G:i:s')
							,'id_curs'				=>	cleanvars($_POST['id'])
							,'id_teacher'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
							,'academic_session'		=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_added'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'			=>	date('Y-m-d G:i:s')
						);
		$sqllms	= $dblms->insert(COURSES_INFO, $values);

		// REMARKS
		if($sqllms) {
			$latestID = $dblms->lastestid();
			sendRemark('Course Info Outcomes Added', '1', $latestID);
			sessionMsg('Successfully', 'Record Successfully Added.', 'success');
			header("Location: ".moduleName().".php?".$redirection."", true, 301);
			exit();
		}
	}else{
		$values = array(
							 'outcomes'				=>	cleanvars($_POST['outcomes'])
							,'outcomes_date'		=>	date('Y-m-d G:i:s')
							,'id_modify'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'			=>	date('Y-m-d G:i:s')
						);
		$sqllms = $dblms->Update(COURSES_INFO , $values , "WHERE id  = '".cleanvars($_POST['info_id'])."'");

		// REMARKS
		if($sqllms) { 
			$latestID = $_POST['info_id'];
			sendRemark('Course Info Outcomes Updated', '2', $latestID);
			sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
			header("Location: ".moduleName().".php?".$redirection."", true, 301);
			exit();
		}
	}
}

// ADD, UPDATE -- STRATEGIES
if(isset($_POST['submit_strategies'])) {
	if(empty($_POST['info_id'])){
		$values = array(
							 'status'				=>	'1'
							,'strategies'			=>	cleanvars($_POST['strategies'])
							,'strategies_date'		=>	date('Y-m-d G:i:s')
							,'id_curs'				=>	cleanvars($_POST['id'])
							,'id_teacher'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
							,'academic_session'		=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_added'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'			=>	date('Y-m-d G:i:s')
						);
		$sqllms	= $dblms->insert(COURSES_INFO, $values);

		// REMARKS
		if($sqllms) {
			$latestID = $dblms->lastestid();
			sendRemark('Course Info Strategies Added', '1', $latestID);
			sessionMsg('Successfully', 'Record Successfully Added.', 'success');
			header("Location: ".moduleName().".php?".$redirection."", true, 301);
			exit();
		}
	}else{
		$values = array(
							 'strategies'			=>	cleanvars($_POST['strategies'])
							,'strategies_date'		=>	date('Y-m-d G:i:s')
							,'id_modify'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'			=>	date('Y-m-d G:i:s')
						);
		$sqllms = $dblms->Update(COURSES_INFO , $values , "WHERE id  = '".cleanvars($_POST['info_id'])."'");

		// REMARKS
		if($sqllms) { 
			$latestID = $_POST['info_id'];
			sendRemark('Course Info Strategies Updated', '2', $latestID);
			sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
			header("Location: ".moduleName().".php?".$redirection."", true, 301);
			exit();
		}
	}
}

// ADD, UPDATE -- EFFECTIVENESS
if(isset($_POST['submit_effectiveness'])) {
	if(empty($_POST['info_id'])){
		$values = array(
							 'status'				=>	'1'
							,'effectiveness'		=>	cleanvars($_POST['effectiveness'])
							,'effectiveness_date'	=>	date('Y-m-d G:i:s')
							,'id_curs'				=>	cleanvars($_POST['id'])
							,'id_teacher'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
							,'academic_session'		=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_added'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'			=>	date('Y-m-d G:i:s')
						);
		$sqllms	= $dblms->insert(COURSES_INFO, $values);

		// REMARKS
		if($sqllms) {
			$latestID = $dblms->lastestid();
			sendRemark('Course Info Effectiveness Added', '1', $latestID);
			sessionMsg('Successfully', 'Record Successfully Added.', 'success');
			header("Location: ".moduleName().".php?".$redirection."", true, 301);
			exit();
		}
	}else{
		$values = array(
							 'effectiveness'		=>	cleanvars($_POST['effectiveness'])
							,'effectiveness_date'	=>	date('Y-m-d G:i:s')
							,'id_modify'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'			=>	date('Y-m-d G:i:s')
						);
		$sqllms = $dblms->Update(COURSES_INFO , $values , "WHERE id  = '".cleanvars($_POST['info_id'])."'");

		// REMARKS
		if($sqllms) { 
			$latestID = $_POST['info_id'];
			sendRemark('Course Info Effectiveness Updated', '2', $latestID);
			sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
			header("Location: ".moduleName().".php?".$redirection."", true, 301);
			exit();
		}
	}
}

// ADD, UPDATE -- OUTLINES
if(isset($_POST['submit_outlines'])) {
	if(empty($_POST['info_id'])){
		$values = array(
							 'status'				=>	'1'
							,'outlines'				=>	cleanvars($_POST['outlines'])
							,'outlines_date'		=>	date('Y-m-d G:i:s')
							,'id_curs'				=>	cleanvars($_POST['id'])
							,'id_teacher'			=>	cleanvars($_SESSION['userlogininfo']['EMPLYID'])
							,'academic_session'		=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_added'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'			=>	date('Y-m-d G:i:s')
						);
		$sqllms	= $dblms->insert(COURSES_INFO, $values);

		// REMARKS
		if($sqllms) {
			$latestID = $dblms->lastestid();
			sendRemark('Course Info Outlines Added', '1', $latestID);
			sessionMsg('Successfully', 'Record Successfully Added.', 'success');
			header("Location: ".moduleName().".php?".$redirection."", true, 301);
			exit();
		}
	}else{
		$values = array(
							 'outlines'				=>	cleanvars($_POST['outlines'])
							,'outlines_date'		=>	date('Y-m-d G:i:s')
							,'id_modify'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'			=>	date('Y-m-d G:i:s')
						);
		$sqllms = $dblms->Update(COURSES_INFO , $values , "WHERE id  = '".cleanvars($_POST['info_id'])."'");

		// REMARKS
		if($sqllms) { 
			$latestID = $_POST['info_id'];
			sendRemark('Course Info Outlines Updated', '2', $latestID);
			sessionMsg('Successfully', 'Record Successfully Updated.', 'info');
			header("Location: ".moduleName().".php?".$redirection."", true, 301);
			exit();
		}
	}
}
?>