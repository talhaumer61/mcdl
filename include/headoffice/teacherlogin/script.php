<script type="text/javascript">
    $("#id_dept").change(function(){
        var id_dept = $("#id_dept").val();
        $.ajax({  
            type: "POST",
            url: "include/ajax/get_deptemployee.php",
            data: {
                "id_dept"	: id_dept
            },
            success: function(msg){  
                $("#get_deptemployee").html(msg);
            }
        });  
    });
    $("#id_emply").change(function(){
        var id_emply = $("#id_emply").val();
        console.log(id_emply);
        $.ajax({  
            type: "POST",
            url: "include/ajax/get_employeedetail.php",
            data: {
                "id_emply"	: id_emply
            },
            success: function(msg){  
                $("#get_employeedetail").html(msg);
            }
        });  
    });
</script>