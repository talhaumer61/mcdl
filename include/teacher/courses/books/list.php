<?php
include_once ('query.php');
$condition = array ( 
                         'select' 	    =>  'id, status, book_name, author_name, edition, isbn, publisher, url'
                        ,'where' 	    =>  array(  
                                                     'is_deleted'           => 0
                                                    ,'id_session'           => cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                    ,'id_campus'            => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                    // ,'id_teacher'           => cleanvars($_SESSION['userlogininfo']['EMPLYID'])
                                                    ,'id_curs'			    => cleanvars($_GET['id'])
                                                )
                        ,'order_by'     =>  'id DESC'
                        ,'return_type'  =>  'all' 
                    ); 
$COURSES_BOOKS    = $dblms->getRows(COURSES_BOOKS, $condition);
echo'
<div class="card mb-5">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"><i class="ri-information-line align-bottom me-1"></i>'.$courseMenu[cleanvars($_GET['view'])]['title'].'</h5>
            <div class="flex-shrink-0">
                <a class="btn btn-primary btn-sm" onclick="showAjaxModalZoom(\'include/modals/books/books.php?id='.cleanvars($_GET['id']).'&view='.cleanvars($_GET['view']).'\');"><i class="ri-add-circle-line align-bottom me-1"></i>'.$courseMenu[cleanvars($_GET['view'])]['title'].'</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12" >
                <table class="table table-bordered table-nowrap align-middle">
                    <thead>
                        <tr>
                            <th class="text-center" width="10">Sr.</th>
                            <th>Name</th>
                            <th>Author</th>
                            <th width="35">Status</th>
                            <th width="35">Action</th>
                        </tr>
                    </thead>';
                    if ($COURSES_BOOKS) {
                        $sr=0;
                        foreach($COURSES_BOOKS as $key => $val):
                            $sr++;
                            echo'
                            <tbody>
                                <tr>
                                    <td class="text-center">'.$sr.'</td>
                                    <td>'.$val['book_name'].'</td>
                                    <td>'.$val['author_name'].'</td>
                                    <td class="text-center">'.get_status($val['status']).'</td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-soft-primary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-fill"></i></button>
                                            <ul class="dropdown-menu dropdown-menu-end" style="cursor: pointer;">
                                                <li><a class="dropdown-item" onclick="showAjaxModalView(\'include/modals/books/view.php?book_id='.$val['id'].'\');" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                                                <li><a class="dropdown-item" onclick="showAjaxModalZoom(\'include/modals/books/books.php?book_id='.$val['id'].'&id='.cleanvars($_GET['id']).'&view='.cleanvars($_GET['view']).'\');"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                                <li><a class="dropdown-item" onclick="confirm_modal(\'courses.php?deleteid='.$val['id'].'&id='.cleanvars($_GET['id']).'&view='.cleanvars($_GET['view']).'\');"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>';
                        endforeach;
                    } else {
                        echo'
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center">*** No Record Found ***</td>
                            </tr>
                        </tbody>';
                    }
                    echo'
                </table>
            </div>
        </div>
    </div>
</div>';
?>