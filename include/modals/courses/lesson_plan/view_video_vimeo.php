<?php 
require_once ("../../../dbsetting/lms_vars_config.php");
require_once ("../../../dbsetting/classdbconection.php");
require_once ("../../../functions/functions.php");
require_once ("../../../functions/login_func.php");
$dblms = new dblms();

include "../../../db.classes/courses.php";
$coursecls  = new courses();

$result     = $coursecls->get_courselessondetail($_GET['view_id']);

$videoId = $result['lesson_video_code_vimeo']; // '1132011025', '1141042458'
$accessToken = VIMEO_ACCESS_TOKEN;

// --------------- Vimeo API Call (Corrected) ------------------

// initialize cURL session
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.vimeo.com/videos/$videoId");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $accessToken"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    $videoData = json_decode($response, true);
} else {
    $videoData = null;
}
echo'
<style>
    .embed-video {
        overflow: hidden;
        padding-bottom: 56.25%;
        position: relative;
        height: 0;
    }

    .embed-video iframe {
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        position: absolute;
    }
</style>
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title text-dark" id="exampleModalLabel"><i class="ri-vimeo-fill align-bottom me-1"></i>'.moduleName(LMS_VIEW).' Video</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="embed-video rounded shadow overflow-hidden">
                        '.$videoData['embed']['html'].'
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="hstack gap-2 justify-content-end">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
            </div>
        </div>
    </div>
</div>';