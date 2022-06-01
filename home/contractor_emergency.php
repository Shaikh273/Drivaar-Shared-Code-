<?php
include 'DB/config.php';
  $page_title = "Contractor Emergency Contact";
   $page_id=7;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $id = $_SESSION['cid'];
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $query = "SELECT * FROM `tbl_contractor` WHERE `id`=".$id;
        $row =  $mysql -> selectFreeRun($query);
        $cntresult = mysqli_fetch_array($row);
        if($cntresult['isactive']==0)
        {
            $colorcode= "green";
            $statusname = "Active";
        }
        else
        {
            $colorcode= "red";
            $statusname = "Inactive";
        }
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
    <meta name="viewport" content="cidth=1024">
    <title><?php echo $page_title; ?></title>
    <?php include('head.php'); ?>
    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
    <link rel="stylesheet" href="countrycode/build/css/demo.css">
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
                            <div class="header">Contractor / <?php echo $cntresult['name'];?></div>
                            <div> 
                              <a href="">
                                    <button type="button" class="btn btn-secondary"><i class="fas fa-paper-plane"></i> Send Message</button>
                               </a>
                              <a href="editcontractor.php"> 
                                    <button type="button" class="btn btn-info"><i class="fas fa-pencil-alt"></i> Edit Details</button>
                              </a>
                           </div>
                        </div>
                    </div>
<div class="card-body">
<div class="col">
  <div class="d-flex align-items-center">
      <div class="mr-2">
          <span class="label label-success">Active</span>
      </div>
      <div class="mr-3 text-grey-darkest whitespace-no-wrap">
        <i class="fas fa-suitcase"></i> 
        <?php
        if($cntresult['type']==1)
        {
          echo 'self-employed';
        }
        else
        {
          echo 'company';
        }
        ?>
       
      </div>
      <div class="mr-3 text-grey-darkest whitespace-no-wrap">
         <i class="fas fa-envelope-open"></i>
          <?php echo $cntresult['email'];?>
      </div>
      <div class="mr-3 text-grey-darkest whitespace-no-wrap">
          <i class="fas fa-warehouse"></i>
          <?php echo $cntresult['depot_type'];?>
      </div>
  </div>  
</div>
<br><hr>
<?php include('contractor_setting.php');?>
  <div class="row">
    <div class="card col-md-12" style="border: 1px solid #d1d5db;">   
      <div class="card-header" style="background-color: #fff;">
          <div class="d-flex justify-content-between align-items-center">
              <div class="header"><?php echo $cntresult['name'];?>'s Emergency Contact</div>                  
          </div>
      </div>
      <div class="card-body" id="AddFormDiv">
          <div class="row">
            <div class="col-md-12">
                <form method="post" id="ContractorEmergencyForm" name="ContractorEmergencyForm" action="">
                    <input type="hidden" name="id" id="id" value="<?php echo $cntresult['id'] ?>"> 
                    <input type="hidden" name="cid" id="cid" value="<?php echo $cntresult['id'] ?>">  
                       <div class="form-body">
                          <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Name *</label>
                                    <input type="text" id="emegency_name" name="emegency_name" class="form-control" placeholder="">
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"> Telephone* </label>
                                    <input type="text" id="emegency_phone" name="emegency_phone" class="form-control" placeholder="">
                                </div>
                              </div>
                          </div>
                      </div>
                      <div class="form-actions">
                          <button type="submit" name="update" class="btn btn-success" id="submit">Update</button>
                      </div>
                </form>
            </div>
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
$(document).ready(function(){
  var id = <?php echo $id ?>;
  $.ajax({
      type: "POST",
      url: "loaddata.php",
      data: {action : 'ContractorUpdateData', id: id},
      dataType: 'json',
      success: function(data) {
          $result_data = data.statusdata;
          $('#emegency_phone').val($result_data['emegency_phone']);
          $('#emegency_name').val($result_data['emegency_name']);
      }
  });

$("#ContractorEmergencyForm").validate({
rules: {
    emegency_phone: 'required',
    emegency_name: 'required',
},
messages: {
    emegency_phone: "Please provide emergency contact telephone number.",
    emegency_name: "Please provide emergency contact name.",
},
submitHandler: function(form) {
  event.preventDefault();
  $.ajax({
    url: "InsertData.php", 
    type: "POST", 
    dataType:"JSON",            
    data: $("#ContractorEmergencyForm").serialize()+"&action=ContractorEmergencyForm",
    success: function(data) {
        if(data.status==1)
        {
             myAlert(data.title + "@#@" + data.message + "@#@success");
             //$('#ContractorEmergencyForm')[0].reset();
                // if(data.name == 'Update')
                // {
                //     var table = $('#myTable').DataTable();
                //     table.ajax.reload();
                //     $("#AddFormDiv,#AddDiv").hide();
                //     $("#ViewFormDiv,#ViewDiv").show();
                // }
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
});
</script>
</body>
</html>