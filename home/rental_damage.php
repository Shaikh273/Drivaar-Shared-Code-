<?php
    $page_title="Drivaar";
    include 'DB/config.php';
    $page_id=59;
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
        $id = $_GET['id'];
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $query = "SELECT r.*,r.`vehicle_id` as vid,v.`registration_number` as regestrationnumber, s.`name` as suppliername,c.`name` as contaractorname,vm.`name` as makename,vmo.`name` as modelname FROM `tbl_vehiclerental_agreement` r
        INNER JOIN `tbl_contractor` c ON c.`id`= r.`driver_id`
        INNER JOIN `tbl_vehicles` v ON v.`id`=r.`vehicle_id`
        INNER JOIN `tbl_vehiclesupplier` s ON s.`id`=v.`supplier_id`
        INNER JOIN `tbl_vehiclemake` vm ON vm.`id`=v.`make_id`
        INNER JOIN `tbl_vehiclemodel` vmo ON vmo.`id`=v.`model_id`
        WHERE r.`id`=$id AND r.`isdelete`=0 AND r.`userid` LIKE '".$uid."'";
        $row =  $mysql -> selectFreeRun($query);
        $cntresult = mysqli_fetch_array($row);
        $mysql -> dbDisConnect();
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
<?php
$page_title="Rental Agreement Damages Page";
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
                                <div class="card-header" style="background-color: rgb(255 236 230);">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="header">
                                            <label><h2 style="font-size:30px;">Hire Agreement</h2></label><br>
                                            <small class="bg-secondary rounded px-2 d-inline-flex align-items-center">
                                                <i class="fa fa-user pr-2 pt-1" aria-hidden="true" style="font-size: 12px;"></i>
                                                <?php echo $cntresult['contaractorname']; ?>
                                            </small>
                                            <a href="#" class="text-gray-700">
                                                <small class="bg-secondary rounded px-2 d-inline-flex align-items-center">
                                                    <i class="fas fa-car pr-2 pt-1"></i>
                                                    <?php echo $cntresult['suppliername'] . ' (' . $cntresult['regestrationnumber'] . ')'; ?>
                                                </small>
                                            </a>
                                            <a href="#" class="text-gray-700">
                                                <small class="bg-secondary rounded px-2 d-inline-flex align-items-center">
                                                    <i class="fas fa-clock pt-1 pr-2"></i>
                                                    <?php echo $cntresult['insert_date']; ?>
                                                </small>
                                            </a>
                                       </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php include('rent_agreement_setting.php'); ?>
                                    <div class="col-md-12">
                                         <div class="card">    
                                            <div class="card-header " style="background-color: rgb(255 236 230);">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="header">Account Details</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-5">
                                <img src="imp_files/Depositphotos_144057949_s-2019 - Copy.jpg" alt="" usemap="#image-map" class="map" />
                                <map name="image-map">
            <area  alt="non driver right side wheels" title="non driver right side wheels" href="javascript: void(0);" coords="436,123,25" shape="circle" onclick="areamodel(this);">
            <area  alt="back right side wheels" title="back right side wheels" href="javascript: void(0);" coords="434,420,25" shape="circle" onclick="areamodel(this);">
            <area  alt="driver left side wheels" title="driver left side wheels" href="javascript: void(0);" coords="41,123,24" shape="circle" onclick="areamodel(this);">
            <area  alt="back left  side wheels" title="back left  side wheels" href="javascript: void(0);" coords="41,422,24" shape="circle" onclick="areamodel(this);">
            <area  alt="back left  side wheels" title="back left  side wheels" href="javascript: void(0);" coords="173,694,188,694,192,675,174,671" shape="poly" onclick="areamodel(this);">
            <area  alt="back right side wheels" title="back right side wheels" href="javascript: void(0);" coords="292,675,307,671,309,696,292,693" shape="poly" onclick="areamodel(this);">
            <area  alt="non diver right  side wheels" title="non diver right  side wheels" href="javascript: void(0);" coords="290,14,299,12,305,15,303,29,287,30" shape="poly" onclick="areamodel(this);">
            <area  alt="driver left  side wheels" title="driver left  side wheels" href="javascript: void(0);" coords="171,15,179,12,185,13,185,24,170,31" shape="poly" onclick="areamodel(this);">
            
            <area  alt="driver left side glass" title="driver left side glass" href="javascript: void(0);" coords="157,585,167,583,170,602,154,598" shape="poly" onclick="areamodel(this);">
            <area  alt="driver left side glass" title="driver left side glass" href="javascript: void(0);" coords="149,107,150,124,166,126,170,104" shape="poly" onclick="areamodel(this);">
            <area  alt="driver left side glass" title="driver left side glass" href="javascript: void(0);" coords="114,148,125,150,132,159,110,159" shape="poly" onclick="areamodel(this);">
            <area  alt="non driver right side glass" title="non driver right side glass" href="javascript: void(0);" coords="309,107,324,108,326,122,309,126" shape="poly" onclick="areamodel(this);">
            <area  alt="non driver right side glass" title="non driver right side glass" href="javascript: void(0);" coords="311,600,317,581,329,584,330,602" shape="poly" onclick="areamodel(this);">
            <area  alt="non driver right side glass" title="non driver right side glass" href="javascript: void(0);" coords="346,157,367,158,367,146,352,148" shape="poly" onclick="areamodel(this);">
            
            <area  alt="back left side light" title="back left side light" href="javascript: void(0);" coords="181,610,172,603,174,644,184,643" shape="poly" onclick="areamodel(this);">
            <area  alt="back left side light" title="back left side light" href="javascript: void(0);" coords="72,514,108,512,104,524,72,529" shape="poly" onclick="areamodel(this);">
            <area  alt="back right side light" title="back right side light" href="javascript: void(0);" coords="298,607,309,602,311,638,300,637" shape="poly" onclick="areamodel(this);">
            <area  alt="back right side light" title="back right side light" href="javascript: void(0);" coords="366,511,406,511,404,525,372,525" shape="poly" onclick="areamodel(this);">
            <area  alt="front diver left side head light" title="front diver left side head light" href="javascript: void(0);" coords="94,94,90,75,84,64,71,65,75,79,83,91"  shape="poly" onclick="areamodel(this);">
            <area  alt="front diver side head  light" title="front diver side head  light" href="javascript: void(0);" coords="170,91,195,78,199,62,177,67,171,76" shape="poly" onclick="areamodel(this);">
            <area  alt="front non diver right side head  light" title="front non diver right side head  light" href="javascript: void(0);"  coords="277,66,297,65,309,86,295,90,279,81" shape="poly" onclick="areamodel(this);">
            <area  alt="front non diver side head  light" title="front non diver side head  light" href="javascript: void(0);" coords="397,82,404,65,385,69,383,80,387,90"  shape="poly" onclick="areamodel(this);">
            <area  alt="bonat " title="bonat " href="javascript: void(0);"  coords="183,86,194,81,224,84,261,84,279,84,292,87,299,109,293,109,269,110,251,111,229,114,208,111,189,111,177,109,180,95" shape="poly" onclick="areamodel(this);">
            <area  alt="left side bonat" title="left side bonat" href="javascript: void(0);" coords="87,65,101,78,108,99,114,108,113,120,98,87" shape="poly" onclick="areamodel(this);">
            <area  alt="right side bonat" title="right side bonat" href="javascript: void(0);" coords="364,116,364,98,377,72,384,65,383,74" shape="poly" onclick="areamodel(this);">
            
            <area  alt="front glass" title="front glass" href="javascript: void(0);"  coords="203,113,299,111,296,132,293,151,273,155,252,156,231,155,211,156,185,152,181,137,177,111,260,112,231,111,279,112" shape="poly" onclick="areamodel(this);">
            <area  alt="front glass" title="front glass" href="javascript: void(0);" coords="116,113,159,156,157,162,114,123" shape="poly" onclick="areamodel(this);">
            <area  alt="front glass" title="front glass" href="javascript: void(0);" coords="319,154,359,112,361,119,344,137,318,164" shape="poly" onclick="areamodel(this);">
            
            <area  alt="left window glass" title="left window glass" href="javascript: void(0);" coords="151,169,159,207,146,213,121,204,115,192,110,168,134,155,144,165"  shape="poly" onclick="areamodel(this);">
            <area  alt="right window glass" title="right window glass" href="javascript: void(0);"  coords="323,168,340,154,366,168,362,186,357,201,338,207,320,208,319,192,321,181" shape="poly" onclick="areamodel(this);">
            
            <area  alt="front bumper guard" title="front bumper guard" href="javascript: void(0);" coords="262,31,278,26,296,24,306,38,308,63,299,66,276,61,250,58,219,57,199,62,185,64,174,65,162,55,167,38,178,27,212,30,239,32" shape="poly" onclick="areamodel(this);">
            <area  alt="front bumper guard" title="front bumper guard" href="javascript: void(0);" coords="67,109,67,89,73,78,72,61,57,50,39,55,31,73,29,99,48,93" shape="poly" onclick="areamodel(this);">
            <area  alt="front bumper guard" title="front bumper guard" href="javascript: void(0);" coords="401,73,408,58,419,50,431,50,442,62,446,77,446,96,431,93,417,98,408,111,408,85" shape="poly" onclick="areamodel(this);">
            <area  alt="front left bumper guard" title="front left bumper guard" href="javascript: void(0);"  coords="66,89,69,107,57,100,46,94,34,94,33,78,38,67,43,56,49,55,59,54,67,63,71,75" shape="poly" onclick="areamodel(this);">
            
            <area  alt="back bumper guard" title="back bumper guard" href="javascript: void(0);" coords="66,437,72,532,44,529,35,484,34,456,55,453" shape="poly" onclick="areamodel(this);">
            <area  alt="back bumper guard" title="back bumper guard" href="javascript: void(0);" coords="404,524,405,436,426,451,441,452,440,480,436,508,436,528,419,528" shape="poly" onclick="areamodel(this);">
            <area  alt="back bumper guard" title="back bumper guard" href="javascript: void(0);" coords="171,646,171,671,191,682,214,674,238,682,257,675,285,678,308,678,312,645,302,641,296,662,268,662,241,663,216,663,187,662,183,642" shape="poly" onclick="areamodel(this);">
            
            <area  alt="roof" title="roof" href="javascript: void(0);" coords="180,154,295,156,285,187,282,304,281,418,281,504,238,512,191,506,191,418,192,313,192,193" shape="poly" onclick="areamodel(this);">
            <area  alt="driver  side door" title="driver  side door" href="javascript: void(0);"  coords="34,180,37,167,78,144,103,144,112,191,122,207,152,211,164,205,167,218,145,219,127,215,113,215,95,214,70,213,50,213,40,204,35,195" shape="poly" onclick="areamodel(this);">
            <area  alt="non driver  side door" title="non driver  side door" href="javascript: void(0);"  coords="367,141,402,143,420,159,441,163,438,190,435,209,384,215,354,216,330,218,311,219,318,172,321,209,350,210,372,152" shape="poly" onclick="areamodel(this);">
            <area  alt="driver side body" title="driver side body" href="javascript: void(0);" coords="183,310,183,411,182,518,134,523,108,526,111,514,71,512,70,439,70,413,56,392,35,393,33,221,90,221,150,222,182,223" shape="poly" onclick="areamodel(this);">
            <area  alt="non driver side body" title="non driver side body" href="javascript: void(0);" coords="292,222,289,325,288,513,370,524,368,511,407,514,406,435,406,407,419,392,440,385,437,285,439,221,372,219" shape="poly" onclick="areamodel(this);">
            <area alt="back door driver side glass" title="back door driver side glass" href="javascript: void(0);" coords="240,553,194,554,191,577,193,599,215,602,242,600" shape="poly" onclick="areamodel(this);">
            <area alt="back door non driver side glass" title="back door non driver side glass"  href="javascript: void(0);" coords="242,552,286,552,290,573,288,596,264,600,241,600,242,582" shape="poly" onclick="areamodel(this);">
            <area alt="driver back door" title="driver back door" href="javascript: void(0);"coords="241,522,274,522,289,527,298,561,300,584,299,606,300,654,280,663,242,661,242,599,270,601,287,595,292,575,287,551,270,552,257,551,242,552" shape="poly" onclick="areamodel(this);">
            <area alt="non driver back door" title="non driver back door" href="javascript: void(0);" coords="191,598,218,603,241,599,242,633,243,662,212,661,186,662,182,638,182,609,190,539,197,523,239,524,240,552,219,553,193,554,192,575" shape="poly" onclick="areamodel(this);">
            <area  alt="radiator cover" title="radiator cover" href="javascript: void(0);" coords="194,79,204,62,227,57,252,56,273,65,281,82,237,85" shape="poly" onclick="areamodel(this);">
            <area  alt="driver side fender" title="driver side fender" href="javascript: void(0);"  coords="89,92,94,83,95,74,101,90,104,97,116,122,114,130,105,124,106,138,98,141,79,143,62,156,37,164,33,157,72,130,65,94,72,77,80,85" shape="poly" onclick="areamodel(this);">
            <area  alt="non driver side fender" title="non driver side fender" href="javascript: void(0);"  coords="364,117,380,77,384,85,391,89,399,82,404,75,410,90,409,98,409,106,406,125,406,135,418,149,428,151,442,155,438,161,427,161,414,157,397,140,379,142,371, 139,364,138" shape="poly" onclick="areamodel(this);">
            <area  alt="non driver side wheel" title="non driver side wheel" href="javascript: void(0);" coords="435,122,23" shape="circle">
            <area  alt="driver side wheel" title="driver side wheel" href="javascript: void(0);" coords="41,125,24" shape="circle">
            <area  alt="back non driver side wheel" title="back non driver side wheel" href="javascript: void(0);" coords="42,421,24" shape="circle">
            <area  alt="back driver side wheel" title="back driver side wheel" href="javascript: void(0);" coords="434,419,26" shape="circle">
            <area  alt="front driver side wheel" title="front driver side wheel" href="javascript: void(0);" coords="170,12,188,11,189,27,170,33" shape="poly">
            <area  alt="front non driver side wheel" title="front non driver side wheel" href="javascript: void(0);" coords="306,14,288,13,290,27,307,34" shape="poly">
            <area  alt="back driver side wheel" title="back driver side wheel" href="javascript: void(0);" coords="172,693,173,675,192,675,193,695" shape="poly">
            <area  alt="back non driver side wheel" title="back non driver side wheel" href="javascript: void(0);" coords="290,694,311,693,310,671,290,672" shape="poly">
        </map>
                            </div>
                                                    <div class="col-md-7">
                                                    <div id="uploaddamagesdiv" class="row">
                                    
                                                        <div class="col-md-12" >
                                                          <div class="card datacard">
                                                              <div class="card-header dataheader">
                                                                  <h6 class="mb-2 cardmb2">Vehicle Details</h6>
                                                              </div>    
                                                             <div class="card-body">
                                                                  <dl class="row mb-0 mb-4" style="line-height: 1.2;">
                                                                    <dt class="col-md-4 mb-2">Vehicle Reg:</dt>
                                                                    <dd class="col-md-8 mb-2 fortWeight">
                                                                        <?php echo $cntresult['regestrationnumber']?>
                                                                    </dd>
                    
                                                                    <dt class="col-md-4 mb-2">Make:</dt>
                                                                    <dd class="col-md-8 mb-2 fortWeight">
                                                                        <?php echo $cntresult['makename']?>
                                                                    </dd>
                    
                                                                    <dt class="col-md-4 mb-2">Model:</dt>
                                                                    <dd class="col-md-8 mb-2 fortWeight">
                                                                        <?php echo $cntresult['modelname']?>
                                                                    </dd>
                                                                   
                                                                  </dl>
                                                              </div>
                                                          </div>
                                                        </div>
                                                       <div id="vehicledetails" style="width:100%;">
                                                          
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
            
            <?php
                include('footer.php');
            ?>
        </div>
        
        <?php
            include('footerScript.php');
        ?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/maphilight/1.4.0/jquery.maphilight.min.js"></script>
