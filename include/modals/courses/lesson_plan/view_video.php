<?php 
require_once ("../../../dbsetting/lms_vars_config.php");
require_once ("../../../dbsetting/classdbconection.php");
require_once ("../../../functions/functions.php");
require_once ("../../../functions/login_func.php");
$dblms = new dblms();

include "../../../db.classes/courses.php";
$coursecls = new courses();

$result     = $coursecls->get_courselessondetail($_GET['view_id']);
$videoCode  = $result['lesson_video_code'];
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
        <div class="modal-header bg-danger p-3">
            <h5 class="modal-title text-dark" id="exampleModalLabel"><i class="ri-youtube-fill align-bottom me-1"></i>'.moduleName(LMS_VIEW).' Video</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="embed-video rounded shadow overflow-hidden">
                        <iframe
                            src="https://www.youtube.com/embed/'.$videoCode.'?enablejsapi=1"
                            title="YouTube video player"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
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