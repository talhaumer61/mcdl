<script>
	document.getElementById('org_status').addEventListener('change', function() {
        const passwordDiv = document.getElementById('show_password');
        const already_approved = document.getElementById('already_approved').value;
        if (this.value === '1' && already_approved !== '1') {
            passwordDiv.innerHTML = `
				<label class="form-label">Password <span class="text-danger">*</span></label>
				<input type="text" name="adm_userpass" class="form-control" required="">
            `;
        } else {
            passwordDiv.innerHTML = ''; // Clear the password field
        }
    });

	// GET SKILL AMBASSADOR - TYPE ORG
	$(document).ready(function() {
		$('#org_type').change(function() { 
			var org_type = $(this).val();
			var edit_id = <?=(LMS_EDIT_ID ? LMS_EDIT_ID : '0')?>;
			$.ajax({
				type: "POST",
				url: "include/ajax/get_skill_ambassador.php",
				data: { 
					 org_type	: org_type
					,edit_id	: edit_id
					,method		: "_GET_SKILL_AMBASSADOR"
				},
				success: function(response) {
					$("#get_skill_ambassador").html(response);
				}
			});
		});
	});

	// CHECK USERNAME AVAILABLE
	$("input[name=\'adm_username\']").on("input",function () {
		if(this.value.length >= 5){
			$.ajax({
				url: "include/ajax/check_username.php", 
				type: "POST",
				data : {username : this.value},
				dataType : "json",
				success: function(response) {
					if (response.status == "success") {
						$("#username_error").html("<span class=\'text-danger\'>User Name already exsist</span>");
					} else {
						$("#username_error").html("<span class=\'text-success\'>User Name available</span>");
					}
				}
			});
		}else{
			$("#username_error").html("<span class=\'text-danger\'>User Name should be Greater than 5 characters</span>");
		}
	});

	// CHECK EMAIL AVAILABLE
	$("input[name=\'org_email\']").on("input",function () {
		if(this.value.length >= 5){
			const rep = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			if (rep.test(String(this.value).toLowerCase())) {
				$.ajax({
					url: "include/ajax/check_username.php", 
					type: "POST",
					data : {email : this.value},
					dataType : "json",
					success: function(response) {
						if(response.status == "success") {
							$("#email_error").html("<span class=\'text-danger\'>Email already exsist</span>");
						} else {
							$("#email_error").html("<span class=\'text-success\'>Email available</span>");
						}
					}
				});
			} else {
				$("#email_error").html("<span class=\'text-danger\'>Enter a valid email</span>");
			}
		}else{
			$("#email_error").html("<span class=\'text-danger\'>Email should be Greater than 5 characters</span>");
		}
	});

	// ORG PERCENTAGE
	const org_percentage = document.getElementById("org_percentage");
	org_percentage.addEventListener("input", function () {
		const value = parseInt(this.value);

		// Enforce the maximum and minimum value during input
		if (value > 25) {
			this.value = 25; // Set the value to 25 if it exceeds the maximum
		} else if (value < 1) {
			this.value = 1; // Set the value to 1 if it goes below the minimum
		}
	});

	// ORG PROFIT PERCENTAGE
	const org_profit_percentage = document.getElementById("org_profit_percentage");
	org_profit_percentage.addEventListener("input", function () {
		const value = parseInt(this.value);

		// Enforce the maximum and minimum value during input
		if (value > 20) {
			this.value = 20; // Set the value to 20 if it exceeds the maximum
		} else if (value < 1) {
			this.value = 1; // Set the value to 1 if it goes below the minimum
		}
	});
</script>