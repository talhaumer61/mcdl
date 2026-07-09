<?php 
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms  = new dblms();
$condition = array (
    'select' => "id, status, doc_title, doc_file, doc_type",
    'where' => array(
        'is_deleted' => 0,
        'id_intern' => $_GET['id_intern'],
        'id' => LMS_EDIT_ID
    ),
    'return_type' => 'single'
);
$row = $dblms->getRows(INTERN_DOCS, $condition);
echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-info p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(LMS_VIEW).' </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input class="form-control" type="hidden" name="edit_id" value="'.$row['id'].'" required>
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control" required name="status" data-choices>
                            <option value="">Choose one</option>';
                            foreach(get_status() as $key => $status):
                                echo '<option value="'.$key.'" '.($key == $row['status'] ? 'selected' : '').'>'.$status.'</option>';
                            endforeach;
                            echo '
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-control" required name="doc_type" data-choices>
                            <option value="">Choose one</option>';
                            foreach(getInternDocTypes() as $key => $val):
                                echo '<option value="'.$key.'" '.($key == $row['doc_type'] ? 'selected' : '').'>'.$val.'</option>';
                            endforeach;
                            echo '
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="doc_title" value="'.$row['doc_title'].'" required>
                    </div>
                </div>                
                <div class="row">
                    <div class="col mb-2">
                        <label class="form-label">File</label>
                        <input class="form-control" type="file" name="doc_file" accept=".jpg,.jpeg,.png,.pdf,">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-info btn-sm" name="submit_edit_document"><i class="ri-edit-circle-line align-bottom me-1"></i>Edit '.moduleName(LMS_VIEW).'</button>
                </div>
            </div>
        </form>
    </div>
</div>';
?>

<script>
document.addEventListener('change', function (e) {

    if (!e.target.matches('input[type="file"]')) return;

    const input   = e.target;
    const file    = input.files[0];
    const maxSize = 300 * 1024; // 300 KB

    // Remove old error message
    const oldError = input.parentNode.querySelector('.file-size-error');
    if (oldError) oldError.remove();

    if (!file) return;

    // Allowed MIME types
    const allowedTypes = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'application/pdf'
    ];

    // Type check
    if (!allowedTypes.includes(file.type)) {
        input.value = '';
        showError(input, 'Only JPG, JPEG, PNG, or PDF files are allowed.');
        return;
    }

    // Size check (for ALL allowed files)
    if (file.size > maxSize) {
        input.value = '';
        showError(input, 'File size must not exceed 300 KB.');
        return;
    }

    // Helper function
    function showError(input, message) {
        const error = document.createElement('small');
        error.className = 'text-danger file-size-error d-block mt-1';
        error.textContent = message;
        input.parentNode.appendChild(error);
    }

});
</script>