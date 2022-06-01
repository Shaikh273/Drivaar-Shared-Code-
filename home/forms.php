<?php
include 'DB/config.php';
$page_title = "Forms";
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $id = $_SESSION['vid'];
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $query = "SELECT v.*,vs.`name` as statusname,vs.`colorcode` ,vsp.`name` as suppliername,vg.`name` as groupname,vm.`name` as makename,vmo.`name` as modelname FROM `tbl_vehicles` v LEFT JOIN `tbl_vehiclestatus` vs ON vs.`id`=v.`status` LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=v.`supplier_id` LEFT JOIN `tbl_vehiclegroup` vg ON vg.`id`=v.`group_id` LEFT JOIN `tbl_vehiclemake` vm ON vm.`id`=v.`make_id` LEFT JOIN `tbl_vehiclemodel` vmo ON vmo.`id`=v.`model_id` WHERE v.`id`=".$id;
        $row =  $mysql -> selectFreeRun($query);
        $cntresult = mysqli_fetch_array($row);
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
        <title><?php echo $page_title;?></title>
        <?php include('head.php');?>
        <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
        <link rel="stylesheet" href="countrycode/build/css/demo.css">
        <style>
        .height {
          height: 100px;
        }
        .table td, .table th {
    padding: 0.2rem;
    /*vertical-align: top;*/
    border-top: 1px solid #dee2e6;
}
    </style>
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
                    
<main class=" container-fluid  animated">
            <div class="container" style="max-width: 100%;">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">    
                    <div class="card-header " style="background-color: rgb(255 236 230);margin-bottom: 25px;">
                        <div class="d-flex justify-content-between align-items-center">
                             <div class="header">Vehicle Check</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div>
                                    <?php
                                        $mysql = new Mysql();
                                        $mysql -> dbConnect();
                                        $rentquery = "SELECT COUNT(*) as checkrow FROM `tbl_vehiclechecklist` WHERE `isdelete`= 0 AND `isactive`= 0";
                                        $checkrow =  $mysql -> selectFreeRun($rentquery);
                                        $chkresult = mysqli_fetch_array($checkrow);
                                        $mysql -> dbDisConnect();
                                    ?>

                                    <a href="checklistitem.php"><b>Checklist Items</b></a>
                                    <span class="label label-info" style="float:right"><?php echo $chkresult['checkrow']; ?></span>

                                    <div class="dropdown-divider"></div>

                                    <a href="#"><b>Submissions</b></a>
                                    <span class="label label-info" style="float:right"> 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer"></div>
                </div>        
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
        
        <!-- <script src="../assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
        <script src="../assets/node_modules/popper/popper.min.js"></script>
        <script src="../assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
        <script src="dist/js/waves.js"></script>
        <script src="dist/js/sidebarmenu.js"></script>
        <script src="../assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js"></script>
        <script src="../assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
        <script src="dist/js/custom.min.js"></script>
        <script src="../assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
        <script src="../assets/node_modules/sparkline/jquery.charts-sparkline.js"></script>
        <!--<script src="https://code.jquery.com/jquery-3.5.1.js" ></script>-->
<!--         <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script> -->
 -->        

        <script>
        $(document).ready(function() {
    // Setup - add a text input to each footer cell
    $('#example tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
 
    // DataTable
    var table = $('#example').DataTable({
        initComplete: function () {
            // Apply the search
            this.api().columns().every( function () {
                var that = this;
 
                $( 'input', this.footer() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );
            } );
        }
    });
 
} );
        </script>
        
    </body>

</html>