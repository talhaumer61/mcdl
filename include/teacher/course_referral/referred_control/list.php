<?php
$search_word    = '';
$search_query   = '';
$filters        = 'search&'.$redirection.'';

if (!empty($_GET['search_word'])) {
    $search_word     = $_GET['search_word'];
    $search_query   .= " AND (rts.std_emails LIKE '%".$search_word."%' OR c.curs_name LIKE '%".$search_word."%' OR rc.ref_percentage LIKE '%".$search_word."%')";
    $filters        .= '&search_word='.$search_word.'';
}

$condition = array(
                     'select'       =>  'rc.ref_id, c.curs_id, rts.std_emails, c.curs_name, rc.ref_percentage, a.adm_email'
                    ,'join'         =>  'INNER JOIN '.REFERRAL_CONTROL.' AS rc ON rts.id_ref = rc.ref_id AND rc.ref_status = 1 AND rc.is_deleted = 0
                                         INNER JOIN '.COURSES.' AS c ON rc.id_curs = c.curs_id AND c.curs_status = 1 AND c.is_deleted = 0
                                         LEFT JOIN '.ADMINS.' AS a ON a.adm_email = rts.std_emails AND a.adm_status = 1 AND a.is_deleted = 0'
                    ,'where'        =>  array(
                                                 'rts.is_deleted'   =>  0
                                                ,'rts.id_curs'		=>  $REFERRAL_CONTROL["curs_id"]
                                                ,'rts.id_ref'       =>  $REFERRAL_CONTROL['ref_id']
                                            )
                    ,'search_by'    =>  ' '.$search_query.' '
                    ,'group_by'     =>  ' rts.ref_shr_id '
                    ,'return_type'  =>  'count'
);
$count = $dblms->getRows(REFERRAL_TEACHER_SHARING.' AS rts ', $condition);
echo'
<div class="row justify-content-end">
    <div class="col-3">
        <form class="form-horizontal" id="form" enctype="multipart/form-data" method="get" autocomplete="off" accept-charset="utf-8">
            <input type="hidden" name="id" value="'.$REFERRAL_CONTROL["curs_id"].'">
            <input type="hidden" name="view" value="'.LMS_VIEW.'">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Search..." name="search_word" value="'.$search_word.'">
                <button type="submit" class="btn btn-primary btn-sm" name="search"><i class="ri-search-2-line"></i></button>
            </div>
        </form>
    </div>
</div>';       
if ($page == 0 || empty($page)) { $page = 1; }
$prev       = $page - 1;
$next       = $page + 1;
$lastpage   = ceil($count / $Limit);   //lastpage = total pages // items per page, rounded up
$lpm1       = $lastpage - 1;

$condition['order_by']      = " rts.ref_shr_id DESC LIMIT " . ($page - 1) * $Limit . ",$Limit";
$condition['return_type']   = 'all';

$rowslist = $dblms->getRows(REFERRAL_TEACHER_SHARING.' AS rts ', $condition);
if ($rowslist) {
    echo'
    <div class="table-responsive table-card">
        <table class="table mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-center" width="10">Sr.</th>
                    <th>Course</th>
                    <th>Email</th>
                    <th width="35" class="text-center">Percentage</th>
                    <th width="35" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>';
                $srno = ($page == 1 ? 0 : ($page - 1) * $Limit);
                foreach ($rowslist as $row) {
                    $srno++;
                    echo '
                    <tr style="vertical-align: middle;">
                        <td class="text-center">'.$srno.'</td>
                        <td>'.html_entity_decode(html_entity_decode($row['curs_name'])).'</td>
                        <td>'.$row['std_emails'].'</td>
                        <td class="text-center">'.$row['ref_percentage'].'%</td>
                        <td class="text-center">';
                            if (empty($row['adm_email'])) {
                                echo'
                                <div class="dropdown">
                                    <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end" style="cursor: pointer;">
                                        <li><a class="dropdown-item" onclick="discount_email_again_send(\''.$row['std_emails'].'\','.$REFERRAL_CONTROL["curs_id"].','.$REFERRAL_CONTROL['ref_id'].')"><i class="ri-logout-circle-line align-bottom me-2 text-muted"></i> Send Mail Again</a></li>
                                    </ul>
                                </div>';
                            }
                            echo'                                
                        </td>
                    </tr>';
                }
                echo'
            </tbody>
        </table>';                
        include_once('include/pagination.php');
        echo'
    </div>';
} else {
    echo'
    <div class="noresult" style="display: block">
        <div class="text-center">
            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px">
            </lord-icon>
            <h5 class="mt-2">Sorry! No Record Found</h5>
            <p class="text-muted">We\'ve searched '.$count.' Record and We did not find any for you search.</p>
        </div>
    </div>';
}
?>
<script>
    function discount_email_again_send(std_emails, id_curs, id_ref) {
        let data = new FormData();
        data.append("std_emails", std_emails);
        data.append("id_curs", id_curs);
        data.append("id_ref", id_ref);
        data.append("_type", "discount_email_send");
        fetch("include/ajax/get_CourseDiscountEmailSend.php", {
            method: "POST",
            body: data
        })
        .then(response => response.text())
        .then(response => {
            let responseArray = response.split(",");
            responseArray.forEach(record => {
                let [email, status] = record.split("|");
                Toastify({
                    newWindow: true,
                    text: status ? `Email sent to ${email}.` : `Email not sent to ${email}.`,
                    gravity: "top",
                    position: "right",
                    className: status ? "bg-success" : "bg-warning",
                    stopOnFocus: true,
                    offset: "50",
                    duration: 2000,
                    close: true,
                }).showToast();
            });
        })
        .catch(error => {
            console.error("Error sending emails:", error);
        });
    }
</script>