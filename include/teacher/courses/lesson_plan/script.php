<script>
    document.getElementById('fileInput').addEventListener('change', function() {
        var maxFileSize = 5 * 1024 * 1024;
        var errorMessage = document.getElementById('errorMessage');
        var selectedFile = this.files[0];

        if (selectedFile && selectedFile.size > maxFileSize) {
            errorMessage.style.display = 'block';
            this.value = '';
        } else {
            errorMessage.style.display = 'none';
        }
    });
    var i = <?= ($COURSES_DOWNLOADS?count($COURSES_DOWNLOADS):0); ?>;
    function addResource() {
        i++;
        var rowResource = $('#rowResource');
        $.ajax({
            type    : "POST",
            url     : "include/ajax/get_Resource.php",
            data    : { 'flagi' : i },
            success: function (response) {
                rowResource.after(response);
            }
        });
    }
    function editResource(id) {
        var rowResource = $('#'+id);
        rowResource = rowResource.parent().parent();
        rowResource.html('');
    }
</script>