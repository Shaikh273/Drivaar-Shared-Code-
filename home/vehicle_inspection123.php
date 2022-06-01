<?php
include 'DB/config.php';
    $page_title = "Vehicles Inspections";
    $page_id=52;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $id = $_SESSION['vid']; 
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $query = "SELECT v.*,vs.`name` as statusname,vs.`colorcode` ,vsp.`name` as suppliername FROM `tbl_vehicles` v LEFT JOIN `tbl_vehiclestatus` vs ON vs.`id`=v.`status` LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=v.`supplier_id` WHERE v.`id`=".$id;
        $row =  $mysql -> selectFreeRun($query);
        $cntresult = mysqli_fetch_array($row);
        $mysql -> dbDisConnect();

    }else
    {
        if((isset($_SESSION['adt']) && $_SESSION['adt']==0))
        {
           header("location: login.php");
        }
        else
        {
           header("location: login.php");  
        }
    }
                               
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta name="viewport" content="width=1024">
    <title><?php echo $page_title; ?></title>
    <?php include('head.php'); ?>
    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
    <link rel="stylesheet" href="countrycode/build/css/demo.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> -->

    <style type="text/css">
        .chk {
          float: left;
          border: 1px solid red;
          border-radius: 5px;
        }
        .chkicn {
           color: #ce0909;
           margin:5px
        }
        .cross {
            float: right;
            border: 1px solid green;
            border-radius: 5px;
        }
        .crossicn {
           color: green;
           margin:5px;
        }
    </style>
</head>

<body class="skin-default-dark fixed-layout">
<?php include('loader.php'); ?>
<div id="main-wrapper">
<?php include('header.php'); ?>
   <div class="page-wrapper">
        <div class="container-fluid">
            <main class="container-fluid  animated">
                <div class="card">    
                    <div class="card-header" style="background-color: rgb(255 236 230);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="header"><?php echo $cntresult['suppliername'].' ('.$cntresult['registration_number'].')';?></div>
                            <div> 
                              <a href="">
                                    <button type="button" class="btn btn-secondary"><i class="fas fa-paper-plane"></i> Action Log</button>
                               </a>
                              <button type="button" class="btn btn-secondary" disabled="true"><i class="fas fa-circle" style="color: <?php echo $cntresult['colorcode']?>;"></i> <?php echo $cntresult['statusname'];?></button>
                              <a href=""> 
                                    <button type="button" class="btn btn-info"><i class="fas fa-pencil-alt"></i> New Conditional Report</button>
                              </a>
                           </div>
                        </div>
                    </div>
<div class="card-body">
  <?php include('vehicle_setting.php'); ?>
  <div class="row">
    <div class="card col-md-12" style="border: 1px solid #d1d5db;">   
      <div class="card-header" style="background-color: #fff;">
          <div class="d-flex justify-content-between align-items-center">
              <div class="header">Inspections</div>

               <div> 
                    <?php
                        if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][53]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
                        { ?>
                            <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Inspections</button>
                          <?php
                        }
                        else
                        {
                          if((isset($_SESSION['adt']) && $_SESSION['adt']==0))
                          {
                             header("location: login.php");
                          }
                          else
                          {
                             header("location: login.php");  
                          }
                      }
                    ?>

                    <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Inspections</button>

               </div>                   

          </div>
      </div>
      <div class="card-body" id="AddFormDiv">
          <div class="row">
            <div class="col-md-12" style="height: 500px">
        

<form id="msform" method="post" name="msform" action="" enctype="multipart/form-data">
    <div class="header">Vehicle Inspection - <?php echo $cntresult['suppliername'].' ('.$cntresult['registration_number'].')';?></div>
    <br>
    <fieldset style="margin: 0 10%;">
      <input type="hidden" name="vid" id="vid" value="<?php echo $id ?>">  
      <div class="form-body">
          <div class="row">
              <div class="col-md-12">
                   <div class="form-group">
                     <label class="control-label">Odometer *</label>
                      <div class="input-group">
                          <input type="number" class="form-control" id="odometer" name="odometer" placeholder="">
                      </div>
                      <small>Current odometer value is __________ mi</small>
                  </div>
              </div>
          </div>
        </div>
      <input type="button" name="next" class="next action-button" value="Continue" />
    </fieldset>
