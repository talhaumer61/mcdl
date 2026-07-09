<?php
include "include/dbsetting/lms_vars_config.php";
include "include/dbsetting/classdbconection.php";
include "include/functions/functions.php";
$dblms = new dblms();
include "include/functions/login_func.php";
checkCpanelLMSALogin();
include("include/header.php");

echo' 
<title>'.moduleName(false).' - '.TITLE_HEADER.'</title>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">'.moduleName(false).'</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                            <li class="breadcrumb-item"><a href="'.moduleName().'.php" class="text-primary">'.moduleName(false).'</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-lg-12">';
                $conditions = [
                    'select'        => 'track_id, id_std, id_curs, id_quiz, is_completed',
                    'where'         =>  [
                                            'is_deleted'  =>  0,
                                        ],
                    'not_equal'     =>  [
                                            'id_std'     =>  0,
                                            'id_curs'    =>  0,
                                            'id_quiz'    =>  0
                                        ],
                    'return_type'   => 'all'
                ]; 
                $LECTURE_TRACKING = $dblms->getRows(LECTURE_TRACKING, $conditions);

                $report = [];

                // if($LECTURE_TRACKING) {

                //     foreach($LECTURE_TRACKING as $track) {
                //         // 1️⃣ Skip if lecture not completed
                //         if ((int)$track['is_completed'] !== 1) {
                //             continue;
                //         }

                //         // get quiz attempts
                //         $conditions = [
                //             'select'        => 'qzstd_id, qzstd_pass_fail',
                //             'where'         => [
                //                 'is_deleted'        => 0,
                //                 'id_std'            => $track['id_std'],
                //                 'id_quiz'           => $track['id_quiz'],                                
                //                 'qzstd_pass_fail'   => 1
                //             ],
                //             'return_type'   => 'all'
                //         ];
                //         $QUIZ_STUDENTS = $dblms->getRows(QUIZ_STUDENTS, $conditions);

                //         $has_pass = false;

                //         if($QUIZ_STUDENTS) {
                //             foreach($QUIZ_STUDENTS as $q) {
                //                 if($q['qzstd_pass_fail'] == 1) {
                //                     $has_pass = true;
                //                     break;
                //                 }
                //             }
                //         }

                //         $action = "";

                //         // =========================
                //         // CASE 1: PASS FOUND
                //         // =========================
                //         if($has_pass) {

                //             $dblms->querylms("
                //                 UPDATE ".LECTURE_TRACKING."
                //                 SET is_completed = 2,
                //                     date_modify = NOW()
                //                 WHERE track_id = '".$track['track_id']."'
                //             ");

                //             $deleted = $dblms->querylms("
                //                 UPDATE ".QUIZ_STUDENTS."
                //                 SET is_deleted = 1,
                //                     date_deleted = NOW(),
                //                     ip_deleted = '".LMS_IP."'
                //                 WHERE id_std = '".$track['id_std']."'
                //                 AND id_quiz = '".$track['id_quiz']."'
                //                 AND qzstd_pass_fail IN (0,2)
                //                 AND is_deleted = 0
                //             ");

                //             $action = "PASS FOUND → COMPLETED + FAIL DELETED";

                //         } 

                //         // =========================
                //         // CASE 2: NO PASS
                //         // =========================
                //         else {

                //             $dblms->querylms("
                //                 UPDATE ".LECTURE_TRACKING."
                //                 SET is_completed = 1,
                //                     date_modify = NOW()
                //                 WHERE track_id = '".$track['track_id']."'
                //             ");

                //             $action = "NO PASS → MARKED INCOMPLETE";
                //         }

                //         // =========================
                //         // REPORT DATA
                //         // =========================
                //         $report[] = [
                //             'track_id' => $track['track_id'],
                //             'std'      => $track['id_std'],
                //             'curs'     => $track['id_curs'],
                //             'quiz'     => $track['id_quiz'],
                //             'action'   => $action
                //         ];
                //     }
                // }

                // if(!empty($report)) {
                //     echo '
                //     <div class="table-responsive mt-4">
                //         <h4>Auto Fix Report</h4>
                //         <table class="table table-bordered table-striped">
                //             <thead>
                //                 <tr>
                //                     <th>Track ID</th>
                //                     <th>Student</th>
                //                     <th>Course</th>
                //                     <th>Quiz</th>
                //                     <th>Action Taken</th>
                //                 </tr>
                //             </thead>
                //             <tbody>';
                //                 foreach($report as $r) {
                //                     echo '
                //                     <tr>
                //                         <td>'.$r['track_id'].'</td>
                //                         <td>'.$r['std'].'</td>
                //                         <td>'.$r['curs'].'</td>
                //                         <td>'.$r['quiz'].'</td>
                //                         <td>'.$r['action'].'</td>
                //                     </tr>';
                //                 }
                //                 echo '
                //             </tbody>
                //         </table>
                //     </div>';
                // }                

                if($LECTURE_TRACKING) {
                    echo '
                    <div class="table-responsive">';
                        foreach($LECTURE_TRACKING as $track) {
                            if($track['is_completed'] == 1) { // skip completed
                                continue;
                            }
                            $conditions = [
                                'select'        => 'qzstd_id, qzstd_pass_fail, qzstd_obtain_marks, id_quiz, id_std',
                                'where'         =>  [
                                                        'is_deleted'        =>  0,
                                                        'id_std'            =>  $track['id_std'],
                                                        'id_quiz'           =>  $track['id_quiz']
                                                    ],
                                'return_type'   => 'all'
                            ];
                            $QUIZ_STUDENTS = $dblms->getRows(QUIZ_STUDENTS, $conditions);

                            if($QUIZ_STUDENTS) {
                                echo '
                                <table class="table table-bordered table-striped table-hover mt-3">
                                    <thead>
                                        <tr>
                                            <th>Track ID: '.$track['track_id'].'</th>
                                            <th>Student ID: '.$track['id_std'].'</th>
                                            <th>Course ID: '.$track['id_curs'].'</th>
                                            <th>Quiz ID: '.$track['id_quiz'].'</th>
                                            <th>Is Completed: '.$track['is_completed'].'</th>
                                        </tr>                                    
                                        <tr>
                                            <th>Sr.</th>
                                            <th>ID</th>
                                            <th>Obtain Marks</th>
                                            <th>Id_quiz</th>
                                            <th>Pass/Fail</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                        $i=0;
                                        $has_pass = false;
                                        foreach($QUIZ_STUDENTS as $quiz_std) {
                                            if($quiz_std['qzstd_pass_fail'] == 1) {
                                                $has_pass = true;
                                            }
                                            $i++;
                                            echo '
                                            <tr>
                                                <td>'.$i.'</td>
                                                <td>'.$quiz_std['qzstd_id'].'</td>
                                                <td>'.$quiz_std['qzstd_obtain_marks'].'</td>
                                                <td>'.$quiz_std['id_quiz'].'</td>
                                                <td>'.$quiz_std['qzstd_pass_fail'].'</td>
                                            </tr>';
                                        }
                                        echo '
                                         <tr>
                                            <td colspan="5" class="text-center font-weight-bold">Has Pass: '.($has_pass ? "YES" : "NO").'</td>
                                        </tr>';
                                        echo'
                                    </tbody>
                                </table>';
                            }
                        }
                        echo'
                    </div>';
                }
                echo'
            </div>
        </div>
    </div>
</div>';

include("include/footer.php");
?>