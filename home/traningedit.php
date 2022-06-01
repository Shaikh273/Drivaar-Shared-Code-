<?php
include 'DB/config.php';
$page_title="Training Detail";

 if(!isset($_SESSION)) 
    { 
        session_start();
        
    }
    if($_SESSION['userid']==1)
        {
           $userid='%'; 
        }
        else
        {
           $userid = $_SESSION['userid']; 
        }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        
    }else
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

    $mysql = new Mysql();
    $mysql -> dbConnect();
    $id = $_GET['id'];

    $sql = "SELECT * FROM `tbl_training` WHERE `id`=$id AND `isdelete`=0 AND `userid` LIKE ('".$userid."')";
    $fire1 =  $mysql -> selectFreeRun($sql);
    $userresult1 = mysqli_fetch_array($fire1);
    $mysql -> dbDisConnect();
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=1024">
    <title><?php echo $page_title;?></title>
    <?php include('head.php');?>
    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
    <link rel="stylesheet" href="countrycode/build/css/demo.css">

</head>
<body class="skin-default-dark fixed-layout">
<?php include('loader.php');?>
<div id="main-wrapper">
<?php include('header.php');?>
<div class="page-wrapper p-0">
<div class="">
    <main class="animated">
        <div class="row">
            <div class="col-md-12">
                <div class="card">  
                    <div class="card-header" style="background-color: rgb(255 236 230);">
               <h2 class="align-items-center text-xl" style="line-height: 1;">Accident</h2>
                 <div class="row d-flex align-items-center pl-2 border-bottom" title="Accident" style="">
                    <div class="d-flex align-items-center">
                        <small class="mr-3 text-grey-darkest d-inline-block">
                            <i class="fas fa-bell pr-1"></i> <?php echo $userresult1['typename'];?>
                        </small>
                        <small class="mr-3 text-grey-darkest d-inline-block">
                            <i class="fas fa-car pr-1"></i> Driver Accident Form
                        </small>
                        <small class="mr-3 text-grey-darkest d-inline-block">
                            <i class="fa fa-user pr-1" aria-hidden="true"></i> <?php echo $userresult1['contractorname'];?>
                        </small>
                    </div>
                    <!-- <div class="col text-right">
                        <button type="submit" class="btn btn-secondary">Close Accident</button> 

                        <a href="https://bryanstonlogistics.karavelo.co.uk/accidents/11/edit" class="btn btn-primary"><i class="fas fa-edit"></i> Edit Accident</a>
                    </div> -->

                </div>
                    </div>
                    <div class="card-body" >
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="tid" id="tid" value="<?php echo $_GET['id'];?>">
                                <div class="row">
                                    <div class="col-3">
                                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                      <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true"><span><i class="fas fa-list mr-2" style="color: black;font-size: 12px;"></i>
                                        <span class="pl-1">Details</span></span></a>
                                      <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false"><span><i class="fas fa-image mr-2" style="font-size: 14px;"></i>Sessions</span></a>
                                        </div>
                                    </div>
                                    <div class="col-9">
                                        <div class="tab-content" id="v-pills-tabContent">
                                            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                                <div class="col-md-6">
                                                    <div class="card border">
                                                        <div class="card-body">
                                                        <div class="form-group">
                                                          <label class=" cursor-pointer"> Name *</label>

                                                          <input type="text" class="form-control" name="name" id="name" value="<?php echo $userresult1['name'];?>" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="hidden" name="refre" id="refre" value="<?php echo $userresult1['refreshment'];?>">
                                                            <label class=" cursor-pointer">Refreshment period:</label><br>
                                                            <select class="form-select form-select-lg mb-3 form-control" aria-label=".form-select-lg example" id="refreshment" name="refreshment">
                                                                <option value="1-year">1 Year</option>
                                                                <option value="9-month">9 Months</option>
                                                                <option value="6-month">6 Months</option>
                                                                <option value="3-month">3 Months</option>
                                                                <option value="1-month">1 Months</option>
                                                            </select> 
                                                        </div>   
                                                        <button type="" name="" class="btn btn-success" id="submit" onclick="update();">Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
            <div class="card border rounded">
                <div class="card-header" style="background-color: rgb(255 236 230);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="header">Amazon Onboarding Training Conducted Sessions</div>
                        <div> 
                             <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="view">View Session</button>
                            <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="add">+ New Session</button>
                           
                        </div>                   
                    </div>
                </div>
                <div class="card-body col-md-6 border m-4 p-0 rounded-md" id="AddFormDiv" style="display: none;">
                    <form method="post" action="" name="sessionform" id="sessionform">
                    <div class="card">
                        <div class="card-body">                        
