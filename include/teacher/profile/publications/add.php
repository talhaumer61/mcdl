<?php
//COUNTRIES
$condition = array ( 
                        'select' 	    =>  'country_id,country_name'
                        ,'where' 	    =>  array(  
                                                     'is_deleted'           => 0
                                                )
                        ,'order_by'     =>  'country_id DESC'
                        ,'return_type'  =>  'all' 
                    ); 
$COUNTRIES = $dblms->getRows(COUNTRIES, $condition);

// DEPARTMENTS
$condition = array ( 
                         'select' 	    =>  'dept_id, dept_name'
                        ,'where' 	    =>  array(  
                                                     'is_deleted'           => 0
                                                    ,'id_campus'            => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
                                                )
                        ,'order_by'     =>  'dept_id DESC'
                        ,'return_type'  =>  'all' 
                    ); 
$DEPARTMENTS = $dblms->getRows(DEPARTMENTS, $condition);
echo'
<form autocomplete="off" class="form-validate"  enctype="multipart/form-data" method="post" accept-charset="utf-8">
    <div class="row">
        <div class="col mb-2">
            <label class="form-label">Publication Type <span class="text-danger">*</span></label>
            <select class="form-control" data-choices name="id_type" required="" onchange="publicationType(this.value)">
                <option value=""> Choose one</option>';
                foreach(get_PublicationType() as $key => $val):
                    echo'<option value="'.$key.'">'.$val.'</option>';
                endforeach;
                echo'
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col mb-2">
            <label class="form-label">Title <span class="text-danger">*</span></label>
            <input class="form-control" type="text" name="title" id="title" required>
        </div>
        <div class="col mb-2">
            <label class="form-label">Sub Title</label>
            <input class="form-control" type="text" name="title" id="title">
        </div>
    </div>

    <div id="articles">
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Name of Journal</label>
                <input class="form-control" type="text" name="journal" id="journal">
            </div>
            <div class="col mb-2">
                <label class="form-label">Author</label>
                <input class="form-control" type="text" name="author" id="author">
            </div>
        </div>
        <div class="row" id="rowCoAuthor">
            <div class="col-md-6 mb-2">
                <label class="form-label">Co-Author</label>
                <input class="form-control" type="text" name="co_author[]" id="co_author[]">
            </div>
            <div class="col-md-5 mb-4">
                <label class="form-label">Current Affiliation</label>
                <select class="form-control" data-choices name="affiliation[]" id="affiliation">
                    <option value=""> Choose one</option>';
                    foreach(get_journalAffiliation() as $key => $val):
                        echo'<option value="'.$key.'">'.$val.'</option>';
                    endforeach;
                    echo'
                </select>
            </div>
            <div class="col-md-1" style="margin-top: 12px;">
                <i class="ri-add-circle-line" onclick="addCoAuthor()" style="font-size: 40px;"></i>
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Country of Journal</label>
                <select class="form-control" data-choices name="id_country" id="id_country">
                    <option value=""> Choose one</option>';
                    foreach($COUNTRIES as $key => $val):
                        echo'<option value="'.$val['country_id'].'">'.$val['country_name'].'</option>';
                    endforeach;
                    echo'
                </select>
            </div>
            <div class="col mb-2">
                <label class="form-label">ISSN</label>
                <input class="form-control" type="text" name="issn" id="issn">
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">DOI</label>
                <input class="form-control" type="text" name="doi" id="doi">
            </div>
            <div class="col mb-2">
                <label class="form-label">Pages</label>
                <input class="form-control" type="text" name="page" id="page">
            </div>
            <div class="col mb-2">
                <label class="form-label">Volume</label>
                <input class="form-control" type="text" name="vloume" id="vloume">
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Issue No</label>
                <input class="form-control" type="text" name="issue_num" id="issue_num">
            </div>
            <div class="col mb-2">
                <label class="form-label">Language</label>
                <select class="form-control" data-choices name="id_language" id="id_language">
                    <option value=""> Choose one</option>';
                    foreach(get_Language() as $key => $val):
                        echo'<option value="'.$key.'">'.$val.'</option>';
                    endforeach;
                    echo'
                </select>
            </div>
            <div class="col mb-2">
                <label class="form-label">Subject</label>
                <input class="form-control" type="text" name="subject" id="subject">
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Keywords</label>
                <input class="form-control" type="text" name="keywords" id="keywords">
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Artical Abstract</label>
                <textarea class="form-control" type="text" name="abstract" id="abstract"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Date of Publication</label>
                <input class="form-control" type="text" name="year_date" id="year_date">
            </div>
            <div class="col mb-2">
                <label class="form-label">Publisher Name</label>
                <input class="form-control" type="text" name="publisher_name" id="publisher_name">
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Url of Publication <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="url" id="url" required="">
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">HEC Category</label>
                <select class="form-control" data-choices name="hec_category" id="hec_category">
                    <option value=""> Choose one</option>';
                    foreach(get_hec_cat() as $key => $val):
                        echo'<option value="'.$key.'">'.$val.'</option>';
                    endforeach;
                    echo'
                </select>
            </div>
            <div class="col mb-2">
                <label class="form-label">HEC Category Proof URL</label>
                <input class="form-control" type="text" name="hec_category_url" id="hec_category_url">
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">HEC Medallion</label>
                <select class="form-control" data-choices name="hec_medallion" id="hec_medallion">
                    <option value=""> Choose one</option>';
                    foreach(get_hec_medallion() as $key => $val):
                        echo'<option value="'.$key.'">'.$val.'</option>';
                    endforeach;
                    echo'
                </select>
            </div>
            <div class="col mb-2">
                <label class="form-label">Affiliation</label>
                <select class="form-control" data-choices name="hec_affiliation" id="hec_affiliation">
                    <option value=""> Choose one</option>';
                    foreach(get_hec_Affiliation() as $key => $val):
                        echo'<option value="'.$key.'">'.$val.'</option>';
                    endforeach;
                    echo'
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Impact Factor</label>
                <input class="form-control" type="text" name="impact_factor" id="impact_factor">
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Indexed On</label>
                <input class="form-control" type="text" name="indexed_on" id="indexed_on">
            </div>
            <div class="col mb-2">
                <label class="form-label">Indexed On Url</label>
                <input class="form-control" type="text" name="indexed_on_url" id="indexed_on_url">
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Attachment</label>
                <input class="form-control" type="file" name="attachment" id="attachment" accept=".pdf, .doc, .docx, .png, .jpg, .jpeg">
                <span class="text-danger fw-bold" style="font-size: 12px;">(pdf, doc, docx, png, jpg, jpeg)</span>
            </div>
        </div>
    </div>

    <div id="thesis">
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Pages</label>
                <input class="form-control" type="text" name="page" id="page">
            </div>
            <div class="col mb-2">
                <label class="form-label">Language</label>
                <select class="form-control" data-choices name="id_language" id="id_language">
                    <option value=""> Choose one</option>';
                    foreach(get_Language() as $key => $val):
                        echo'<option value="'.$key.'">'.$val.'</option>';
                    endforeach;
                    echo'
                </select>
            </div>
            <div class="col mb-2">
                <label class="form-label">Department</label>
                <select class="form-control" data-choices name="id_dept" id="id_dept">
                    <option value=""> Choose one</option>';
                    foreach($DEPARTMENTS as $dept):
                        echo'<option value="'.$dept['dept_id'].'">'.$dept['dept_name'].'</option>';
                    endforeach;
                    echo'
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Class</label>
                <input class="form-control" type="text" name="std_class" id="std_class">
            </div>
            <div class="col mb-2">
                <label class="form-label">Accompanying Material</label>
                <input class="form-control" type="text" name="material" id="material">
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Barcode</label>
                <input class="form-control" type="text" name="barcode" id="barcode">
            </div>
            <div class="col mb-2">
                <label class="form-label">Session</label>
                <input class="form-control" type="text" name="session" id="session">
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Student Registration</label>
                <input class="form-control" type="text" name="std_regno" id="std_regno">
            </div>
            <div class="col mb-2">
                <label class="form-label">Submitted By</label>
                <input class="form-control" type="text" name="submitted_by" id="submitted_by">
            </div>
            <div class="col mb-2">
                <label class="form-label">Submitted To Supervisor</label>
                <input class="form-control" type="text" name="submitted_to" id="submitted_to">
            </div>
        </div>
    </div>

    <div id="book">
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Author</label>
                <input class="form-control" type="text" name="author" id="author">
            </div>
            <div class="col mb-2">
                <label class="form-label">Corporate Name</label>
                <input class="form-control" type="text" name="corporate_name" id="corporate_name">
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">ISBN</label>
                <input class="form-control" type="text" name="isbn" id="isbn">
            </div>
            <div class="col mb-2">
                <label class="form-label">ISSN</label>
                <input class="form-control" type="text" name="issn" id="issn">
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Book Type</label>
                <input class="form-control" type="text" name="book_type" id="book_type">
            </div>
            <div class="col mb-2">
                <label class="form-label">Pages</label>
                <input class="form-control" type="text" name="page" id="page">
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Volume</label>
                <input class="form-control" type="text" name="vloume" id="vloume">
            </div>
            <div class="col mb-2">
                <label class="form-label">Language</label>
                <select class="form-control" data-choices name="id_language" id="id_language">
                    <option value=""> Choose one</option>';
                    foreach(get_Language() as $key => $val):
                        echo'<option value="'.$key.'">'.$val.'</option>';
                    endforeach;
                    echo'
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Subject</label>
                <input class="form-control" type="text" name="subject" id="subject">
            </div>
            <div class="col mb-2">
                <label class="form-label">Keywords</label>
                <input class="form-control" type="text" name="keywords" id="keywords">
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Date of Publication</label>
                <input class="form-control" type="text" name="year_date" id="year_date">
            </div>
            <div class="col mb-2">
                <label class="form-label">Publisher Name</label>
                <input class="form-control" type="text" name="publisher_name" id="publisher_name">
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Edition</label>
                <input class="form-control" type="text" name="edition" id="edition">
            </div>
            <div class="col mb-2">
                <label class="form-label">Editor Name</label>
                <input class="form-control" type="text" name="editor" id="editor">
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Serial Name</label>
                <input class="form-control" type="text" name="series_name" id="series_name">
            </div>
            <div class="col mb-2">
                <label class="form-label">Serial Number</label>
                <input class="form-control" type="text" name="series_num" id="series_num">
            </div>
        </div>
        <div class="row">
            <div class="col mb-2">
                <label class="form-label">Download Link</label>
                <input class="form-control" type="text" name="url" id="url">
            </div>
            <div class="col mb-2">
                <label class="form-label">Department</label>
                <select class="form-control" data-choices name="id_dept" id="id_dept">
                    <option value=""> Choose one</option>';
                    foreach($DEPARTMENTS as $key => $val):
                        echo'<option value="'.$val['dept_id'].'">'.$val['dept_name'].'</option>';
                    endforeach;
                    echo'
                </select>
            </div>
        </div>
    </div>

    <hr>
    <div class="hstack gap-2 justify-content-end">
        <a href="'.moduleName().'.php?id='.cleanvars($_GET['id']).'&view='.cleanvars($_GET['view']).'" class="btn btn-danger btn-sm""><i class="ri-close-circle-line align-bottom me-1"></i>Close</a>
        <button type="submit" class="btn btn-primary btn-sm" name="submit_add"><i class="ri-add-circle-line align-bottom me-1"></i>Add Publication</button>
    </div>
</form>';
?>