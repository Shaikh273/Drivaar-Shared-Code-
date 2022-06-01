
<?php
$page_title="Rental Adjustment";
include 'DB/config.php';
$page_id=40;
if(!isset($_SESSION)) 
{
    session_start();
}
$userid = $_SESSION['userid']; 
if($userid==1)
{
  $uid='%';
}
else
{
  $uid=$userid;
}
if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
{
    $id = $_GET['id']; 
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $query = "SELECT r.*,v.`registration_number` as regestrationnumber, s.`name` as suppliername,c.`name` as contaractorname FROM `tbl_vehiclerental_agreement` r
    INNER JOIN `tbl_contractor` c ON c.`id`= r.`driver_id`
    INNER JOIN `tbl_vehicles` v ON v.`id`=r.`vehicle_id`
    INNER JOIN `tbl_vehiclesupplier` s ON s.`id`=v.`supplier_id`
    WHERE r.`id`=$id AND r.`isdelete`=0 AND r.`userid` LIKE '".$uid."'";
    $row =  $mysql -> selectFreeRun($query);
    $cntresult = mysqli_fetch_array($row);
    $mysql -> dbDisConnect();

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
<?php
include('loader.php');
?>
<div id="main-wrapper">
<?php
    include('header.php');
    // include('menu.php');
?>
<div class="page-wrapper">
<div class="container-fluid">
<main class="container-fluid  animated">
             <div class="card">    
                <div class="card-header" style="background-color: rgb(255 236 230);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="header">
                                <label>Hire Agreement</label><br>
                                <small class="bg-secondary rounded px-2 d-inline-flex align-items-center">
                                    <i class="fa fa-user pr-2 pt-1" aria-hidden="true" style="font-size: 12px;"></i>
                                    <?php echo $cntresult['contaractorname'];?>
                                </small>
                                <a href="#" class="text-gray-700">
                                    <small class="bg-secondary rounded px-2 d-inline-flex align-items-center">
                                    <i class="fas fa-car pr-2 pt-1"></i>
                                        <?php echo $cntresult['suppliername']. ' ('.$cntresult['regestrationnumber'].')';?>
                                    </small>
                                </a>
                                <a href="#" class="text-gray-700">
                                    <small class="bg-secondary rounded px-2 d-inline-flex align-items-center">
                                    <i class="fas fa-clock pt-1 pr-2"></i>
                                        <?php echo $cntresult['insert_date'];?>
                                    </small>
                                </a>
                            </div>
                         <div> 

                            <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Adjustment</button>

                            <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Adjustment</button>

                         </div>                   

                    </div>
                </div>
                <div class="card-body" id="AddFormDiv">
                    <div class="row">
                      <div class="col-md-12">
                          <form method="post" id="RentalAdjustmentForm" name="RentalAdjustmentForm" action="">
                              <input type="hidden" name="rid" id="rid" value="<?php echo $_GET['id'];?>">
                              <input type="hidden" name="userid" id="userid" value="<?php echo $userid?>">  
                                 <div class="form-body">
                                    <div class="row p-t-20">
                                        <div class="col-md-4">
                                              <div class="form-group">
                                                <label class="control-label">Type *</label>
                                                <select class="form-control" style="width: 100%" name="rent_type" id="rent_type"  Placeholder="---">
                                                    <?php
                                                    $mysql = new Mysql();
                                                    $mysql -> dbConnect();
                                                    $statusquery = "SELECT * FROM `tbl_user` WHERE  `roleid` IN (10) AND `isdelete`=0 AND `isactive`=0";
                                                    $strow =  $mysql -> selectFreeRun($statusquery);
                                                    while($statusresult = mysqli_fetch_array($strow))
                                                    {
                                                      ?>
                                                          <option value="<?php echo $statusresult['id']?>"><?php echo $statusresult['name']?></option>
                                                      <?php
                                                    }
                                                    $mysql -> dbDisconnect();
                                                    ?>
                                                </select> 
                                            </div>

                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                              <label class="control-label">Amount *</label>
                                              <input type="number" id="amount" name="amount" class="form-control" placeholder="">
                                            </div>    
                                            
                                        </div>
                                    </div>
                                     <div class="row">
                                        <div class="form-group col-md-10">
                                          <label class="control-label">Description *</label>
                                          <input type="text" id="description" name="description" class="form-control" placeholder="">
                                        </div>
                                    </div>    
                                </div>
                                 <div class="form-actions">
                                    <button type="submit" name="insert" class="btn btn-success" id="submit">Add Adjustment</button>
                                 </div>
                          </form>
                      </div>
                    </div>
                </div>
                <div class="card-body" id="ViewFormDiv">
                    <?php include('rent_agreement_setting.php'); ?>
                        <div class="table-responsive m-t-40" style="margin-top: 0px;">
                            <table id="myTable" class="table table-responsive tabelvendor">
                                <thead class="default">
                                    <tr>
                                        <th>Label</th>
                                        <th>Category</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Date</th>
                                        <th data-orderable="false"></th>
                                    </tr>
                                </thead>
                                  <tbody>
                                   
                                </tbody>

                            </table>
                            <br>

                        </div>
                </div>
             </div>  
</main>


</div>
</div>

<?php
    include('footer.php');
?>
</div>

<?php
include('footerScript.php');
?>
<script type="text/javascript">

$(document).ready(function(){
    $("#AddFormDiv,#AddDiv").hide();

    $('#myTable').DataTable({
                  'processing': true,
                  'serverSide': true,
                  'serverMethod': 'post',
                  'ajax': {
                      'url':'loadtabledata1.php',
                      'data': {
                          'action': 'loadaRentalAdjustmenttabledata'
                      }
                  },
                  'columns': [
                      { data: 'label' },
                      { data: 'category' },
                      { data: 'amount' },
                      { data: 'description' },
                      { data: 'insert_date' },
                      { data: 'Action' }
                  ]
    });

    $("#RentalAdjustmentForm").validate({

            rules: {

                subject: 'required',
                

            },
            messages: {
                subject: "Please enter your document type",
               
            },
            submitHandler: function(form) {
                event.preventDefault();
                $.ajax({
                    url: "InsertData.php", 
                    type: "POST", 
                    dataType:"JSON",            
                    data: $("#RentalAdjustmentForm").serialize()+"&action=AddRentalAdjustmentform",
                    cache: false,             
                    processData: false,      
                    success: function(data) {
                        if(data.status==1)
                        {
                            myAlert(data.title + "@#@" + data.message + "@#@success");
                            $('#RentalAdjustmentForm')[0].reset();
                            if(data.name == 'Update')
                            {
                                var table = $('#myTable').DataTable();
                                table.ajax.reload();
                                $("#AddFormDiv,#AddDiv").hide();
                                $("#ViewFormDiv,#ViewDiv").show();
                            }
                        }
                        else
                        {
                            myAlert(data.title + "@#@" + data.message + "@#@danger");
                        }
                    }
                });
               // return false;
            }
        });
    // $("#RentalAdjustmentForm").validate({
    //         // rules: {
    //         //     // rent_type: 'required',
    //         //     amount: 'required',
    //         // },
    //         // messages: {
    //         //     // rent_type: "Please select your type",
    //         //     amount: "Please enter your amount",
    //         // },
    //         submitHandler: function(form) {
    //             event.preventDefault();
    //             $.ajax({
    //                 url: "InsertData1.php", 
    //                 type: "POST", 
    //                 dataType:"JSON",            
    //                 data: $("#RentalAdjustmentForm").serialize()+"&action=AddRentalAdjustmentform",
    //                 cache: false,             
    //                 processData: false,      
    //                 success: function(data) {
    //                     if(data.status==1)
    //                     {
    //                          myAlert(data.title + "@#@" + data.message + "@#@success");
    //                          // $('#RentalAdjustmentForm')[0].reset();
    //                          //  if(data.name == 'Update')
    //                          //  {
    //                          //      var table = $('#myTable').DataTable();
    //                          //      table.ajax.reload();
    //                          //      $("#AddFormDiv,#AddDiv").hide();
    //                          //      $("#ViewFormDiv,#ViewDiv").show();
    //                          //  }
    //                     }
    //                     else
    //                     {
    //                         myAlert(data.title + "@#@" + data.message + "@#@danger");
    //                     }
    //                 }
    //             });
    //            // return false;
    //         }
    // });
});

  function edit(id)
  {
        $('#rid').val(id);

        ShowHideDiv('view');

        $.ajax({

            type: "POST",

            url: "loaddata.php",

            data: {action : 'AddVehicleRenewalTypeUpdateData', id: id},

            dataType: 'json',

            success: function(data) {

                $result_data = data.statusdata;

                $('#renewaltype').val($result_data['renewal_id']);
                $('#duedate').val($result_data['duedate']);
                $("#submit").attr('name', 'update');
                $("#submit").text('Update');

            }

        });
  }

  function deleterow(id)
  {

      $.ajax({

          type: "POST",

          url: "loaddata.php",

          data: {action : 'AddVehicleRenewalTypeDeleteData', id: id},

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


 function ShowHideDiv(divValue)
  {
      $('#RentalAdjustmentForm')[0].reset(); 
      if(divValue == 'view')
      {
          $("#submit").attr('name', 'insert');
          $("#submit").text('Submit');
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
</body>

</html>