<?php
include 'DB/config.php';
  $page_title = "Contractor Payment Arrears";
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
              <div class="header">Update <?php echo $cntresult['name'];?> Payment Arrears</div>                  
          </div>
      </div>
      <div class="card-body" id="AddFormDiv">
          <div class="row">
            <div class="col-md-12">
                <form method="post" id="ContractorPaymentArreasForm" name="ContractorPaymentArreasForm" action="">
                    <input type="hidden" name="id" id="id" value="<?php echo $cntresult['id'] ?>"> 
                    <input type="hidden" name="cid" id="cid" value="<?php echo $cntresult['id'] ?>">  
                       <div class="form-body">
                          <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Arrears *</label>
                                    <select class="select form-control custom-select" id="arrears" name="arrears">
                                      <?php
                                        $mysql = new Mysql();
                                        $mysql -> dbConnect();
                                        $query = "SELECT * FROM `tbl_arrears` WHERE `isdelete`=0 AND `isactive`=0";
                                        $row =  $mysql -> selectFreeRun($query);
                                        while($result = mysqli_fetch_array($row))
                                        {
                                          ?>
                                              <option value="<?php echo $result['id']?>"><?php echo $result['name']?></option>
                                          <?php
                                        }
                                        $mysql -> dbDisconnect();
                                      ?>
                                    </select> 
                                </div>
                              </div>
                          </div>
                          <div class="row">
                            <div class="alert alert-warning alert-rounded col-md-6"><i class="fas fa-exclamation-circle"></i> <b>Changing arrears will update future invoices due dates.
                                  All pending/approved/sent invoices will change their due dates.</b></div>
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
          $('#arrears').val($result_data['arrears']);
      }
  });

  $("#ContractorPaymentArreasForm").validate({
    rules: {
        arrears: 'required',
    },
    messages: {
        arrears: "Please provide your arrears.",
    },
    submitHandler: function(form) {
      event.preventDefault();
      $.ajax({
        url: "InsertData.php", 
        type: "POST", 
        dataType:"JSON",            
        data: $("#ContractorPaymentArreasForm").serialize()+"&action=ContractorPaymentArreasForm",
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
  });
});
</script>
</body>
</html>