<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
include "../../functions/functions.php";
$dblms = new dblms();
include "../../functions/login_func.php";
checkCpanelLMSALogin();
$condition = array ( 
                             'select' 	    =>  'id, status, book_name, author_name, edition, isbn, publisher, url'
                            ,'where' 	    =>  array(  
                                                         'is_deleted'           => 0
                                                        ,'id_session'           => cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                        ,'id_campus'            => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                        ,'id_teacher'           => cleanvars($_SESSION['userlogininfo']['EMPLYID'])
                                                        ,'id'			        => cleanvars($_GET['book_id'])
                                                    )
                            ,'return_type'  =>  'single' 
                        ); 
    $COURSES_BOOKS    = $dblms->getRows(COURSES_BOOKS, $condition);
echo '
<div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" id="offcanvasRightLabel">Book Detail</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body p-0 overflow-hidden">
    <div data-simplebar style="height: calc(100vh - 112px);">
        <div class="acitivity-timeline p-4">
            <div class="card-body">
                <table class="table table-bordered table-nowrap align-middle">
                    <tr width="100">
                        <th><h5 class=" text-uppercase fw-semibold">Edition</h5></th>
                        <td>:</td>
                        <td>'.$COURSES_BOOKS['edition'].'</td>
                    </tr>
                    <tr width="100">
                        <th><h5 class=" text-uppercase fw-semibold">ISBN</h5></th>
                        <td>:</td>
                        <td>'.$COURSES_BOOKS['isbn'].'</td>
                    </tr>
                    <tr width="100">
                        <th><h5 class=" text-uppercase fw-semibold">Publisher</h5></th>
                        <td>:</td>
                        <td>'.$COURSES_BOOKS['publisher'].'</td>
                    </tr>
                    <tr width="100">
                        <th><h5 class=" text-uppercase fw-semibold">Url</h5></th>
                        <td>:</td>
                        <td>'.$COURSES_BOOKS['url'].'</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>';
?>