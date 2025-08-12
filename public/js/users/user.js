$(function(){
    var form = $('#userForm');
    $(document).on("keydown", form, function(event) {
        return event.key != "Enter";
    });
    $("#saveBtn").on("click", function(event) {
        var roleId = document.getElementById('roleId');
        if(!roleId.value){
            swal.fire({
                title:"No Role Seleted",
                text:'The user with no roles will be disabled',
                type: 'warning',
                showCancelButton: true,
            }).then((result)=>{
                if (result.value){
                    form.submit();
                }
            });
        }else{
            form.submit();
        }
    });
});
