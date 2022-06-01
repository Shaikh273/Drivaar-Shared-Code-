<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['load']) || !isset($_SESSION['uid']))
{
    header("location: login.php");
}
$_SESSION['page_id']=5;
//include('authentication.php');
include('config.php');
$page_title="New Taaka Entry";



?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=1024">
        <title><?php echo $page_title;?></title>
        <?php include('head.php');?>
        
    </head>
    <body class="skin-default-dark fixed-layout">
        <?php
            include('loader.php');
        ?>
        <div id="main-wrapper">
            <?php
                include('header.php');
                include('menu.php');
            ?>
            <div class="page-wrapper">
                <div class="container-fluid">
                    <div class="row page-titles">
                        <div class="col-md-5 align-self-center">
                            <h4 class="text-themecolor">New Taaka Entry</h4>
                        </div>
                        <!--<div class="col-md-7 align-self-center text-right">-->
                        <!--    <div class="d-flex justify-content-end align-items-center">-->
                                <!--<ol class="breadcrumb">-->
                                <!--    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>-->
                                <!--    <li class="breadcrumb-item active">Dashboard</li>-->
                                <!--</ol>-->
                                <!--<button type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Create New</button>-->
                        <!--    </div>-->
                        <!--</div>-->
                    </div>
                    
                    
                    
                    <!--Main content-->
                    <!--<iframe scrolling src="https://usdemo.livebox.co.in/lbmeeting/?key=416f5ff096e0ef425a8deac9269579e6" width="1000px" height="1000px" allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen> </iframe>-->
                    
                    
                    <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <!--<div class="card-header bg-info">-->
                            <!--    <h4 class="m-b-0 text-white">Registration Form</h4>-->
                            <!--</div>-->
                            <div class="card-body">
                                    <div class="form-body">
                                        <form action="#" method="post" id="form12"onsubmit="return mySubmitFunction(event)">
                                        <div class="row p-t-20">
                                            <div class="col-md-1">
                                                <div class="form-group has-success">
                                                    <label class="control-label">Taaka ID</label>
                                                    <input type="text" id="tId" name="tId" class="form-control" placeholder="ID" required style="padding-left: 6px;padding-right: 6px;" onkeypress="return isNumberKey1(event)" >
                                                    </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-1">
                                                <div class="form-group has-success">
                                                    <label class="control-label">Mach No</label>
                                                    <select class="form-control custom-select" name="machno" id="machno" required>
                                                        <option value=""></option>
                                                        <?php
                                                        $i=1;
                                                            while($i<83)
                                                            {
                                                                echo "<option value='$i'>$i</option>";
                                                                $i++;
                                                            }
                                                        ?>
                                                    </select>
                                                    </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group has-success">
                                                    <label class="control-label">Meter</label>
                                                    <input type="text" id="mtr" name="mtr" class="form-control" placeholder="Meter" required onkeypress="return isNumberKey(event,'mtr')" >
                                                    </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group has-success">
                                                    <label class="control-label">Weight</label>
                                                    <input type="text" id="wgt" name="wgt" class="form-control" placeholder="Weight" required onkeypress="return isNumberKey(event,'wgt')" >
                                                    </div>
                                            </div>
                                        
                                            <div class="col-md-2">
                                                <div class="form-group has-success">
                                                    <label class="control-label">Avg. Weight</label>
                                                    <input type="text" id="awgt" name="awgt" class="form-control" placeholder="0.0 gm" required disabled>
                                                    <div class="alert alert-danger" role="alert" style="display:block;" id="avgAlert">
                                                      Wrong data!!!
                                                    </div>
                                                    </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group has-success">
                                                    <label class="control-label">Unload Date</label>
                                                    <input type="date" class="form-control" name="undt" id="undt" style="padding-left: 2px;padding-right: 2px;" value="22/07/2020">
                                                </div>
                                            </div>
                                            <script>
                                                var d = new Date();
                                                var month = d.getMonth()+1;
                                                var day = d.getDate();
                                                var output = d.getFullYear() + '-' +(month<10 ? '0' : '') + month + '-' + (day<10 ? '0' : '') + day;
                                                document.getElementById("undt").value = output; 
                                             </script>
                                            <div class="col-md-2 ">
                                                <div style="margin-top: 29px;">
                                                    <button type="submit" class="btn btn-info" ><i class="fa fa-plus-circle"></i> Add Now</button>
                                                </div>
                                            </div>
                                            </div>
                                            </form>
                                            
                                            
                                            <div class="card-header bg-info">
                                                <h4 class="m-b-0 text-white"> New Entries</h4>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 table-responsive">
                                                    <table id="example12" class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Unload Date</th>
                                                                <th>Taaka ID</th>
                                                                <th>Mach No</th>
                                                                <th>Meter</th>
                                                                <th>Weight</th>
                                                                <th>Avg. Weight</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            
                                    </div>
                                    <div class="form-actions">
                                        <a href="taakaDetails.php" class="btn btn-success" name="newReg" > <i class="fa fa-check"></i> Save & Exit</a>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                    
                    <?php
                        include('right_sidebar.php');
                    ?>
                </div>
            </div>
            
            <?php
                include('footer.php');
            ?>
        </div>
        
        <?php
            include('footerScript.php');
        ?>
        <script>
            var giCount = 0;
                function mySubmitFunction(evt)
                {
                    
                    var tid = document.getElementById('tId');
                    var machno = document.getElementById('machno');
                    var mtr = document.getElementById('mtr');
                    var wgt = document.getElementById('wgt');
                    var awgt = document.getElementById('awgt');
                    var undt = document.getElementById('undt');
                     $.ajax({
                          type:"get",
                          url:"ajax_newTaakaLedger.php?tid="+tid.value+"&machno="+machno.value+"&mtr="+mtr.value+"&wgt="+wgt.value+"&undt="+undt.value+"&awgt="+awgt.value,
                          datatype:"text",
                          beforeSend:function(xhr)
                          {
                           $('#myOverlay').show();
                            $('#loadingGIF').show();   
                          },
                          success:function(data)
                          {
                              if(data!="ERROR")
                              {
                                  var cols = data.split("@#@");
                                  giCount++;
                                  $('#example12').dataTable().fnAddData( [
                                        giCount,
                                        cols[0],
                                        cols[1],
                                        cols[2],
                                        cols[3],
                                        cols[4],
                                        cols[5],
                                        cols[6],
                                        ] );
                                  tid.value="";
                                  machno.selectedIndex = "0";
                                  mtr.value="";
                                  wgt.value="";
                                  document.getElementById("awgt").value="0.0 gm";
                                  $.toast({
                                    heading: 'Success!!!',
                                    text: 'New taaka entry added successfuly.',
                                    position: 'top-right',
                                    loaderBg:'#ff6849',
                                    icon: 'success',
                                    hideAfter: 3500, 
                                    stack: 6
                                  });
                                  var nextTID = parseInt(cols[1])+1;
                                  tId.value = nextTID;
                                  document.getElementById("tId").focus();
                              }else
                              {
                                   $.toast({
                                    heading: 'Error!!!',
                                    text: 'Taaka Id already exist!!!',
                                    position: 'top-right',
                                    loaderBg:'#ff6849',
                                    icon: 'error',
                                    hideAfter: 3500
                                  });
                              }
                              $('#myOverlay, #loadingGIF').hide();  
                          }
                        });
                  return false;
                }
               function isNumberKey(evt,eid)
               {
                  var charCode = (evt.which) ? evt.which : evt.keyCode;
                  var eid1 = document.getElementById(eid).value;
                  if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
                     return false;
                  if((eid1.length==0 && charCode==46) || (eid1.match(/\./g)!=null && (eid1.match(/\./g).length)==1 && charCode==46))
                    return false
                    
                  var avg="0.0";
                  if(eid=='wgt')
                  {
                      var lmtr = document.getElementById('mtr').value;
                      if(lmtr!="" || lmtr!=0)
                      {
                          if(charCode!=46)
                            eid1 = eid1 + (charCode%48);
                            
                          avg = parseFloat(((eid1/lmtr)*1000).toPrecision(3)); 
                      }
                  }
                  
                  if(eid=='mtr')
                  {
                      var lwgt = document.getElementById('wgt').value;
                      if(lwgt!="" || lwgt!=0)
                      {
                          if(charCode!=46)
                            eid1 = eid1 + (charCode%48);
                            
                         avg = parseFloat(((lwgt/eid1)*1000).toPrecision(3)); 
                      }
                  }
                  document.getElementById("awgt").value=avg+" gm";
                  if(parseFloat(avg)<parseFloat(64) || parseFloat(avg)>parseFloat(65))
                  {
                      document.getElementById("avgAlert").style.display="block";
                  }else
                  {
                      document.getElementById("avgAlert").style.display="none";
                  }
                  
                  
                  
                  return true;
               }
               
               
                function isNumberKey1(evt)
               {
                  var charCode = (evt.which) ? evt.which : evt.keyCode;
                  if (charCode > 31 && (charCode < 48 || charCode > 57))
                     return false;
        
                  return true;
               }
               function delNTE(delID)
               {
                   alert(delID);
               }
               
            </script>       
            
            
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1">New message</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Recipient:</label>
                        <input type="text" class="form-control" id="recipient-name1">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="control-label">Message:</label>
                        <textarea class="form-control" id="message-text1"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Send message</button>
            </div>
        </div>
    </div>
</div>
    </body>

</html>