
<?php
$page_title="Vehicle";
include 'DB/config.php';
    $page_id=48;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        if($_SESSION['userid']==1)
        {
           $userid='%'; 
        }
        else
        {
           $userid = $_SESSION['userid']; 
        }
    }
    else
    {
        header("location: login.php");  
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
	     	<?php
		     	$mysql = new Mysql();
	            $mysql -> dbConnect();
	            $statusquery = "SELECT DISTINCT v.*,vt.`name` as type,vs.`name` as suppliername FROM `tbl_vehicles` v 
								INNER JOIN `tbl_vehicletype` vt ON vt.`id`=v.`type_id`
								INNER JOIN `tbl_vehiclesupplier` vs ON vs.`id`=v.`supplier_id`
                INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL  
								WHERE v.`isdelete`=0";
	            $strow =  $mysql -> selectFreeRun($statusquery);
	            $rowcount=mysqli_num_rows($strow);

	            $today = date('d');
	            $month = date('m');
	            $year = date('Y');

	            $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);  
			?>
	        <div class="card-header" style="background-color: rgb(255 236 230);">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="header"><?php echo date('F')." ".$year." - ".$rowcount." vehicles"; ?>
                      <input type="hidden" name="hidmonth" id="hidmonth">
                       <input type="hidden" name="hidyear" id="hidyear">
                    </div>
                    
                </div>
            </div> 


    <br><br><div class="row">
        <!-- <div class="col-md-4">

            <div class="input-group col-md-12">
                <div class="input-group-prepend"><span class="input-group-text"><i class="ti-search"></i></span></div>
                <input type="text" class="form-control" id="searchTable" placeholder="Search">
            </div>

            <div class="d-flex mt-3">
                <span class="custom-control custom-checkbox">
                    <label class="custom-control custom-checkbox m-b-0">
                        <input type="checkbox" class="custom-control-input">
                        <span class="custom-control-label">SWB</span>
                    </label>
                </span>

                <span class="custom-control custom-checkbox">
                    <label class="custom-control custom-checkbox m-b-0">
                        <input type="checkbox" class="custom-control-input">
                        <span class="custom-control-label">LWB</span>
                    </label>
                </span>
                
                 <span class="custom-control custom-checkbox">
                    <label class="custom-control custom-checkbox m-b-0">
                        <input type="checkbox" class="custom-control-input">
                        <span class="custom-control-label">MWB</span>
                    </label>
                </span>

                 <span class="custom-control custom-checkbox">
                    <label class="custom-control custom-checkbox m-b-0">
                        <input type="checkbox" class="custom-control-input">
                        <span class="custom-control-label">XLWB</span>
                    </label>
                </span>

                 <span class="custom-control custom-checkbox">
                    <label class="custom-control custom-checkbox m-b-0">
                        <input type="checkbox" class="custom-control-input">
                        <span class="custom-control-label">LUTON</span>
                    </label>
                </span>

                 <span class="custom-control custom-checkbox">
                    <label class="custom-control custom-checkbox m-b-0">
                        <input type="checkbox" class="custom-control-input">
                        <span class="custom-control-label">Car</span>
                    </label>
                </span>

                 <span class="custom-control custom-checkbox">
                    <label class="custom-control custom-checkbox m-b-0">
                        <input type="checkbox" class="custom-control-input">
                        <span class="custom-control-label">Truck</span>
                    </label>
                </span>

                 <span class="custom-control custom-checkbox">
                    <label class="custom-control custom-checkbox m-b-0">
                        <input type="checkbox" class="custom-control-input">
                        <span class="custom-control-label">Scooter</span>
                    </label>
                </span>
        
                </div>
            
        </div>
        <div class="col-md-4">
            <label class="custom-control custom-checkbox m-b-0">
	            <input type="checkbox" class="custom-control-input">
	            <span class="custom-control-label">Available Today</span>
            </label>
        </div>
 -->
        <div class="col-md-4 mb-4">
            <div class="card">
	            <div class="d-flex text-center">
				    <div class="col  border-right  py-2">
					    <div class="text-grey-dark"><small>All Vehicles</small></div>
					    <h5 class="m-0" style="font-weight: 700;"><?php echo $rowcount?></h5>
				    </div>

				    <div class="col  border-right  py-2">
              <?php
               $tdate = date('Y-m-d');
               $vrqr = "SELECT COUNT(v.`id`) as cnt FROM `tbl_assignvehicle` v WHERE ('".$tdate."' BETWEEN v.`start_date` AND v.`end_date`)";
               $vrrow =  $mysql -> selectFreeRun($vrqr);
               $vrresult = mysqli_fetch_array($vrrow);

              ?>
					    <div class="text-grey-dark"><small>Available Today</small></div>
					    <h5 class="m-0" style="font-weight: 700;">
					        <span class=" text-warning "><?php echo $vrresult['cnt'];?></span>
					    </h5>
					</div>

				    <div class="col border-right py-2">
					    <div class="text-grey-dark"><small class="whitespace-no-wrap">Returns Tomorrow</small></div>
					    <h5 class="m-0" style="font-weight: 700;">0</h5>
					</div>
				</div>
            </div>
        </div>

