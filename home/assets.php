<?php
$page_title = "Assets";
include 'DB/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=1024">
<title><?php echo $page_title; ?></title>
<?php include('head.php'); ?>
<link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
<link rel="stylesheet" href="countrycode/build/css/demo.css">
<style type="text/css">
  .titlehead {
        font-size: 28px;
        font-weight: 500;
  }
</style>
</head>
<body class="skin-default-dark fixed-layout">
<?php include('loader.php');  ?>
<div id="main-wrapper">
    <?php include('header.php'); ?>
      <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        
                                <div class="card">    
                                        <div class="card-header" style="background-color: rgb(255 236 230);">
                                            <div class="d-flex justify-content-between align-items-center">
                                                 <div class="header">Assets</div>
                                                 <div> 
                                                    <a href="add_asset.php"> 
                                                    <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Asset</button>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">

                                                        <thead class="default">

                                                            <tr>
                                                        
                                                            <th>Type</th>

                                                            <th>Name</th>

                                                            <th>Notes</th>

                                                            <th>Number</th>

                                                            <th>Price</th>
                                                            
                                                            <th>Assign To</th>

                                                            <th>Action</th>

                                                        </tr>

                                                        </thead>

                                                        <tbody>


                                                        </tbody>

                                                        <!-- You have no assets yet. Add your first asset now. -->
                                            </table>       
                                        </div>
                                </div>
                            
                    </div>
                </div>
            </div>
      </div>
    
      <div class="modal fade" id="empModal" role="dialog">
          <div class="modal-dialog">
           <!-- Modal content-->
           <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Assign Asset</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="modelBoby">
              <input type="hidden" id="id" name="id" value="">
              <label class="font-weight-bold">User : </label><br>
              <select class="form-control p-1 col-md-8" id="assign_val" aria-label=".form-select-lg example">
                  <option selected value="--">Open this select menu</option>
                    <?php
                      $mysql = new Mysql();
                      $mysql -> dbConnect();
                      $sql = $mysql->selectFreeRun("SELECT * FROM `tbl_user` WHERE `isactive`=0 AND `isdelete`=0");
                      while($fetch = mysqli_fetch_array($sql))
                      {
                        ?>
                        <option value="workforce-<?php echo $fetch['id'];?>"><?php echo $fetch['name'];?></option>
                        <?php
                      }

                      $query = $mysql->selectFreeRun("SELECT * FROM `tbl_contractor` WHERE `isdelete`=0 AND `iscomplated`=1");
                      while($fetch1 = mysqli_fetch_array($query))
                      {
                        ?>
                        <option value="contractor-<?php echo $fetch1['id'];?>"><?php echo $fetch1['name'];?></option>
                        <?php
                      }

                      $mysql -> dbDisConnect();
                    ?>
                  </select>
            </div>
            <div class="modal-footer"> 
             <button type="button" class="btn btn-reply btn-sm btn-success pull-right" id="assign_to"> Assign </button>
             <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
           </div>
          </div>
      </div>
</div>
<?php include('footer.php'); ?>
</div>
<?php include('footerScript.php');?> 
<script>
  var table = $('#myTable').DataTable;  
  $(document).ready(function(){
     $('#myTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'loadtabledata.php',
                    'data': {
                        'action': 'loadfinanceAssetstable',
                        // 'vid': vid
                    }
                },
                'columns': [
                    { data: 'type_name' },
                    { data: 'name'},
                    { data: 'description' },
                    { data: 'number' },
                    { data: 'price' },
                    { data: 'assign_to' },
                    { data: 'Action' }
                ]
    });
  });
      
  function deleterow(id)
  {
      var id = id;
      $.ajax({

          type: "POST",

          url: "loaddata.php",

          data: {action : 'financeassetdelete', id: id},

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
      
  function getmdl(id){
    $('#empModal').modal('show');
    $('#id').val(id);
  }

  $('#assign_to').click(function(){
      var aid = $('#id').val();
      var assign = $('#assign_val').val();
       $.ajax({
          url: "InsertData.php", 
          type: "POST", 
          dataType:"JSON",            
          data: {
               'action':'finance_asset_assignupdate',
               'id': aid,
               'assign_to': assign,
          },      
          success: function(data) {
              if(data.status==1)
              {
                  myAlert(data.title + "@#@" + data.message + "@#@success");
                  $('#empModal').modal('hide');
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
</script>
</body>
</html>