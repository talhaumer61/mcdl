<?php 
require_once ("../../dbsetting/lms_vars_config.php");
require_once ("../../dbsetting/classdbconection.php");
require_once ("../../functions/functions.php");
require_once ("../../functions/login_func.php");
$dblms = new dblms();

$conditions = array ( 
                         'select'       =>	'n.not_id, n.not_title, n.not_description, n.start_date, n.end_date, n.dated'
                        ,'where'        =>	array( 
                                                         'n.not_status'   => 1
                                                        ,'n.id_type'      => 1
                                                        ,'n.is_deleted'   => 0
                                                        ,'n.not_id'       => cleanvars($_GET['view_id'])
                                                    )
                        ,'return_type'	=>	'single'
                    );
$valNot = $dblms->getRows(NOTIFICATIONS.' n', $conditions);
$NotTitleFirst = substr($valNot['not_title'], 0, 1);   
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title text-dark" id="exampleModalLabel"><i class="ri-eye-line align-bottom me-1"></i>'.moduleName(false).'</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col mb-2">
                    <table class="table border">
                        <tbody>
                            <tr>
                                <td>'.$valNot['start_date'].' to '.$valNot['end_date'].'</td>
                                <td width="200" class="text-end">'.timeAgo($valNot['dated']).'</td>
                            </tr>
                            <tr>
                                <th colspan="4">'.$valNot['not_title'].'</th>
                            </tr>
                            <tr>
                                <td colspan="4">'.html_entity_decode(html_entity_decode($valNot['not_description'])).'</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="hstack gap-2 justify-content-end">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
            </div>
        </div>
</div>';