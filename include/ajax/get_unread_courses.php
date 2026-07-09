<?php
include '../dbsetting/lms_vars_config.php';
include '../dbsetting/classdbconection.php';
include '../functions/functions.php';

$dblms = new dblms();
if (!empty($_GET['user_type'])) {
    $userType = $_GET['user_type'];
    if($userType == 1) {
        $condition = array(
                            'select'       => 'qa.id_curs, c.curs_name, COUNT(*) as unread_count',
                            'join'         => 'INNER JOIN '.ADMINS.' a ON a.adm_id = qa.id_added AND a.adm_status = 1 AND a.is_deleted = 0
                                                INNER JOIN '.COURSES.' c ON c.curs_id = qa.id_curs',
                            'where'        => array(
                                                        'qa.read_status'   => 2
                                                        ,'qa.type'          => 1
                                                        ,'qa.is_deleted'    => 0
                                                        ,'c.curs_status'    => 1
                                                        ,'c.is_deleted'     => 0
                                                        ),
                            'group_by'     => 'qa.id_curs',
                            'order_by'     => 'c.curs_name ASC',
                            'return_type'  => 'all'
        );

        // Run the query
        $data = $dblms->getRows(QUESTION_ANSWERS.' qa', $condition);

        // Return as JSON
        echo json_encode($data);
    }
    elseif ($userType == 3) {
        $emplyID= $_GET['emplyID'];
        $queryCourses = "
        SELECT GROUP_CONCAT(DISTINCT id_curs) as courses
        FROM cms_courses_allocateteachers 
        WHERE FIND_IN_SET($emplyID, id_teacher)
        ";
        
        $condition = array(
            'select'       => 'GROUP_CONCAT(DISTINCT id_curs) as courses',
        'search_by'     => 'WHERE FIND_IN_SET('.$emplyID.', id_teacher)',
        'return_type'  => 'single'
        );
        $data = $dblms->getRows(ALLOCATE_TEACHERS, $condition);

        $condition = array(
            'select'       => 'qa.id_curs, c.curs_name, COUNT(qa.id) as unread_count',
            'join'         => 'INNER JOIN '.ADMINS.' a ON a.adm_id = qa.id_added AND a.adm_status = 1 AND a.is_deleted = 0
                                INNER JOIN '.COURSES.' c ON c.curs_id = qa.id_curs',
            'where'        => array(
                'qa.read_status'   => 2
                ,'qa.type'          => 1
                ,'qa.is_deleted'    => 0
                ,'c.curs_status'    => 1
                ,'c.is_deleted'     => 0
                ),
        'search_by'     => 'AND qa.id_curs IN ('.$data['courses'].')',
        'return_type'  => 'all'
        );
        $qns = $dblms->getRows(QUESTION_ANSWERS.' qa', $condition, $sql);
        // if($qns['unread_count'] == 0){
            // echo json_encode(null);
        // }else{
            echo json_encode($qns);
        // }    
    } 
    else {
        echo json_encode([]); // No allocated courses
    }
    
}
?>
