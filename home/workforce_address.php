<?php
include 'DB/config.php';
  $page_title = "Workforce Address";
   $page_id=37;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $id = $_SESSION['wid'];
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $query = "SELECT * FROM `tbl_user` WHERE `id`=".$id;
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
    <meta name="viewport" content="width=1024">
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
                            <div class="header">Workforce / <?php echo $cntresult['name'];?></div>
                            <div> 
                              <a href="">
                                    <button type="button" class="btn btn-secondary"><i class="fas fa-paper-plane"></i> Send Message</button>
                               </a>
                              <a href="workforce_edit.php"> 
                                    <button type="button" class="btn btn-info"><i class="fas fa-pencil-alt"></i> Edit Details</button>
                              </a>
                           </div>
                        </div>
                    </div>
<div class="card-body">
  <div class="col">
    <div class="d-flex align-items-center">
        <div class="mr-2">
          <?php 
          if($cntresult['isactive']==0)
          {
            ?>
              <span class="label label-success">Active</span>
            <?php
          }
          else
          {
            ?>
              <span class="label label-danger">Inactive</span>
            <?php
          }
          ?>  
        </div>
        <div class="mr-3 text-grey-darkest whitespace-no-wrap">
          <i class="fas fa-suitcase"></i> 
          <?php echo $cntresult['role_type'];?>
        </div>
        <div class="mr-3 text-grey-darkest whitespace-no-wrap">
           <i class="fas fa-envelope-open"></i>
            <?php echo $cntresult['email'];?>
        </div>
    </div>  
</div><br><hr>
<?php include('workforce_setting.php'); ?>
  <div class="row">
    <div class="card col-md-12" style="border: 1px solid #d1d5db;">   
      <div class="card-header" style="background-color: #fff;">
          <div class="d-flex justify-content-between align-items-center">
              <div class="header">Address Information</div>                  
          </div>
      </div>
      <div class="card-body" id="AddFormDiv">
          <div class="row">
            <div class="col-md-12">
                <form method="post" id="WorkforceAddressForm" name="WorkforceAddressForm" action="">
                    <input type="hidden" name="id" id="id" value="<?php echo $cntresult['id'] ?>"> 
                    <input type="hidden" name="wid" id="wid" value="<?php echo $cntresult['id'] ?>">  
                       <div class="form-body">
                          <div class="row">
                              <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label"> Address Line 1 *</label>
                                    <input type="text" id="address" name="address" class="form-control" placeholder="">
                                </div>
                              </div>
                               <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label"> Address Line 2 </label>
                                    <input type="text" id="street_address" name="  street_address" class="form-control" placeholder="">
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label"> Town/City *</label>
                                    <input type="text" id="city" name="city" class="form-control" placeholder="">
                                </div>
                              </div>
                          </div>
                            <div class="row">
                              <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label"> County/District *</label>
                                    <input type="text" id="state" name="state" class="form-control" placeholder="">
                                </div>
                              </div>
                               <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label"> Postal Code *</label>
                                    <input type="text" id="postcode" name="postcode" class="form-control" placeholder="">
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label"> Country *</label>
                                    <select class="select form-control custom-select" id="country" name="country">
                                          <?php
                                          $mysql = new Mysql();
                                          $mysql -> dbConnect();
                                          $query = "SELECT * FROM `tbl_country` WHERE `isdelete`=0 AND `isactive`=0";
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
      data: {action : 'WorkforcwUpdateData', id: id},
      dataType: 'json',
      success: function(data) {
          $result_data = data.statusdata;
          $('#address').val($result_data['address']);
          $('#street_address').val($result_data['street_address']);
          $('#postcode').val($result_data['postcode']);
          $('#state').val($result_data['state']);
          $('#city').val($result_data['city']);
          $('#country').val($result_data['country']);
      }
  });

$("#WorkforceAddressForm").validate({
rules: {
    address: 'required',
    postcode: 'required',
    state: 'required',
    city: 'required',
    country: 'required',
},
messages: {
    address: "Please enter your address line 1",
    postcode: "Please enter your postal code",
    state: "Please enter your state",
    city: "Please enter your city",
    country: "Please enter your country",
},
submitHandler: function(form) {
  event.preventDefault();
  $.ajax({
    url: "InsertData.php", 
    type: "POST", 
    dataType:"JSON",            
    data: $("#WorkforceAddressForm").serialize()+"&action=WorkforceAddressForm",
    success: function(data) {
        if(data.status==1)
        {
             myAlert(data.title + "@#@" + data.message + "@#@success");
             //$('#WorkforceAddressForm')[0].reset();
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