<!--                        <input type="hidden" name="sessionid" name="sessionid">
 -->                            <div class="form-group">    
                                    <label class="cursor-pointer">Date</label>
                                    <input type="date" class="form-control" name="sessiondate" id="sessiondate" required="">
                                </div>
                                <label class="cursor-pointer">Depot</label>
                                <select class="form-select form-select-lg mb-3 form-control" aria-label=".form-select-lg example" name="depot" id="depot" required="">
                                    <option value="%">All Depot Name</option>
                                        <?php
                                         $mysql = new Mysql();
                                         $mysql -> dbConnect();
                                        $statusquery = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id` WHERE w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND w.`userid` LIKE '".$userid."'";
                                            $strow =  $mysql -> selectFreeRun($statusquery);
                                            while($statusresult = mysqli_fetch_array($strow))
                                            {
                                        ?>
                                            <option value="<?php echo $statusresult['id']?>"><?php echo $statusresult['name']?></option>
                                        <?php
                                            }
                                        ?>
                                </select>
                                <div class="row">
                                    <div class="col-md-5 pt-2">
                                        <hr>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <span class="">OR</span>
                                    </div>
                                    <div class="col-md-5 pt-2">
                                        <hr>
                                    </div>
                                </div>
                                <label class="cursor-pointer">People</label>
                                <select class="form-select form-select-lg mb-3 form-control" aria-label=".form-select-lg example" name="people" id="people" required="">
                                    <?php
                                     $mysql = new Mysql();
                                     $mysql -> dbConnect();
                                    $statusquery = "SELECT * FROM `tbl_contractor` WHERE isdelete=0 AND `userid` LIKE '".$userid."'";
                                        $strow =  $mysql -> selectFreeRun($statusquery);
                                        while($statusresult = mysqli_fetch_array($strow))
                                        {
                                    ?>
                                        <option value="<?php echo $statusresult['id']?>"><?php echo $statusresult['name']?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                        </div> 
                    </div>
                    <div class="card-footer ">
                        <button class="btn btn-primary " type="button" id="insertsession" value="0" name="insertsession">Conduct Session</button>
                        <a href="traings_health_safety.php" class="btn btn-default">Cancel</a>
                    </div>
                </form>  
                </div>                
                <div class="card-body" id="ViewFormDiv">
                    <div class="card">
                            <table id="myTable1" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info" style="width: 100%;">
                                <thead class="default">
                                    <tr role="row">
                                        <th>Date</th>
                                        <th data-orderable="false"> Attendees</th>
                                        <th data-orderable="false"> Action</th>
                                    </tr>
                                </thead>
                            </table>
                    </div>
                </div> 
                 
            </div>
        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>                         
    </main>
</div>
</div>

<?php include('footer.php');?>
</div>

<?php include('footerScript.php');?>

<script type="text/javascript">
$(document).ready(function(){
    var url = window.location.href;
    var activeTab = url.substring(url.indexOf("#") + 1);
    if(activeTab == 'v-pills-profile-tab'){
      $("#v-pills-home-tab").removeClass("active");
      $("#v-pills-profile-tab").addClass("active");
      $('#v-pills-profile').addClass("show active");
      $('#v-pills-home').removeClass("show active");
    }else{
      $("#v-pills-home-tab").addClass("active");
      $("#v-pills-profile-tab").removeClass("active");
      $('#v-pills-profile').removeClass("show active");
      $('#v-pills-home').addClass("show active");
    }

    var refre = $('#refre').val();
    $('#refreshment option[value="'+refre+'"]').attr("selected", "selected");
    $("#AddDiv").hide();

    $('#myTable1').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'loadtabledata1.php',
                'data': {
                    'action': 'loadTrainingSessiontabledata',
                }
            },
            'columns': [
                { data: 'date' },
                { data: 'attendeces' },             
                { data: 'action' }
            ]
        });
});

function ShowHideDiv(divValue)
{
    if(divValue == 'view')
    {
        document.getElementById('AddFormDiv').style.display = 'none';
        document.getElementById('AddDiv').style.display = 'none';
        document.getElementById('ViewFormDiv').style.display = 'block';
        document.getElementById('ViewDiv').style.display = 'block';
        // $("#ViewFormDiv,#ViewDiv").show();  
    }else if(divValue == 'add')
    {    
        document.getElementById('AddFormDiv').style.display = 'block';
        document.getElementById('AddDiv').style.display = 'block';
        document.getElementById('ViewFormDiv').style.display = 'none';
        document.getElementById('ViewDiv').style.display = 'none';
    }
}

// function insertsession(status)
$( "#insertsession" ).click(function() {
  var tid = $('#tid').val();
  var status = $(this).val();
    $.ajax({
        url: "InsertData1.php", 
        type: "POST", 
        dataType:"JSON",            
        data: $("#sessionform").serialize()+"&action=TrainingSessionForm"+"&tid="+tid+"+&status="+status+"",     
        success: function(data) {
            if(data.status==1)
            {
                myAlert(data.title + "@#@" + data.message + "@#@success");
                $('#sessionform')[0].reset();
                var table = $('#myTable').DataTable();
                table.ajax.reload();

            }
            else
            {
                myAlert(data.title + "@#@" + data.message + "@#@danger");
            }
        }
    });  
});
function update()
{

    var tid = $('#tid').val();
    var name = $('#name').val();
    var refre = $('#refreshment').val();

    // ShowHideDiv('view');

    $.ajax({

        type: "POST",

        url: "loaddata1.php",

        data: {

            action : 'TrainingEditData',
            tid: tid,
            name:name,
            refre:refre
        },

        dataType: 'json',

        success: function(data)
        {
            if(data.status==1)
            {
               var table = $('#myTable').DataTable();
               table.ajax.reload();
               myAlert(data.title + "@#@" + data.message + "@#@success");
            }
            else
            {
                myAlert(data.title + "@#@" + data.message + "@#@danger");
            }
        }

    });
}
function editsession(id)
{
    $('#sessionid').val(id);

        // ShowHideDiv('view');
        document.getElementById('AddFormDiv').style.display = 'block';
        document.getElementById('AddDiv').style.display = 'block';
        document.getElementById('ViewFormDiv').style.display = 'none';
        document.getElementById('ViewDiv').style.display = 'none';

        $.ajax({

            type: "POST",

            url: "loaddata1.php",

            data: {action : 'TrainingSessionUpdateData', id: id},

            dataType: 'json',

            success: function(data) {

                $result_data = data.statusdata;
                $('#sessiondate').val($result_data['date']);
                $('#depot').val($result_data['depot_id']);
                $('#people').val($result_data['people_id']);
                $("#insertsession").attr('name', 'update');
                $("#insertsession").text('Update');
                $("#insertsession").val('1');

            }

        });

}

function deletesession(id)
{

    $.ajax({

        type: "POST",

        url: "loaddata1.php",

        data: {action : 'TraningSessionDeleteData', id: id},

        dataType: 'json',

        success: function(data) {
            if(data.status==1)
            {
               var table = $('#myTable').DataTable();
               table.ajax.reload();
                myAlert("Delete @#@ Data has been deleted successfully.@#@success");
            }
            else
            {
                myAlert("Delete @#@ Data can not been deleted.@#@danger");
            }
            
        }

    });
}


</script>
</body>
</html>