</div>
      <div class="card-header ">
          <div class="d-flex justify-content-between align-items-center">
              <div>
                  <h6 class="m-0"></h6>
              </div>
              <div>
                <div class="btn-group" role="group" aria-label="Basic example">
                  <button type="button" class="btn btn-sm btn-info" onclick="previous()">
                       <i class="fas fa-chevron-left"></i>                        
                  </button>
                  <button type="button" class="btn btn-sm btn-info" onclick="currentmonth(<?php echo date('d'); ?>,<?php echo date('m'); ?>,<?php echo date('Y'); ?>)">Month</button>
                  <button type="button" class="btn btn-sm btn-info" onclick="next()">
                     <i class="fas fa-chevron-right"></i>               
                  </button>
                </div>
              </div>
          </div>
      </div>
			<div class="table-responsive">    
			    <table id="myTable" role="grid" aria-describedby="example2_info" class="table table-bordered">
					<thead class="default">
					  <tr role="row" id="setdate">
						<th></th>
						<?php
							for($i = 1; $i <= $days; $i++)
							{
							   if($i == $today)
							   {
							   	 echo "<th style='background-color: #f7d694;'>".$i."</th>";
							   } 
							   else
							   {
							   	 echo "<th>".$i."</th>";
							   }
							}
						?>
					  </tr>
					</thead>
					<tbody>
						<?php
							$i=0;
							while($row = mysqli_fetch_array($strow)) {
						?>
							  <tr>
								<td><?php echo "[".$row["type"]."] ".$row["registration_number"]."(".$row["suppliername"].")"; ?></td>
								<?php
									for($i = 1; $i <= $days; $i++)
									{
									   $fulldate = $i."-".$month."-".$year;
									   if($i == $today)
									   {
echo "<td style='background-color: #f7d694;' 
onclick=\"AddDeiverInfo('".$year."','".$month."','".$i."','".$row['id']."','".$row['registration_number']."','spandiv_".$row['id']."_".$i."_".$month."_".$year."')\" 
id='spandiv_".$row['id']."_".$i."_".$month."_".$year."'></td>";
									   } 
									   else
									   {
echo "<td onclick=\"AddDeiverInfo('".$year."','".$month."','".$i."','".$row['id']."','".$row['registration_number']."','spandiv_".$row['id']."_".$i."_".$month."_".$year."')\" 
id='spandiv_".$row['id']."_".$i."_".$month."_".$year."'></td>";
									   }
									}
								?>
								<td></td>
							  </tr>
							  <?php
						$i++;
						$mysql->dbDisconnect();
						}
						?>
					</tbody>
			  	</table>
		  	</div>
	     </div>         
    </main>
