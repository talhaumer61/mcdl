<?php
$search_word    = '';
$search_query   = '';
$filters        = 'search';

if (!empty($_GET['search_word'])) {
    $search_word  = $_GET['search_word'];

    $search_query .= 'AND (
        doc_title LIKE "%'.$search_word.'%" 
    )';
    $filters .= '&search_word='.$search_word;
}

$condition = array (
    'select' => "id, status, doc_title, doc_file, doc_type",
    'where' => array(
        'is_deleted' => 0,
        'id_intern' => LMS_EDIT_ID
    ),
    'search_by' => $search_query,
    'order_by' => 'id DESC',
    'return_type' => 'count'
);
$count = $dblms->getRows(INTERN_DOCS, $condition);
echo'
<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-file-paper-2-fill align-bottom me-1"></i><span class="text-primary">'.htmlspecialchars($_GET['full_name']).'\'s</span> '.moduleName(LMS_VIEW).' List</h5>
            <div class="flex-shrink-0">
                <a class="btn btn-primary btn-sm" onclick="showAjaxModalZoom(\'include/modals/intern_documents/add.php?view=documents&id_intern='.LMS_EDIT_ID.'\');"><i class="ri-add-circle-line align-bottom me-1"></i>'.moduleName(LMS_VIEW).'</a>
            </div>
        </div>
    </div>
    <div class="card-body">        
        <div class="row justify-content-end">
            <div class="col-3">
                <form class="form-horizontal" id="form" enctype="multipart/form-data" method="get" autocomplete="off" accept-charset="utf-8">
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

        $condition['order_by'] = "id DESC LIMIT ".($page - 1) * $Limit.",$Limit";
        $condition['return_type'] = 'all';

        $rowsList = $dblms->getRows(INTERN_DOCS, $condition);
        
        if ($rowsList) {

            foreach ($rowsList as $row) {

                $file = $row['doc_file'];
                $ext  = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                // ICON LOGIC
                if ($ext == 'pdf') {
                    $icon = 'ri-file-pdf-fill text-danger';
                } elseif (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                    $icon = 'ri-image-fill text-success';
                } else {
                    $icon = 'ri-file-unknow-fill text-muted';
                }

                /* ✅ FILE SYSTEM PATH (NOT URL) */
                $filePath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/images/intern_documents/' . $file;

                /* ✅ FILE URL (FOR DOWNLOAD) */
                $showFile  = SITE_URL . 'uploads/images/intern_documents/' . $file;

                if (is_file($filePath)) {
                    $fileSizeBytes  = filesize($filePath);
                    $fileSizeMB     = round($fileSizeBytes / (1024 * 1024), 2);
                    $releaseDate    = filemtime($filePath);
                } else {
                    $fileSizeMB = 'N/A';
                }

                echo '
                <div class="col-xxl-3 col-6 folder-card">
                    <div class="card bg-light shadow-none" id="folder-2">
                        <div class="card-body">
                            <div class="d-flex mb-1">
                                <div class="form-check form-check-danger mb-3 fs-15 flex-grow-1">
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-soft-primary btn-icon btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ri-more-2-fill fs-16 align-bottom"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a href="'.$showFile.'"" class="dropdown-item" download> <i class="ri-download-line me-1 align-bottom"></i> Download</a></li>
                                        <li><a href="'.$showFile.'" target="_blank" class="dropdown-item"> <i class="ri-eye-line me-1 align-bottom"></i> View</a></li>
                                        <li><a href="javascript:void(0);" onclick="showAjaxModalZoom(\'include/modals/intern_documents/edit.php?view=documents&edit_id='.$row['id'].'&id_intern='.LMS_EDIT_ID.'\');" class="dropdown-item"> <i class="ri-pencil-line me-1 align-bottom"></i> Edit </a></li>
                                        <li><a href="javascript:void(0);" onclick="confirm_modal(\'?deleteid_doc='.$row['id'].'\');" class="dropdown-item text-danger"> <i class="ri-delete-bin-line me-1 align-bottom"></i> Delete </a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="text-center">
                                <div class="mb-2">
                                    <i class="'.$icon.' align-bottom text-warning display-5" style="font-size:50px;"></i>
                                </div>
                                <h6 class="fs-15 folder-name">'.$row['doc_title'].'</h6>
                            </div>
                            <div class="hstack mt-4 text-muted">
                                <span class="me-auto">'.getInternDocTypes($row['doc_type']).'</span>
                                <span><b>'.$fileSizeMB.'</b></span>
                            </div>
                        </div>
                    </div>
                </div>';
            }

        } else {

            echo '
            <div class="col-12">
                <div class="text-center p-4">
                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json"
                            trigger="loop"
                            colors="primary:#405189,secondary:#0ab39c"
                            style="width:80px;height:80px">
                    </lord-icon>
                    <h5 class="mt-2">No Documents Found</h5>
                </div>
            </div>';
        }
        echo'
    </div>
</div>';
?>