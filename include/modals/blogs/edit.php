<?php 
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms  = new dblms();

require_once("../../db.classes/blogs.php");
$blogsecls = new blogs();

$result = $blogsecls->get_blogs($_GET['blog_id']);

echo '
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header bg-light p-3">
            <h5 class="modal-title text-dark" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Blog</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="blogs.php" autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input class="form-control" type="hidden" name="blog_id" value="'.$result['blog_id'].'" required>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="blog_name" value="'.$result['blog_name'].'" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label" for="card-name">Tags <span class="text-danger">*</span></label>
                        <input class="form-control" name="blog_tags" data-choices data-choices-text-unique-true type="text" value="'.$result['blog_tags'].'" required>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Image</label>
                        <input type="file" name="blog_photo" accept="image/*" class="form-control"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="blog_description" id="ckeditor0" class="form-control" required>'.html_entity_decode(html_entity_decode($result['blog_description'])).'</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Staus <span class="text-danger">*</span></label>
                        <select class="form-control" required name="blog_status" data-choices>
                            <option value="">Choose one</option>';
                            $statuses = get_status();
                            foreach($statuses as $key => $status):
                                echo '
                                <option value="'.$key.'" '.($key == $result['blog_status']?'selected':'').'>'.$status.'</option>';
                            endforeach;
                            echo '
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="text" name="blog_date" id="blog_date" class="form-control" value="'.$result['blog_date'].'" data-provider="flatpickr" data-date-format="Y-m-d" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit Blog</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    CKEDITOR.replace("ckeditor0");
</script>';