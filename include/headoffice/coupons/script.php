<script>
document.querySelectorAll('[id^="ckeditor"]').forEach(function(element) {
    CKEDITOR.replace(element);
});

document.getElementById('generate_coupon').addEventListener('click', function() {
    $.ajax({
        url: 'include/ajax/get_coupon_code.php',
        type: 'POST',
        success: function(response) {
            $('#cpn_code').val(response);
        }
    });
});
</script>