<?php
include 'DB/config.php';
    $page_title = "Depot Target";
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
              <div class="header">Target</div>

               <div> 

                    <?php
                        if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][40]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
                        { ?>
                            <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Target</button>
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

                    <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Target</button>
               </div>                   

          </div>
      </div>
      <div class="card-body" id="AddFormDiv">
          <div class="row">
            <div class="col-md-12">
                <form method="post" id="TargetForm" name="TargetForm" action="">
                    <input type="hidden" name="id" id="id" value=""> 
                     <input type="hidden" name="did" id="did" value="<?php echo $id ?>">  
                       <div class="form-body">
                          <div class="row p-t-20">
                              <div class="col-md-4">
                                   <div class="form-group">
                                      <label class="control-label">Metric *</label>
                                      <select class="select form-control custom-select" id="metric" name="metric">
                                        <?php
                                          $mysql = new Mysql();
                                          $mysql -> dbConnect();
                                          $Targetquery = "SELECT * FROM `tbl_workforcemartic` WHERE `isdelete`=0 AND `isactive`=0";
                                          $Targetrow =  $mysql -> selectFreeRun($Targetquery);
                                          while($Targetresult = mysqli_fetch_array($Targetrow))
                                          {
                                            ?>
                                                <option value="<?php echo $Targetresult['id']?>"><?php echo $Targetresult['name']?></option>
                                            <?php
                                          }
                                          $mysql -> dbDisconnect();
                                      ?>
                                      </select> 
                                  </div>
                              </div>
                               <div class="col-md-4">
                                  <div class="form-group">
                                      <label class="control-label">Target *</label>
                                      <input type="text" id="target" name="target" class="form-control" placeholder="">
                                  </div>
                              </div>
                              <div class="col-md-4">
                                  <div class="form-group">
                                      <label class="control-label">Threshold (%) *</label>
                                      <input type="text" id="threshold" name="threshold" class="form-control" placeholder="">
                                  </div>
                              </div>
                          </div>
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

                                    <th>Metric</th>

                                    <th>Target</th>

                                    <th>Threshold</th>

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
  $(document).ready(function(){
      $("#AddFormDiv,#AddDiv").hide();

      var did = <?php echo $id ?>;
      $('#myTable').DataTable({
                  'processing': true,
                  'serverSide': true,
                  'serverMethod': 'post',
                  'ajax': {
                      'url':'loadtabledata.php',
                      'data': {
                          'action': 'loaddepotTargettabledata',
                          'did' : did,
                      }
                  },
                  'columns': [
                      { data: 'metric_type' },
                      { data: 'target' },
                      { data: 'threshold' },
                      { data: 'date' },
                      { data: 'Status' },
                      { data: 'Action' }
                  ]
      });

      $("#TargetForm").validate({
          rules: {
              metric: 'required',
              target: 'required',
              threshold: 'required',
          },
          messages: {
              metric: "Please select your Metric",
              target: "Please enter your target",
              threshold: "Please enter your threshold",
          },
          submitHandler: function(form) {
              event.preventDefault();
              $.ajax({
                  url: "InsertData.php", 
                  type: "POST", 
                  dataType:"JSON",            
                  data: $("#TargetForm").serialize()+"&action=AddDepotTargetform",
                  cache: false,             
                  processData: false,      
                  success: function(data) {
                      if(data.status==1)
                      {
                           myAlert(data.title + "@#@" + data.message + "@#@success");
                           $('#TargetForm')[0].reset();
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
  });

  function edit(id)
  {
        $('#id').val(id);
        ShowHideDiv('view');
        $.ajax({
            type: "POST",
            url: "loaddata.php",
            data: {action : 'DepotTargetUpdateData', id: id},
            dataType: 'json',
            success: function(data) {
                $result_data = data.statusdata;
                $('#metric').val($result_data['metric']);
                $('#target').val($result_data['target']);
                $('#threshold').val($result_data['threshold']);
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
          data: {action : 'DepotTargetDeleteData', id: id},
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
      $('#TargetForm')[0].reset(); 
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