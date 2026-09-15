<script type="text/javascript">   
    jQuery(document).ready(function($) { 
        $("#id_type").change(function(){
            var id_type	= $("#id_type").val();
            $.ajax({  
                type: "POST",
                url: "include/ajax/get_resources_form.php",
                data: {
                    "id_type"	: id_type
                },
                success: function(msg){  
                    $("#resources_form").html(msg);
                }
            });  
        });
    });
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
</script>