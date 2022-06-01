<?php
if (!isset($_SESSION)) {
    session_start();
}
$page_id = '144';
if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
{
    
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
//include('authentication.php');
include('config.php');
include 'DB/config.php';
$page_title="Dashboard";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=1024">
    <title><?php echo $page_title;?></title>
    <?php include('head.php');?>
    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
    <link rel="stylesheet" href="countrycode/build/css/demo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="dist/jq.dice-menu.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Orbitron:400,500,700&display=swap');

.clock {
    background: linear-gradient(
        45deg, 
        rgba(31, 31, 31, .9), 
        rgba(31, 31, 31, .9) 50%,
        hsla(0, 0%, 53%, .9) 50%, 
        rgba(135, 135, 135, .9)
    );
    background: #03a9f3;
    width: 200px;
    height: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 18px;
    color: #B6FF77;
    text-shadow: 
        -2px -2px 10px #B6FF77,
        2px 2px 10px #B6FF77;
/*    padding: 0 .5em;
*/}

.clock span {
    font-size: .8em;
}

    </style>

</head>
    <body class="skin-default-dark fixed-layout mini-sidebar">
        <?php
        
            include('loader.php');
        ?>
        
        <div id="main-wrapper" id="top">
            <?php
                include('header.php');
            ?>
            <div class="page-wrapper content" id="top" >
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card p-2 pl-3">
                                <h5>Start My Day</h5>
                                <button class="btn btn-primary col-sm-6 mt-2">Inspect Vehicle[vehicle no]</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card p-3">
                                <a href="myinvoice.php" class="btn btn-primary col-sm-6 mt-1">My Invoice</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">

                             <div class="card mb-4 p-3 pb-5">
                                <h6 class="m-0">Attendance</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                             <button type="button" name="clockin" id="clockin" onclick="attendence(this.id);" class="clock btn btn-info"><i class="fas fa-bell"></i><label class="text-center pl-3 pt-1"> CLOCK IN</label> <br><?php //echo date('H:i:s');?>
                                          </button>
                                        </div> 
                                        <div class="col-md-6"> 
                                           <button type="button" name="clockout" id="clockout" onclick="attendence(this.id);" class="clock btn btn-info"><i class="fas fa-bell" style="float: left;"></i> <label class="text-center pl-3 pt-1">CLOCK OUT</label> <br><?php // echo date('H:i:s');?>
                                          </button>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                            
                            <form action="" method="POST" class="" id="addleaverequestForm" name="addleaverequestForm">
                                <input type="hidden" name="" value="">
                                <div class="card no-shadow">
                                    <div class="card-header ">
                                        <h6 class="m-0">Request Day Off</h6>
                                    </div>
    
                                <div class="card-body">
                                    <div class="row ">
                                        <div class="form-group col-md-6">
                                            <label class=" cursor-pointer">Start Dates</label>
                                            <input type="date" class="form-control" name="startdate" id="startdate" onclick="validdate()">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class=" cursor-pointer">End Dates</label>
                                            <input type="date" class="form-control" name="enddate" id="enddate" onclick="validdate()">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class=" cursor-pointer">Notes:</label>
                                        <textarea class="form-control" name="notes" data-aire-for="notes" id=""></textarea>
                                        <small class="form-text text-muted">Your request depends on approval from your supervisor.</small>
                                    </div>

                                </div>
    
                                <div class="card-footer">
                                    <button class="btn btn-primary " type="button" onclick="insertleaverequest();">Send Request</button>
                                </div>
                            </div>
                            </form>
                        </div>
                        <div class="col-md-8 card">
                            <div class="table-responsive m-t-40">
                                <input type="hidden" name="cid" id="cid" value="<?php echo $_SESSION['cid'];?>">
                                <table id="myTable" class="display dataTable table" role="grid" aria-describedby="example2_info" style="width: 100%;">
                                <thead class="default">
                                <tr role="row">
                                    <th>Date</th>
                                    <th>Notes</th>
                                    <th>Status</th>
                                    
                                </tr>
                            </thead>
                        </table>
                        <br>
                            </div>
                            </div>
                           
                        </div>
                    </div>
                    
                   
                </div>
            </div>
                    
                    
                    
            <?php include('footer.php');?>         
        </div>
            
       
<?php include('footerScript.php'); ?>
<script src="dist/jq.dice-menu.js"></script>
<script type="text/javascript">

$(document).ready(function(){
        $('#myTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'loadtabledata.php',
                'data': {
                    'action': 'loadleaverequesttabledata'
                   
                }
            },
            'columns': [
                { data: 'date'},
                { data: 'notes' },
                { data: 'status' }
            ]
        });
    }); 

function attendence(status)
{
    $.ajax({
        url: "loaddata.php", 
        type: "POST", 
        dataType:"JSON",            
        data: {
            'action': 'todayinspectioncheckdata',
            'clockstatus':status
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
            if(data.name==0)
            {
                alert("Before Clock Out Add a Today's Inspection");
                window.location.href="contractor_vehicle_inspection.php";
            }
            
        }
    });
}      

function validdate(){
    var dtToday = new Date();
    
    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();
    
    var maxDate = year + '-' + month + '-' + day;

   
    $('#txtDate').attr('min', maxDate);
}

function insertleaverequest() {
    $.ajax({
            url: "InsertData.php", 
            type: "POST", 
            dataType:"JSON",            
            data: $("#addleaverequestForm").serialize()+"&action=addleaverequestForm",
            cache: false,             
            processData: false,      
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
</script>

</body>

</html>