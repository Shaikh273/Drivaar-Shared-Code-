<?php

$page_title = "Vehicle Damage Report";

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
                    <div class="row">
                      <div class="col-md-4">

                      </div>
                      <div class="col-md-8">
                        <div class="card">
    
            <div id="js-trend" style="min-height: 245px;"><div id="apexchartseujkvr0h" class="apexcharts-canvas apexchartseujkvr0h apexcharts-theme-light" style="width: 917px; height: 230px;"><svg id="SvgjsSvg1216" width="917" height="230" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><g id="SvgjsG1218" class="apexcharts-inner apexcharts-graphical" transform="translate(33.125, 54)"><defs id="SvgjsDefs1217"><linearGradient id="SvgjsLinearGradient1223" x1="0" y1="0" x2="0" y2="1"><stop id="SvgjsStop1224" stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)" offset="0"></stop><stop id="SvgjsStop1225" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop><stop id="SvgjsStop1226" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop></linearGradient><clipPath id="gridRectMaskeujkvr0h"><rect id="SvgjsRect1230" width="899.875" height="144.2" x="-14" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMaskeujkvr0h"></clipPath><clipPath id="nonForecastMaskeujkvr0h"></clipPath><clipPath id="gridRectMarkerMaskeujkvr0h"><rect id="SvgjsRect1231" width="877.875" height="148.2" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath></defs><rect id="SvgjsRect1227" width="0" height="144.2" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke-dasharray="3" fill="url(#SvgjsLinearGradient1223)" class="apexcharts-xcrosshairs" y2="144.2" filter="none" fill-opacity="0.9"></rect><g id="SvgjsG1236" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG1237" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"><text id="SvgjsText1239" font-family="Helvetica, Arial, sans-serif" x="0" y="173.2" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1240"></tspan><title></title></text><text id="SvgjsText1242" font-family="Helvetica, Arial, sans-serif" x="145.64583333333331" y="173.2" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1243"></tspan><title></title></text><text id="SvgjsText1245" font-family="Helvetica, Arial, sans-serif" x="291.2916666666667" y="173.2" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1246"></tspan><title></title></text><text id="SvgjsText1248" font-family="Helvetica, Arial, sans-serif" x="436.93750000000006" y="173.2" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1249"></tspan><title></title></text><text id="SvgjsText1251" font-family="Helvetica, Arial, sans-serif" x="582.5833333333335" y="173.2" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1252"></tspan><title></title></text><text id="SvgjsText1254" font-family="Helvetica, Arial, sans-serif" x="728.2291666666669" y="173.2" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1255"></tspan><title></title></text><text id="SvgjsText1257" font-family="Helvetica, Arial, sans-serif" x="873.8750000000002" y="173.2" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1258"></tspan><title></title></text></g><line id="SvgjsLine1259" x1="0" y1="145.2" x2="873.875" y2="145.2" stroke="#e0e0e0" stroke-dasharray="0" stroke-width="1"></line></g><g id="SvgjsG1274" class="apexcharts-grid"><g id="SvgjsG1275" class="apexcharts-gridlines-horizontal"><line id="SvgjsLine1277" x1="0" y1="0" x2="873.875" y2="0" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-gridline"></line><line id="SvgjsLine1278" x1="0" y1="28.839999999999996" x2="873.875" y2="28.839999999999996" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-gridline"></line><line id="SvgjsLine1279" x1="0" y1="57.67999999999999" x2="873.875" y2="57.67999999999999" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-gridline"></line><line id="SvgjsLine1280" x1="0" y1="86.51999999999998" x2="873.875" y2="86.51999999999998" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-gridline"></line><line id="SvgjsLine1281" x1="0" y1="115.35999999999999" x2="873.875" y2="115.35999999999999" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-gridline"></line><line id="SvgjsLine1282" x1="0" y1="144.2" x2="873.875" y2="144.2" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-gridline"></line></g><g id="SvgjsG1276" class="apexcharts-gridlines-vertical"></g><rect id="SvgjsRect1283" width="873.875" height="28.839999999999996" x="0" y="0" rx="0" ry="0" opacity="0.5" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#f3f3f3" clip-path="url(#gridRectMaskeujkvr0h)" class="apexcharts-grid-row"></rect><rect id="SvgjsRect1284" width="873.875" height="28.839999999999996" x="0" y="28.839999999999996" rx="0" ry="0" opacity="0.5" stroke-width="0" stroke="none" stroke-dasharray="0" fill="transparent" clip-path="url(#gridRectMaskeujkvr0h)" class="apexcharts-grid-row"></rect><rect id="SvgjsRect1285" width="873.875" height="28.839999999999996" x="0" y="57.67999999999999" rx="0" ry="0" opacity="0.5" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#f3f3f3" clip-path="url(#gridRectMaskeujkvr0h)" class="apexcharts-grid-row"></rect><rect id="SvgjsRect1286" width="873.875" height="28.839999999999996" x="0" y="86.51999999999998" rx="0" ry="0" opacity="0.5" stroke-width="0" stroke="none" stroke-dasharray="0" fill="transparent" clip-path="url(#gridRectMaskeujkvr0h)" class="apexcharts-grid-row"></rect><rect id="SvgjsRect1287" width="873.875" height="28.839999999999996" x="0" y="115.35999999999999" rx="0" ry="0" opacity="0.5" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#f3f3f3" clip-path="url(#gridRectMaskeujkvr0h)" class="apexcharts-grid-row"></rect><line id="SvgjsLine1289" x1="0" y1="144.2" x2="873.875" y2="144.2" stroke="transparent" stroke-dasharray="0"></line><line id="SvgjsLine1288" x1="0" y1="1" x2="0" y2="144.2" stroke="transparent" stroke-dasharray="0"></line></g><g id="SvgjsG1232" class="apexcharts-bar-series apexcharts-plot-series"><g id="SvgjsG1233" class="apexcharts-series" rel="1" seriesName="Damages" data:realIndex="0"></g></g><line id="SvgjsLine1290" x1="0" y1="0" x2="873.875" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine1291" x1="0" y1="0" x2="873.875" y2="0" stroke-dasharray="0" stroke-width="0" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG1292" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG1293" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG1294" class="apexcharts-point-annotations"></g></g><text id="SvgjsText1220" font-family="Helvetica, Arial, sans-serif" x="10" y="16.5" text-anchor="start" dominant-baseline="auto" font-size="14px" font-weight="900" fill="#373d3f" class="apexcharts-title-text" style="font-family: Helvetica, Arial, sans-serif; opacity: 1;">Number of damages</text><g id="SvgjsG1234" class="apexcharts-datalabels" data:realIndex="0"></g><g id="SvgjsG1235" class="apexcharts-bar-goals-markers" style="pointer-events: none"></g><g id="SvgjsG1260" class="apexcharts-yaxis" rel="0" transform="translate(3.125, 0)"><g id="SvgjsG1261" class="apexcharts-yaxis-texts-g"><text id="SvgjsText1262" font-family="Helvetica, Arial, sans-serif" x="20" y="55.5" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1263">5</tspan><title>5</title></text><text id="SvgjsText1264" font-family="Helvetica, Arial, sans-serif" x="20" y="84.34" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1265">4</tspan><title>4</title></text><text id="SvgjsText1266" font-family="Helvetica, Arial, sans-serif" x="20" y="113.18" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1267">3</tspan><title>3</title></text><text id="SvgjsText1268" font-family="Helvetica, Arial, sans-serif" x="20" y="142.02" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1269">2</tspan><title>2</title></text><text id="SvgjsText1270" font-family="Helvetica, Arial, sans-serif" x="20" y="170.86" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1271">1</tspan><title>1</title></text><text id="SvgjsText1272" font-family="Helvetica, Arial, sans-serif" x="20" y="199.70000000000002" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;"><tspan id="SvgjsTspan1273">0</tspan><title>0</title></text></g></g><g id="SvgjsG1219" class="apexcharts-annotations"></g></svg><div class="apexcharts-legend" style="max-height: 115px;"></div></div></div>
    
    <div class="resize-triggers"><div class="expand-trigger"><div style="width: 918px; height: 60px;"></div></div><div class="contract-trigger"></div></div></div>
                      </div>
                    </div>  
                        <div class="card">   
                                <div class="card-header" style="background-color: rgb(255 236 230);">
                                    <div class="d-flex justify-content-between align-items-center">
                                            <div class="header">Damages</div>
                                       </div>
                                </div>
                                <div class="card-body">
                                     
                                  <table class="table table-responsive">
                                            <thead class="default">
                                                <tr>
                                                <th>Date</th>
                                                <th>Vehicle Reg.</th>
                                                <th>Description</th>
                                                <th>Depot</th>
                                                <th>DA Name</th>
                                                <th>Garage</th>
                                                <th>Invoice Amount</th>
                                                <th>Excess Amount</th>
                                                <th>Diff</th>
                                                <th>Reference</th>
                                                <th>Paid</th>
                                                <th>Subtotal</th>
                                            </tr>
                                            </thead>
                                            <tbody id="extendedTable">
                                                
                                            </tbody>
                                  </table>
                                  
                              </div>
                          </div> 
                      </div>
                  </div>
          </div>
      </div>
    </div>
  </div>
</div>


<?php include('footer.php');?>

</div>



<?php include('footerScript.php');?>
<script>
    
$(document).ready(function(){
      
    // $('#myTable').DataTable({
    //     'processing': true,
    //     'serverSide': true,
    //     'serverMethod': 'post',
    //     'ajax': {
    //         'url':'loadtabledata1.php',
    //         'data': {
    //             'action': 'loadleaverequesttabledata',
    //         }
    //     },
    //     'columns': [
    //         { data: 'name' },
    //         { data: 'period' },
    //         { data: 'status' },
    //         { data: 'action' }
            
    //     ]
    // });

});
</script>


</body>
</html>