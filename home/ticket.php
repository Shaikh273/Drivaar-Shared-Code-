<?php
include 'DB/config.php';
if (!isset($_SESSION)) {
    session_start();
    // $userid = $_SESSION['userid'];
}
$page_id = '144';
if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
{
  $userid = $_SESSION['userid']; 
        if($userid==1)
        {
          $uid='%';
        }
        else
        {
          $uid=$userid;
        }  
}
else
{
    if((isset($_SESSION['adt']) && $_SESSION['adt']==0))
    {
       header("location: userlogin.php");
    }
    else
    {
       header("location: login.php");  
    }
}
include('config.php');
$page_title="Dashboard";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=1024">
    <title><?php echo $page_title;?></title>
    <script src="js/jquery.js"></script>
    <script src="js/timeline.min.js"></script>
    <link rel="stylesheet" href="css/timeline.min.css" />
    <?php include('head.php');?>

    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
    <link rel="stylesheet" href="countrycode/build/css/demo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="dist/jq.dice-menu.min.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        body {
    background-color: #f9f9fa
}

@media (min-width:992px) {
    .page-container {
        max-width: 1140px;
        margin: 0 auto
    }

    .page-sidenav {
        display: block !important
    }
}

.padding {
    padding: 2rem
}

.w-32 {
    width: 32px !important;
    height: 32px !important;
    font-size: .85em
}

.tl-item .avatar {
    z-index: 2
}

.circle {
    border-radius: 500px
}

.gd-warning {
    color: #fff;
    border: none;
    background: #f4c414 linear-gradient(45deg, #f4c414, #f45414)
}

.timeline {
    position: relative;
    border-color: rgba(160, 175, 185, .15);
    padding: 0;
    margin: 0
}

.p-4 {
    padding: 1.5rem !important
}

.block,
.card {
    background: #fff;
    border-width: 0;
    border-radius: .25rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
    margin-bottom: 1.5rem
}

.mb-4,
.my-4 {
    margin-bottom: 1.5rem !important
}

.tl-item {
    border-radius: 3px;
    position: relative;
    display: -ms-flexbox;
    display: flex
}

.tl-item>* {
    padding: 10px
}

.tl-item .avatar {
    z-index: 2
}

.tl-item:last-child .tl-dot:after {
    display: none
}

.tl-item.active .tl-dot:before {
    border-color: #448bff;
    box-shadow: 0 0 0 4px rgba(68, 139, 255, .2)
}

.tl-item:last-child .tl-dot:after {
    display: none
}

.tl-item.active .tl-dot:before {
    border-color: #448bff;
    box-shadow: 0 0 0 4px rgba(68, 139, 255, .2)
}

.tl-dot {
    position: relative;
    border-color: rgba(160, 175, 185, .15)
}

.tl-dot:after,
.tl-dot:before {
    content: '';
    position: absolute;
    border-color: inherit;
    border-width: 2px;
    border-style: solid;
    border-radius: 50%;
    width: 10px;
    height: 10px;
    top: 15px;
    left: 50%;
    transform: translateX(-50%)
}

.tl-dot:after {
    width: 0;
    height: auto;
    top: 25px;
    bottom: -15px;
    border-right-width: 0;
    border-top-width: 0;
    border-bottom-width: 0;
    border-radius: 0
}

tl-item.active .tl-dot:before {
    border-color: #448bff;
    box-shadow: 0 0 0 4px rgba(68, 139, 255, .2)
}

.tl-dot {
    position: relative;
    border-color: rgba(160, 175, 185, .15)
}

.tl-dot:after,
.tl-dot:before {
    content: '';
    position: absolute;
    border-color: inherit;
    border-width: 2px;
    border-style: solid;
    border-radius: 50%;
    width: 10px;
    height: 10px;
    top: 15px;
    left: 50%;
    transform: translateX(-50%)
}

.tl-dot:after {
    width: 0;
    height: auto;
    top: 25px;
    bottom: -15px;
    border-right-width: 0;
    border-top-width: 0;
    border-bottom-width: 0;
    border-radius: 0
}

.tl-content p:last-child {
    margin-bottom: 0
}

.tl-date {
    font-size: .85em;
    margin-top: 2px;
    min-width: 100px;
    max-width: 100px
}

.avatar {
    position: relative;
    line-height: 1;
    border-radius: 500px;
    white-space: nowrap;
    font-weight: 700;
    border-radius: 100%;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-pack: center;
    justify-content: center;
    -ms-flex-align: center;
    align-items: center;
    -ms-flex-negative: 0;
    flex-shrink: 0;
    border-radius: 500px;
    box-shadow: 0 5px 10px 0 rgba(50, 50, 50, .15)
}

