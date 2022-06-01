var current_fs, next_fs, previous_fs;
var left, opacity, scale;
var animating;

$(".next").click(function(){
    var cid = $(this).attr('id');
    var clsname = cid.split("-")[1];
    var cls = document.getElementsByClassName(clsname);
    var cnt = cls.length;
    var i=0;
    var flg = 1;
    var flg123 = 0;
    if(clsname != 'step_0')
    {
        for(i=0;i<cnt;i++)
        {
            if(!cls[i].checkValidity())
            {
                flg=0;
                document.getElementById("chkrequire").innerHTML="<h1 style='color: red;'>* Oops! Some fields are required !!!!</h1>";
                $(cls[i]).css('border', 'solid 1px red'); 
            }else{
              flg=1;  
            }
        }
    }else{
        flg=1;  
    }
        if(flg == 1)
        { 
            if(clsname == "step_0")
            {
                var driver = $('#driver').val();
                var id = $('#userid').val();
                var depoTID = $('#depotList').val();
                
                $.ajax({
                    url: "InsertData.php", 
                    type: "POST", 
                    dataType:"JSON",            
                    data: {
                         'action':'msformrentalagreement',
                         'driver': driver,
                         'userid': id,
                         'dpid' : depoTID
                    },      
                    success: function(data) {
                        if(data.status==1)
                        {
                            myAlert(data.title + "@#@" + data.message + "@#@success" + data.id);
                            var rentalid = data.id;
                            document.getElementById('last_insert_id').value = rentalid;
                            document.getElementById('signcanvas').value = rentalid;
                            document.getElementById('signcanvas2').value = rentalid;
                            document.getElementById('vehicle').innerHTML = data.vehList;
                            getPricePerDay();
                            // $('#last_insert_id').val(rentalid);
                            // $('#signcanvas').val(rentalid);
                            // $('#signcanvas1').val(rentalid);
                        }
                        else
                        {
                            myAlert(data.title + "@#@" + data.message + "@#@danger");

                            flg123=1;
                        }
                    }
                });
                    
            }   
         
            else
            {
                // var stp = 0;
                var last_id = document.getElementById('last_insert_id').value;//$('#last_insert_id').val();
                var values = "";
                values = '{"action":"msformrentalagreementstep2","last_insert_id":"'+last_id+'"';
                var i=1;
                var st2flg=1;
            
                if(clsname == "step_4")
                {
                    var last_id = $('#last_insert_id').val();  
                    var form_data = new FormData();
    
                  var totalfiles = document.getElementById('image').files.length;
                  for (var index = 0; index < totalfiles; index++) {
                      form_data.append("files[]", document.getElementById('image').files[index]);
                  }
                  form_data.append("last_insert_id",last_id);
                  form_data.append("action","vehiclerentalaggreementimage");
                  $.ajax({
                     url: 'InsertData.php', 
                     type: 'post',
                     data: form_data,
                     dataType: 'json',
                     contentType: false,
                     processData: false,
                     success: function (response) {
                
                      for(var index = 0; index < response.length; index++) {
                        var src = response[index];
                        $('#image').append('<img src="'+src+'" width="200px;" height="200px">');
                      }
                
                     }
                  });                   
                }
                
                else if(clsname == "step_5")
                {
                    var sigpad= $("#signature64").val();
                    var last_insert_id = $('#signcanvas').val();
                    $.ajax({
                        url: "InsertData.php", 
                        type: "POST", 
                        async:false,
                        data: {
                            'action':'rentalaggreementsignature',
                            'last_insert_id':last_insert_id,
                            'signed': sigpad,
                        }, 
                        dataType:"JSON",            
                        success: function(result) {
                            if(result.status==1)
                            {
                                $('#status').val(data.status);
                                flg123 = 1;
                            }
                            else
                            {
                                myAlert(result.title + "@#@" + result.message + "@#@danger");
                            }
                        }
                    });
                }
                
                else if(clsname == "step_6")
                {
                    var sigpad= $("#signature642").val();
                    var last_insert_id = $('#signcanvas2').val();

                    $.ajax({
                        url: "InsertData.php", 
                        type: "POST", 
                        data: {
                            'action':'rentalaggreementsignature2',
                            'last_insert_id':last_insert_id,
                            'signed': sigpad,
                        }, 
                        dataType:"JSON",            
                        success: function(result) {
                            if(result.status==1)
                            {
                                $('#status').val(data.status);
                                flg123 = 1;
                            }
                            else
                            {
                                 myAlert(result.title + "@#@" + result.message + "@#@danger");
                            }
                        }
                    });
                }
                
                else if(clsname == "step_8")
                {
                    var selectedVal = "";
                    var selected = $("input[type='radio'][name='ins_cmp_type']:checked");
                    selectedVal = selected.val();
                    
                    $.ajax({
                        url: "InsertData.php", 
                        type: "POST", 
                        dataType:"JSON",            
                        data: {
                         'action':'rentalinsuranceupdate',
                         'insurance_type': selectedVal,
                         'last_insert_id':last_id
                        },
                        success: function(data) {
                            if(data.status==1)
                            {
                                myAlert(data.title + "@#@" + data.message + "@#@success");
                                
                            }
                            else
                            {
                                myAlert(data.title + "@#@" + data.message + "@#@danger");
                            }
                        }
                    });
                    
                }
              
           
                else
                {
                    var stpno = clsname.split('_')[1];
                    values += ',"step":"'+stpno+'"';
                    $('.'+clsname).each(function(){
                    values += ',"'+this.name+'":"'+this.value+'"';
                    i++;
                    });
                    values += "}";
                    $.ajax({
                        url: "InsertData.php", 
                        type: "POST", 
                        dataType:"JSON", 
                        async: false,           
                        data: JSON.parse(values),
                        success: function(data) {
                            if(data.end==1)
                            {
                                location.reload();
                            }
                            if(data.status==1)
                            {
                                myAlert(data.title + "@#@" + data.message + "@#@success");
                                $('#veh_reg').text(data.vehicle_reg_no);
                                $('#veh_make').text(data.make_id);
                                $('#veh_model').text(data.model_id);
                            }
                            else
                            {

                                flg123 = 1;
                                myAlert(data.title + "@#@" + data.message + "@#@danger");
                            }
                        }
                    });
                }
            }    
            
       
            
        if(flg123 == 0){
            
            document.getElementById("chkrequire").innerHTML="";
            $("."+clsname).css('border', 'solid 1px black'); 
            
            if(animating) return false;
            animating = true;
           
            
            current_fs = $(this).parent();
            next_fs = $(this).parent().next();
            
            next_fs.show(); 
            current_fs.animate({opacity: 0}, {
            step: function(now, mx) {
            
            scale = 1 - (1 - now) * 0.2;
            left = (now * 50)+"%";
            opacity = 1 - now;
            current_fs.css({'transform': 'scale('+scale+')'});
            next_fs.css({'left': left, 'opacity': opacity});
            }, 
            duration: 800, 
            complete: function(){
            current_fs.hide();
            animating = false;
            }, 
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
            });
        }

     }
        
});

$(".previous").click(function(){
    if(animating) return false;
    animating = true;

    current_fs = $(this).parent();
    previous_fs = $(this).parent().prev();

    $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

    previous_fs.show(); 
    current_fs.animate({opacity: 0}, {
        step: function(now, mx) {
          
            scale = 0.8 + (1 - now) * 0.2;
            left = ((1-now) * 50)+"%";
            opacity = 1 - now;
            current_fs.css({'left': left});
            previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
        }, 
        duration: 800, 
        complete: function(){
            current_fs.hide();
            animating = false;
        }, 
        //this comes from the custom easing plugin
        easing: 'easeInOutBack'
    });
  // stp--;
});

$(".submit").click(function(){
    return false;
})
