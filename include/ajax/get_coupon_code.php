
<?php
require_once ('../dbsetting/lms_vars_config.php');
require_once ('../dbsetting/classdbconection.php');
$dblms = new dblms();
require_once ('../functions/login_func.php');
require_once ('../functions/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    do {
        $couponCode = generateCouponCode();
        $sqlCheck	= $dblms->querylms("SELECT cpn_code FROM ".COUPONS." WHERE cpn_code = '$couponCode'");
    } while (mysqli_num_rows($sqlCheck) > 0);
    
    echo $couponCode;
}
?>