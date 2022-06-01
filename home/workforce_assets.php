<?php
include 'DB/config.php';
  $page_title = "Drivvar";
  $page_id=35;
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
            <main class="container-fluid animated">
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
              <div class="header">Workforce Assets</div>

               <div> 
                    <?php
                        if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][35]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
                        { ?>
                            <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Assign Asset</button>
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

                    <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Assets</button>
               </div>                   

          </div>
      </div>
      <div class="card-body" id="AddFormDiv">
          <div class="row">
            <div class="col-md-12">
                 <form method="post" id="WorkforceAssetForm" name="ContractorAssetForm" action="">
                    <input type="hidden" name="id" id="id" value=""> 
                     <input type="hidden" name="wid" id="wid" value="<?php echo $id ?>">  
                       <div class="form-body">
                          <div class="row p-t-20">
                              <div class="col-md-6">
                                  <label class="control-label">Assets *</label>
                                  <select class="select form-control custom-select" id="asset" name="asset">
                                      <?php
                                        $mysql = new Mysql();
                                        $mysql -> dbConnect();
                                        $query = $mysql->selectFreeRun("SELECT * FROM `tbl_financeassets` WHERE `isdelete`=0 AND `isactive`=0");
                                        while($fetch1 = mysqli_fetch_array($query))
                                        {
                                          ?>
                                          <option value="<?php echo $fetch1['id'];?>"><?php echo $fetch1['name'].' ('.$fetch1['type_name'].')';?></option>
                                          <?php
                                        }
                                        $mysql -> dbDisConnect();
                                      ?>
                                    </select>
                              </div>
                          </div>
                          <br>
                      </div>
                       <div class="form-actions">
                          <button type="submit" name="insert" class="btn btn-success" id="submit">Submit</button>
                       </div>
                </form>
            </div>
          </div>
      </div>
      <div class="card-body" id="ViewFormDiv">
                    <div class="table-responsive m-t-40" style="margin-top: 0px;">
                         <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                            <thead class="default">
                                <tr role="row">
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>Identifier</th>
                                    <th>Price</th>
                                    <th data-orderable="false">Assigned On</th>
                                    <th data-orderable="false">Date</th>
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
</div>                        
            </main>
        </div>
    </div>
</div>

<?php include('footer.php');?>
</div>
<?php include('footerScript.php'); ?>
<script type="text/javascript">

  var wid=<?php echo $id ?>;

  $(document).ready(function(){

      $("#AddFormDiv,#AddDiv").hide();
      $('#myTable').DataTable({
                  'processing': true,
                  'serverSide': true,
                  'serverMethod': 'post',
                  'ajax': {
                      'url':'loadtabledata.php',
                      'data': {
                          'action': 'loadWorkforceAssetstabledata',
                          'wid':wid
                      }
                  },
                  'columns': [
                      { data: 'type' },
                      { data: 'name' },
                      { data: 'identifier' },
                      { data: 'price' },
                      { data: 'assignon' },
                      { data: 'date' },
                      { data: 'Action' }
                  ]
      });

      $("#WorkforceAssetForm").validate({
        rules: {
            type: 'required',
            starts: 'required',
            end: 'required',
        },
        messages: {
            type: "Please enter your type",
            starts: "Please enter your starts",
            end: "Please enter your end",
        },
        submitHandler: function(form) {
            event.preventDefault();
            $.ajax({
                url: "InsertData.php", 
                type: "POST", 
                dataType:"JSON",            
                data: $("#WorkforceAssetForm").serialize()+"&action=WorkforceAssetForm",
                cache: false,             
                processData: false,      
                success: function(data) {
                    if(data.status==1)
                    {
                         myAlert(data.title + "@#@" + data.message + "@#@success");
                         $('#WorkforceAssetForm')[0].reset();
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

      settime(1);
  });

  function ShowHideDiv(divValue)
  {
      $('#WorkforceAssetForm')[0].reset(); 
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