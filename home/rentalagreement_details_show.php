<?php
    $page_title="Drivaar";
    include 'DB/config.php';
    $page_id=58;
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
        if(!isset($_GET['id']) && base64_encode(base64_decode($_GET['id'])) != $_GET['id'])
        {
            header("Location: rent_agreements.php");
        }
        $id = base64_decode($_GET['id']);
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $query = "SELECT r.*,c.*,DATE_FORMAT(r.`pickup_date`,'%D %M%, %Y %H %i %s') as pickup_date1,v.`registration_number` as regestrationnumber,tc.`name` as passportcountryname,tcc.`name` as countryname, s.`name` as suppliername,c.`name` as contaractorname FROM `tbl_vehiclerental_agreement` r
        INNER JOIN `tbl_contractor` c ON c.`id`= r.`driver_id`
        INNER JOIN `tbl_vehicles` v ON v.`id`=r.`vehicle_id`
        INNER JOIN `tbl_vehiclesupplier` s ON s.`id`=v.`supplier_id`
        LEFT JOIN `tbl_country` tc ON tc.`id`=c.`passport_country`
        LEFT JOIN `tbl_country` tcc ON tcc.`id`=c.`country`
        WHERE r.`id`=$id AND r.`isdelete`=0";
        $row =  $mysql -> selectFreeRun($query);
        if($cntresult = mysqli_fetch_array($row))
        {

        }else
        {
            header("Location: rent_agreements.php");
        }


        $query1 = "SELECT * FROM `tbl_company` WHERE `isdelete`=0 AND `isactive`=0";
        $row1 =  $mysql -> selectFreeRun($query1);
        $cntresult1 = mysqli_fetch_array($row1);


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
$page_title="Rental Agreement Details";
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
        body {
            font-weight: 390;
        }
        @media only screen and (min-device-width: 480px){
        
        .label{
            font-weight: 600;
        }

        .a{
            font-weight: 500;
        }

        .widget-box {
            padding: 0;
            box-shadow: none;
            margin: 3px 0;
            border: 1px solid #000
        }

        .lable-header {
            font-size: 20px;
            color: black;
        }

        @media only screen and (max-width:767px) {
            .widget-box {
                margin-top: 7px;
                margin-bottom: 7px
            }
        }

        .widget-header {
            -webkit-box-sizing: content-box;
            -moz-box-sizing: content-box;
            box-sizing: content-box;
            position: relative;
            min-height: 38px;
            background: repeat-x #f7f7f7;
            background-image: -webkit-linear-gradient(top, #FFF 0, #EEE 100%);
            background-image: -o-linear-gradient(top, #FFF 0, #EEE 100%);
            background-image: linear-gradient(to bottom, #FFF 0, #EEE 100%);
            filter: progid: DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff', endColorstr='#ffeeeeee', GradientType=0);
            color: #669FC7;
            border-bottom: 1px solid #000;
            padding-left: 12px
        }

        .widget-box.transparent>.widget-header,
        .widget-header-flat {
            filter: progid: DXImageTransform.Microsoft.gradient(enabled=false)
        }

        .widget-header:after,
        .widget-header:before {
            content: "";
            display: table;
            line-height: 0
        }

        .widget-header:after {
            clear: right
        }

        .widget-box.collapsed>.widget-header {
            border-bottom-width: 0
        }

        .collapsed.fullscreen>.widget-header {
            border-bottom-width: 1px
        }

        .collapsed>.widget-body {
            display: none
        }

        .widget-header-flat {
            background: #F7F7F7
        }

        .widget-header-large {
            min-height: 49px;
            padding-left: 18px
        }

        .widget-header-small {
            min-height: 31px;
            padding-left: 10px
        }

        .widget-header>.widget-title {
            line-height: 36px;
            padding: 0;
            margin: 0;
            display: inline
        }

        .widget-header>.widget-title>.ace-icon {
            margin-right: 5px;
            font-weight: 400;
            display: inline-block
        }

        .infobox .infobox-content:first-child,
        .infobox>.badge,
        .infobox>.stat,
        .percentage {
            font-weight: 700
        }

        .widget-header-large>.widget-title {
            line-height: 48px
        }

        .widget-header-small>.widget-title {
            line-height: 30px
        }

        .widget-toolbar {
            display: inline-block;
            padding: 0 10px;
            line-height: 37px;
            float: right;
            position: relative
        }

        .widget-header-large>.widget-toolbar {
            line-height: 48px
        }

        .widget-header-small>.widget-toolbar {
            line-height: 29px
        }

        .widget-toolbar.no-padding {
            padding: 0
        }

        .widget-toolbar.padding-5 {
            padding: 0 5px
        }

        .widget-toolbar:before {
            display: inline-block;
            content: "";
            position: absolute;
            top: 3px;
            bottom: 3px;
            left: -1px;
            border: 1px solid #D9D9D9;
            border-width: 0 1px 0 0
        }

        .popover-notitle+.popover .popover-title,
        .popover.popover-notitle .popover-title,
        .widget-toolbar.no-border:before {
            display: none
        }

        .widget-header-large>.widget-toolbar:before {
            top: 6px;
            bottom: 6px
        }

        [class*=widget-color-]>.widget-header>.widget-toolbar:before {
            border-color: #EEE
        }

        .widget-color-orange>.widget-header>.widget-toolbar:before {
            border-color: #FEA
        }

        .widget-color-dark>.widget-header>.widget-toolbar:before {
            border-color: #222;
            box-shadow: -1px 0 0 rgba(255, 255, 255, .2), inset 1px 0 0 rgba(255, 255, 255, .1)
        }

        .widget-toolbar label {
            display: inline-block;
            vertical-align: middle;
            margin-bottom: 0
        }

        .widget-toolbar>.widget-menu>a,
        .widget-toolbar>a {
            font-size: 14px;
            margin: 0 1px;
            display: inline-block;
            padding: 0;
            line-height: 24px
        }

        .widget-toolbar>.widget-menu>a:hover,
        .widget-toolbar>a:hover {
            text-decoration: none
        }

        .widget-header-large>.widget-toolbar>.widget-menu>a,
        .widget-header-large>.widget-toolbar>a {
            font-size: 15px;
            margin: 0 1px
        }

        .widget-toolbar>.btn {
            line-height: 27px;
            margin-top: -2px
        }

        .widget-toolbar>.btn.smaller {
            line-height: 26px
        }

        .widget-toolbar>.btn.bigger {
            line-height: 28px
        }

        .widget-toolbar>.btn-sm {
            line-height: 24px
        }

        .widget-toolbar>.btn-sm.smaller {
            line-height: 23px
        }

        .widget-toolbar>.btn-sm.bigger {
            line-height: 25px
        }

        .widget-toolbar>.btn-xs {
            line-height: 22px
        }

        .widget-toolbar>.btn-xs.smaller {
            line-height: 21px
        }

        .widget-toolbar>.btn-xs.bigger {
            line-height: 23px
        }

        .widget-toolbar>.btn-minier {
            line-height: 18px
        }

        .widget-toolbar>.btn-minier.smaller {
            line-height: 17px
        }

        .widget-toolbar>.btn-minier.bigger {
            line-height: 19px
        }

        .widget-toolbar>.btn-lg {
            line-height: 36px
        }

        .widget-toolbar>.btn-lg.smaller {
            line-height: 34px
        }

        .widget-toolbar>.btn-lg.bigger {
            line-height: 38px
        }

        .widget-toolbar-dark {
            background: #444
        }

        .widget-toolbar-light {
            background: rgba(255, 255, 255, .85)
        }

        .widget-toolbar>.widget-menu {
            display: inline-block;
            position: relative
        }

        .widget-toolbar>.widget-menu>a[data-action],
        .widget-toolbar>a[data-action] {
            -webkit-transition: transform .1s;
            -o-transition: transform .1s;
            transition: transform .1s
        }

        .widget-toolbar>.widget-menu>a[data-action]>.ace-icon,
        .widget-toolbar>a[data-action]>.ace-icon {
            margin-right: 0
        }

        .widget-toolbar>.widget-menu>a[data-action]:focus,
        .widget-toolbar>a[data-action]:focus {
            text-decoration: none;
            outline: 0
        }

        .widget-toolbar>.widget-menu>a[data-action]:hover,
        .widget-toolbar>a[data-action]:hover {
            -moz-transform: scale(1.2);
            -webkit-transform: scale(1.2);
            -o-transform: scale(1.2);
            -ms-transform: scale(1.2);
            transform: scale(1.2)
        }

        .widget-body {
            background-color: #FFF
        }

        .widget-main {
            padding: 12px
        }

        .widget-main.padding-32 {
            padding: 32px
        }

        .widget-main.padding-30 {
            padding: 30px
        }

        .widget-main.padding-28 {
            padding: 28px
        }

        .widget-main.padding-26 {
            padding: 26px
        }

        .widget-main.padding-24 {
            padding: 24px
        }

        .widget-main.padding-22 {
            padding: 22px
        }

        .widget-main.padding-20 {
            padding: 20px
        }

        .widget-main.padding-18 {
            padding: 18px
        }

        .widget-main.padding-16 {
            padding: 16px
        }

        .widget-main.padding-14 {
            padding: 14px
        }

        .widget-main.padding-12 {
            padding: 12px
        }

        .widget-main.padding-10 {
            padding: 10px
        }

        .widget-main.padding-8 {
            padding: 8px
        }

        .widget-main.padding-6 {
            padding: 6px
        }

        .widget-main.padding-4 {
            padding: 4px
        }

        .widget-main.padding-2 {
            padding: 2px
        }

        .widget-main.no-padding,
        .widget-main.padding-0 {
            padding: 0
        }

        .widget-toolbar .progress {
            vertical-align: middle;
            display: inline-block;
            margin: 0
        }

        .widget-toolbar>.dropdown,
        .widget-toolbar>.dropup {
            display: inline-block
        }

        .widget-toolbox.toolbox-vertical,
        .widget-toolbox.toolbox-vertical+.widget-main {
            display: table-cell;
            vertical-align: top
        }

        .widget-box>.widget-header>.widget-toolbar>.widget-menu>[data-action=settings],
        .widget-box>.widget-header>.widget-toolbar>[data-action=settings],
        .widget-color-dark>.widget-header>.widget-toolbar>.widget-menu>[data-action=settings],
        .widget-color-dark>.widget-header>.widget-toolbar>[data-action=settings] {
            color: #99CADB
        }

        .widget-box>.widget-header>.widget-toolbar>.widget-menu>[data-action=reload],
        .widget-box>.widget-header>.widget-toolbar>[data-action=reload],
        .widget-color-dark>.widget-header>.widget-toolbar>.widget-menu>[data-action=reload],
        .widget-color-dark>.widget-header>.widget-toolbar>[data-action=reload] {
            color: #ACD392
        }

        .widget-box>.widget-header>.widget-toolbar>.widget-menu>[data-action=collapse],
        .widget-box>.widget-header>.widget-toolbar>[data-action=collapse],
        .widget-color-dark>.widget-header>.widget-toolbar>.widget-menu>[data-action=collapse],
        .widget-color-dark>.widget-header>.widget-toolbar>[data-action=collapse] {
            color: #AAA
        }

        .widget-box>.widget-header>.widget-toolbar>.widget-menu>[data-action=close],
        .widget-box>.widget-header>.widget-toolbar>[data-action=close],
        .widget-color-dark>.widget-header>.widget-toolbar>.widget-menu>[data-action=close],
        .widget-color-dark>.widget-header>.widget-toolbar>[data-action=close] {
            color: #E09E96
        }

        .widget-box[class*=widget-color-]>.widget-header {
            color: #FFF;
            filter: progid: DXImageTransform.Microsoft.gradient(enabled=false)
        }

        .widget-color-blue {
            border-color: #307ECC
        }

        .widget-color-blue>.widget-header {
            background: #307ECC;
            border-color: #307ECC
        }

        .widget-color-blue2 {
            border-color: #5090C1
        }

        .widget-color-blue2>.widget-header {
            background: #5090C1;
            border-color: #5090C1
        }

        .widget-color-blue3 {
            border-color: #6379AA
        }

        .widget-color-blue3>.widget-header {
            background: #6379AA;
            border-color: #6379AA
        }

        .widget-color-green {
            border-color: #82AF6F
        }

        .widget-color-green>.widget-header {
            background: #82AF6F;
            border-color: #82AF6F
        }

        .widget-color-green2 {
            border-color: #2E8965
        }

        .widget-color-green2>.widget-header {
            background: #2E8965;
            border-color: #2E8965
        }

        .widget-color-green3 {
            border-color: #4EBC30
        }

        .widget-color-green3>.widget-header {
            background: #4EBC30;
            border-color: #4EBC30
        }

        .widget-color-red {
            border-color: #E2755F
        }

        .widget-color-red>.widget-header {
            background: #E2755F;
            border-color: #E2755F
        }

        .widget-color-red2 {
            border-color: #E04141
        }

        .widget-color-red2>.widget-header {
            background: #E04141;
            border-color: #E04141
        }

        .widget-color-red3 {
            border-color: #D15B47
        }

        .widget-color-red3>.widget-header {
            background: #D15B47;
            border-color: #D15B47
        }

        .widget-color-purple {
            border-color: #7E6EB0
        }

        .widget-color-purple>.widget-header {
            background: #7E6EB0;
            border-color: #7E6EB0
        }

        .widget-color-pink {
            border-color: #CE6F9E
        }

        .widget-color-pink>.widget-header {
            background: #CE6F9E;
            border-color: #CE6F9E
        }

        .widget-color-orange {
            border-color: #E8B10D
        }

        .widget-color-orange>.widget-header {
            color: #855D10!important;
            border-color: #E8B10D;
            background: #FFC657
        }

        .widget-color-dark {
            border-color: #5a5a5a
        }

        .widget-color-dark>.widget-header {
            border-color: #666;
            background: #404040
        }

        .widget-color-grey {
            border-color: #9e9e9e
        }

        .widget-color-grey>.widget-header {
            border-color: #aaa;
            background: #848484
        }

        .widget-box.transparent {
            border-width: 0
        }

        .widget-box.transparent>.widget-header {
            background: 0 0;
            border-width: 0;
            border-bottom: 1px solid #DCE8F1;
            color: #4383B4;
            padding-left: 3px
        }

        .widget-box.transparent>.widget-header-large {
            padding-left: 5px
        }

        .widget-box.transparent>.widget-header-small {
            padding-left: 1px
        }

        .widget-box.transparent>.widget-body {
            border-width: 0;
            background-color: transparent
        }

        [class*=widget-color-]>.widget-header>.widget-toolbar>.widget-menu>[data-action],
        [class*=widget-color-]>.widget-header>.widget-toolbar>[data-action] {
            text-shadow: 0 1px 1px rgba(0, 0, 0, .2)
        }

        [class*=widget-color-]>.widget-header>.widget-toolbar>.widget-menu>[data-action=settings],
        [class*=widget-color-]>.widget-header>.widget-toolbar>[data-action=settings] {
            color: #D3E4ED
        }

        [class*=widget-color-]>.widget-header>.widget-toolbar>.widget-menu>[data-action=reload],
        [class*=widget-color-]>.widget-header>.widget-toolbar>[data-action=reload] {
            color: #DEEAD3
        }

        [class*=widget-color-]>.widget-header>.widget-toolbar>.widget-menu>[data-action=collapse],
        [class*=widget-color-]>.widget-header>.widget-toolbar>[data-action=collapse] {
            color: #E2E2E2
        }

        [class*=widget-color-]>.widget-header>.widget-toolbar>.widget-menu>[data-action=close],
        [class*=widget-color-]>.widget-header>.widget-toolbar>[data-action=close] {
            color: #FFD9D5
        }

        .widget-color-orange>.widget-header>.widget-toolbar>.widget-menu>[data-action],
        .widget-color-orange>.widget-header>.widget-toolbar>[data-action] {
            text-shadow: none
        }

        .widget-color-orange>.widget-header>.widget-toolbar>.widget-menu>[data-action=settings],
        .widget-color-orange>.widget-header>.widget-toolbar>[data-action=settings] {
            color: #559AAB
        }

        .widget-color-orange>.widget-header>.widget-toolbar>.widget-menu>[data-action=reload],
        .widget-color-orange>.widget-header>.widget-toolbar>[data-action=reload] {
            color: #7CA362
        }

        .widget-color-orange>.widget-header>.widget-toolbar>.widget-menu>[data-action=collapse],
        .widget-color-orange>.widget-header>.widget-toolbar>[data-action=collapse] {
            color: #777
        }

        .widget-color-orange>.widget-header>.widget-toolbar>.widget-menu>[data-action=close],
        .widget-color-orange>.widget-header>.widget-toolbar>[data-action=close] {
            color: #A05656
        }

        .widget-box.light-border[class*=widget-color-]:not(.fullscreen) {
            border-width: 0
        }

        .widget-box.light-border[class*=widget-color-]:not(.fullscreen)>.widget-header {
            border: 1px solid;
            border-color: inherit
        }

        .widget-box.light-border[class*=widget-color-]:not(.fullscreen)>.widget-body {
            border: 1px solid #D6D6D6;
            border-width: 0 1px 1px
        }

        .widget-box.no-border {
            border-width: 0
        }

        .widget-box.fullscreen {
            position: fixed;
            margin: 0;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #FFF;
            border-width: 3px;
            z-index: 1040!important
        }

        .widget-box.fullscreen:not([class*=widget-color-]) {
            border-color: #AAA
        }

        .widget-body .table {
            border-top: 1px solid #E5E5E5
        }

        .widget-body .table thead:first-child tr {
            background: #FFF
        }
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
                                            <!-- <div class="card-header " style="background-color: rgb(255 236 230);">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="header">Account Details</div>
                                                </div>
                                            </div> -->
                                            <div class="card-body">
                                                <div class="container" style="padding-bottom: 5%;">
                            <div class="container bootdey">
                                <div class="row" style="width: auto;" id="printTable">
                                    <div class="col-lg-10 container">
                                        <div class="widget-box">
                                            <div class="row widget-header widget-header-large" style="padding-right: 50px;padding-left: 0px;padding-bottom: 15px; margin-left: 0px; margin-right: 0px; padding-top: 10px;">

                                                <div class="col-lg-2">
                                                    <img src="logo/fulllogo.jpg" alt="" style="height: 100px;width: 100px;margin-bottom: 5px;margin-top: 5px;">
                                                </div>
                                                 <?php
                                                    $mysql = new Mysql();
                                                    $mysql -> dbConnect();
                                                        $cmpquery = "SELECT * FROM `tbl_company` WHERE `isactive`=0 AND `isdelete`=0 ORDER BY id DESC LIMIT 1";
                                                        $cmprow =  $mysql -> selectFreeRun($cmpquery);
                                                        $cmpresult = mysqli_fetch_array($cmprow);
                                                    $mysql -> dbDisConnect();
                                                    ?>
                                                <div class="col-lg-6">
                                                    <label class="invoice-info-label lable-header"><?php echo $cmpresult['name'];?></label><br>
                                                    <label class="text-black"><?php echo $cmpresult['street'];?>, <?php echo $cmpresult['postcode'].", ".$cmpresult['city'];?></label><br>
                                                    <label class="text-black">Company number <?php echo $cmpresult['registration_no'];?> VAT number <?php echo $cmpresult['vat'];?></label><br>
                                                    <span class="blue">Email: driversupport@bryanstonlogistics.com</span><br>

                                                    <span class="invoice-info-label"></span>
                                                </div>

                                                <div class="col-lg-4">
                                                        <label class="lable-header text-sm a">Rental Agreement #1814</label>
                                                        <span class="invoice-info-label lable-header"></span> 
                                                        <br>
                                                        <span class="text-black">Vehicle Reg No.:</span>
                                                        <span class="blue"><?php echo $cntresult['regestrationnumber'];?></span><br>
                                                </div>
                                            </div>
                                            
                                            <div class="widget-body">
                                                <div class="widget-main padding-24">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="card-header p-1">
                                                                <label class="a pl-2">Hirer Information</label>
                                                            </div> 
                                                            <div class="row mt-2">
                                                                <div class="col-md-6">
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <span class="a">Hirer</span>
                                                                        </div>
                                                                        <div class="col-md-9">
                                                                            <span><?php echo $cntresult['contaractorname'];?></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <span class="a">Email</span>
                                                                        </div>
                                                                        <div class="col-md-9">
                                                                            <span><?php echo $cntresult['email'];?></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <span class="a">Phone</span> 
                                                                        </div>
                                                                        <div class="col-md-9">
                                                                            <span><?php echo $cntresult['contact'];?></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <span class="a">Address</span>  
                                                                        </div>
                                                                        <div class="col-md-9">
                                                                            <span><?php echo $cntresult['address'];?></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <span class="a">Postcode</span>
                                                                        </div>
                                                                        <div class="col-md-9">
                                                                            <span><?php echo $cntresult['postcode'];?></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <span class="a">Country</span> 
                                                                        </div>
                                                                        <div class="col-md-9">
                                                                            <span><?php echo $cntresult['countryname'];?></span>
                                                                        </div>
                                                                    </div>
                                                                    <br>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="row">
                                                                        <div class="col-md-5">
                                                                            <span class="a">Date Of Birth</span> 
                                                                        </div>
                                                                        <div class="col-md-7">
                                                                            <span><?php echo $cntresult['dob'];?></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-5">
                                                                            <span class="a">Driver's License</span> 
                                                                        </div>
                                                                        <div class="col-md-7">
                                                                            <span><?php echo $cntresult['drivinglicence_number'];?></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-5">
                                                                            <span class="a">Exp. Date</span> 
                                                                        </div>
                                                                        <div class="col-md-7">
                                                                            <span><?php echo $cntresult['drivinglicence_expiry'];?></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-5">
                                                                            <span class="a">Country Of Issue</span> 
                                                                        </div>
                                                                        <div class="col-md-7">
                                                                            <span><?php echo $cntresult['passportcountryname'];?></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            
                                                           
                                                        </div> 
                                                        <div class="col-md-4">
                                                            <div class="card">
                                                                <div class="card-header p-1 pl-3 a">
                                                                    <label class="a">Vehicle Information</label>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <span class="text-sm">Vehicle Reg. No</span><span class="a pl-2"> <?php echo $cntresult['regestrationnumber'];?></span><br>
                                                                       
                                                                            <span class="text-sm pt-1">Model </span><span class="a pl-2"><?php echo $cntresult['modelname'];?></span><br>
                                                                       
                                                                            <span class="text-sm">Class</span><span class="a pl-2"> <?php echo $cntresult['pickup_date1'];?></span><br>

                                                                            <span class="text-sm">Odometer Out</span><span class="a pl-2"> <?php echo $cntresult['current_odometer'];?></span><br>

                                                                            <span class="text-sm">Odometer In:</span><span class="a pl-2"> <?php echo $cntresult['current_odometer'];?></span><br>

                                                                        </div>
                                                                    </div>
                                                                </div> 
                                                            </div>
                                                  
                                                            <div class="hr hr8 hr-double hr-dotted"></div>

                                                            <div class="space-6"></div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="card">
                                                                <div class="card-header p-1 pl-2 a">
                                                                    <label>Rental Information</label>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <span class="a">Start Date </span> 
                                                                            <?php 
                                                                            $s = $cntresult['start_date'];
                                                                            if($s == "")
                                                                            {}
                                                                            else{
                                                                                echo date("d M Y", strtotime($s));

                                                                            }
                                                                           ?> 
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <span class="pt-1 a">Return Date </span><?php echo $cntresult['leave_date'];?></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <span class="pt-1 a">Actual Pickup </span><?php echo $cntresult['pickup_date1'];?></span>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <span class="pt-1 a">Actual Return </span><?php echo date("d M Y", strtotime($cntresult['return_date']));?></span>
                                                                        </div>
                                                                    </div>
                                                                   
                                                                       
                                                                </div> 
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="card">
                                                                <div class="card-header p-1 pl-2 a">
                                                                    <label>Charge Information</label>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="row">

                                                                        <div class="col-md-12">
                                                                            <span class="a">Daily Rate + VAT</span><span> <?php echo $cntresult['daily_rate'] +  $cntresult1['vat_per'];?></span><br>
                                                                       
                                                                            <span class="pt-1 a">Deposit </span><span><?php echo $cntresult['deposit'];?></span><br>
                                                                       
                                                                            <span class="a">Mileage</span><span> <?php echo $cntresult['daily_miles_rate'];?></span><br>
                                                                       
                                                                        </div>
                                                                    </div>
                                                                </div> 
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="card-header p-1 pl-2 a">
                                                                <label>Insurance Information</label>
                                                            </div>    
                                                                <label class="p-2">
                                                                    Where I hire the vehicle under the Lessor Insurance
                                                                    Policy, I will make sure the minimum requirements of
                                                                    the insurance are metand if in breach, I will inform the
                                                                    Lessor
                                                                    I the undersigned agree to pay the insurance excess
                                                                    set out in the Terms and Conditions,in the event of any
                                                                    damage or theft claim, or any third party claim made
                                                                    against their insurance policy
                                                                </label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card-header p-1 pl-2 a">
                                                                <label>Unauthorised Driver Declaration</label>
                                                            </div>    
                                                                <label class="p-2">
                                                                    Any vehicle hired under this agreement may only be
                                                                    driven by authorised drivers, who have been
                                                                    approved the lessor
                                                                    I understand that should I breach these terms an
                                                                    additional rental charge will be levied.(This extra
                                                                    charge will not offer any insurance cover, and the
                                                                    hirer & driver will remain responsible for any losses
                                                                    incurred by thelessor or any third party.

                                                                </label>
                                                        </div>
                                                    </div>


                                                    <div class="row mt-3">
                                                        <div class="col-md-8">
                                                            <div class="card-header p-1 pl-2 a">
                                                                <label>Liability Statement</label>
                                                            </div>
                                                            <label class="p-2">
                                                                I agree to hire the above vehicle on the terms and conditions set out here and overleaf. If any Master Agreement
                                                                has beenentered into then the terms of the Master Hire agreement shall apply to this hire.
                                                                I acknowledge that throughout the period of hiring I shall be liable as owner of the vehicle hired to me under this
                                                                agreement inrespect of:
                                                                (a) Any fixed penalty offence as defined in Schedule 3 of the Road Traffic Offenders Act 1988 (or as amended,
                                                                replaced orrevised by subsequent legislation or orders)
                                                                (b) Any excess parking charges which may be incurred in respect of the vehicle under an order made under
                                                                Section 45 orSection 46 of the Road Traffic Regulations Act, 1984 (as amended, replaced or revised by
                                                                subsequent legislation or orderwhether payable under such order of Act).
                                                                (c) Any liability arising under any Congestion Charging Order.
                                                                (d) Any changes made by Customs & Excise as a result of seizure of the vehicle by them together with any loss
                                                                sustained by theLessor in consequence thereof.
                                                                I also acknowledge that this liability shall extend to any other vehicle let to me under the same rental agreement
                                                                or to anyreplacement vehicle and to any period by which the original period of hiring may be extended.

                                                            </label><br>
                                                            <label class="a pl-5" style="font-size: 16px;">HIRER ACCEPTS FULL LIABILITY FOR ANY OVERHEAD DAMAGES</label>
                                                        </div> 
                                                        <div class="col-md-4">
                                                            <div class="card">
                                                                <div class="card-header p-1 pl-2 a">
                                                                    <label>Hirer Signature</label>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <span class="">
                                                                                OFFHIRE By this signature the hirer accepts any
                                                                                damage, missing accessories and fuel reading, and
                                                                                resultant chargings. The deposit will be withheld if the
                                                                                vehicle is returned with any damage whatsoever,
                                                                                which is noted on the damage inspection report.
                                                                            </span>

                                                                            <div class="card border mt-3 p-2">
                                                                                <img src="<?php echo $cntresult['driver_signature'];?>">
                                                                            </div>
                                                                       
                                                                        </div>
                                                                    </div>
                                                                </div> 
                                                            </div>
                                                  

                                                    

                                                            <div class="hr hr8 hr-double hr-dotted"></div>
                                                            <div class="row">
                                                                <div class="col-sm-7 pull-left"></div>
                                                                    <div class="col-sm-5 pull-right" style="text-align: right;">
                                                                        
                                                                </div>
                                                            </div>

                                                            <div class="space-6"></div>
                                                        </div>
                                                    </div>

                                                    <br><br>

                                                    <span class="a mt-3 pl-2 text-sm">TERMS AND CONDITIONS:</span><br>
                                                    <label class="p-2">
                                                        Rental Contract Terms & Conditions <br>
                                                        I confirm that I the owner/hirer agree to the following Rental Agreement Conditions stated below:<br>
                                                        Bryanston Logistics Limited charge the rental of the vehicle at a daily rate of;<br>
                                                        · Short Wheelbase Vehicles Short-term = £34.50<br>
                                                        · Short Wheelbase Vehicles Long-term = £30.70<br>
                                                        · Long Wheelbase Vehicle Short-term = £39.50<br>
                                                        · Long Wheelbase Vehicle Long-term = £33.58 <br>

                                                        Under this agreement, I will be liable for all parking and traffic violations from the date and time section, from the start of this agreement until the return date and time.<br>

                                                        I agree that there will be no other person(s) other than myself utilizing the vehicle under this agreement as sole responsibility will fall on me for any damages, traffic violations,
                                                        and accidents incurred.<br>

                                                        I will be responsible for making sure the vehicle is always roadworthy including regular maintenance and checks but not limited to; the tires, thread, locking system, and lights. I
                                                        must inform members of the Bryanston Logistics management team of any issues with the vehicle as soon as an issue is found.<br>

                                                        Under this agreement, I will be liable for any damage caused to the vehicle, but not limited to punctures, damage to tires, wheel rims, and windscreens. I will be responsible for
                                                        using the correct fuel to ensure the vehicle functions are not compromised and maintain a roadworthy status.
                                                        I agree that any incidents involving myself regardless of the fault will be raised and reported to Bryanston Management within 12 hours from the time of the incident. An
                                                        unreported accident insurance fee of up to £500 will be applied if any accidents are not reported within 12hours. It will be my sole responsibility to provide a detailed report of the
                                                        incident. Immediate action must be taken if me (the Hirer) and/or the vehicle is stolen or is involved in any accidents.<br>

                                                        A full report is required with a rough plan of the scene of the incident showing the position of the vehicles involved with measurements.
                                                        · The names and addresses of the person(s) involved in the accident should be taken.<br>
                                                        · The name and address of any witnesses should be taken.<br>
                                                        · Pictures of the incident must be taken showing the full extent of the damage/collision.<br>
                                                        · Take the name and address of the third party's Insurance Company.<br>
                                                        · I must assist Bryanston Logistics with any information in dealing with any claims or ongoing disputes that involves me directly.<br>
                                                        · Under this rental agreement I will be subject to a standard excess of £2500.00, this excess is not applied if you are involved in a clear non-fault accident with another
                                                        insured motor vehicle.<br>
                                                        A deposit of £500.00 will be held by Bryanston Logistics to ensure the following points stated above are met.
                                                        By signing you agree:<br>
                                                        To pay the standard policy excess should the hire vehicle be returned with any damage, other than the damage detailed in this checklist.<br>

                                                        For any fuel supplied on this vehicle must be returned the same, any missing fuel will be charged £2.50 per liter

                                                    </label>

                                                    <img src="<?php echo $cntresult['ledger_signature'];?>">


                                                </div>                                                  
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary col-md-1 mb-5" style="margin-left: 150px;" onclick="printJ()">PRINT</button>
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
        <script>
            function printJ()
            {
                 var printContents = document.getElementById("printTable").innerHTML;
                 var originalContents = document.body.innerHTML;
                 document.body.innerHTML = printContents;
                 window.print();
                 document.body.innerHTML = originalContents;
            }
        </script>
    <script>

    $(function(){
        $id = <?php echo $userid?>;
        ajaxreload();
    });  
    $("#form").validate({
        rules: {
            name: 'required',
            phone: 'required',
        },
        messages: {
            name: "Please enter your make name",
            phone:  "Please enter your mobile",
        },
        submitHandler: function(form) {
            event.preventDefault();
            $.ajax({
                url: "loaddata.php", 
                type: "POST", 
                dataType:"JSON",            
                data: $("#form").serialize()+"&action=updategenerale_settingdetails",
                success: function(data) {
                    if(data.status==1)
                    {
                        myAlert(data.title + "@#@" + data.message + "@#@success");
                        $('#form')[0].reset();
                        ajaxreload();
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
    function ajaxreload() {
        $.ajax({
            url:"loaddata.php",
            method:"POST",
            dataType:"json",
            data: {action : 'loadgenerale_settingdetails', id: $id},
            success:function(response){  
                $("#name").val(response['name'].toUpperCase());
                $("#phone").val(response['contact']);
            }
        });
    }
                
    </script>

    </body>
</html>