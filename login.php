<?php 
include "include/dbsetting/lms_vars_config.php";
include "include/functions/login_func.php";
if (isset($_COOKIE['SWITCHTOINSTRUCTOR']) && !empty($_COOKIE['SWITCHTOINSTRUCTOR'])) {
    cpanelLMSAuserLogin();
}
if(isset($_SESSION['userlogininfo']['LOGINIDA'])) {
	sessionMsg("Success", "Login Successfully.", "success");
	header("Location: dashboard.php", true, 301);
} else { 
    $login_id = (isset($_POST['login_id']) && $_POST['login_id'] != '') ? $_POST['login_id'] : '';	
    $errorMessage = '';
    if (isset($_POST['login_id'])) {
        $result = cpanelLMSAuserLogin();
        if ($result != '') {
            $errorMessage = $result;
        }
    }
    include "login/header.php";
    echo'
    <div class="page-wrapper default-version">
        <div class="form-area bg_img" data-background="login/images/1.jpg">
            <div class="form-wrapper">
                <h4 class="logo-text mb-15">Welcome to <strong>'.SITE_NAME.'</strong></h4>
                <!--<p>Admin Login to <strong>'.SITE_NAME.'</strong></p>-->
                <form method="post" class="cmn-form mt-20" accept-charset="utf-8" name="frmLogin" id="frmLogin">
                    <label class="text-danger">'.(($errorMessage)?$errorMessage:'').'</label>
                    <div class="form-group">
                        <label for="email">Username</label>
                        <input type="text" name="login_id" class="form-control b-radius--capsule" autofocus required id="login_id" autocomplete="off" value="'.$login_id.'" placeholder="Enter your username">
                        <i class="las la-user input-icon"></i>
                    </div>
                    <div class="form-group">
                        <label for="pass">Password</label>
                        <input type="password" name="login_pass" class="form-control b-radius--capsule" required value="" autocomplete="off" id="login_pass" placeholder="Enter your password">
                        <i class="las la-lock input-icon"></i>
                    </div>

                    <div class="form-group d-flex justify-content-between align-items-center">
                        <a href="#" class="text-muted text--small"><i class="las la-lock"></i> Forgot password?</a>
                        <a href="'.WEBSITE_URL.'" class="text-danger text--small"><i class="las la-globe"></i> Back to website</a>
                    </div>

                    <div class="form-group">
                        <button type="submit" id="btn_submit" class="submit-btn mt-25 b-radius--capsule">Login <i class="las la-sign-in-alt"></i></button>
                    </div>
                </form>
                <div style="clear:both;"></div>
                <footer id="footer" style="margin-top:20px;">
                    <div class="text-center padder">
                        <p> <small>'.COPY_RIGHTS_ORG.'
                        <br>Powered by: <a href="'.COPY_RIGHTS_URL.'">'.COPY_RIGHTS.'</a>  v1.0</small></p>
                    </div>
                </footer>
            </div>	
        </div>
    </div>';
    include "login/footer.php";
}
?>