</div>
<div id="AssignVehicleModel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header" style="background-color: rgb(255 236 230);">
			<h4 class="modal-title">Vehicle Assignment   <span class="label label-info" id="reg_no" style="font-size: 15px;"> </span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body">
              <form method="post" id="AssignVehicleForm" name="AssignVehicleForm" action="">
                <input type="hidden" name="vid" id="vid">
                  <input type="hidden" name="id" id="id">
                  <input type="hidden" name="tdid" id="tdid">

                  <div>DIRECT ASSIGNMENT</div>
                  <br><br>
                  <div class="row">
                    <div class="form-group col-md-12">
                      <label class="control-label">Driver *</label>
                      <select class="select form-control custom-select" id="driver" name="driver">
                      	
                      </select>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label class="control-label">Start Date *</label>
	                  <input type="date" class="form-control" id="start_date" name="start_date" placeholder="mm/dd/yyyy" >
                    </div>
                    <div class="form-group col-md-6">
                       <label class="control-label">End Date *</label>
	                   <input type="date" class="form-control " id="end_date" name="end_date" placeholder="mm/dd/yyyy" onchange="datevalidation(this.value);">  
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-12">
                      <label class="control-label">Price *</label>
                      <div class="input-group">
                      	  <div class="input-group-append">
                              <span class="input-group-text"><i class="fas fa-pound-sign"></i></span>
                          </div>
                          <input type="text" class="form-control" id="amount" name="amount" placeholder="">
                      </div>
                      <small>Price per day with VAT</small>
                    </div>
                  </div>
              </form>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-success waves-effect waves-light" name="insert"  id="submit">Assign Vehicle</button>
              <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
          </div>
      </div>
  </div>
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
	  getdatashow();
    $('#hidmonth').val(<?php echo date('m'); ?>);
    $('#hidyear').val(<?php echo date('Y'); ?>);
  });

  function datevalidation(enddate)
  {
   	var startDate = $('#start_date').val();
   	var endDate = enddate;
   	if ((Date.parse(endDate) <= Date.parse(startDate))) {
          myAlert("Error @#@ End date should be greater than Start date @#@danger");
          document.getElementById("end_date").value = "";
          $('#end_date').css("border", "1px solid red");
      }
      else
      {
      	$('#end_date').css("border", "");
      }
  }

  function pad(n) 
  {
		  return (n < 10) ? ("0" + n) : n;
  }

  function previous()
  {
     var d ='';
     var m = $('#hidmonth').val()-1;
     var y = $('#hidyear').val();
     currentmonth(d,m,y);
  }

  function next()
  {
     var d ='';
     var m = $('#hidmonth').val()+1;
     var y = $('#hidyear').val();
     currentmonth(d,m,y);
  }

  function currentmonth(d,m,y)
  {
    $('#hidmonth').val(m);
    $('#hidyear').val(y);
    var month = m;
    var year = y;
    var daysInMonth = new Date(year, month, 0).getDate();
    $('#setdate').html();

    var set = ["<th></th>"];
    var i;
    var data='';
    for (i = 1; i <= daysInMonth; i++) 
    {
        if(i==d)
        {
          var data = "<th style='background-color: #f7d694;'>"+i+"</th>";
        }
        else
        {
          var data = "<th>"+i+"</th>";
        }
        set.push(data);
    }
    $('#setdate').html(set);
  }

  function AddDeiverInfo(y,m,d,vehicleid,reg,tdid)
  {
    $('#AssignVehicleModel').modal('show');
    $('#vid').val(vehicleid);
    $('#reg_no').text(reg);
    $('#tdid').val(tdid);
    document.getElementById("start_date").value = y+"-"+m+"-"+pad(d);
    $("#start_date").attr("disabled", true);

    $.ajax({
        type: "POST",
        url: "loaddata.php",
        data: {action : 'ScheduleDriverData',id: vehicleid,startdate: y+"-"+m+"-"+pad(d)},
        dataType: 'json',
        success: function(data) {
        	if(data.status==1)
        	{
        		$('#driver').html(data.options);
        	}
        }
    });
  }

  $(function() 
  {
    $('#submit').on('click', function(e) 
    {
       e.preventDefault();
       $("#start_date").removeAttr('disabled');
       var startDate = $('#start_date').val();
  	   var endDate = $('#end_date').val();
       var start = new Date(startDate);
       var end = new Date(endDate);
       var diffDate = (end - start) / (1000 * 60 * 60 * 24);
       var days = Math.round(diffDate);
       var driver = $('#driver option:selected').text();
       if (endDate && startDate) 
       { 
        	$.ajax({
                type: "POST",
                url: "InsertData.php",
                dataType:"JSON",
                data: $('#AssignVehicleForm').serialize()+"&action=AssignVehicleForm",
                success: function(data) 
                {
                  if(data.status==1)
                  {
                       myAlert(data.title + "@#@" + data.message + "@#@success");
                       $('#AssignVehicleForm')[0].reset();
                       $('#AssignVehicleModel').modal('hide');
                       if(data.name == 'Update')
                       {
                          var table = $('#myTable').DataTable();
                          table.ajax.reload();
                          $("#AddFormDiv,#AddDiv").hide();
                          $("#ViewFormDiv,#ViewDiv").show();
                       }
                       var tdid = $('#tdid').val();
                       for(var j=0;j<(days-1);j++)
            				   {
            				 	   $('#'+tdid).next().remove();
            				   }
            				   document.getElementById(tdid+'').setAttribute('colspan', days);
            				   document.getElementById(tdid+'').innerHTML += "<span class='label label-success' style='color: black;background-color: #bfdbfe !important;border: 1px solid #305ae9;border-radius: 0px;width: 100%'>"+driver+"</span>";        
                  }
                  else
                  {
                      myAlert(data.title + "@#@" + data.message + "@#@danger");
                  }
                }
          });
    	 }
       else
       {
      		myAlert("Error @#@ Please,Fill all required field. @#@danger");
       }
    });
  });

  function getdatashow()
  {
   	  $.ajax({
            type: "POST",
            url: "loaddata.php",
            data: {action : 'ScheduledataGet'},
            dataType: 'json',
            success: function(response1) {
            	// var response = JSON.stringify(JSON.parse(response1));
            	var response = response1;
            	var len = 0;
            	if(response.length>0)
            	{
            		len = response.length;
            	}
	            for(var i=0; i<len; i++){
	                var uniqid = response[i].uniqid;
      				    var start = new Date(response[i].startdate);
      				    var end = new Date(response[i].enddate);
      				    var diffDate = new Date(end - start) / (1000 * 60 * 60 * 24);
      				    var days = Math.round(diffDate)+1;

      				    for(var j=0;j<(days-1);j++)
      				    {
      				 	    $('#'+uniqid).next().remove();
      					}
    					if(days)
    					{
    						  var st = document.getElementById(uniqid+'');
                  st.setAttribute('colspan', days);
    				      document.getElementById(uniqid+'').innerHTML += "<span class='label label-success' style='color: black;background-color: #bfdbfe !important;border: 1px solid #305ae9;border-radius: 0px;width: 100%'>"+response[i].name+"</span>";
    					}
	            }
            }
        });
  }

</script>
</body>

</html>