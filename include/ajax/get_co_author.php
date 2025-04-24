<?php
echo'
    <link href="../../assets/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <!-- Layout config Js -->
    <script src="../../assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="../../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="../../assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="../../assets/css/custom.min.css" rel="stylesheet" type="text/css" />
    <!-- JQUERY -->
    <script src="../../assets/js/jquery.js"></script>';
include "../functions/functions.php";
echo'
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
        <i class="ri-close-circle-line" onclick="editCoAuthor(this.id)" id="addCoAuthor'.$_POST['flagi'].'" style="font-size: 40px;"></i>
    </div>
</div>';
?>
<script>
    $( document ).ready(function() {
      document.querySelectorAll("[data-choices]").forEach(function (e) {
        var t = {},
          a = e.attributes;
        a["data-choices-groups"] &&
          (t.placeholderValue = "This is a placeholder set in the config"),
          a["data-choices-search-false"] && (t.searchEnabled = !1),
          a["data-choices-search-true"] && (t.searchEnabled = !0),
          a["data-choices-removeItem"] && (t.removeItemButton = !0),
          a["data-choices-sorting-false"] && (t.shouldSort = !1),
          a["data-choices-sorting-true"] && (t.shouldSort = !0),
          a["data-choices-multiple-remove"] && (t.removeItemButton = !0),
          a["data-choices-limit"] &&
            (t.maxItemCount = a["data-choices-limit"].value.toString()),
          a["data-choices-limit"] &&
            (t.maxItemCount = a["data-choices-limit"].value.toString()),
          a["data-choices-editItem-true"] && (t.maxItemCount = !0),
          a["data-choices-editItem-false"] && (t.maxItemCount = !1),
          a["data-choices-text-unique-true"] && (t.duplicateItemsAllowed = !1),
          a["data-choices-text-disabled-true"] && (t.addItems = !1),
          a["data-choices-text-disabled-true"]
            ? new Choices(e, t).disable()
            : new Choices(e, t);
      });
    });
</script>