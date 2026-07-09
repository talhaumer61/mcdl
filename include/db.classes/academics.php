<?php
class academics {

// get all Courses
	public function get_courses($campusid, $srch = '', $status = 1) { 
		$searchby = '';
		if($srch) {	$searchby .= $srch;	}
		
		$dblms = new dblms();
		$conditions = array ( 
								  'select' 		=> '*'
								, 'where' 		=> array ( 
															   'curs_status' => cleanvars($status) 
															 
														 )
								, 'search_by' 	=> $searchby 
								, 'order_by' 	=> 'curs_code ASC, curs_name ASC'
								, 'return_type' => 'all' 
							); 
		$result = $dblms->getRows(COURSES, $conditions);
		return $result;
	}
// end get all Courses
	
// get all Programs
	public function get_programs($campusid, $srch = '', $status = 1) { 
		$searchby = '';
		if($srch) {	$searchby .= $srch;	}
		
		$dblms = new dblms();
		$conditions = array ( 
								  'select' 		=> 'prg_id, prg_name, prg_code'
								, 'where' 		=> array ( 
															   'prg_status' => cleanvars($status) 
															 
														 )
								, 'search_by' 	=> $searchby 
								, 'order_by' 	=> 'prg_name ASC'
								, 'return_type' => 'all' 
							); 
		$result = $dblms->getRows(PROGRAMS, $conditions);
		return $result;
	}
// end get all Programs
// get all Programs Category
	public function get_programscategory($campusid, $srch = '', $status = 1) { 
		$searchby = '';
		if($srch) {	$searchby .= $srch;	}
		
		$dblms = new dblms();
		$conditions = array ( 
								  'select' 		=> 'cat_id, cat_name, cat_code'
								, 'where' 		=> array ( 
															    'cat_status' => cleanvars($status) 
															  , 'id_campus' => cleanvars($campusid) 
														 )
								, 'search_by' 	=> $searchby 
								, 'order_by' 	=> 'cat_id ASC'
								, 'return_type' => 'all' 
							); 
		$result = $dblms->getRows(PROGRAMSCATS, $conditions);
		return $result;
	}
// end get all Programs Category
	
// get all Departments
	public function get_departments($campusid, $srch = '', $status = 1) { 
		$searchby = '';
		if($srch) {	$searchby .= $srch;	}
		
		$dblms = new dblms();
		$conditions = array ( 
								  'select' 		=> 'dept_id, dept_name, dept_code, id_faculty'
								, 'where' 		=> array ( 
															   'dept_status' => cleanvars($status) 
														 )
								, 'search_by' 	=> $searchby 
								, 'order_by' 	=> 'dept_name ASC'
								, 'return_type' => 'all' 
							); 
		$result = $dblms->getRows(DEPARTMENTS, $conditions);
		return $result;
	}
// end get all Departments

	
// get all Faculties
	public function get_faculties($campusid, $status = 1) { 
		$dblms = new dblms();
		$conditions = array ( 
								  'select' 		=> 'faculty_id, faculty_name'
								, 'where' 		=> array ( 
															   'faculty_status' => cleanvars($status) 
														 )
								, 'order_by' 	=> 'faculty_name ASC'
								, 'return_type' => 'all' 
							); 
		$result = $dblms->getRows(FACULTIES, $conditions);
		return $result;
	}
// end get all Faculties

	
}
// end class 