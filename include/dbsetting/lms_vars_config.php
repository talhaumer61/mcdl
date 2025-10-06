<?php
error_reporting(0);
ob_start();
ob_clean();
date_default_timezone_set("Asia/Karachi");


// GET USER LOCATION SETTING 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://ipinfo.io/103.4.94.182/json?token=c36af2361792c4");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$userGeoInfoRes = curl_exec($ch);
curl_close($ch);
$userGeoInfo = json_decode($userGeoInfoRes, true);
// LOCATION SETTING VARIABLES
define('__IP__'                 , (isset($userGeoInfo['ip'])         && !empty($userGeoInfo['ip']))           ? strtolower($userGeoInfo['ip'])         : '');
define('__CITY__'               , (isset($userGeoInfo['city'])       && !empty($userGeoInfo['city']))         ? strtolower($userGeoInfo['city'])       : '');
define('__REGION__'             , (isset($userGeoInfo['region'])     && !empty($userGeoInfo['region']))       ? strtolower($userGeoInfo['region'])     : '');
define('__COUNTRY__'            , (isset($userGeoInfo['country'])    && !empty($userGeoInfo['country']))      ? strtolower($userGeoInfo['country'])    : '');
define('__LOCATION__'           , (isset($userGeoInfo['loc'])        && !empty($userGeoInfo['loc']))          ? strtolower($userGeoInfo['loc'])        : '');


// define('LMS_HOSTNAME'			, 'localhost');
// define('LMS_NAME'				, 'mcdl_muldodl2025');
// define('LMS_USERNAME'			, 'mcdl_dodl');
// define('LMS_USERPASS'			, 'KSkTGFEhOD0asI#S');

define('LMS_HOSTNAME'			, 'localhost');
define('LMS_NAME'				, 'gptech_odl');
define('LMS_USERNAME'			, 'root');
define('LMS_USERPASS'			, '');

// DB Tables
define('ADMINS'					            , 'cms_admins');
define('ADMIN_ROLES'			            , 'cms_admins_roles');
define('LOGS'					            , 'cms_logfile');
define('LOGIN_HISTORY'			            , 'cms_login_history');
define('CURRENCIES'				            , 'cms_currencies');
define('REGIONS'				            , 'cms_regions');
define('COUNTRIES'				            , 'cms_countries');
define('STATES'				                , 'cms_states');
define('SUB_STATES'                         , 'cms_substates');
define('CITIES'                             , 'cms_cities');
define('PROGRAMS_CATEGORIES'                , 'cms_programs_categories');
define('COURSES_CATEGORIES'                 , 'cms_courses_categories');
define('FACULTIES'      		            , 'cms_faculties');
define('DEPARTMENTS'      		            , 'cms_departments');
define('COURSES'     			            , 'cms_courses');
define('LANGUAGES'      		            , 'cms_languages');
define('COURSES_SKILLS'			            , 'cms_courses_skills');
define('ADMISSION_PROGRAMS'		            , 'cms_admission_programs');
define('ACADEMIC_SESSION'		            , 'cms_academic_session');
define('SETTINGS'		                    , 'cms_settings');
define('PROGRAMS'				            , 'cms_programs');
define('DESIGNATIONS'			            , 'cms_designtions');
define('SKILLS'					            , 'cms_courses_skills');
define('EMPLOYEES'                          , 'cms_employees');
define('COURSES_INFO'                       , 'cms_courses_info');
define('COURSES_ASSIGNMENTS'                , 'cms_courses_assignments');
define('COURSES_ASSIGNMENTS_STUDENTS'       , 'cms_courses_assignmentstudents');
define('COURSES_GLOSSARY'                   , 'cms_courses_glossary');
define('COURSES_DOWNLOADS'                  , 'cms_courses_downloads');
define('COURSES_LESSONS'                    , 'cms_courses_lessons');
define('COURSES_DISCUSSION'                 , 'cms_courses_discussion');
define('COURSES_ANNOUNCEMENTS'              , 'cms_courses_announcements');
define('COURSES_FAQS'                       , 'cms_courses_faqs');
define('COURSES_BOOKS'                      , 'cms_courses_books');
define('QUESTION_BANK'                      , 'cms_question_bank');
define('QUESTION_BANK_DETAIL'               , 'cms_question_bank_detail');
define('EMPLOYEE_EXPERIENCE'                , 'cms_employee_experience');
define('EMPLOYEE_LANGUAGE_SKILLS'           , 'cms_employee_language_skills');
define('EMPLOYEE_TRAININGS'                 , 'cms_employee_trainings');
define('EMPLOYEE_MEMBERSHIPS'               , 'cms_employee_memberships');
define('EMPLOYEE_ACHIEVEMENTS'              , 'cms_employee_achievements');
define('EMPLOYEE_PUBLICATIONS'              , 'cms_employee_publications');
define('EMPLOYEE_EDUCATIONS'                , 'cms_employee_educations');
define('BANK_INFORMATION'                   , 'cms_employee_bank_informations');
define('STUDENTS'			                , 'cms_students');
define('SOCIAL_PROFILE'                     , 'cms_social_profile');
define('TRANSACTION'                        , 'cms_transactions');
define('WISHLIST'                           , 'cms_wishlist');
define('ENROLLED_COURSES'                   , 'cms_enrolled_courses');
define('DEGREE'                             , 'cms_degree');
define('DEGREE_DETAIL'                      , 'cms_degree_detail');
define('MASTER_TRACK'                       , 'cms_master_track');
define('ADMISSION_OFFERING'                 , 'cms_admission_offering');
define('MASTER_TRACK_CATEGORIES'            , 'cms_master_track_categories');
define('MASTER_TRACK_DETAIL'                , 'cms_master_track_detail');
define('ALLOCATE_TEACHERS'                  , 'cms_courses_allocateteachers');
define('COURSES_DISCUSSIONSTUDENTS'         , 'cms_courses_discussionstudents');
define('PROGRAMS_STUDY_SCHEME'              , 'cms_programs_study_scheme');
define('LECTURE_TRACKING'		            , 'cms_lecture_tracking');
define('QUIZ'                               , 'cms_quiz');
define('QUIZ_QUESTIONS'                     , 'cms_quiz_questions');
define('QUIZ_STUDENTS'                      , 'cms_quiz_students');
define('QUIZ_STUDENT_DETAILS'               , 'cms_quiz_student_details');
define('CHALLANS'                           , 'cms_challans');
define('STUDENT_EDUCATIONS'                 , 'cms_student_educations');
define('GENERATED_CERTIFICATES'             , 'cms_generated_certificates');
define('COURSES_WEEK_TITLE'                 , 'cms_courses_week_title');
define('NOTIFICATIONS'                      , 'cms_notifications');
define('COUPONS'                            , 'cms_coupons');
define('FEEDBACK_QUESTIONS'                 , 'cms_feedback_questions');
define('STUDENT_FEEDBACK'                   , 'cms_student_feedback');
define('STUDENT_FEEDBACK_DETAIL'            , 'cms_student_feedback_detail');
define('REVIEWS'                            , 'cms_reviews');
define('BLOGS'                              , 'cms_blogs');
define('DISCOUNT'                           , 'cms_courses_discounts');
define('DISCOUNT_DETAIL'                    , 'cms_courses_discounts_details');
define('REFERRAL_CONTROL'                   , 'cms_referral_control');
define('REFERRAL_TEACHER_SHARING'           , 'cms_referral_teacher_sharing');
define('QUESTION_ANSWERS'                   , 'cms_student_teacher_qna');
define('STUDENT_INTERESTED_COURSES'         , 'cms_students_interested_courses');
define('TEACHER_INTEREST_QUESTIONS'         , 'cms_teacher_interest_questions');
define('TEACHER_INTEREST'                   , 'cms_teacher_interest');
define('TEACHER_INTEREST_DETAIL'            , 'cms_teacher_interest_detail');
define('SKILL_AMBASSADOR'                   , 'cms_skill_ambassador');
define('SA_BANK_DETAILS'                    , 'cms_skill_ambassador_bank_details');
define('SA_EDU_DETAILS'                     , 'cms_skill_ambassador_education_details');
define('FAQS'                               , 'cms_faqs');