.b-warning {
    border-color: #f4c414 !important
}

.b-primary {
    border-color: #448bff !important
}

.b-danger {
    border-color: #f54394 !important
}
    </style>
</head>
    <body class="skin-default-dark fixed-layout">
        
        <?php include('loader.php');?>
        
        <div id="main-wrapper" id="top">
            <?php include('header.php');?>
           
            <div class="page-wrapper content" id="top" >
                <div class="container-fluid">
                 <main class="container-fluid  animated">
                    <div class="row">
                    <input type="hidden" name="status" id="status" value="%">
                    <div class="col-lg-3 col-md-6">
                        <div class="card bg-dark text-white">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="m-l-10 align-self-center text-center">
                                        <?php
                                            $mysql = new Mysql();
                                            $mysql -> dbConnect();
                                            $exquery = "SELECT count(*) as total FROM `tbl_ticketstatus` WHERE `isdelete`=0";
                                            $exrow =  $mysql -> selectFreeRun($exquery);
                                            $fetch = mysqli_fetch_array($exrow);
                                        ?>
                                        <div class="ticketshow" onclick="loadtable('total');"><h3 class="m-b-0 text-xl"><?php echo $fetch['total'];?></h3></div>
                                        <h5 class="m-b-0 text-center text-xl">Total Tickets</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                    <div class="col-lg-3 col-md-6">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="m-l-10 align-self-center text-center">
                                        <?php
                                            $exquery = "SELECT count(*) as statustotal FROM `tbl_ticketstatus` WHERE `isdelete`=0 AND status=1";
                                            $exrow =  $mysql -> selectFreeRun($exquery);
                                            $fetch = mysqli_fetch_array($exrow);
                                        ?>
                                        <div class="ticketshow" onclick="ticketshow('1');"><h3 class="m-b-0 text-xl"><?php echo $fetch['statustotal'];?></h3></div>
                                        <h5 class="m-b-0 text-center text-xl">Process</h5></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="m-l-10 align-self-center text-center">
                                        <?php
                                            $exquery = "SELECT count(*) as escolet FROM `tbl_ticketstatus` WHERE `isdelete`=0 AND `status`=2";
                                            $exrow =  $mysql -> selectFreeRun($exquery);
                                            $fetch = mysqli_fetch_array($exrow);
                                        ?>
                                        <div class="ticketshow" onclick="ticketshow('2');"><h3 class="m-b-0 text-xl " id="escolet"><?php echo $fetch['escolet'];?></h3></div>
                                        <h5 class="m-b-0 text-center text-xl">ESCALATE</h5></div>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                    <div class="col-lg-3 col-md-6">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="m-l-10 align-self-center text-center">
                                        <?php
                                            $exquery = "SELECT count(*) as closetotal FROM `tbl_ticketstatus` WHERE `isdelete`=0 AND `status`=3";
                                            $exrow =  $mysql -> selectFreeRun($exquery);
                                            $fetch = mysqli_fetch_array($exrow);
                                        ?>
                                        <div class="ticketshow" onclick="ticketshow('3');"><h3 class="m-b-0 text-xl " id="closetotal"><?php echo $fetch['closetotal'];?></h3></div>
                                        <h5 class="m-b-0 text-center text-xl">Close</h5></div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Column -->
                </div>

                    <div class="card"> 

                    <div class="card-body" style="background-color: white;" id="">
                        <div class="modal fade" id="editstatus" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content" style="width: 120%;">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Edit Ticket Status</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body" id="modelBoby">
                                        <form method="post" name="updatestatus" id="updatestatus" action="">
                                            <input type="hidden" name="ticketid" id="ticketid">
                                          <fieldset class="form-group row border p-2">
                                          <legend class="col-form-legend col-sm-4 text-center">Select Status</legend>
                                          <div class="col-sm-12">
                                            <div class="form-check">

                                                <label class="form-check-label">
                                                <input class="form-check-input radio-inline" type="radio" style="height: 15px; width: 15px;" name="ticketstatus" id="processing" value="1">
                                                Processing</label>

                                              <label class="form-check-label pl-4">
                                                <input class="form-check-input radio-inline" type="radio" style="height: 15px; width: 15px;" name="ticketstatus" id="escolet" value="2">
                                                Escolet</label>
                                                
                                                <label class="form-check-label pl-4">
                                                <input class="form-check-input" type="radio" name="ticketstatus" style="height: 15px; width: 15px;" id="clos" value="3">
                                                Close</label>
                                            </div>
                                            
                                            <div class="row mt-2">

                                                <div class="1 box col-md-12" id="processing_box">
                                                    <div class="row">
                                                        <label>Commet</label>
                                                        <textarea type="" name="commet_processing" id="commet_processing" class="form-control" rows="3"></textarea>
                                                    </div>    
                                                </div>

                                                <div class="col-md-12 desc 2 box" id="escolet_box">
                                                     <input type="hidden" name="ticketdepartment" id="ticketdepartment">
                                                     <input type="hidden" name="ticketrole" id="ticketrole">
                                                        <label>Commet</label>
                                                        <textarea type="" name="commet_escolet" id="commet_escolet" class="form-control" rows="3"></textarea>
                                                    <div class="form-check mt-3">
                                                      <label class="form-check-label">
                                                        <input class="form-check-input radio-inline" type="radio" style="height: 15px; width: 15px;" name="ticketstatusdepartment" id="other" value="other">
                                                        Other Department</label>
                                                        <label class="form-check-label pl-4">
                                                        <input class="form-check-input radio-inline" type="radio" style="height: 15px; width: 15px;" name="ticketstatusdepartment" id="same" value="same">
                                                        Same Department</label>
                                                    </div>
                                                    <div class="other dept col-md-12 mt-3 pl-0" id="other_dept">
                                                        <label>Other Department</label>
                                                        <select class="form-select form-select-lg col-md-12 mb-3 p-2" aria-label=".form-select-lg example" name="department" id="other_department">
                                                        
                                                        </select>
                                                    </div>
                                                    <div class="same dept col-md-12 mt-3 pl-0" id="same_dept">
                                                        <label>Same Department</label>
                                                        <select class="form-select form-select-lg col-md-12 mb-3 p-2" aria-label=".form-select-lg example" name="department" id="same_department">
                                                          
                                                        </select>
                                                    </div>
                                                    
                                                </div>
                                                
                                                <div class="3 box desc col-md-12" id="clos_box">
                                                    <div class="row">
                                                        <label>Commet</label>
                                                        <textarea type="" name="commet_clos" id="commet_clos" class="form-control" rows="3"></textarea>
                                                    </div>     
                                                </div>
                                            </div>
                                            
                                            
                                          </div>
                                        </fieldset>
                                        </form>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" name="savestatus" id="savestatus" onclick="insertstatus();">Save</button> 
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="modal fade" id="viewhistory" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content" style="width: 130%;">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Ticket Status</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body" id="modelBoby">
                                                <input type="hidden" name="ticketid" id="ticketid">
                                                  <div class="col-sm-12">
                                                    <div class="row mt-2">
                                                        <div class="col-md-12 escolet box" id="Ownerbody">
                                                            
                                                               
                                                        </div>
                                                    </div>
                                                  </div>
                                                <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                        <div class="col-md-12 mt-3" id="ViewFormDiv">
                            <div class="table-responsive m-t-40" style="margin-top: 0px;">
                                <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                                    <thead class="default">
                                                <tr role="row">
                
                                                    <th>SR.No</th>
                                                    <th>Ticket ID</th>
                                                    <th>User</th>
                                                    <th>Role</th>
                                                    <th>Department</th>
                                                    <th>Problem</th>
                                                    <th data-orderable="false">Status</th>
                                                    <th data-orderable="false">Action</th>
                                                </tr>
                                            </thead>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>

            </main>
