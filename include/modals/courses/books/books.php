<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
include "../../functions/functions.php";
$dblms = new dblms();
include "../../functions/login_func.php";
checkCpanelLMSALogin();
if (!empty($_GET['book_id'])) {
    $condition = array ( 
                             'select' 	    =>  'id, status, book_name, author_name, edition, isbn, publisher, url'
                            ,'where' 	    =>  array(  
                                                         'is_deleted'           => 0
                                                        ,'id_session'           => cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                        ,'id_campus'            => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                        ,'id_teacher'           => cleanvars($_SESSION['userlogininfo']['EMPLYID'])
                                                        ,'id'			        => cleanvars($_GET['book_id'])
                                                    )
                            ,'return_type'  =>  'single' 
                        ); 
    $COURSES_BOOKS    = $dblms->getRows(COURSES_BOOKS, $condition);
    $color      = 'info';
    $btn        = 'edit';
} else {
    $color      = 'primary';
    $btn        = 'add';
}

$inputFields = array(
    'book_name'   => array( 'title' => 'Book Name'   , 'class' => '6' , 'required' => '1')
   ,'author_name' => array( 'title' => 'Author Name' , 'class' => '6' , 'required' => '1')
   ,'edition'     => array( 'title' => 'Edition'     , 'class' => '6' , 'required' => '1')
   ,'isbn'        => array( 'title' => 'ISBN'        , 'class' => '6' , 'required' => '')
   ,'publisher'   => array( 'title' => 'Publisher'   , 'class' => '6' , 'required' => '')
   ,'url'         => array( 'title' => 'Url'         , 'class' => '6' , 'required' => '')
);

echo'
<script src="assets/js/app.js"></script>
<div class="modal-dialog modal-lg modal-dialog-centered" >
    <div class="modal-content">
        <div class="modal-header bg-'.$color.' p-3">
            <h5 class="modal-title" id="exampleModalLabel"><i class="ri-add-circle-line align-bottom me-1"></i>'.ucfirst($btn).' Books</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="courses.php?id='.cleanvars($_GET['id']).'&view='.cleanvars($_GET['view']).'" autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <input type="hidden" name="book_id"             value="'.cleanvars($_GET['book_id']).'">
            <input type="hidden" name="id"                  value="'.cleanvars($_GET['id']).'">
            <input type="hidden" name="view"                value="'.cleanvars($_GET['view']).'">
            <div class="modal-body">
                <div class="row">';
                    foreach($inputFields as $key => $val):
                        echo'
                        <div class="col-md-'.$val['class'].' mb-2">
                            <label class="form-label">'.$val['title'].' '.(($val['required']==1)?'<span class="text-danger">*</span>':'').'</label>
                            <input class="form-control" id="'.$key.'" name="'.$key.'" value="'.$COURSES_BOOKS[$key].'" required="">
                        </div>';
                    endforeach;
                    echo'
                    <div class="col-md-12 mb-2">
                        <label class="form-label">Staus <span class="text-danger">*</span></label>
                        <select class="form-control" data-choices name="status" required="">
                            <option value=""> Choose one</option>';
                            foreach(get_status() as $key => $status):
                                echo'<option value="'.$key.'" '.(($key == $COURSES_BOOKS['status'])? 'selected': '').'>'.$status.'</option>';
                            endforeach;
                            echo'
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="ri-close-circle-line align-bottom me-1"></i>Close</button>
                    <button type="submit" class="btn btn-'.$color.' btn-sm" name="submit_'.$btn.'"><i class="ri-'.$btn.'-circle-line align-bottom me-1"></i>'.ucfirst($btn).' Books</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    CKEDITOR.replace(\'ckeditor1\');
</script>';
?>








