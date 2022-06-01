    function Isactivebtn(id,status1,action1){
        swal({ 
            title: "Account Activation Successful!!!", 
            text: "Congratulations!!! Your account has been activated successfully.", 
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Yes, change it!",   
            closeOnConfirm: false
        }).then((result) => {
            if(result.value)
            {
                $.ajax({
                    type: "POST",
                    url: "loaddata.php",
                    data: {action : action1, id: id , status: status1},
                    dataType: 'json',
                    success: function(data) {
                        if(data.status == 1)
                        {
                            swal("Changed!", "Your Status has been changed.", "success");
                            
                            if(status1)
                            {
                                document.getElementById(id+"-td").innerHTML = "<button type='button' class='btn btn-success isactivebtn' onclick=\"Isactivebtn("+id+","+0+",'"+action1+"');\">Active</button>";
                            }
                            else
                            {
                                document.getElementById(id+"-td").innerHTML = "<button type='button' class='btn btn-danger isactivebtn' onclick=\"Isactivebtn("+id+","+1+",'"+action1+"');\">Inactive</button>";
                            }
                            
                        }
                    }
                });
            }
        });
    }