</div>
</div>  
            
            <?php include('footer.php');?>
            
        </div>
<script src="dist/jq.dice-menu.js"></script>

<?php include('footerScript.php'); ?>

<script>
$(document).ready(function(){
   $('#myTable').DataTable({
        'dom': 'Bfrtip',
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'destroy': true,
        'ajax': {
            'url':'loadtabledata1.php',
            'data': function(d) 
            {
                d.action = 'loadCloseticketdata';
                d.status = $('#status').val();
            }
        },
        'columns': [
            { data: 'srno' },
            { data: 'id' },
            { data: 'user' },
            { data: 'role' },
            { data: 'department' },
            { data: 'problem' },
            { data: 'status' },
            { data: 'edit' }
        ]
    });
});

function editstatus(id,department,role)
{
  $('#editstatus').modal('show');  
  var id = id;
  // alert(department);
  $('#ticketid').val(id);
  $('#ticketdepartment').val(department);
  $('#ticketrole').val(role);
  $(".desc").hide();
  $('input[type="radio"]').click(function(){
        var inputValue = $(this).attr("value");
        if(inputValue == "2")
        {
            $(".dept").hide();
            var targetBox = $("." + inputValue);
            $(".box").not(targetBox).hide();
            $(targetBox).show();
            $('input[type="radio"][name="ticketstatusdepartment"]').click(function(){
            var inputValue1 = $(this).attr("value");
            var targetBox1 = $("." + inputValue1);
            $(".dept").not(targetBox1).hide();
            $(targetBox1).show();
            $(targetBox).show();
        });

        }
        else{
        var targetBox = $("." + inputValue);
        $(".box").not(targetBox).hide();
        $(targetBox).show();
        }
 
    });

}

