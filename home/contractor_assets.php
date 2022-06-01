<?php
include 'DB/config.php';
  $page_title = "Drivaar";
  $page_id=5;
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

        $sql = "SELECT a.*,v.registration_number FROM `tbl_vehiclerental_agreement`  a 
          INNER JOIN `tbl_vehicles` v ON v.id=a.vehicle_id
          WHERE a.`driver_id`=$id  AND CURRENT_DATE() BETWEEN a.`pickup_date` AND a.`return_date`";
        $fire = $mysql -> selectFreeRun($sql);
        $cntresult1 = mysqli_fetch_array($fire);
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
            <main class="container-fluid animated">
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
          <div class="mr-3 text-grey-darkest whitespace-no-wrap">
             <i class="fas fa-car"></i>
             <?php echo $cntresult1['registration_number'];?>
            </div>
      </div>  
  </div>
  <br><hr>
  <?php include('contractor_setting.php');?>
  <div class="row">
      <div class="card col-md-12" style="border: 1px solid #d1d5db;">   
      <div class="card-header" style="background-color: #fff;">
          <div class="d-flex justify-content-between align-items-center">
              <div class="header">Contractor Assets</div>

               <div> 
                    <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Assign Asset</button>
                         
                    <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Assets</button>
               </div>                   

          </div>
      </div>
      <div class="card-body" id="AddFormDiv">
          <div class="row">
            <div class="col-md-12">
                <form method="post" id="ContractorAssetForm" name="ContractorAssetForm" action="">
                    <input type="hidden" name="id" id="id" value=""> 
                     <input type="hidden" name="cid" id="cid" value="<?php echo $id ?>">  
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
      <div class="card-body" id="ViewFormDiv"> <div class="table-responsive m-t-40" style="margin-top: 0px;">
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

  var cid=<?php echo $id ?>;0

  $(document).ready(function(){
        $("#AddFormDiv,#AddDiv").hide();
        $('#myTable').DataTable({
              'processing': true,
              'serverSide': true,
              'serverMethod': 'post',
              'ajax': {
                  'url':'loadtabledata.php',
                  'data': {
                      'action': 'loadContractorAssetstabledata',
                      'cid':cid
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

        $("#ContractorAssetForm").validate({
            rules: {
                asset: 'required'
            },
            messages: {
                asset: "Please enter your asset type"
            },
            submitHandler: function(form) {
                event.preventDefault();
                $.ajax({
                    url: "InsertData.php", 
                    type: "POST", 
                    dataType:"JSON",            
                    data: $("#ContractorAssetForm").serialize()+"&action=ContractorAssetForm",     
                    success: function(data) {
                        if(data.status==1)
                        {
                            myAlert(data.title + "@#@" + data.message + "@#@success");
                            $('#ContractorAssetForm')[0].reset();
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

    function ShowHideDiv(divValue)
    {
        $('#ContractorAssetForm')[0].reset(); 
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