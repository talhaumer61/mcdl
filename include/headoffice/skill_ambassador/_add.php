<?php
$year = date('y');

$sqlQuery = $dblms->querylms("SELECT 
                                IFNULL(
                                    CONCAT(
                                        'SA-', $year, '-', 
                                        LPAD(
                                            CAST(SUBSTRING_INDEX(MAX(org_reg), '-', -1) AS UNSIGNED) + 1, 
                                            5, '0'
                                        )
                                    ), 
                                    CONCAT('SA-', $year, '-00001')
                                ) AS new_org_reg
                                FROM ".ORGANIZATIONS."
                                WHERE org_reg LIKE CONCAT('SA-', $year, '-%')
                            ");
$valQuery = mysqli_fetch_array($sqlQuery);
echo'
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header bg-primary">
                <h5 class="mb-0 modal-title"><i class="ri-add-circle-line align-bottom me-1"></i>Add ' . moduleName(false) . '</h5>
            </div>
            <form enctype="multipart/form-data" autocomplete="off" method="post" accept-charset="utf-8">
                <div class="card-body create-project-main">
                    <div class="row mb-2">
                        <div class="col">
                            <div class="card mb-3">
                                <div class="card-header alert-primary">
                                    Basic Information
                                </div>
                                <div class="card-body border">
                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label">Name <span class="text-danger">*</span></label>
                                            <input type="text" name="org_name" class="form-control" required="">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Registration Number <span class="text-danger">*</span></label>
                                            <input type="text" name="org_reg" class="form-control" value="'.$valQuery['new_org_reg'].'" required readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" name="org_status" data-choices required="">
                                                <option value="">Choose one</option>';
                                                foreach (get_status() as $key => $status):
                                                    echo '<option value="' . $key . '">' . $status . '</option>';
                                                endforeach;
                                                echo '
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Discount Percentage <span class="text-danger">*</span></label>
                                            <input type="number" name="org_percentage" id="org_percentage" class="form-control" required min="1" max="25">
                                            <script>
                                                const org_percentage = document.getElementById("org_percentage");

                                                org_percentage.addEventListener("input", function () {
                                                    const value = parseInt(this.value);

                                                    // Enforce the maximum and minimum value during input
                                                    if (value > 25) {
                                                        this.value = 25; // Set the value to 25 if it exceeds the maximum
                                                    } else if (value < 1) {
                                                        this.value = 1; // Set the value to 1 if it goes below the minimum
                                                    }
                                                });
                                            </script>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Profilt Percentage <span class="text-danger">*</span></label>                                            
                                            <input type="number" name="org_profit_percentage" id="org_profit_percentage" class="form-control" required min="1" max="25">
                                            <script>
                                                const org_profit_percentage = document.getElementById("org_profit_percentage");

                                                org_profit_percentage.addEventListener("input", function () {
                                                    const value = parseInt(this.value);

                                                    // Enforce the maximum and minimum value during input
                                                    if (value > 20) {
                                                        this.value = 20; // Set the value to 20 if it exceeds the maximum
                                                    } else if (value < 1) {
                                                        this.value = 1; // Set the value to 1 if it goes below the minimum
                                                    }
                                                });
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <div class="card mb-3">
                                <div class="card-header alert-primary">
                                    Login Information
                                </div>
                                <div class="card-body border">
                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label">Photo <span class="text-danger">*</span></label>
                                            <input type="file" name="org_photo" class="form-control" required="">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="text" name="org_email" class="form-control" required="">
                                            <small id="email_error"></small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label">User Name <span class="text-danger">*</span></label>
                                            <input type="text" name="adm_username" id="adm_username" class="form-control" required="">
                                            <small id="username_error"></small>
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Password <span class="text-danger">*</span></label>
                                            <input type="text" name="adm_userpass" class="form-control" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <div class="card mb-3">
                                <div class="card-header alert-primary">
                                    Referral Information
                                </div>
                                <div class="card-body border">
                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label">Link</label>
                                            <input type="text" name="org_referral_link" id="org_referral_link" class="form-control" required="" readonly="">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Link Expiry <span class="text-danger">*</span></label>
                                            <input type="text" name="org_referral_link_expiry" class="form-control" data-provider="flatpickr" data-date-format="Y-m-d" data-range-date="true" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <div class="card mb-3">
                                <div class="card-header alert-primary">
                                    Contact Information
                                </div>
                                <div class="card-body border">
                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                                            <input type="text" name="org_phone" class="form-control" required="">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">Telephone <span class="text-danger">*</span></label>
                                            <input type="text" name="org_telephone" class="form-control" required="">
                                        </div>
                                        <div class="col">
                                            <label class="form-label">What\'s App <span class="text-danger">*</span></label>
                                            <input type="text" name="org_whatsapp" class="form-control" required="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label">Address </label>
                                            <textarea class="form-control" name="org_address"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <a href="' . moduleName() . '.php" class="btn btn-danger btn-sm"><i class="ri-close-circle-line align-bottom me-1"></i>Close</a>
                        <button type="submit" class="btn btn-primary btn-sm" name="submit_add"><i class="ri-add-circle-line align-bottom me-1"></i>Add ' . moduleName(0) . '</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $("input[name=\'adm_username\']").on("input",function () {
        if(this.value.length >= 5){
            $.ajax({
                url: "include/ajax/check_username.php", 
                type: "POST",
                data : {username : this.value},
                dataType : "json",
                success: function(response) {
                    if (response.status == "success") {
                        $("#username_error").html("<span class=\'text-danger\'>User Name already exsist</span>");
                    } else {
                        $("#username_error").html("<span class=\'text-success\'>User Name available</span>");
                    }
                }
            });
        }else{
            $("#username_error").html("<span class=\'text-danger\'>User Name should be Greater than 5 characters</span>");
        }
    });
    $("input[name=\'org_email\']").on("input",function () {
        if(this.value.length >= 5){
            const rep = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (rep.test(String(this.value).toLowerCase())) {
                $.ajax({
                    url: "include/ajax/check_username.php", 
                    type: "POST",
                    data : {email : this.value},
                    dataType : "json",
                    success: function(response) {
                        if(response.status == "success") {
                            $("#email_error").html("<span class=\'text-danger\'>Email already exsist</span>");
                        } else {
                            $("#email_error").html("<span class=\'text-success\'>Email available</span>");
                        }
                    }
                });
            } else {
                $("#email_error").html("<span class=\'text-danger\'>Enter a valid email</span>");
            }
        }else{
            $("#email_error").html("<span class=\'text-danger\'>Email should be Greater than 5 characters</span>");
        }
    });

    let typingTimer; 
    let doneTypingInterval = 500; 
    const adm_username = document.getElementById("adm_username");
    const org_referral_link = document.getElementById("org_referral_link");
    adm_username.addEventListener("keyup", function() {
        clearTimeout(typingTimer); 
        typingTimer = setTimeout(doneTyping, doneTypingInterval); 
    });   
    adm_username.addEventListener("keydown", function() {
        clearTimeout(typingTimer); 
    });   
    function doneTyping() {
        const input1Value = adm_username.value;
        let currentIndex = 0;
        org_referral_link.value = "_";         
        function typeWriter() {
            if (currentIndex < input1Value.length) {
                org_referral_link.value += input1Value.charAt(currentIndex);
                currentIndex++;
                setTimeout(typeWriter, 200); 
            }
        }
        typeWriter();
    }
</script>';
?>