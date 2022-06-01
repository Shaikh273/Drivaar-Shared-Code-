
var current_fs, next_fs, previous_fs; //fieldsets
var left, opacity, scale; //fieldset properties which we will animate
var animating; //flag to prevent quick multi-click glitches

$(".next").click(function(){
        var cid = $(this).attr('id');
        var clsname = cid.split("-")[1];
        var cls = document.getElementsByClassName(clsname);
        var cnt = cls.length;
        var i=0;
        var flg = 1;
        for(i=0;i<cnt;i++)
        {
            if(!cls[i].checkValidity())
            {
                flg=0;
                document.getElementById("chkrequire").innerHTML="<h1 style='color: red;'>* Oops! Some fields are required !!!!</h1>";
                $("."+clsname).css('border', 'solid 1px red'); 
            }
        }
        if(flg == 1)
        {
            
            if(clsname == "step_0")
            {
                var od = $('#odometer').val();
                var id = $('#vid').val();
                
                $.ajax({
                        url: "InsertData.php", 
                        type: "POST", 
                        dataType:"JSON",            
                        data: {
                            'action':'msformOdometer',
                             'odometer': od,
                             'vid': id,
                        },      
                        success: function(data) {
                            if(data.status==1)
                            {
                                myAlert(data.title + "@#@" + data.message + "@#@success" + data.date + "@#@");
                                var timedate = data.date;
                                var qvt = document.getElementsByClassName("qvt");
                                var i = 0;
                                for(i=0;i<qvt.length;i++){
                                    qvt[i].value = qvt[i].value+data.date;
                                }
                                var oid = document.getElementsByClassName("oid");
                                var i = 0;
                                for(i=0;i<qvt.length;i++){
                                    oid[i].value = oid[i].value+data.date;
                                }
                                
                                
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
                var id = $('#vid').val();
                
                var selectedVal = "";
                var selected = $("input[type='radio'][class='"+clsname+" swt']:checked");
                selectedVal = selected.val();

                var remarkInput = $("textarea[name='remark'][id='remark'][class='form-control "+clsname+"']");
                remark = remarkInput.val(); 
                
                var quesInput = $("input[type='hidden'][name='question_id'][class='"+clsname+"']");    
                var que_id = quesInput.val();
                
                var oinsert_date = $("input[type='hidden'][name='odometerInsert_date'][class='"+clsname+" oid']");    
                var oid = oinsert_date.val();

                var qvtInput = $("input[type='hidden'][name='qvtId'][class='"+clsname+" qvt']"); 
                var qvtId =qvtInput.val();

                   
                var fd = new FormData();
                if(selectedVal == 0){
                  var files = $("#file_"+clsname)[0].files; 
                  fd.append('file',files[0]);
                }
                else{

                }
                    
                fd.append('vid',id);
                fd.append('answer_type',selectedVal);
                fd.append('action','msformremark');
                fd.append('remark',remark);
                fd.append('question_id',que_id);
                fd.append('queVehclIdtime',qvtId);
                fd.append('oid',oid);
                   
                $.ajax({
                    url: "InsertData.php", 
                    type: "POST", 
                    dataType:"JSON",            
                    data: fd,   
                    contentType: false,
                    processData: false,

                    success: function(data) {
                        if(data.status==1)
                        {
                            myAlert(data.title + "@#@" + data.message + "@#@success");
                            var date = data.date;
                        }
                        else
                        {
                            myAlert(data.title + "@#@" + data.message + "@#@danger");
                        }
                    }
                });

            }    
            
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
            easing: 'easeInOutBack'
            });
        }
});

$(".previous").click(function(){
	if(animating) return false;
	animating = true;
	
	current_fs = $(this).parent();
	previous_fs = $(this).parent().prev();
	
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
		easing: 'easeInOutBack'
	});
});

$(".submit").click(function(){
	return false;
})


