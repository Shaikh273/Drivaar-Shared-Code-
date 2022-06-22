<?php
    $page_title="Contractor Invoice";
    include 'DB/config.php';
    $page_id=48;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    $ginvID = "";
    function getAlphaCode($n,$pad){
        $alphabet   = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $n = (int)$n;
        if($n <= 26){
            $alpha =  $alphabet[$n-1];
        } elseif($n > 26) {
            $dividend   = ($n);
            $alpha      = '';
            $modulo     = '';
            while($dividend > 0){
                $modulo     = ($dividend - 1) % 26;
                $alpha      = $alphabet[$modulo].$alpha;
                $dividend   = floor((($dividend - $modulo) / 26));
            }
        }
        return str_pad($alpha,$pad,"0",STR_PAD_LEFT);
    }

    $conid = 0;
    $hideHeader =0;
    $dueDate = "";
    $bank_name="";
    $account_number="";
    $bank_account_name="";
    $sort_code="";
    $result1 = array();
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $data = base64_decode($_GET['invkey']);
        $extDt = explode("#",$data);
        
        if(count($extDt)==5)
        {
            $deco2 = base64_decode($extDt[4]);
            $verf = explode("#", $deco2);
            $Arrresult=array_diff($extDt,$verf);
            $Arrresult1=array_diff($verf,$extDt);
            if(count($Arrresult)==1 && count($Arrresult1)==0 && $Arrresult[4]==$extDt[4])
            {
                $hideHeader=1;

            }else
            {
                header("Location: ../contractorAdmin/myinvoice.php");
            }
        }

        //changes
        $conid = $extDt[0];
        $cntqueryN = "SELECT c.*,d.invoicetype,ar.name as arname FROM `tbl_contractor` c 
        LEFT JOIN `tbl_arrears` ar On ar.id=c.arrears 
        INNER JOIN `tbl_depot`  d ON d.id=c.depot
        WHERE c.id=".$extDt[0];
        $cntrowN =  $mysql -> selectFreeRun($cntqueryN);
        $result1N = mysqli_fetch_array($cntrowN);
        if($result1N['invoicetype']>0)
        {
            $invoicetype = $result1N['invoicetype']; 
        }
        else
        {
            $invoicetype = 0;
        }
        $bank_name=$result1N['bank_name'];
        $account_number=$result1N['account_number'];
        $bank_account_name=$result1N['bank_account_name'];
        $sort_code=$result1N['sort_code'];
        if (isset($result1N['vat_number']) || !empty($result1N['vat_number'])) 
        {
            $vatset=1;
        }
        else
        {
            $vatset=0;
        }

        $tcode='';
        if($extDt[1]==1)
        {
            $tcode='C';
        }
        $depCode="";
        if($extDt[3]==2021)
        {
            $depCode = getAlphaCode($result1N['depot'],3);
        }
        $yrCode = (int)$extDt[3] - 2020;
        $wkCode = getAlphaCode($extDt[2],2);
        $invId = $yrCode.$tcode.$depCode.$wkCode.$extDt[0];
        $ginvID = $invId;
        if($invoicetype==2)
        {
            $setname = 'Month';
            $setnumber = $extDt[2];
            $tablename = 'tbl_monthlyinvoice';

            $firstdayofmonth = date('Y-m-01', strtotime($extDt[3]-$extDt[2]-01));
            $lastdayofmonth =  date('Y-m-t', strtotime($extDt[3]-$extDt[2]-01));
            $onUpdate ="";
            if(isset($statusresult['arname']) && $statusresult['arname']!=NULL)
            {
                $arTemp = explode(" ",$statusresult['arname'])[0];
                $dueDate = date('Y-m-d', strtotime('+'.$arTemp.' week', strtotime($lastdayofmonth)));
                $onUpdate = ", duedate='$dueDate'";
            }else
            {
                $onUpdate = "";
            }

            $values = array();
            $values[0]['cid']= $extDt[0];
            $values[0]['invoice_no']= $invId;
            $values[0]['month']= $extDt[2];
            $values[0]['from_date']= $wkStart;
            $values[0]['to_date']= $wkEnd;
            $values[0]['weekyear']= $extDt[3];
            $values[0]['vat']= $vatset;
            $values[0]['monthyear']=$result1N['invoicetype'];
            $values[0]['istype']= 1;
            $values[0]['depot_id']= $result1N['depot'];
            if($dueDate!="")
            {
                $values[0]['duedate']= $dueDate;
            }
            $mysql -> OnduplicateInsert('tbl_monthlyinvoice',$values,"ON DUPLICATE KEY UPDATE `invoice_no` = '$invId' $onUpdate");
        }
        else
        {
            $setname = 'Week';
            $setnumber = $extDt[2];
            $tablename = 'tbl_contractorinvoice';
        
            $week_start = new DateTime();
            $week_start->setISODate($extDt[3],$extDt[2]);
            $wkStart = date('Y-m-d', strtotime('-1 day', strtotime($week_start->format('Y-m-d'))));
            $wkEnd = date('Y-m-d', strtotime('+5 day', strtotime($week_start->format('Y-m-d'))));
            $onUpdate = "";
            if(isset($result1N['arname']) && $result1N['arname']!=NULL)
            {
                $arTemp = explode(" ",$result1N['arname'])[0];
                $dueDate = date('Y-m-d', strtotime('+'.$arTemp.' week', strtotime($wkEnd)));
                $onUpdate = ", duedate='$dueDate'";
            }else
            {
                $onUpdate = "";
            }
            
            $values = array();
            $values[0]['cid']= $extDt[0];
            $values[0]['invoice_no']= $invId;
            $values[0]['week_no']= $extDt[2];
            $values[0]['from_date']= $wkStart;
            $values[0]['to_date']= $wkEnd;
            $values[0]['weekyear']= $extDt[3];
            $values[0]['vat']= $vatset;
            $values[0]['invoicetype']=$result1N['invoicetype'];
            $values[0]['istype']= 1;
            $values[0]['depot_id']= $result1N['depot'];
            if($dueDate!="")
            {
                $values[0]['duedate']= $dueDate;
            }
            $mysql -> OnduplicateInsert('tbl_contractorinvoice',$values,"ON DUPLICATE KEY UPDATE `invoice_no` = '$invId' $onUpdate");
        }
        $cntquery = "SELECT c.*,ci.*,ci.`insert_date` as insdt FROM `tbl_contractor` c INNER JOIN $tablename ci ON ci.cid=c.id WHERE ci.invoice_no='$invId'";
        $cntrow =  $mysql -> selectFreeRun($cntquery);
        $result1 = mysqli_fetch_array($cntrow);
        $mysql->dbDisconnect();
        $fromdate = date("d/m/Y",strtotime($result1['from_date']));
        $todate = date("d/m/Y",strtotime($result1['to_date']));
        $dueDate = date("d/m/Y",strtotime($result1['duedate']));
            
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
    <style>
        body {
            font-weight: 390;
        }
        @media only screen and (min-device-width: 480px){
        .label.arrowed-in-right,
        .label.arrowed-right {
            margin-right: 5px
        }

        .label.arrowed,
        .label.arrowed-in {
            margin-left: 5px
        }

        .label.arrowed,
        .label.arrowed-in {
            position: relative;
            z-index: 1
        }

        .label.arrowed-in:before,
        .label.arrowed:before {
            display: inline-block;
            content: "";
            position: absolute;
            top: 0;
            z-index: -1;
            border: 1px solid transparent;
            border-right-color: #ABBAC3
        }

        .label.arrowed-in:before {
            border-color: #ABBAC3 #ABBAC3 #ABBAC3 transparent
        }

        .label.arrowed-in-right,
        .label.arrowed-right {
            position: relative;
            z-index: 1
        }

        .label.arrowed-in-right:after,
        .label.arrowed-right:after {
            display: inline-block;
            content: "";
            position: absolute;
            top: 0;
            z-index: -1;
            border: 1px solid transparent;
            border-left-color: #ABBAC3
        }

        label.arrowed,
        .label.arrowed-in {
            position: relative;
            z-index: 1
        }

        .label.arrowed-in:before,
        .label.arrowed:before {
            display: inline-block;
            content: "";
            position: absolute;
            top: 0;
            z-index: -1;
            border: 1px solid transparent;
            border-right-color: #ABBAC3
        }

        .label.arrowed-in:before {
            border-color: #ABBAC3 #ABBAC3 #ABBAC3 transparent
        }

        .label.arrowed-in-right,
        .label.arrowed-right {
            position: relative;
            z-index: 1
        }

        .label.arrowed-in-right:after,
        .label.arrowed-right:after {
            display: inline-block;
            content: "";
            position: absolute;
            top: 0;
            z-index: -1;
            border: 1px solid transparent;
            border-left-color: #ABBAC3
        }

        .label.arrowed-in-right:after {
            border-color: #ABBAC3 transparent #ABBAC3 #ABBAC3
        }

        .label-info.arrowed:before {
            border-right-color: #3A87AD
        }

        .label-info.arrowed-in:before {
            border-color: #3A87AD #3A87AD #3A87AD transparent
        }

        .label-info.arrowed-right:after {
            border-left-color: #3A87AD
        }

        .label-info.arrowed-in-right:after {
            border-color: #3A87AD transparent #3A87AD #3A87AD
        }

        .label-primary.arrowed:before {
            border-right-color: #428BCA
        }

        .label-primary.arrowed-in:before {
            border-color: #428BCA #428BCA #428BCA transparent
        }

        .label-primary.arrowed-right:after {
            border-left-color: #428BCA
        }

        .label-primary.arrowed-in-right:after {
            border-color: #428BCA transparent #428BCA #428BCA
        }

        .label-success.arrowed:before {
            border-right-color: #82AF6F
        }

        .label-success.arrowed-in:before {
            border-color: #82AF6F #82AF6F #82AF6F transparent
        }

        .label-success.arrowed-right:after {
            border-left-color: #82AF6F
        }

        .label-success.arrowed-in-right:after {
            border-color: #82AF6F transparent #82AF6F #82AF6F
        }

        .label-warning.arrowed:before {
            border-right-color: #F89406
        }

        .label-danger.arrowed:before,
        .label-important.arrowed:before {
            border-right-color: #D15B47
        }

        .label-warning.arrowed-in:before {
            border-color: #F89406 #F89406 #F89406 transparent
        }

        .label-warning.arrowed-right:after {
            border-left-color: #F89406
        }

        .label-danger.arrowed-right:after,
        .label-important.arrowed-right:after {
            border-left-color: #D15B47
        }

        .label-warning.arrowed-in-right:after {
            border-color: #F89406 transparent #F89406 #F89406
        }

        .label-important.arrowed-in:before {
            border-color: #D15B47 #D15B47 #D15B47 transparent
        }

        .label-important.arrowed-in-right:after {
            border-color: #D15B47 transparent #D15B47 #D15B47
        }

        .label-danger.arrowed-in:before {
            border-color: #D15B47 #D15B47 #D15B47 transparent
        }

        .label-danger.arrowed-in-right:after {
            border-color: #D15B47 transparent #D15B47 #D15B47
        }

        .label-inverse.arrowed:before {
            border-right-color: #333
        }

        .label-inverse.arrowed-in:before {
            border-color: #333 #333 #333 transparent
        }

        .label-inverse.arrowed-right:after {
            border-left-color: #333
        }

        .label-inverse.arrowed-in-right:after {
            border-color: #333 transparent #333 #333
        }

        .label-pink.arrowed:before {
            border-right-color: #D6487E
        }

        .label-pink.arrowed-in:before {
            border-color: #D6487E #D6487E #D6487E transparent
        }

        .label-pink.arrowed-right:after {
            border-left-color: #D6487E
        }

        .label-pink.arrowed-in-right:after {
            border-color: #D6487E transparent #D6487E #D6487E
        }

        .label-purple.arrowed:before {
            border-right-color: #9585BF
        }

        .label-purple.arrowed-in:before {
            border-color: #9585BF #9585BF #9585BF transparent
        }

        .label-purple.arrowed-right:after {
            border-left-color: #9585BF
        }

        .label-purple.arrowed-in-right:after {
            border-color: #9585BF transparent #9585BF #9585BF
        }

        .label-yellow.arrowed:before {
            border-right-color: #FEE188
        }

        .label-yellow.arrowed-in:before {
            border-color: #FEE188 #FEE188 #FEE188 transparent
        }

        .label-yellow.arrowed-right:after {
            border-left-color: #FEE188
        }

        .label-yellow.arrowed-in-right:after {
            border-color: #FEE188 transparent #FEE188 #FEE188
        }

        .label-light.arrowed:before {
            border-right-color: #E7E7E7
        }

        .label-light.arrowed-in:before {
            border-color: #E7E7E7 #E7E7E7 #E7E7E7 transparent
        }

        .label-light.arrowed-right:after {
            border-left-color: #E7E7E7
        }

        .label-light.arrowed-in-right:after {
            border-color: #E7E7E7 transparent #E7E7E7 #E7E7E7
        }

        .label-grey.arrowed:before {
            border-right-color: #A0A0A0
        }

        .label-grey.arrowed-in:before {
            border-color: #A0A0A0 #A0A0A0 #A0A0A0 transparent
        }

        .label-grey.arrowed-right:after {
            border-left-color: #A0A0A0
        }

        .label-grey.arrowed-in-right:after {
            border-color: #A0A0A0 transparent #A0A0A0 #A0A0A0
        }

        .label {
            line-height: 1.15;
            height: 20px
        }

        .label.arrowed:before {
            left: -10px;
            border-width: 10px 5px
        }

        .label-lg.arrowed,
        .label-lg.arrowed-in {
            margin-left: 6px
        }

        .label.arrowed-in:before {
            left: -5px;
            border-width: 10px 5px
        }

        .label.arrowed-right:after {
            right: -10px;
            border-width: 10px 5px
        }

        .label-lg.arrowed-in-right,
        .label-lg.arrowed-right {
            margin-right: 6px
        }

        .label.arrowed-in-right:after {
            right: -5px;
            border-width: 10px 5px
        }

        .label-lg {
            padding: 0.2em .7em .5em;
            font-size: 18px;
            line-height: 0.9;
            height: 24px;
        }

        .green {
            color: #69AA46!important;
        }

        .red {
            color: #DD5A43!important;
        }

        .hr-8, .hr8 {
            margin: 8px 0;
        }
        .hr-dotted, .hr.dotted {
            border-style: dotted;
        }

        .label-info.arrowed-in:before {
            border-color: #3A87AD #3A87AD #3A87AD transparent;
        }

        .hr-double {
            height: 3px;
            border-top: 1px solid #E3E3E3;
            border-bottom: 1px solid #E3E3E3;
            border-top-color: rgba(0,0,0,.11);
            border-bottom-color: rgba(0,0,0,.11);
        }

        .spaced>li {
            margin-top: 9px;
            margin-bottom: 9px;
        }

        .label-lg.arrowed:before {
            left: -12px;
            border-width: 12px 6px
        }

        .label-xlg.arrowed,
        .label-xlg.arrowed-in {
            margin-left: 7px
        }

        .label-lg.arrowed-in:before {
            left: -6px;
            border-width: 12px 6px
        }

        .label-lg.arrowed-right:after {
            right: -12px;
            border-width: 12px 6px
        }

        .label-xlg.arrowed-in-right,
        .label-xlg.arrowed-right {
            margin-right: 7px
        }

        .label-lg.arrowed-in-right:after {
            right: -6px;
            border-width: 12px 6px
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
if($hideHeader==0)
{include('header.php');}else if($hideHeader==1)
{
 include('contractorHeader.php');   
}?>
    <div class="page-wrapper">
        <div class="container-fluid">
            <main class="container-fluid  animated">
                <div class="card">    
                    <div class="card-header" style="background-color: rgb(255 236 230);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="header">Invoice</div>               
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="container" style="padding-bottom: 10%;">
                            <div class='container bootdey'>
                                <div class='row' style='width: auto;'>
                                    <div class='widget-box' id='printTable'>
                                        <div class='row widget-header widget-header-large' style="padding-right: 50px;padding-left: 0px;padding-bottom: 15px; margin-left: 0px; margin-right: 0px; padding-top: 10px;">
                                                <?php
                                                 $imgid = 1;
                                                 $mysql = new Mysql();
                                                 $mysql->dbConnect();
                                                 $imgqry =  $mysql -> selectWhere('tbl_orgdocument','id','=',$imgid,'int');
                                                 $imgresult = mysqli_fetch_array($imgqry);
                                                 $imagelogo = $webroot.'uploads/organizationdocuments/'.$imgresult['file1'];
                                                ?>    
                                                <div class="col-lg-2">
                                                        <img src="<?php echo $imagelogo;?>" alt=""style="margin-top: 22px;">
                                                </div>
                                                <div class="col-md-7 mt-3">
                                                <span class='invoice-info-label lable-header'>Invoice:</span>
                                                <span class='red lable-header'><?php echo $result1['invoice_no'];?></span><br>
                                                
                                                <span class='invoice-info-label'>Payment Date:</span>
                                                <span class='blue'><?php //echo $dueDate;?></span><br>

                                                <span class='invoice-info-label'><?php echo $result1['statusname'];?></span>
                                            </div>

                                            <div class="col-md-3 mt-3" style="text-align: right;">
                                                    <span class='invoice-info-label lable-header'><?php echo $setname;?>:</span>
                                                    <span class='invoice-info-label lable-header'><?php echo $setnumber;?></span>   
                                                    <br>
                                                    <span class='invoice-info-label'>Period:</span>
                                                    <span class='blue'><?php echo $fromdate.' - '.$todate;?></span><br>
                                                    <span class='invoice-info-label'>Associate:</span>
                                                    <span class='invoice-info-label'><?php echo $result1['name'];?></span><br>
                                                    <span class='invoice-info-label'>Total Attendance:</span>
                                                    <span class='invoice-info-label' id="totalAttendance"></span>
                                            </div>
                                           <!--  <div class="col-lg-2 pull-right">
                                                <img src="logo/fulllogo.jpg" alt="" style="height: 100px;width: 100px;margin-bottom: 5px;margin-top: 5px;float: right;">
                                            </div> -->
                                        </div>

                                        <div class='widget-body'>
                                            <div class='widget-main padding-24'>
                                                <div class='row'>
                                                    <div class='col-sm-7'>
                                                        <div>
                                                            <ul class='list-unstyled spaced'>
                                                                <li>
                                                                    <div class='col-xs-11 label label-lg label-info'>
                                                                        <b>FROM</b>
                                                                    </div>
                                                                </li>
                                                                <li><b><?php echo $result1['name'];?></b></li>
                                                                <li><?php echo $result1['address']. "<br>" .$result1['street_address'] . "-" .$result1['postcode'] . "<br>" .$result1['city'] ." ".$result1['state'];?></li>
                                                                <li>UTR: <?php echo $result1['utr'];?></li>
                                                                <?php
                                                                if($result1['vat_number'])
                                                                {
                                                                    ?>
                                                                     <li>VAT: <?php echo $result1['vat_number'];?></li>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <li>Phone: <?php echo $result1['contact'];?></li>
                                                                <li>Email: <a href="<?php echo $result1['email'];?>"><?php echo $result1['email'];?></a></li>
                                                            </ul>
                                                        </div>
                                                    </div><!-- /.col -->


                                                    <?php
                                                    $mysql = new Mysql();
                                                    $mysql -> dbConnect();
                                                        $cmpquery = "SELECT * FROM `tbl_company` WHERE `isactive`=0 AND `isdelete`=0 ORDER BY id DESC LIMIT 1";
                                                        $cmprow =  $mysql -> selectFreeRun($cmpquery);
                                                        $cmpresult = mysqli_fetch_array($cmprow);
                                                    $mysql -> dbDisConnect();
                                                    ?>
                                                    <div class='col-sm-5'>
                                                        <div>
                                                            <ul class='list-unstyled  spaced'>
                                                                <li>
                                                                    <div class='col-xs-11 label label-lg label-success'><b>TO</b></div>
                                                                </li>
                                                                <li><b><?php echo $cmpresult['name'];?></b></li>
                                                                <li><?php echo $cmpresult['street'];?></li>
                                                                <li><?php echo $cmpresult['postcode'].", ".$cmpresult['city'];?></li>
                                                                <li>Register Number: <?php echo $cmpresult['registration_no'];?></li>
                                                                <li>VAT: <?php echo $cmpresult['vat'];?></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            <div>
                                                <table class='table table-responsive table'>
                                                        <thead class="default">
                                                        </thead>
                                                       
                                                        <tbody>
                                                            <?php
                                                            $mysql = new Mysql();
                                                            $mysql -> dbConnect();
                                                            $tblGetDept = "SELECT `tbl_depot`.`name`,`tbl_depot`.`reference`,`tbl_contractor`.`vat_number` 
                                                            FROM `tbl_contractor` 
                                                            INNER JOIN `tbl_depot` on `tbl_depot`.`id`=`tbl_contractor`.`depot` 
                                                            WHERE `tbl_contractor`.`id`=$conid";
                                                            $tblDeptRow1 =  $mysql -> selectFreeRun($tblGetDept);
                                                            $tblDeptRow = mysqli_fetch_array($tblDeptRow1);
                                                            $vatFlag = 0;
                                                             // if (isset($tblDeptRow['vat_number']) || !empty($tblDeptRow['vat_number'])) 
                                                             // {
                                                             //    $vatFlag = 1;
                                                             // }
                                                             // else
                                                             // {
                                                             //    $vatFlag = 0;
                                                             // }
                                                            $tblquery = "SELECT ci.`id`,ci.`invoice_no`,ct.`rateid`,ct.`date`,ct.`value`,
                                                                ct.`ischeckval`,ci.`vat` as civat,p.`name`,p.`type`,p.`amount`,p.`vat`,tdp.name as OTHDPT, 
                                                                tdp.reference as OTHDPTR, ct.othDepotId,ci.from_date,ci.to_date,
                                                                (CASE WHEN (ct.othDepotId>0) THEN ct.othDepotId ELSE ci.depot_id END) as depotid 
                                                                FROM $tablename ci 
                                                                INNER JOIN `tbl_contractortimesheet` ct ON ct.cid=ci.cid 
                                                                INNER JOIN `tbl_paymenttype` p ON p.`id`=ct.`rateid` 
                                                                INNER JOIN `tbl_depot` tdp ON tdp.id=ct.othDepotId 
                                                                WHERE ci.`isdelete`=0 AND ci.`isactive`=0 AND ci.`invoice_no`='".$ginvID."' 
                                                                AND ct.`date` BETWEEN ci.`from_date` AND ci.`to_date` AND ct.`value` NOT LIKE '0' 
                                                                AND ct.`value` NOT LIKE '' AND ct.isdelete=0 ORDER BY p.`type` ASC, ct.`date` ASC,ct.`rateid` ASC";
                                                                       // echo $tblquery;
                                                            $tblrow =  $mysql -> selectFreeRun($tblquery);
                                                            $type=0;
                                                            $totalAttendance=0;
                                                            $finaltotal=0;
                                                            $totalnet=0;
                                                            $totalvat=0;
                                                            $route="";
                                                            $deptName = "";
                                                            $deptRef = "";
                                                            $prvDate="";
                                                            $vanDeduct = array();
                                                            $frm_Date = "";
                                                            $to_Date = "";
                                                            while($tblresult = mysqli_fetch_array($tblrow))
                                                            {
                                                                $vatFlag = $tblresult['civat'];
                                                                $frm_Date = $tblresult['from_date'];
                                                                $to_Date = $tblresult['to_date'];
                                                                $dpt =  $mysql->selectWhere('tbl_depot', 'id', '=', $tblresult['depotid'], 'int');
                                                                $dptresult = mysqli_fetch_array($dpt);
                                                                $deptRef = $dptresult['reference'];
                                                                if($tblresult['rateid']==0 && $tblresult['value']!=NULL && $tblresult['value']!='')
                                                                {
                                                                    $route = " (".$tblresult['value'].")";
                                                                }
                                                                if($prvDate!=$tblresult['date'])
                                                                {
                                                                    // if($tblresult['othDepotId']>0)
                                                                    // {
                                                                    //     $deptName = $tblresult['OTHDPT'];
                                                                    //     $deptRef = $tblresult['OTHDPTR'];
                                                                    // }else
                                                                    // {
                                                                    //     $deptName = $tblDeptRow['name'];
                                                                    //     $deptRef = $tblDeptRow['reference'];
                                                                    // }
                                                                    $prvDate = $tblresult['date'];
                                                                    $vanDeduct[] = "'$prvDate' BETWEEN tav.`start_date` AND tav.`end_date`";
                                                                    $totalAttendance++;
                                                                }
                                                                if($tblresult['rateid']>0 && $tblresult['value']!=NULL && $tblresult['value']!='')
                                                                {
                                                                $adddate = date("d/m/Y",strtotime($tblresult['date']));
                                                                if($type!=$tblresult['type'])
                                                                {
                                                                    if($tblresult['type']==1)
                                                                    {
                                                                        $typename = 'STANDARD SERVICES';
                                                                    }
                                                                    else if($tblresult['type']==2)
                                                                    {
                                                                         $typename = 'BONUS';
                                                                    }
                                                                    else if($tblresult['type']==3)
                                                                    {
                                                                         $typename = 'DEDUCTION';
                                                                    }
                                                                    ?>
                                                                    <tr style="background-color: #e9ecef;">
                                                                        <th style="width: 60%;"><?php echo $typename;?></th>
                                                                        <th>QUANTITY</th>
                                                                        <th>UNIT COST</th>
                                                                        <th>NET</th>
                                                                        <?php if($vatFlag==1){ ?>
                                                                        <th>VAT</th>
                                                                        <?php }?>
                                                                        <th>GROSS</th>
                                                                    </tr>
                                                                    <?php
                                                                    $type=$tblresult['type'];
                                                                }
                                                               

                                                                    $net = $tblresult['amount']*$tblresult['value'];
                                                                    $vat=0;
                                                                    if($vatFlag==1){ 
                                                                     $vat = ($net*$tblresult['vat'])/100;   
                                                                    }
                                                                    $neg = "";
                                                                    if($type==3)
                                                                    {
                                                                        $net = -$net;
                                                                        $vat = -$vat;
                                                                        $neg = "-";
                                                                    }
                                                                    $total = $net+$vat;
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $tblresult['name'].' - <strong>'.$adddate."</strong> ".$route." <strong>".$deptRef."</strong>"; ?></td>
                                                                        <td><?php echo $tblresult['value']; ?></td>
                                                                        <td><?php echo 'Â£ '.$neg.$tblresult['amount']; ?></td>
                                                                        <td><?php echo 'Â£ '.$net; ?></td>
                                                                        <?php if($vatFlag==1){ ?>
                                                                            <td><?php echo 'Â£ '.$vat; ?></td>
                                                                        <?php }?>
                                                                        <td><?php echo 'Â£ '.$total; ?></td>
                                                                    </tr>
                                                                    <?php
                                                                    $finaltotal+=$total;
                                                                    $totalnet+=$net;
                                                                    $totalvat+=$vat;
                                                                }
                                                            }
                                                            $vanWher = implode(" OR ",$vanDeduct);
                                                            $tqueryNE = "SELECT tav.*,tv.registration_number FROM `tbl_assignvehicle` tav INNER JOIN `tbl_vehicles` tv ON tv.id=tav.vid WHERE ($vanWher) AND tav.isdelete=0 AND tav.driver=".$result1['cid']." ORDER BY tav.`start_date` ASC";
                                                            //echo $tqueryNE;
                                                            $trowNE =  $mysql -> selectFreeRun($tqueryNE);
                                                            while($tresultNE = mysqli_fetch_array($trowNE))
                                                            {
                                                                if($type!=3)
                                                                {
                                                                    $typename = 'DEDUCTION';
                                                                        
                                                                        ?>
                                                                        <tr style="background-color: #e9ecef;">
                                                                            <th style="width: 60%;"><?php echo $typename;?></th>
                                                                            <th>QUANTITY</th>
                                                                            <th>UNIT COST</th>
                                                                            <th>NET</th>
                                                                            <?php if($vatFlag==1){ ?>
                                                                            <th>VAT</th>
                                                                            <?php }?>
                                                                            <th>GROSS</th>
                                                                        </tr>
                                                                        <?php
                                                                        $type=3;
                                                                }
                                                                $net = -$tresultNE['amount'];
                                                                $vat=0;
                                                                if($vatFlag==1){ 
                                                                 $vat = -($tresultNE['amount']*20)/100;
                                                                }
                                                                $total = $net+$vat;
                                                                $i=0;
                                                                while($vanDeduct[$i]>=$tresultNE['start_date'] && $vanDeduct<=$tresultNE['end_date'])
                                                                {
                                                                    ?>
                                                                    <tr>
                                                                        <td>Van deduction [<?php echo $tresultNE['registration_number']; ?>] <?php echo $vanDeduct;?></td>
                                                                        <td>1</td>
                                                                        <td><?php echo 'Â£ '.$net; ?></td>
                                                                        <td><?php echo 'Â£ '.$net; ?></td>
                                                                         <?php if($vatFlag==1){ ?>
                                                                            <td><?php echo 'Â£ '.$vat; ?></td>
                                                                        <?php }?>
                                                                        <td><?php echo 'Â£ '.$total; ?></td>
                                                                    </tr>
                                                                        <?php

                                                                        $finaltotal+=$total;
                                                                        $totalnet+=$net;
                                                                        $totalvat+=$vat;
                                                                        $i++;
                                                                }
                                                            }
                                                            $frm_Date = $result1['from_date'];
                                                            $to_Date =$result1['to_date'];
                                                            $VehRentQry = "SELECT DISTINCT va.*,v.registration_number FROM `tbl_vehiclerental_agreement` va INNER JOIN `tbl_vehicles` v ON v.id=va.vehicle_id WHERE va.`driver_id`=$conid AND (va.`pickup_date` BETWEEN '$frm_Date' AND '$to_Date' OR va.`return_date` BETWEEN '$frm_Date' AND '$to_Date' OR '$frm_Date' BETWEEN va.`pickup_date` AND va.`return_date` OR '$to_Date' BETWEEN va.`pickup_date` AND va.`return_date`) AND va.`iscompalete`=1 AND va.`isdelete`=0 AND v.supplier_id>1  ORDER BY va.`pickup_date` ASC";
                                                            //echo $VehRentQry;

                                                            $VehRentFire =  $mysql -> selectFreeRun($VehRentQry);
                                                            while($VehRentData = mysqli_fetch_array($VehRentFire))
                                                                {
                                                                    if($type!=3)
                                                                    {
                                                                    $typename = 'DEDUCTION';
                                                                        
                                                                        ?>
                                                                        <tr style="background-color: #e9ecef;">
                                                                            <th style="width: 60%;"><?php echo $typename;?></th>
                                                                            <th>QUANTITY</th>
                                                                            <th>UNIT COST</th>
                                                                            <th>NET</th>
                                                                            <?php if($vatFlag==1){ ?>
                                                                            <th>VAT</th>
                                                                            <?php }?>
                                                                            <th>GROSS</th>
                                                                        </tr>
                                                                        <?php
                                                                        $type=3;
                                                                    }
                                                                    $pikupD = $VehRentData['pickup_date'];
                                                                    if(strtotime($VehRentData['pickup_date'])<strtotime($frm_Date))
                                                                    {
                                                                        $pikupD = $frm_Date;
                                                                    }
                                                                    while($to_Date>=$pikupD && $pikupD<=$VehRentData['return_date'] && $pikupD<=date("Y-m-d"))
                                                                    {
                                                                        $net = -$VehRentData['price_per_day'];
                                                                        $vat=0;
                                                                        if($vatFlag==1){ 
                                                                         $vat = -$VehRentData['price_per_day'] * 0.2;
                                                                        }
                                                                        
                                                                        $total = $net + $vat;
                                                                    ?>
                                                                    <tr>
                                                                        <td>Van deduction - <strong><?php echo $pikupD;?></strong> - [<?php echo $VehRentData['registration_number']; ?>]</td>
                                                                        <td>1</td>
                                                                        <td><?php echo 'Â£ '.$net; ?></td>
                                                                        <td><?php echo 'Â£ '.$net; ?></td>
                                                                        <?php if($vatFlag==1){ ?>
                                                                            <td><?php echo 'Â£ '.$vat; ?></td>
                                                                        <?php }?>
                                                                        <td><?php echo 'Â£ '.$total; ?></td>
                                                                    </tr>
                                                                        <?php

                                                                        $finaltotal+=$total;
                                                                        $totalnet+=$net;
                                                                        $totalvat+=$vat;
                                                                        $pikupD = date('Y-m-d', strtotime($pikupD . ' +1 day'));
                                                                    }
                                                                }
                                                            

                                                            $paidamount=0;
                                                            $singleamount=0;
                                                            $resn = array();
                                                            $amnt123 = array();
                                                           // printf($loanweeks);
                                                            if($invoicetype==2)
                                                            {
                                                                $number = $extDt[2];
                                                                $number = str_pad($number, 2, '0', STR_PAD_LEFT);
                                                                $loanweeks = $extDt[3].'-'.$number;
                                                            }
                                                            else
                                                            {
                                                                $loanweeks = $result1['week_no'];
                                                            }
                                                            $tquery = "SELECT c.*,l.`amount` as totalamount,l.`no_of_instal`,c.reason 
                                                            FROM `tbl_contractorpayment` c 
                                                            INNER JOIN `tbl_contractorlend` l ON l.`id`=c.`loan_id` WHERE c.`cid`=".$result1['cid']." 
                                                            AND c.`week_no` IN ('".$loanweeks."') AND c.`isdelete`=0";
                                                            //echo $tquery;
                                                            $trow =  $mysql -> selectFreeRun($tquery);
                                                            while($tresult = mysqli_fetch_array($trow))
                                                            {
                                                                 $resn[] = $tresult['reason'];
                                                                 $amnt123[] = $tresult['amount'];
                                                                 $paidamount += $tresult['amount'];
                                                                 $singleamount += $tresult['totalamount']/$tresult['no_of_instal'];
                                                            }
                                                            $lastamt = $finaltotal-$paidamount;
                                                            $grtotalamount = round($lastamt,2);
                                                            $mysql -> dbDisConnect();
                                                            ?>
                                                        </tbody>

                                                </table>
                                                
                                            </div>
                                            <input type="hidden" id="totalAttendanceval" value="<?php echo $totalAttendance;?>">
                                            <div class='hr hr8 hr-double hr-dotted'></div>
                                                <div class='row'>
                                                    <div class='col-sm-7 pull-left'>
                                                        I agree and give consent to deduct fees and van charges from my invoices as shown above and approved by myself, to take advantage of combined purchase discounts via Bryanston Logistics Limited, and to repay any driver penalties enforced by law, and/or van repairs on rented vehicles I drive and have total responsibility for.
                                                        <br>
                                                        <b>
                                                        Bank Name: <?php echo $bank_name;?>
                                                        <br>
                                                        Sort Code: <?php echo $sort_code;?>
                                                        <br>
                                                        Account Number: <?php echo $account_number;?>
                                                    </b>
                                                    </div>
                                                        <div class='col-sm-5 pull-right' style="text-align: right;">
                                                            <span>NET :  Â£ <?php echo $totalnet;?></span><br>
                                                            <span>VAT :  Â£ <?php echo $totalvat;?></span><br>
                                                            <span>Total :  Â£ <?php echo $finaltotal;?></span><br>
                                                            <?php
                                                            if($paidamount>0)
                                                            {
                                                                $i=0;
                                                                while($resn[$i])
                                                                {
                                                                ?>
                                                                  <span><?php echo $resn[$i];?> <br>: -Â£ <?php echo $amnt123[$i];?></span><br>
                                                                <?php
                                                                $i++;
                                                                }
                                                            }
                                                            ?>
                                                          
                                                            <br>

                                                            <span><i>The VAT shown is your output tax due to HM Revenue & Customs</i></span><br><br>
                                                           
                                                            <h4 class='pull-right'>
                                                             <b>GROSS : <span class='green'>Â£ <?php echo $grtotalamount;?></b></span>
                                                            </h4>
                                                            <h4 class='pull-right'>
                                                             <b>Due Date : <span class=''><?php echo $dueDate;?></b></span>
                                                            </h4>

                                                        </div>
                                                </div>

                                                <!-- <div class='space-6'></div>
                                                <div class='well col-sm-4'>
                                                    I agree and give consent to deduct fees and van charges from my invoices as shown above and approved by myself, to take advantage of combined purchase discounts via Bryanston Logistics Limited, and to repay any driver penalties enforced by law, and/or van repairs on rented vehicles I drive and have total responsibility for.
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-primary" style="margin-top: 10px; margin-left: 8px;" onclick="printJ()">PRINT</button>

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

                        </div>
                    </div>
                </div>        
            </main>
        </div>
    </div>

<?php include('footer.php');?>

</div>

<?php include('footerScript.php');?>
 <script type="text/javascript">
     $(document).ready(function() 
    {
      $("#totalAttendance").html($("#totalAttendanceval").val());
    });
 </script>
</body>

</html>   