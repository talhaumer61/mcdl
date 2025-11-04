<?php
class courses {

// get Single Course Details
	public function get_course($id) {
		$dblms = new dblms();
		$conditions = array (
                                  'select'      => 'c.*, c2.curs_name as pre_requisite_name, 
                                                     GROUP_CONCAT(DISTINCT cl.id_week) as LessonWeek, 
                                                     GROUP_CONCAT(DISTINCT ca.id_week) as AssignmentWeek, 
                                                     GROUP_CONCAT(DISTINCT cq.id_week) as QuizWeek'
                                , 'join' 		=> 'LEFT JOIN '.COURSES.' c2 ON c2.curs_id =  c.curs_pre_requisite
                                                    LEFT JOIN '.COURSES_LESSONS.' cl ON cl.id_curs = c.curs_id AND cl.lesson_status = 1 AND cl.is_deleted = 0
                                                    LEFT JOIN '.COURSES_ASSIGNMENTS.' ca ON ca.id_curs = c.curs_id AND ca.status = 1 AND ca.is_deleted = 0
                                                    LEFT JOIN '.QUIZ.' cq ON cq.id_curs = c.curs_id AND cq.quiz_status = 1 AND cq.is_deleted = 0 AND cq.is_publish = 1'
                                , 'where'       => array (
                                                                  'c.is_deleted'  => 0
                                                                , 'c.curs_id'     => cleanvars($id)
                                                           )
                                , 'return_type' => 'single'
							); 
		$result = $dblms->getRows(COURSES. " c", $conditions);
		return $result;
	}
// end Single Course Details

// get all Courses
	public function get_allcourses($status = 1) {

		$dblms = new dblms();
		$conditions = array (
                                  'select'      => 'curs_id, curs_name, curs_code'
                                , 'where'       => array (
                                                                  'curs_status' => $status
                                                                , 'is_deleted'  => 0
                                                         )
                                , 'order_by'    => 'curs_name ASC'
                                , 'return_type' => 'all'
							);
		$result = $dblms->getRows(COURSES, $conditions);
		return $result;
	}
// end all Courses

// get all Departments
	public function get_departments($status = '', $srch = '') {
        $sqlsrch = (($status) ? " AND dept_status = '".$status."'" : '');
		$dblms = new dblms();
		$conditions = array (
                                  'select'      => 'dept_id, dept_status, dept_publish, dept_ordering, 
                                                    dept_name, dept_icon, dept_photo, dept_keyword, dept_code'
                                , 'where'       => array (
                                                                 'is_deleted'  => 0
                                                         )
                                , 'search_by'   => $sqlsrch.$srch
                                , 'order_by'    => 'dept_name ASC'
                                , 'return_type' => 'all'
							);
		$result = $dblms->getRows(DEPARTMENTS, $conditions);
		return $result;
	}
// end all Departments

// get Single Department
	public function get_department($id) {

		$dblms = new dblms();
		$conditions = array (
                                  'select'      => 'dept_id, dept_status, dept_publish, dept_ordering, 
                                                    dept_code, dept_name, dept_intro, dept_meta, dept_keyword, id_faculty, dept_icon, dept_photo'
                                , 'where'       => array (
                                                                 'dept_id'      => $id
                                                               , 'is_deleted'   => 0
                                                         )
                                , 'return_type' => 'single'
							);
		$result = $dblms->getRows(DEPARTMENTS, $conditions);
		return $result;
	}
// end  Single Department

// get all faculties
	public function get_faculties($status = '', $srch = '') {
        $sqlsrch = (($status) ? " AND faculty_status = '".$status."'" : '');
		$dblms = new dblms();
		$conditions = array (
                                  'select'      => 'faculty_id, faculty_status, faculty_publish, faculty_ordering,  
                                                    faculty_name, faculty_icon, faculty_photo, faculty_keyword, faculty_code'
                                , 'where'       => array (
                                                            'is_deleted'      => 0
                                                         )
                                , 'search_by'   => $sqlsrch .$srch
                                , 'order_by'    => 'faculty_name ASC'
                                , 'return_type' => 'all'
							);
		$result = $dblms->getRows(FACULTIES, $conditions);
		return $result;
	}
// end all faculties

// get Single faculty
	public function get_faculty($id) {

		$dblms = new dblms();
		$conditions = array (
                                  'select'      => 'faculty_id, faculty_status, faculty_publish, faculty_ordering, 
                                                    faculty_code, faculty_name, faculty_intro, faculty_meta, faculty_keyword, 
                                                    faculty_icon, faculty_photo, faculty_email, faculty_phone, faculty_address'
                                , 'where'       => array (
                                                              'faculty_id'   => $id
                                                            , 'is_deleted'   => 0
                                                         )
                                , 'return_type' => 'single'
							);
		$result = $dblms->getRows(FACULTIES, $conditions);
		return $result;
	}
// end Single faculty

// get all programs Category
	public function get_programscategory($status = 1) {

		$dblms = new dblms();
		$conditions = array (
                                  'select'      => 'cat_id, cat_name, cat_code'
                                , 'where'       => array (
                                                                  'cat_status'  => $status
                                                                , 'is_deleted'  => 0
                                                         )
                                , 'order_by'    => 'cat_name ASC'
                                , 'return_type' => 'all'
							);
		$result = $dblms->getRows(PROGRAMS_CATEGORIES, $conditions);
		return $result;
	}
// end all programs Category

// get all languages
	public function get_languages($status = 1) {

		$dblms = new dblms();
		$conditions = array (
                                  'select'      => 'lang_id, lang_name, lang_code'
                                , 'where'       => array (
                                                                  'lang_status'  => $status
                                                         )
                                , 'order_by'    => 'lang_name ASC'
                                , 'return_type' => 'all'
							);
		$result = $dblms->getRows(LANGUAGES, $conditions);
		return $result;
	}
// end all languages

// get all Skills
	public function get_skills($status = 1) {

		$dblms = new dblms();
		$conditions = array (
                                  'select'      => 'skill_id, skill_name'
                                , 'where'       => array (
                                                                  'skill_status'  => $status
                                                         )
                                , 'order_by'    => 'skill_name ASC'
                                , 'return_type' => 'all'
							);
		$result = $dblms->getRows(COURSES_SKILLS, $conditions);
		return $result;
	}
// end all Skills

// get Single Course information
	public function get_courseinfo($cid, $id = 0) {
        $sqlsrch = '';
        if($id !=0) {
            $sqlsrch .= " AND id='".$id."'";
        }
		$dblms = new dblms();
		$conditions = array (
                                  'select'      => 'id, introduction, objectives, outcomes, strategies, effectiveness, outlines'
                                , 'where'       => array (
                                                                  'status'  => 1
                                                                , 'id_curs' => cleanvars($cid)
                                                         )
                                , 'search_by'   => $sqlsrch
                                , 'return_type' => 'single'
							);
		$result = $dblms->getRows(COURSES_INFO, $conditions);
		return $result;
	}
// end Single Course information


// get Single Course Week Detail
	public function get_courseweekdetail($id) {

		$dblms = new dblms();
		$conditions = array (
                                  'select'      => 'id, status, caption, detail, id_week'
                                , 'where'       => array (
                                                                  'is_deleted'  => 0
                                                                , 'id'          => cleanvars($id)
                                                         )
                                , 'return_type' => 'single'
							);
		$result = $dblms->getRows(COURSES_WEEK_TITLE, $conditions);
		return $result;
	}
// end Single Course Week Detail

// get Course Lessons
	public function get_courselessons($id) {

		$dblms = new dblms();
		$conditions = array (
                                  'select'      => 'lesson_id, lesson_content, id_week, lesson_status, lesson_topic, 
                                                    lesson_detail, lesson_video_code, lesson_reading_detail'
                                , 'where'       => array (
                                                                  'is_deleted'      => 0
                                                                , 'lesson_status'   => 1
                                                                , 'id_curs'         => cleanvars($id)
                                                                , 'id_campus'       => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                         )
                                , 'order_by'    => 'lesson_id DESC'
                                , 'return_type' => 'all'
							);
		$result = $dblms->getRows(COURSES_LESSONS, $conditions);
		return $result;
	}
// end Course Lessons

// get Single Course Lesson Detail
	public function get_courselessondetail($id) {

		$dblms = new dblms();
		$conditions = array (
                                  'select'      => 'lesson_id, lesson_content, id_week, lesson_status, lesson_topic, 
                                                    id_lecture, lesson_detail, lesson_video_code, lesson_reading_detail'
                                , 'where'       => array (
                                                                  'is_deleted'   => 0
                                                                , 'lesson_id'    => cleanvars($id)
                                                                , 'id_campus'    => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                                // , 'id_teacher'   => cleanvars($_SESSION['userlogininfo']['EMPLYID'])
                                                         )
                                , 'return_type' => 'single'
							);
		$result = $dblms->getRows(COURSES_LESSONS, $conditions);
		return $result;
	}
// end Single Course Lesson Detail

// get Single Course discussion Detail
	public function get_coursediscussiondetail($id) {

		$dblms = new dblms();
		$conditions = array (
                                  'select'      => 'discussion_id, discussion_status, id_lecture, discussion_subject, 
                                                    discussion_detail, discussion_startdate, discussion_enddate'
                                , 'where'       => array (
                                                                  'is_deleted'   => 0
                                                                , 'discussion_id'=> cleanvars($id)
                                                                , 'id_campus'    => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                         )
                                , 'return_type' => 'single'
							);
		$result = $dblms->getRows(COURSES_DISCUSSION, $conditions);
		return $result;
	}
// end Single Course discussion Detail

// get Single Course Announcement Detail
	public function get_courseannouncementdetail($id) {

		$dblms = new dblms();
		$conditions = array (
                                  'select'      => 'announcement_id, announcement_status, id_lecture, announcement_topic, announcement_detail'
                                , 'where'       => array (
                                                                  'is_deleted'      => 0
                                                                , 'announcement_id' => cleanvars($id)
                                                                , 'id_campus'       => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                         )
                                , 'return_type' => 'single'
							);
		$result = $dblms->getRows(COURSES_ANNOUNCEMENTS, $conditions);
		return $result;
	}
// end Single Course Announcement Detail

// get Single Course Question Bank Detail
	public function get_coursequestionbank($id, $cid) {

		$dblms = new dblms();
		$conditions = array (
                                  'select'     => 'qb.qns_id, qb.qns_status, qb.qns_question, qb.qns_file, qb.qns_level, qb.qns_type, 
                                                    qb.qns_marks, qb.id_lesson, GROUP_CONCAT(qbd.option_id) option_id_comma, 
                                                    GROUP_CONCAT(qbd.qns_option) qns_option_comma, 
                                                    GROUP_CONCAT(qbd.option_true) option_true_comma'
                                , 'join'       =>  'LEFT JOIN '.QUESTION_BANK_DETAIL.' qbd ON qbd.id_qns = qb.qns_id'
                                , 'where'      => array (
                                                                  'qb.is_deleted'  => 0
                                                                , 'qb.id_curs'     => cleanvars($cid)
                                                                , 'qb.id_campus'   => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                                , 'qb.qns_id'      => cleanvars($id)
                                                         )
                                , 'group_by'    => 'qb.qns_id'
                                , 'return_type' => 'single'
							);
		$result = $dblms->getRows(QUESTION_BANK." qb", $conditions);
		return $result;
	}
// end Single Course Question Bank Detail

// get All Course Lesson Download
	public function get_lessondownload($id) {

		$dblms = new dblms();
		$conditions = array (
                                  'select'      => 'id, status, id_type, file_name, detail, open_with, url, file, embedcode'
                                , 'where'       => array (
                                                                  'is_deleted'  => 0
                                                                , 'id'          => cleanvars($id)
                                                         )
                                , 'return_type' => 'single'
						  );
		$result = $dblms->getRows(COURSES_DOWNLOADS, $conditions);
		return $result;
	}
// end All Course Lesson Downloads

// get All Course Lesson Downloads
	public function get_lessondownloads($id) {

		$dblms = new dblms();
		$conditions = array (
                                  'select'      => 'id, file_name, url, file'
                                , 'where'       => array (
                                                                  'is_deleted'   => 0
                                                                , 'id_lesson'    => cleanvars($id)
                                                         )
                                , 'return_type' => 'all'
						  );
		$result = $dblms->getRows(COURSES_DOWNLOADS, $conditions);
		return $result;
	}
// end All Course Lesson Downloads

// get single Course faq
	public function get_coursefaq($id) {

		$dblms = new dblms();
		$conditions = array (
                                  'select'      => 'id, status, question, answer, id_lecture, id_lesson'
                                , 'where'       => array (
                                                                  'is_deleted' => 0
                                                                , 'id'         => cleanvars($id)
                                                         )
                                , 'return_type' => 'single'
						  );
		$result = $dblms->getRows(COURSES_FAQS, $conditions);
		return $result;
	}
// end single Course faq

// get single Course glossary
	public function get_courseglossary($id) {

		$dblms = new dblms();
		$conditions = array (
                                  'select'      => 'id, status, caption, detail'
                                , 'where'       => array (
                                                                  'is_deleted' => 0
                                                                , 'id'         => cleanvars($id)
                                                         )
                                , 'return_type' => 'single'
						  );
		$result = $dblms->getRows(COURSES_GLOSSARY, $conditions);
		return $result;
	}
// end single Course glossary

// get Courses Category
	public function get_coursescategory($typid) {

		$dblms = new dblms();
		$conditions = array (
                                  'select'      => 'cat_id, cat_status, cat_ordering, cat_name, cat_description, 
                                                    cat_meta_keywords, cat_meta_description, cat_code'
                                , 'where'       => array (
                                                                  'is_deleted'  => 0
                                                                , 'cat_status'  => 1
                                                                , 'id_type'     => cleanvars($typid)
                                                         )
                                , 'order_by'    => 'cat_name ASC'
                                , 'return_type' => 'all'
						  );
		$result = $dblms->getRows(COURSES_CATEGORIES, $conditions);
		return $result;
	}
// end Courses Category

// get Course Category
	public function get_coursecategory($id) {

		$dblms = new dblms();
		$conditions = array (
                                  'select'      => 'cat_id, cat_status, cat_ordering, cat_name, cat_description, 
                                                    cat_meta_keywords, cat_meta_description, cat_code'
                                , 'where'       => array (
                                                                  'is_deleted' => 0
                                                                , 'cat_id'     => cleanvars($id)
                                                         )
                                , 'return_type' => 'single'
						  );
		$result = $dblms->getRows(COURSES_CATEGORIES, $conditions);
		return $result;
	}
// end Course Category

}
// end class 