<?php
$mysql = new Mysql();
$mysql -> dbConnect();
$expquery = "SELECT * FROM `tbl_vehiclechecklist` WHERE `isdelete`= 0 AND `isactive`= 0 ORDER BY id ASC";
$exprow =  $mysql -> selectFreeRun($expquery);
while($result = mysqli_fetch_array($exprow))
{
  ?>
    <fieldset class="col-md-7 offset-md-2" style="text-align: center!important;">
      <div  class="header" style="font-size:22px;"><?php echo $result['name']?></div><br><br> 
        <div class="row">
          <input type="hidden" id="checkid" name="checkid" value="<?php echo $result['id']?>">

          <div class="col-md-6">
            <button type="button" id="cross-<?php echo $result['id']?>" name="check" value="0" class="btn btn-danger btn-circle btn-lg"><i class="fa fa-times fa-2x"></i></button>
          </div>

          <div class="col-md-6" style="float: right;">
             <button type="button" id="check-<?php echo $result['id']?>" name="check" value="1" class="btn btn-success btn-circle btn-lg"><i class="fa fa-check fa-2x"></i></button>
          </div>                
        </div>
      <br><br><hr><br>
        <input type="button" name="previous" id="pre-<?php echo $result['id']?>" class="previous action-button" value="Go Back" onclick="isvalidate(<?php echo $result['id']?>)"/>
        <input type="button" name="next" id="next-<?php echo $result['id']?>" class="next action-button"  value="Continue" onclick="isvalidate(<?php echo $result['id']?>)"/>           
    </fieldset>
  <?php
}
$mysql -> dbDisconnect();
?>

    <fieldset>
        <h5>Thank You Abuzer Ansari</h5>
        <p>You have now provided all necessary information and uploaded<br> copies of your documents(ID/Passport, Driver Licence, Bank<br> Statement, Visa).<br><br>
          when you press <b>FINISH</b> we will sent you few Forms for background<br> check and your Service Contract that you need to sign via e-mail. <br><br>
          This will be the <u>last</u> step in the Onboarding Process.<br><br>
          Thank you for your time.    <img src="data:image/jpeg;base64,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" alt="" width="30px" style="background-color: white;">

        </p>
        <input type="button" name="previous" class="previous action-button" value="Go Back" />
        <!--<input type="submit" name="" class="next action-button" value="Finish" />-->
        <button type="submit" class="next action-button"> Finish</button>
    </fieldset>
  
</form>

            </div>
          </div>
      </div>
      <div class="card-body" id="ViewFormDiv">
                <div class="table-responsive m-t-40" style="margin-top: 0px;">
                        <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                            <thead class="default">
                                <tr role="row">

                                    <th>Vehicle</th>
                                    <th>User</th>
                                    <th>Odometer</th>
                                    <th>Checks</th>
                                    <th data-orderable="false">Date</th>
                                    <th data-orderable="false">Status</th>
                                    <th data-orderable="false"></th>
                                </tr>
                            </thead>
                        </table>
                        <br>
                    </div>
      </div>
    </div>    
  </div>
</div>                        
            </main>
        </div>
    </div>
</div>

<?php include('footer.php');?>
</div>
<?php include('footerScript.php'); ?>
<script type="text/javascript">

  function isvalidate(id)
  {   alert('1');
      var next = $('#next-'+id).val();
        if(next=='Continue')
        {
          alert('2');
           var chk = $('#check-'+id).val();
           var cross = $('#cross-'+id).val();
           if(chk || cross)
           {
            alert('3');
           }
           else
           {
             alert('4');
           }
        }
  }

  function Gotopage()
  {
    window.location = '<?php echo $webroot ?>vehicle_details.php?vid=<?php echo $id ?>';
  
  }

  $(document).ready(function(){
        $("#AddFormDiv,#AddDiv").hide();
         $('#myTable').DataTable({
                    'processing': true,
                    'serverSide': true,
                    'serverMethod': 'post',
                    'ajax': {
                        'url':'loadtabledata.php',
                        'data': {
                            'action': 'loadvehicleInspectiontabledata'
                        }
                    },
                    'columns': [
                        { data: 'name' },
                        { data: 'date' },
                        { data: 'Status' },
                        { data: 'Action' }
                    ]
        });



        $("#msform").validate({

            rules: {

                odometer: 'required',

            },

            messages: {
                odometer: "Please enter your Odometer",
            },
            submitHandler: function(form) {
                event.preventDefault();
                // $.ajax({
                //     url: "InsertData.php", 
                //     type: "POST", 
                //     dataType:"JSON",            
                //     data: $("#msform").serialize()+"&action=VehicleSupplierform",
                //     cache: false,             
                //     processData: false,      
                //     success: function(data) {
                //         if(data.status==1)
                //         {
                //             myAlert(data.title + "@#@" + data.message + "@#@success");
                //             $('#msform')[0].reset();
                //         }
                //         else
                //         {
                //             myAlert(data.title + "@#@" + data.message + "@#@danger");
                //         }
                //     }
                // });
               // return false;
            }
        });
  });

  function ShowHideDiv(divValue)
  {
        if(divValue == 'view')
        {

            $('#msform')[0].reset(); 

            $("#AddFormDiv,#AddDiv").show();

            $("#ViewFormDiv,#ViewDiv").hide();     

        }

        if(divValue == 'add')

        {
            var table = $('#myTable').DataTable();
            table.ajax.reload();

            $("#AddFormDiv,#AddDiv").hide();

            $("#ViewFormDiv,#ViewDiv").show();     

        }
  }
</script>
<script src="https://thecodeplayer.com/uploads/js/jquery-1.9.1.min.js" type="text/javascript"></script>
<!-- jQuery easing plugin -->
<script src="https://thecodeplayer.com/uploads/js/jquery.easing.min.js" type="text/javascript"></script>

  <script src="script.js"></script>
</body>
</html>