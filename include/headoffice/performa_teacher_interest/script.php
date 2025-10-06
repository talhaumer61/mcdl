<script>
document.getElementById('type').addEventListener('change', function() {
    var type = $('#type').val();
    if (type == 3) {
        $('#multipleCh').attr("style","display: block;");
    } else {
        $('#multipleCh').attr("style","display: none;");
    }
});
</script>