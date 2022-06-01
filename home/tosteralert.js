    function Isactivebtn(id,status1,action1){
        var setstatus = status1;
        swal({ 
            title: "Alert !", 
            text: "Are you sure, You want Change it.", 
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
                            swal("Congratulations!", "Your Status has been changed.", "success");
                            
                            if(setstatus != 0)
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

    function terminateAgreement(id,cStat,frm,to)
    {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; //January is 0!
        var yyyy = today.getFullYear();
        if (dd < 10) {
           dd = '0' + dd;
        }
        if (mm < 10) {
           mm = '0' + mm;
        } 
        today = yyyy + '-' + mm + '-' + dd;
        var htm = "<h3>Are you sure, You want to terminate this agreement? <br>If Yes!!!  Please select the date of termination.</h3><input type='date' id='terminateDate' class='form-input' onkeydown='return false' min='"+frm+"' max='"+today+"' value=''>";

         swal({ 
                title: "Alert !", 
                text: "", 
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Yes, Terminate Now!",   
                closeOnConfirm: false,
                html: htm,
                preConfirm: function (email) {if($("#terminateDate").val()==""){myAlert("Error@#@Please select termination date!!!@#@danger");return false;}},
            }).then((result) => {
                if(result.value)
                {
                    var oldtermdate = to;
                    var termdate = $("#terminateDate").val();
                    $.ajax({
                        type: "POST",
                        url: "InsertData1.php",
                        data: {action : "terminateAgreement", id: id, termdate: termdate,oldtermdate:oldtermdate},
                        dataType: 'json',
                        success: function(data) {
                            if(data.status == 1)
                            {
                                swal("Confirmation!", "Agrement has been terminated.", "success");
                                document.getElementById(id+"-td").innerHTML = "<button type='button' class='btn btn-danger isactivebtn' disabled>Terminated</button>";
                                
                                
                            }
                        }
                    });
                }
            });
    }