$('#other').click(function(){
  var ticketid = $('#ticketid').val();
  var department = $('#ticketdepartment').val();
  // alert(department);
  var role = $('#ticketrole').val();

  $.ajax({
            type: "POST",
            url: "loaddata1.php",
            data: {
                'action':'selectdepartmentticket',
                'department':department,
                'role':role
               
            },
            success: function(response)
            {
                if(response.status==1)
                {
                   $('#other_department').html(response.options);
                   $('#other_department').select('refresh');
                }
                else
                {
                    myAlert(response.title + "@#@" + response.message + "@#@danger");
                }
            }
        });
    
});


function ticketshow(val){
    $('#status').val(val);
    //st = val;
    //alert(st);
    var table = $('#myTable').DataTable(); 
    table.ajax.reload();
    //loadtable();
}

function loadtable()
{
    var val = $('#status').val();   
}
  

$('#same').click(function(){
  var ticketid = $('#ticketid').val();
  var department = $('#ticketdepartment').val();
  // alert(department);
  var role = $('#ticketrole').val();

  $.ajax({
            type: "POST",
            url: "loaddata1.php",
            data: {
                'action':'selectsamedepartmentticket',
                'department':department,
                'role':role
               
            },
            success: function(response)
            {
                if(response.status==1)
                {
                   $('#same_department').html(response.options);
                   $('#same_department').select('refresh');
                }
                else
                {
                    myAlert(response.title + "@#@" + response.message + "@#@danger");
                }
            }
        });
    
});
  
var status = "";
var commet = "";
var department = "";
var other_dept = "";
var same_dept = "";

function insertstatus()
{
    var value = $('input[name="ticketstatus"]:checked').val();
    
        if(value=="2")
        {
            status="2";
            commet = $('textarea#commet_escolet').val();
            department = $('#department').val();
            other_dept = $('#other_department').val();
            same_dept = $('#same_department').val();

        }
        else if(value=="1")
        {
            status="1";
            commet = $('#commet_processing').val();
        }
        else if(value=="3")
        {
            status="3";
            commet = $('#commet_clos').val();
        }
}

$('#savestatus').click(function(){
    var ticketid = $('#ticketid').val();
    $.ajax({
            type: "POST",
            url: "InsertData1.php",
            data: {
                'action':'inserttblstatus',
                'status':status,
                'commet':commet,
                'department':department,
                'ticket_id':ticketid,
                'other_dept':other_dept,
                'same_dept':same_dept
            },
            success: function(response)
            {
                if(response.status==1)
                {
                    myAlert(response.title + "@#@" + response.message + "@#@success");
                    $('#editstatus').modal('hide');
                    var table = $('#myTable').DataTable();
                    table.ajax.reload();
                }
                else
                {
                    myAlert(response.title + "@#@" + response.message + "@#@danger");
                }
            }
        });
});


var id = "";

function viewhistory(id)
{

  id = id;
  $('#viewhistory').modal('show');
  // table.ajax.reload();
 
   var table = $.ajax({
     url: 'loaddata1.php', 
     type: 'post',
     data: {
        'action':'loadticketstatustabledata',
         ticket_id:id
     },
     dataType: 'json',
     success: function (response) {
        if(response.status==1)
        {
            $('#Ownerbody').html(response.tbldata);
        }
        else
        {
            $('#Ownerbody').html(response.tbldata);
            // myAlert(response.title + "@#@" + response.message + "@#@danger");
        }
    }
});

}

$('#viewhistory').click(function(){
 jQuery('.timeline').timeline({
  //mode: 'horizontal',
  //visibleItems: 4
  //Remove this comment for see Timeline in Horizontal Format otherwise it will display in Vertical Direction Timeline
 });
});

</script> 
<!-- <script>
$(document).ready(function(){
 
});
</script> -->

</body>
</html>