// Variables
$control 		= (isset($_REQUEST['control']) && $_REQUEST['control'] != '') ? $_REQUEST['control'] : '';
$zone 	 		= (isset($_REQUEST['zone']) && $_REQUEST['zone'] != '') ? $_REQUEST['zone'] : '';
$ip	  			= (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '') ? $_SERVER['REMOTE_ADDR'] : '';
$do	  			= (isset($_REQUEST['do']) && $_REQUEST['do'] != '') ? $_REQUEST['do'] : '';
$view 			= (isset($_REQUEST['view']) && $_REQUEST['view'] != '') ? $_REQUEST['view'] : '';
$edit_id        = (isset($_REQUEST['edit_id']) && $_REQUEST['edit_id'] != '') ? $_REQUEST['edit_id'] : '';
$page			= (isset($_REQUEST['page']) && $_REQUEST['page'] != '') ? $_REQUEST['page'] : '';
$current_page	= (isset($_REQUEST['page']) && $_REQUEST['page'] != '') ? $_REQUEST['page'] : 1;
$Limit			= (isset($_REQUEST['Limit']) && $_REQUEST['Limit'] != '') ? $_REQUEST['Limit'] : '';
$do             = '';
$redirection    = '';

define('TITLE_HEADER'		, 'MCDL');
define("SITE_NAME"			, "Minhaj Centre of Distance Learning");
define('TITLE_HEADER_WEB'   , 'DODL');
define("SITE_NAME_WEB"      , "Directorate of Open and Distance Learning");
define("SITE_PHONE"			, "+32 60 87 78 29");
define("SITE_WHATSAPP"		, "+92 326 087 7829");
define("SITE_EMAIL"			, "info.dodl@mul.edu.pk");
define("SITE_ADDRESS"		, "Signature plaza Civil defense road near bikes market, Township Lahore.");
define("SITE_BIO"			, "Empowering society, reducing dependency & improving lives");
define("SITE_URL"			, "https://mcdl.mul.edu.pk/");
define("WEBSITE_URL"        , "https://dodl.mul.edu.pk/");
define('LMS_IP'				, $ip);
define('LMS_DO'				, $do);
define('LMS_EPOCH'			, date("U"));
define('LMS_VIEW'			, $view);
define('LMS_EDIT_ID'        , $edit_id);
define("COPY_RIGHTS"		, "Green Professional Technologies");
define("COPY_RIGHTS_ORG"	, "Copyright &copy; ".date("Y")." - All Rights Reserved.");
define("COPY_RIGHTS_URL"	, "https://gptech.pk/");

// SMTP MAIL VARS
define('SMTP_EMAIL'         , 'info.dodl@mul.edu.pk');
define('SMTP_TOKEN'         , 'b08a9b259dc86a5fa2ab8f409614b38dbef1768edbab1d2a7281c2c963d5b5ed');
?>