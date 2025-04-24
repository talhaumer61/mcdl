
    
<script>
document.querySelectorAll('[id^="ckeditor"]').forEach(function(element) {
    CKEDITOR.replace(element);
});
document.addEventListener("DOMContentLoaded", function() {
    var deleteButtons = document.querySelectorAll(".delete-button");
    deleteButtons.forEach(function(button) {
        button.addEventListener("click", function() {
            var index = parseInt(button.getAttribute("data-index"));
            var row = button.closest(".row");
            row.remove();
        });
    });
});
    
document.getElementById("duplicateButton").addEventListener("click", function() {
    // alert("he");
    event.preventDefault();
    // Create clone
    var what_you_work_div = document.getElementById("what_you_work_div");
    var clonedDiv = what_you_work_div.cloneNode(true);

    // Reset input values in the cloned div
    var clonedInput = clonedDiv.querySelector("input[name=\'what_you_learn[]']");
    clonedInput.value = ''; // Clear the input value

    // Add delete button to the cloned div
    var deleteButton = clonedDiv.querySelector(".delete-button");
    deleteButton.style.display = "inline-block"; // Show delete button
    deleteButton.disabled = false; // Enable the delete button
    deleteButton.addEventListener("click", function() {
        clonedDiv.remove(); // Remove the cloned div when delete button is clicked
    });

    var targetDiv = document.getElementById('targetDiv');
    targetDiv.appendChild(clonedDiv);
});
</script>