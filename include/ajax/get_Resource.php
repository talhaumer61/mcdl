<?php
echo'
<div class="row" id="rowResource">
  <div class="col">
    <label class="form-label">Resource Title</label>
    <input type="text" class="form-control" name="file_name[]"/>
  </div>
  <div class="col">
    <label class="form-label">Attach File </label>
    <input class="form-control" type="file" accept=".pdf, .xlsx, .xls, .doc, .docx, .ppt, .pptx, .png, .jpg, .jpeg" name="file[]" id="fileInput">
    <p id="errorMessage" class="text-danger" style="display: none;">File must be less than 5MB.</p>
    <div class="text-primary mt-2">Upload valid files. Only <span class="text-danger fw-bold">pdf, xlsx, xls, doc, docx, ppt, pptx, png, jpg, jpeg</span> are allowed.</div>
  </div>
  <div class="col">
    <label class="form-label">Url </label>
    <input type="text" class="form-control" name="resource_url[]"/>
  </div>       
  <div class="col-md-1" style="margin-top: 12px;">
    <i class="ri-close-circle-line" onclick="editResource(this.id)" id="addResource'.$_POST['flagi'].'" style="font-size: 40px;"></i>
  </div>
</div>';
?>