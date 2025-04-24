<script>
    $('#articles').hide();
    $('#thesis').hide();
    $('#book').hide();
    function publicationType(ptype){
        if (ptype == 1) {
            $('#articles').show().find("input").prop('disabled', false).find("textarea").prop('disabled', false);

            $('#thesis').hide().find("input").prop('disabled', true).find("textarea").prop('disabled', true);
            $('#book').hide().find("input").prop('disabled', true).find("textarea").prop('disabled', true);
        } else if (ptype == 2) {
            $('#thesis').show().find("input").prop('disabled', false).find("textarea").prop('disabled', false);

            $('#articles').hide().find("input").prop('disabled', true).find("textarea").prop('disabled', true);
            $('#book').hide().find("input").prop('disabled', true).find("textarea").prop('disabled', true);
        } else if (ptype == 3) {
            $('#book').show().find("input").prop('disabled', false).find("textarea").prop('disabled', false);

            $('#articles').hide().find("input").prop('disabled', true).find("textarea").prop('disabled', true);
            $('#thesis').hide().find("input").prop('disabled', true).find("textarea").prop('disabled', true);
        } else {
            $('#articles').hide().find("input").prop('disabled', true).find("textarea").prop('disabled', true);
            $('#thesis').hide().find("input").prop('disabled', true).find("textarea").prop('disabled', true);
            $('#book').hide().find("input").prop('disabled', true).find("textarea").prop('disabled', true);
        }
    }
    var i = 0;
    function addCoAuthor() {
        i++;
        var rowCoAuthor = $('#rowCoAuthor');
        // var newCoAuthor = rowCoAuthor.clone();
        // newCoAuthor.find('select').attr('id'    , 'affiliation'+i);

        // newCoAuthor.find('input').val('');
        // newCoAuthor.find('option:selected').removeAttr('selected');


        // newCoAuthor.find('i').attr('class'      , 'ri-close-circle-line');
        // newCoAuthor.find('i').attr('onclick'    , 'editCoAuthor(this.id)');
        // newCoAuthor.find('i').attr('name'       , 'editCoAuthorId'+i);
        // newCoAuthor.find('i').attr('id'         , 'editCoAuthorId'+i);
        // rowCoAuthor.after(newCoAuthor);
        $.ajax({
            type    : "POST",
            url     : "include/ajax/get_co_author.php",
            data    : { 'flagi' : i },
            success: function (response) {
                rowCoAuthor.after(response);
            }
        });
    }
    function editCoAuthor(id) {
        var rowCoAuthor = $('#'+id);
        rowCoAuthor = rowCoAuthor.parent().parent();
        rowCoAuthor.html('');
    }
</script>