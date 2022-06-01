
<?php
$page_title="Drivaar";
include 'DB/config.php';
$page_id=85;
if(!isset($_SESSION)) 
{
    session_start();
}
if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
{
    $userid = $_SESSION['userid'];
    if($userid==1)
    {
      $uid='%';
    }
    else
    {
      $uid=$userid;
    }
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $query = "SELECT 
              COUNT(DISTINCT i.`id`) as cnt FROM `tbl_contractorinvoice` i
              INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('".$uid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
              WHERE  i.`depot_id` IN (w.depot_id) AND i.`status_id`=9  AND i.`istype`=1 AND i.`isdelete`=0 AND i.`isactive`=0";
    $row =  $mysql -> selectFreeRun($query);
    $result = mysqli_fetch_array($row);
    $mysql -> dbDisconnect();

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
            <div class="card-header" style="background-color: rgb(255 236 230);">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="header">Disputes (<?php echo $result['cnt'];?>)</div>                  
                </div>
            </div>
     
            <div class="card-body" id="ViewFormDiv">
                    <div class="row">
                      <!-- <div class="col-md-4">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i class="ti-search"></i></span></div>
                            <input type="text" class="form-control" id="searchTable" placeholder="Search for contractor..">
                        </div>
                      </div> -->

                      <div class="col-md-4">
                        <div class="input-group">
                          <div class="input-group-prepend"><span class="input-group-text">Depot</span></div>
                          <select class="select form-control custom-select" id="depot_id" name="depot_id" onchange="loadtable();">
                              <option value="%">All Depot</option>
                              <?php
                                  $mysql = new Mysql();
                                  $mysql -> dbConnect();
                           
                                  $statusquery = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w 
                                                    INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id`
                                                    WHERE w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND w.`wid` LIKE '".$uid."'";
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

                      <div class="col-md-4">
                        <div class="input-group">
                          <div class="input-group-prepend"><span class="input-group-text">Status</span></div>
                          <select class="select form-control custom-select" id="status" name="status" onchange="loadtable();">
                                <option value="%">All Status</option>
                                <?php
                                  $mysql = new Mysql();
                                  $mysql -> dbConnect();
                                  $statusquery = "SELECT * FROM `tbl_invoicestatus` WHERE `isdelete`=0 AND `isactive`=0";
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
                    </div><br>
                    <div class="table-responsive m-t-40" style="margin-top: 0px;">
                        <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                            <!-- <thead class="default">
                                <tr role="row">
                                    <th>From</th>
                                    <th>Invoice</th>
                                    <th>Reason</th>
                                    <th></th>
                                    <th>Sumitted</th>
                                    <th></th>
                                </tr>
                            </thead> -->

                            <thead class="default">
                              <tr role="row">
                                <th>Form</th>
                                <th data-orderable="false">Period</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th data-orderable="false">Submited</th>
                                <th></th>
                              </tr>
                            </thead>


                              <!-- "SELECT *,(SELECT `name` FROM `tbl_depot` WHERE `id`=i.`depot_id`) as depotname,
     DATE_FORMAT(i.`duedate`,'%D %M%, %Y') as duedate 
     FROM `tbl_contractorinvoice` i
     INNER JOIN `tbl_contractor` c ON c.`id`=i.`cid`
     WHERE i.`isdelete`= 0 AND i.`isactive`=0 AND i.`status_id`=9 AND `istype`=1 AND c.userid=1"; -->
                                <!--     <tbody id="extendedTable">

                                        <tr>

                                            <td>Paul Wallis<br><small>Bournemouth Airport (DBH2) - AMZL</small></td>

                                            <td><b>Week 12</b> - Mar 21, 2021 » Mar 27, 2021</td>

                                            <td><small>Bonus missing</small></td>

                                            <td><span class="label label-primary">Pending</span></td>

                                            <td>Apr 1, 2021</td>

                                            <td><button type="button" class="btn btn-secondary"   aria-expanded="false">Details</button></td>

                                        </tr>

                                        <tr>

                                            <td>Rifat Rahaman<br><small>Enfield (DIG1) - Amazon Logistics (EN3 7PZ)</small></td>

                                            <td><b>Week 12</b> - Mar 21, 2021 » Mar 27, 2021</td>

                                            <td><small> think is very unfair to take a deduction from me in 23/03 and 25/03. In that week every single day they gave me more than 300 packages. In 23/03 they gave me 337 (19 Bags and 36 overflow) Mihai told me the support should be free and in 25/03 I was stuck in traffic I sent Mihai the photo and asked him to send me the support</small></td>

                                            <td><span class="label label-primary">Pending</span></td>

                                            <td>Mar 31, 2021</td>

                                            <td><button type="button" class="btn btn-secondary"   aria-expanded="false">Details</button></td>

                                        </tr>

                                        <tr>

                                            <td>Evans Adu<br><small>Bournemouth Airport (DBH2) - AMZL</small></td>

                                            <td><b>Week 12</b> - Mar 21, 2021 » Mar 27, 2021</td>

                                            <td><small>Missing payment for support</small></td>

                                            <td><span class="label label-primary">Pending</span></td>

                                            <td>Apr 1, 2021</td>

                                            <td><button type="button" class="btn btn-secondary"   aria-expanded="false">Details</button></td>

                                        </tr>

                                    </tbody> -->
                        </table>
                        <br>
                    </div>

            </div>
        </div>        
    </main>
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
      $('#myTable').DataTable({
            'processing': true,
            "serverSide": true,
            "destroy" : true,
            'pageLength': 10,
            "ajax":
            {
                "url": "loadtabledata.php",
                "type": "POST",
                "data": function(d) 
                {
                    d.action = 'loadDisputedInvoicestabledata';
                    d.did = $('#depot_id').val();
                    d.statusid = $('#status').val();
                },
            },
            'columns': [
                { data: 'name'},
                { data: 'period' },
                { data: 'disputed_comment'},
                { data: 'status' },
                { data: 'disputed_date' },
                { data: 'Action' }
            ]
            //alert(data.iTotalDisplayRecords);
      });
  });

  function loadtable()
  {
    var table = $('#myTable').DataTable();
    table.ajax.reload();
  }
</script>
</body>

</html>