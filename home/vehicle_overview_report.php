<?php
if(session_status() === PHP_SESSION_NONE)
{
  session_start();
}
if($_SESSION['userid']==1)
{
   $userid='%'; 
}
else
{
   $userid = $_SESSION['userid']; 
}

include 'DB/config.php';
$page_title = "Vehicle Overview Report";

$mysql = new Mysql();
$mysql -> dbConnect();
$q = "SELECT COUNT(DISTINCT v.`id`) AS COUNT FROM `tbl_vehicles` v 
      INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
      WHERE v.`isdelete`=0 AND v.`status`=";
$q1 = $q.'1';
$active = mysqli_fetch_array($mysql -> selectFreeRun($q1));
$q2 = $q.'4';
$available = mysqli_fetch_array($mysql -> selectFreeRun($q2));
$q3 = $q.'3';
$garbage = mysqli_fetch_array($mysql -> selectFreeRun($q3));
$q4 = $q.'5';
$returned = mysqli_fetch_array($mysql -> selectFreeRun($q4));
$mysql -> dbDisConnect();
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
    .fontsize h1{
        font-size: 28px;
    }
    html body .font-light {
    font-weight: 500;
}
</style>
</head>

<body class="skin-default-dark fixed-layout">
<?php include('loader.php');?>

<div id="main-wrapper">

<?php

include('header.php');

?>
<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row"> 
          <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                    <?php include('report.php'); ?>
                        <div class="card">   
                            <div class="card-header" style="background-color: rgb(255 236 230);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="header">Dashboard</div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Inspected vs Non-inspected vehicles for the last 30 days</h4>
                                                <div id="bar-chart" style="width:100%; height:400px;"></div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                     </div>
                </div>
          </div>
      </div>
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row ">
                                    <!-- Column -->
                                    <div class="col p-r-0 fontsize">
                                        <h1 class="font-light" ><?= $active['COUNT'];?></h1>
                                        <h6 >Active Vehicles</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <!-- Column -->
                                <div class="col p-r-0 fontsize">
                                    <h1 class="font-light "><?= $available['COUNT'];?></h1>
                                    <h6 >Available Vehicles</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Column -->
                                    <div class="col p-r-0 fontsize">
                                        <h1 class="font-light"><?= $garbage['COUNT'];?></h1>
                                        <h6>Vehicles In Garage</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <!-- Column -->
                                <div class="col p-r-0 fontsize">
                                    <h1 class="font-light"><?= $returned['COUNT'];?></h1>
                                    <h6>Vehicles Returned</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row ">
                                    <!-- Column -->
                                    <div class="col p-r-0 fontsize">
                                        <h1 class="font-light">86</h1>
                                        <h6 >Hires last week</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <!-- Column -->
                                <div class="col p-r-0 fontsize">
                                    <h1 class="font-light">86</h1>
                                    <h6 >Off Hires last week</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title" >Vehicles Status Details</h4>
                        <div class="flot-chart" style="height:250px;">
                            <div class="flot-chart-content" id="flot-pie-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Top 5 Inspection Submissions</h4>
                        <div>
                            <canvas id="chart1" height="150"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Top 5 Failed Inspection Items</h4>
                        <div>
                            <canvas id="chart2" height="150"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php include('footer.php'); ?>
<?php include('footerScript.php'); ?>
<script src="../assets/node_modules/flot/excanvas.js"></script>
    <script src="../assets/node_modules/flot/jquery.flot.js"></script>
    <script src="../assets/node_modules/flot/jquery.flot.pie.js"></script>
    <!-- Chart JS -->
    <script src="../assets/node_modules/echarts/echarts-all.js"></script>
    <script src="../assets/node_modules/Chart.js/Chart.min.js"></script>
<script src="../assets/node_modules/datatables/datatables.min.js"></script>
<script>
$(function () {
    var data = [{
        label: "Active"
        , data: <?php echo $active['COUNT'];?>
        , color: "#4f5467"
    , }, {
        label: "Available"
        , data: <?php echo $available['COUNT'];?>
        , color: "#26c6da"
    , }, {
        label: "Garage"
        , data: <?php echo $garbage['COUNT'];?>
        , color: "#009efb"
    , }, {
        label: "Returned"
        , data: <?php echo $returned['COUNT'];?>
        , color: "#7460ee"
    , }];
    var plotObj = $.plot($("#flot-pie-chart"), data, {
        series: {
            pie: {
                innerRadius: 0.5
                , show: true
            }
        }
        , grid: {
            hoverable: true
        }
        , color: null
        , tooltip: true
        , tooltipOpts: {
            content: "%p.0%, %s", 
            shifts: {
                x: 20
                , y: 0
            }
            , defaultTheme: false
        }
    });
});

