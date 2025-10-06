<script>
    document.getElementById('resultcard').addEventListener('change', function() {
        var maxFileSize = 1 * 1024 * 1024;
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