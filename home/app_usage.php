<?php
    $page_title="Drivaar";
    include 'DB/config.php';
    $page_id=144;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        if ($_SESSION['userid']== 1) 
        {
            $userid = '%';
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
<?php

$page_title = "Usage";

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
    <style>
        
    </style>
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
                        <div class="row">
                            <div class="card col-md-12">
                                <div class="card-body">
                                    <?php include('setting.php'); ?>
                                    <div class="col-md-12">
                                         <div class="card">    
                                            <div class="card-header " style="background-color: rgb(255 236 230);">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="header">Application Usage</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                       
                                                                <!-- <h4 class="card-title">Contractor Invoice Report</h4> -->
                                                                <div id="bar-chart1" style="width:100%; height:400px;"></div>
                                                            
                                                    </div>
                                                </div>
                                            </div>        
                                    </div>
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
        
        
    </body>
    <!-- Chart JS -->
    <script src="../assets/node_modules/echarts/echarts-all.js"></script>
<script>
    $(function() {
        
        $.ajax({
            method: "POST",
            url: "loaddata.php",
            data: {
                userid : '<?php echo $userid; ?>', action : 'getusageechartdata'
            },
            dataType: "json",
            success: function(response){
                Contractors = response['Contractors'];
                Vehicles = response['Vehicles'];
                Workforce = response['Workforce'];
                chartplot(Contractors,Vehicles,Workforce);
            } 
        });
        
            
        function chartplot(Contractors,Vehicles,Workforce) {
                var myChart = echarts.init(document.getElementById('bar-chart1'));
                option = {
                    tooltip : {
                        trigger: 'axis'
                    },
                    legend: {
                        data:['Contractors','Vehicles','Workforce']
                    },
                    toolbox: {
                        show : true,
                        feature : {
                            magicType : {show: true, type: ['line', 'bar']},
                            restore : {show: true},
                            saveAsImage : {show: true}
                        }
                    },
                    color: ["#55ce63", "#009efb", "#e83e65"],
                    calculable : true,
                    xAxis : [
                        {
                            type : 'category',
                            data : ['Jan','Feb','Mar','Apr','May','Jun','July','Aug','Sept','Oct','Nov','Dec']
                        }
                    ],
                    yAxis : [
                        {
                            type : 'value'
                        }
                    ],
                    series : [
                        {
                            name:'Contractors',
                            type:'bar',
                            data:Contractors,
                        },
                        {
                            name:'Vehicles',
                            type:'bar',
                            data:Vehicles,
                        },
                        {
                            name:'Workforce',
                            type:'bar',
                            data:Workforce,
                        }
                        // {
                        //     name:'Documents',
                        //     type:'bar',
                        //     data:dispute,
                        // }
                        
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
    });      
</script>
</html>