<script>

    $(function(){
        $('.map').maphilight({
            fill:true,
            fillColor:'03a9f3',
            fillOpacity: 0.4,
            stroke: false
        });
        ajaxcall();
    });  
    
    function ajaxcall() {
        var userid = <?php echo $userid ?>;
        var id = <?php echo $cntresult['vid']; ?>;
        $.ajax({
                type: 'POST',
                url: 'loaddata.php',
                data:'userid='+userid+'&id='+id+'&action=vehicleDamagedata',
                success: function(data)
                { 
                    var tabledata = data.data;
                    $("#damagetbl tbody").append(tabledata);
                    $("#damagetbl").DataTable();
                    $("#vehicledetails").append(data.div);
                    $(".delete").remove();
                    // console.log(data.part);
                    var obj1 = JSON.parse(JSON.stringify(data.part));
                    var i =0;
                    var j = obj1.length;
                    for(i = 0; i<j; i++) {
                        // console.log(data.type[i]);
                        var fillcolor;
                        var c = data.type[i];
                        var c1 = data.part[i];
                        var c2 = data.state[i];
                        if(c2 == 0) {
                            if(c == 1) {
                                fillcolor = '00c292';
                            } else if(c == 2) {
                                fillcolor = 'fec107';
                            } else if(c == 3) {
                                fillcolor = 'e46a76';
                            }
                            $("area[title='"+c1+"']").each(function(){
                              var data = $(this).data('maphilight') || {};
                                data.alwaysOn = true;
                                data.fillColor = fillcolor;
                                data.fill = true;
                                $(this).data('maphilight', data).trigger('alwaysOn.maphilight');
                            });
                        } else {
                            $("area[title='"+c1+"']").each(function(){
                              var data = $(this).data('maphilight') || {};
                                data.alwaysOn = false;
                                $(this).data('maphilight', data).trigger('alwaysOn.maphilight');
                            });
                        }
                        
                    }
                    
                }
        });
    }
                
    </script>

    </body>
</html>