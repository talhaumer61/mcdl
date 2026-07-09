<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

    $condition = array(
                         'select'       =>  'qns_id, qns_question,id_lesson'
                        ,'where'        =>  array(
                                                     'qns_status'     => 1
                                                    ,'qns_level'          => $_POST['difficulty_level']
                                                    ,'qns_type'          => $_POST['qns_type']
                                                    ,'is_deleted'       => 0
                                                )
                        ,'order_by'     =>  'qns_id ASC'
                        ,'return_type'  =>  'all'
    );
    $questions = $dblms->getRows(QUESTION_BANK, $condition);
    // <script src="assets/js/app.js"></script>

    echo'
    <script>
    maxCheckboxCount = '.$_POST['no_of_question'].';
    </script>
    <div class="container mt-3">';
    if($questions){
        foreach($questions as $row) {
            
            $matchingValues = false;
            foreach (explode(",",$row['id_lesson']) as $value1) {
                if (in_array($value1,$_POST['id_chapter'])) {
                    $matchingValues = true;
                }
            }
            if($matchingValues)
                echo '<div class="form-check">
                        <input class="form-check-input random-checkbox" name="questions[]" type="checkbox" value="'.$row['qns_id'].'" id="flexCheckDefault'.$row['qns_id'].'">
                        <label class="form-check-label" for="flexCheckDefault'.$row['qns_id'].'">
                            '.strip_tags(html_entity_decode(html_entity_decode($row['qns_question']))).'
                        </label>
                    </div>';
            }
    }else{
        echo'<h4 value="">No Record Found</h4>';
    }
    echo ' 
    <div class="row mt-3">
        <div class="col">
            <button type="button" class="btn btn-info btn-sm" onclick="shufflecheck(maxCheckboxCount)">Shuffle question</button>
        </div>
    </div>
    </div>
    ';
?>
<script>
shufflecheck(maxCheckboxCount)
</script>