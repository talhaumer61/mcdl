<?php
$condition = array(
                     'select'       =>  'c.curs_id, c.curs_name, r.ref_id, r.ref_percentage'
                    ,'join'         =>  'INNER JOIN '.COURSES.' AS c ON c.curs_id = '.cleanvars($REFERRAL_CONTROL['curs_id']).' AND c.curs_status = 1 AND c.is_deleted = 0'
                    ,'where'        =>  array(
                                                 'r.is_deleted' =>	0
                                                ,'r.ref_status' =>	1
                                            )
                    ,'search_by'    =>  ' AND r.ref_date_time_from < "'.date('Y-m-d G:i:s').'" AND r.ref_date_time_to > "'.date('Y-m-d G:i:s').'" AND FIND_IN_SET('.cleanvars($REFERRAL_CONTROL['curs_id']).', r.id_curs) AND FIND_IN_SET('.cleanvars($_SESSION['userlogininfo']['LOGINIDA']).', r.id_user)'
                    ,'return_type'  =>  'single'
);
$REFERRAL_CONTROL = $dblms->getRows(REFERRAL_CONTROL.' AS r', $condition); 
if ($REFERRAL_CONTROL) {
    echo'
    <div class="row mb-2">
        <div class="col-md-12 text-center">
            <lord-icon
                src="https://cdn.lordicon.com/ibydboev.json"
                trigger="loop"
                state="morph-open"
                style="width:150px;height:150px"
                colors="primary:#405189,secondary:#0ab39c">
            </lord-icon>
        </div>
        <div class="col-md-12 text-center">
            <h4>
            You have access to refer 
            <span class="badge badge-gradient-success">'.$REFERRAL_CONTROL['ref_percentage'].'%</span> 
            discount on this course
            <span class="badge badge-gradient-success">'.$REFERRAL_CONTROL['curs_name'].'</span> 
            you can give dicount to your students.</h4>
            <p class="text-muted">Don\'t worry we not spam on these emails. 😊</p>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col">
            <input type="text" class="form-control border-light shadow" id="curs_share_discount_email" data-choices data-choices-removeItem data-choices-text-unique-true data-choices data-choices-limit="10"/>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col text-center">
            <button type="button" id="curs_share_discount_email_btn" class="btn btn-outline-success btn-load">
                <span class="d-flex align-items-center">
                    <span class="flex-grow-1 me-2">Send</span>
                </span>
            </button>
        </div>
    </div>';
} else {
    echo'
    <div class="noresult" style="display: block">
        <div class="text-center">
            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px">
            </lord-icon>
            <h5 class="mt-2">Sorry! No Record Found</h5>
            <!--<p class="text-muted">We\'ve searched more than 150+ Orders We did not find any orders for you search.</p>-->
        </div>
    </div>';
}
?>

<script>
    $("#curs_share_discount_email_btn").on("click", function() {
        const emailInput = $("#curs_share_discount_email").val().trim();
        if (emailInput !== "") {
            $("#curs_share_discount_email_btn").html(`
                <span class="d-flex align-items-center">
                    <span class="flex-grow-1 me-2">Sending...</span>
                    <span class="spinner-border flex-shrink-0" role="status"></span>
                </span>
            `);
            $.ajax({
                url: "include/ajax/get_CourseDiscountEmailSend.php",
                type: "POST",
                data: {
                    "std_emails": emailInput,
                    "id_curs": "<?= $REFERRAL_CONTROL["curs_id"]; ?>",
                    "id_ref": "<?= $REFERRAL_CONTROL["ref_id"]; ?>",
                    "_type": "discount_email_send",
                },
                success: function(response) {
                    let responseArray = response.split(',');
                    responseArray.forEach(record => {
                        let [email, status] = record.split('|');

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
                            style: "style",
                        }).showToast();
                    });
                    $("#curs_share_discount_email_btn").html(`
                        <span class="d-flex align-items-center">
                            <span class="flex-grow-1 me-2">Send</span>
                        </span>
                    `);       
                }
            });
        } else {
            Toastify({
                newWindow: true,
                text: "Warning | Please enter emails.",
                gravity: "top",
                position: "right",
                className: "bg-warning",
                stopOnFocus: true,
                offset: "50",
                duration: 2000,
                close: true,
                style: "style",
            }).showToast();
        }
    });

</script>