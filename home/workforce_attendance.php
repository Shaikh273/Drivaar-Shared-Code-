<?php
include 'DB/config.php';
  $page_title = "Workforce Attendance";
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
              <div class="header">Workforce Attendance</div>

               <div> 
                    <?php
                        if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][35]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
                        { ?>
                            <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Attendance</button>
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

                    <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Attendance</button>
               </div>                   

          </div>
      </div>
      <div class="card-body" id="AddFormDiv">
          <div class="row">
            <div class="col-md-12">
                <form method="post" id="WorkforceAttendanceForm" name="WorkforceAttendanceForm" action="">
                    <input type="hidden" name="id" id="id" value=""> 
                     <input type="hidden" name="wid" id="wid" value="<?php echo $id ?>">  
                       <div class="form-body">
                        <div class="row">
                            <div class="alert alert-info alert-rounded col-sm-12"><i class="fas fa-exclamation-circle"></i> <b> When switching between work/lunch preset values for start and time:</b><br><br>
                            Work: 9:30 - 18:00<br>
                            Lunch: 12:00 - 12:35</div>
                         </div>
                          <div class="row p-t-20">
                              <div class="col-md-4">
                                   <div class="form-group">
                                        <br>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="work" name="type" class="custom-control-input" value="1" checked onchange="settime(this.value);">
                                            <label class="custom-control-label" for="work">Work</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="lunch" name="type" class="custom-control-input" value="2" onchange="settime(this.value);">
                                            <label class="custom-control-label" for="lunch">Lunch Break</label>
                                        </div>
                                  </div>
                              </div>
                              <div class="col-md-4">
                                  <label class="control-label">Starts *</label>
                                  <input type="time" class="form-control" id="starts" name="starts" placeholder="">
                              </div>
                              <div class="col-md-4">
                                  <label class="control-label">End *</label>
                                  <input type="time" class="form-control" id="end" name="end" placeholder="">
                              </div>
                          </div>
                          <div class="row">
                            <label class="control-label">Description</label>
                            <textarea type="text" rows="2" class="form-control" id="description" name="description" placeholder=""></textarea>
                          </div><br>
                      </div>
                       <div class="form-actions">
                          <button type="submit" name="insert" class="btn btn-success" id="submit">Submit</button>
                       </div>
                </form>
            </div>
          </div>
      </div>
      <div class="card-body" id="ViewFormDiv">
        <?php

          function GetAttendanceTime($starttime,$endtime)
          { 

            $time1 = $starttime;
            $time2 = $endtime;
            $time1 = explode(':',$time1);
            $time2 = explode(':',$time2);
            $hours1 = $time1[0];
            $hours2 = $time2[0];
            $mins1 = $time1[1];
            $mins2 = $time2[1];
            $hours = $hours2 - $hours1;
            $mins = 0;
            if($hours < 0)
            {
              $hours = 24 + $hours;
            }
            if($mins2 >= $mins1) {
                  $mins = $mins2 - $mins1;
              }
              else {
                $mins = ($mins2 + 60) - $mins1;
                $hours--;
              }
              if($mins < 9)
              {
                $mins = str_pad($mins, 2, '0', STR_PAD_LEFT);
              }
              if($hours < 9)
              {
                $hours =str_pad($hours, 2, '0', STR_PAD_LEFT);
              }
            return  $hours.':'.$mins;
          }
          
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $query = "SELECT * FROM `tbl_workforceattendance` WHERE `workforce_id`=".$id." AND `isdelete`=0 AND `isactive`=0";
          $strow =  $mysql -> selectFreeRun($query);
          $hr=0;
          $mn=0;
          while($result = mysqli_fetch_array($strow))
          {
            $duration = GetAttendanceTime($result['starts'],$result['end']);
            $spl=explode(':', $duration);
            $hr+=(int)$spl[0];
            $mn+=(int)$spl[1];
          }
          if($mn>60)
          {
            $hr+=(int)$mn/60;
          }
          $mn=(int)$mn%60;
          $totaltime = (int)$hr.":".(int)$mn;
          $mysql -> dbDisconnect();
        ?>
        <div class="row">
          <div class="alert alert-success alert-rounded col-sm-12"><i class="fas fa-exclamation-circle"></i> <b>Today’s total working time : <i class="far fa-clock"></i>  <?php echo $totaltime;?></b></div>
       </div>
                    <div class="table-responsive m-t-40" style="margin-top: 0px;">
                        <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                            <thead class="default">
                                <tr role="row">

                                    <th data-orderable="false">Activity</th>

                                    <th>Start</th>

                                    <th>Stop</th>

                                    <th data-orderable="false">Duration</th>

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
                          'action': 'loadWorkforceAttendancetabledata',
                          'wid':wid
                      }
                  },
                  'columns': [
                      { data: 'Activity' },
                      { data: 'starts' },
                      { data: 'end' },
                      { data: 'Duration' },
                      { data: 'date' },
                      { data: 'Status' },
                      { data: 'Action' }
                  ]
      });

      $("#WorkforceAttendanceForm").validate({
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
                data: $("#WorkforceAttendanceForm").serialize()+"&action=WorkforceAttendanceForm",
                cache: false,             
                processData: false,      
                success: function(data) {
                    if(data.status==1)
                    {
                         myAlert(data.title + "@#@" + data.message + "@#@success");
                         $('#WorkforceAttendanceForm')[0].reset();
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

  function settime(val)
  {
    if(val==1)
    {
      $('#starts').val('09:30:00');
      $('#end').val('18:00:00');
    }
    else if(val==2)
    {
      $('#starts').val('12:00:00');
      $('#end').val('12:35:00');
    }
  }

  function edit(id)
  {
        $('#id').val(id);

        ShowHideDiv('view');

        $.ajax({

            type: "POST",

            url: "loaddata.php",

            data: {action : 'WorkforceAttendanceUpdateData', id: id},

            dataType: 'json',

            success: function(data) {

              $result_data = data.statusdata;
              $('#wid').val($result_data['wid']);
              $("input[name='type'][value='"+$result_data['type']+"']").prop('checked', true);
              $('#starts').val($result_data['starts']);
              $('#end').val($result_data['end']);
              $('#description').val($result_data['description']);
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

          data: {action : 'WorkforceAttendanceDeleteData', id: id},

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
      $('#WorkforceAttendanceForm')[0].reset(); 
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