$(function() {
    $.ajax({
        method: "POST",
        url: "loaddata.php",
        data: {
            userid : '<?php echo $userid; ?>', action : 'getvehiclesechartdata'
        },
        dataType: "json",
        success: function(response){
            inspected = response['inspected'];
            notinspected = response['notinspected'];
            dates = response['dates'];
            chartplot(inspected,notinspected,dates);
        } 
    });
    $.ajax({
        method: "POST",
        url: "loaddata.php",
        data: {
            action : 'gettopinspectionchartdata'
        },
        dataType: "json",
        success: function(response){
            insname = response['insname'];
            inscnt = response['inscnt'];
            finsname = response['finsname'];
            finscnt = response['finscnt'];
           
            topins(insname,inscnt,finsname,finscnt);
        } 
    });
    
});
function topins(insname,inscnt,finsname,finscnt) {
    new Chart(document.getElementById("chart2"),
    {
    "type":"horizontalBar",
    "data":{
        "labels":finsname,
        
    "datasets":[{
                    "label":"Failed Inspection",
                    "data":finscnt,
                    "fill":false,
                    "backgroundColor":["rgba(255, 99, 132, 0.2)","rgba(255, 159, 64, 0.2)","rgba(255, 205, 86, 0.2)","rgba(75, 192, 192, 0.2)","rgba(54, 162, 235, 0.2)"],
                    "borderColor":["rgb(255, 99, 132)","rgb(255, 159, 64)","rgb(255, 205, 86)","rgb(75, 192, 192)","rgb(54, 162, 235)"],
                    "borderWidth":1}
                ]
        
    },
    'options': {
        'indexAxis': 'y',
        "scales":{"xAxes":[{"ticks":{"beginAtZero":true}}]},
        'responsive': true,
        'plugins': {
          'legend': {
            'position': 'right',
          },
        }
    }
});
    new Chart(document.getElementById("chart1"),
    {
    "type":"horizontalBar",
    "data":{
        "labels":insname,
        
    "datasets":[{
                    "label":"Inspection",
                    "data":inscnt,
                    "fill":false,
                    "backgroundColor":["rgba(255, 99, 132, 0.2)","rgba(255, 159, 64, 0.2)","rgba(255, 205, 86, 0.2)","rgba(75, 192, 192, 0.2)","rgba(54, 162, 235, 0.2)"],
                    "borderColor":["rgb(255, 99, 132)","rgb(255, 159, 64)","rgb(255, 205, 86)","rgb(75, 192, 192)","rgb(54, 162, 235)"],
                    "borderWidth":1}
                ]
        
    },
    'options': {
        'indexAxis': 'y',
        "scales":{"xAxes":[{"ticks":{"beginAtZero":true}}]},
        'responsive': true,
        'plugins': {
          'legend': {
            'position': 'right',
          },
        }
    }
});
    
}
function chartplot(inspected,notinspected,dates) {
    var myChart = echarts.init(document.getElementById('bar-chart'));

    option = {
    tooltip : {
        trigger: 'axis'
    },
    legend: {
        data:['Inspected','Not Inspected']
    },
    toolbox: {
        show : true,
        feature : {
            magicType : {show: true, type: ['line', 'bar']},
            restore : {show: true},
            saveAsImage : {show: true}
        }
    },
    color: ["#55ce63", "#009efb"],
    calculable : true,
    xAxis : [
        {
            type : 'category',
            data : dates
        }
    ],
    yAxis : [
        {
            type : 'value'
        }
    ],
    series : [
        {
            name:'Inspected',
            type:'bar',
            data:inspected
        },
        {
            name:'Not Inspected',
            type:'bar',
            data:notinspected
        }
    ]
};

    myChart.setOption(option, true), $(function() {
                    function resize() {
                        setTimeout(function() {
                            myChart.resize()
                        }, 100)
                    }
                    $(window).on("resize", resize), $(".sidebartoggler").on("click", resize)
                });
}

</script>
</body>



</html>