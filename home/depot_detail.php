<?php
include 'DB/config.php';
    $page_title = "Depot Details";
    $page_id=40;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $id = $_SESSION['did'];
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $query = "SELECT * FROM `tbl_depot` WHERE `id`=".$id;
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
                            <div class="header"><?php echo $cntresult['name'];?></div>
                            <div> 
                              <button type="button" class="btn btn-secondary" disabled="true"><i class="fas fa-circle" style="color: <?php echo $colorcode?>;"></i> <?php echo $statusname;?></button>
                           </div>
                        </div>
                    </div>
<div class="card-body">
  <?php include('depot_setting.php'); ?>
  <div class="row">
    <div class="card col-md-12" style="border: 1px solid #d1d5db;">   
      <div class="card-header" style="background-color: #fff;">
          <div class="d-flex justify-content-between align-items-center">
              <div class="header">Details</div>                  
          </div>
      </div>
      <div class="card-body" id="AddFormDiv">
          <div class="row">
            <div class="col-md-12">
                <form method="post" id="depotDetailForm" name="depotDetailForm" action="">
                    <input type="hidden" name="id" id="id" value="<?php echo $cntresult['id'] ?>"> 
                    <input type="hidden" name="did" id="did" value="<?php echo $cntresult['id'] ?>">  
                       <div class="form-body">
                          <div class="row p-t-20">
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label class="control-label">Name *</label>
                                      <input type="text" id="name" name="name" class="form-control" placeholder="">
                                  </div>
                              </div>

                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label class="control-label">Reference </label>
                                      <input type="text" id="reference" name="reference" class="form-control" placeholder="">
                                      <small>e.g. DHP1</small>
                                  </div>
                              </div>
                          </div>

                          <div class="row">
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label class="control-label">Supervisor(s) *</label>
                                      <select class="select2 select2-multiple" style="width: 100%" multiple="multiple" name="supervisor[]" id="supervisor" Placeholder="Select Supervisor">
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
                              <div class="col-md-6">
                                <br><br>
                                  <div class="custom-control custom-radio">
                                      <input type="radio" id="active" name="isactive" class="custom-control-input" value="0">
                                      <label class="custom-control-label" for="active">Active</label>
                                  </div>
                                  <div class="custom-control custom-radio">
                                      <input type="radio" id="inactive" name="isactive" class="custom-control-input" value="1">
                                      <label class="custom-control-label" for="inactive">Inactive</label>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="form-actions">
                          <button type="submit" name="update" class="btn btn-success" id="submit">Submit</button>
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
        data: {action : 'DepotUpdateData', id: id},
        dataType: 'json',
        success: function(data) {
            $result_data = data.userdata;
            $('#supervisor').val($result_data['supervisor'].split(",")).change();
            $('#name').val($result_data['name']);
            $('#reference').val($result_data['reference']);
            $("input[name='isactive'][value='"+$result_data['isactive']+"']").prop('checked', true);
        }
    });


    $("#depotDetailForm").validate({
          rules: {
              name: 'required',
              'supervisor[]': 'required',
          },
          messages: {
              name: "Please enter depot name",
              'supervisor[]': "Please enter your supervisor",
          },
          submitHandler: function(form) {
            event.preventDefault();
            $.ajax({
                url: "InsertData.php", 
                type: "POST", 
                dataType:"JSON",            
                data: $("#depotDetailForm").serialize()+"&action=AddworkforcedepotDetailForm",
                success: function(data) {
                    if(data.status==1)
                    {
                         myAlert(data.title + "@#@" + data.message + "@#@success");
                         $('#depotDetailForm')[0].reset();
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