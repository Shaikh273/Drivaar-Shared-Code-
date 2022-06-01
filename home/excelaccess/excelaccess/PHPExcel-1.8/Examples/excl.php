<?php
include "../../../config.php";
$db = new DbCon();
$pdo = $db->getCon();
require '../Classes/PHPExcel.php';
require '../Classes/PHPExcel/IOFactory.php';
require '../Classes/PHPExcel/Writer/Excel2007.php';
$fileType = 'Excel2007';
$fileName = "../../../uploads/".$_GET['lnk'];
$shname = "Sheet1";
$objPHPExcel = PHPExcel_IOFactory::createReader($fileType);
$objPHPExcel = $objPHPExcel->load($fileName);
$objPHPExcel->setActiveSheetIndexbyName($shname);
date_default_timezone_set('Asia/Kolkata');
$dt1 = $_GET['ddate'];
$dt = date("Y-m-d H:i:s",$dt1);
try{
mysqli_query($pdo,'START TRANSACTION');
$fl=0;
$flErr="";
$j=2;
$insrt = array();
global $last_id;
$last_id=0;
$nfl=0;
$err="";
switch($_GET['id'])
{
    case 1:
        $sql1 = "INSERT INTO `excel_details`(`platform_id`, `disp_timestmp`,`timestmp`) VALUES (".$_GET['plt'].",'$dt','$dt')";
        if($pdo->query($sql1)===TRUE) 
        {
            $last_id = $pdo->insert_id;
            while($objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue()!=NULL || $objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue()!=NULL)
            {
                $dtar1 = "NULL";
                $vir_dtar1 = is_numeric($objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue())?sprintf("%d",$objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue()):rtrim($objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue());
                $vir_dtar1 = ltrim($vir_dtar1);
                if(strlen($vir_dtar1)>0)
                {
                     $dtar1 = "'".$_GET['plt']."@#@".$vir_dtar1."'";
                     $vir_dtar1 = "'".$vir_dtar1."'";
                 }else
                 {
                     $vir_dtar1 = "NULL";
                 }
                $dtar2 = "NULL";
                $vir_dtar2 = is_numeric($objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue())?sprintf("%d",$objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue()):rtrim($objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue());
                $vir_dtar2 = ltrim($vir_dtar2);
                if(strlen($vir_dtar2)>0)
                {
                     $dtar2 = "'".$_GET['plt']."@#@".$vir_dtar2."'";
                     $vir_dtar2 = "'".$vir_dtar2."'";
                 }
                 else
                 {
                     $vir_dtar2 = "NULL";
                 }
                $insrt[$j-2] = "($last_id,$dtar1,$dtar2,$vir_dtar1,$vir_dtar2,'$dt')";
                $j++;
            }
            $sql2 = "INSERT INTO `excel_rows`(`detail_id`, `order_id`, `awb`, `vir_order_id`, `vir_awb`, `disp_timestmp`) VALUES ".implode(",",$insrt)." ON DUPLICATE KEY UPDATE `detail_id`=IF(`detail_id`=0,VALUES(`detail_id`),`detail_id`), `disp_timestmp`=IF(`detail_id`=0,VALUES(`disp_timestmp`),`disp_timestmp`)";
            if ($pdo->query($sql2) === TRUE) 
            {
                if($pdo->affected_rows==0)
                {
                    $fl=1;
                    $flErr="Case 1 Insert 1";
                    break;
                }
                $sql0 = mysqli_query($pdo,"SELECt count(id) as cnt from excel_rows where disp_timestmp='$dt'");
                if($row0 = mysqli_fetch_array($sql0))
                 {
                    $cnt = $row0['cnt'];
                    $sql3 = "UPDATE `excel_details` SET `disp_count`=$cnt WHERE `id`=$last_id";
                    if ($pdo->query($sql3) === TRUE) 
                    {   
                        $err = "$cnt data reflected successfuly!!!";
                    }else
                    {
                        $fl=1;
                        $flErr="Case 1 Update 1";
                        break;
                    }
                }
            }else
            {
                $fl=1;
                $flErr="Case 1 Insert 2";
                break;
             }
        }
        else
        {
            $fl=1;
            $flErr="case 1 Insert 3";
            break;
        }
        break;
     case 2:
            $cnt=0;
             while($objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue()!=NULL || $objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue()!=NULL)
             {
                 $dtar1 = is_numeric($objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue())?sprintf("%d",$objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue()):rtrim($objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue());
                 $dtar1 = ltrim($dtar1);
                 if(strlen($dtar1)>0)
                 {
                     $dtar1 = "'".$_GET['plt']."@#@".$dtar1."'";
                 }else
                 {
                     $dtar1 = "NULL";
                 }
                 $dtar2 = is_numeric($objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue())?sprintf("%d",$objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue()):rtrim($objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue());
                 $dtar2 = ltrim($dtar2);
                 if(strlen($dtar2)>0)
                 {
                     $dtar2 = "'".$_GET['plt']."@#@".$dtar2."'";
                 }
                 else
                 {
                     $dtar2 = "NULL";
                 }
                 $insrt[$j-2] = "($dtar1,$dtar2,'$dt')";
                 $j++;
             }
             $sql2 = "INSERT INTO `excel_rows`(`order_id`, `awb`, `pay_timestmp`) VALUES ".implode(",",$insrt)." ON DUPLICATE KEY UPDATE `pay_timestmp` = IF(`pay_timestmp` IS NULL,VALUES(`pay_timestmp`),`pay_timestmp`)";
             if ($pdo->query($sql2) === TRUE) 
             {
                 if($pdo->affected_rows==0)
                 {
                     $fl=1;
                     $flErr="case 2 insert 1";
                     break;
                 }
                 $sql9 = mysqli_query($pdo,"SELECT COUNT(`id`) as cnt1 FROM `excel_rows` WHERE `detail_id`=0 AND `pay_timestmp`='$dt'");
                 $row9 = mysqli_fetch_array($sql9);
                 $cnt = $row9['cnt1'];
                 $s=0;
                 $ups = array();
                 $fire3 = mysqli_query($pdo,"Select count(*) as cnt, detail_id from excel_rows where pay_timestmp='$dt' AND detail_id > 0 group by detail_id");
                 while($row3 = mysqli_fetch_array($fire3))
                 {
                   if($s==0)
                   {
                       $ups[$s] = "SELECT ".$row3['detail_id']." as id, ".$row3['cnt']." as cnt";
                   }else
                   {
                       $ups[$s] = "SELECT ".$row3['detail_id'].", ".$row3['cnt'];
                   }
                   $s++;
                 }
                 $cnt12=0;
                 $cnt12 = count($ups);
                 $sql4 = "UPDATE excel_details e JOIN ( ".implode(" UNION ALL ",$ups)." ) vals ON e.id = vals.id SET pay_count = pay_count + vals.cnt";
                 if ($pdo->query($sql4) === TRUE) 
                 {
                     $err = "$cnt data updated successfuly!!! ".$cnt - $cnt12." miscellaneous data found!!!";
                 }else
                 {
                     $fl=1;
                     $flErr="case 2 update 1";
                     break;
                 }
             }else
             {
                 $fl=1;
                 $flErr="case 2 insert 2";
                 break;
              }
         break;
     case 3:
         $lk1=0;
         $lk2=0;
         $insrt1 = array();
         while($objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue()!=NULL || $objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue()!=NULL)
             {
                 $dtar1 = is_numeric($objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue())?sprintf("%d",$objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue()):rtrim($objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue());
                 $dtar1 = ltrim($dtar1);
                 if(strlen($dtar1)>0)
                 {
                     if($lk1==0)
                     {
                         $insrt[$lk1] = "SELECT '$dtar1' as oid, '$dt' as tmps";
                     }else
                     {
                         $insrt[$lk1] = "SELECT '$dtar1', '$dt'";
                     }
                     $lk1++;
                 }
                 $dtar2 = is_numeric($objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue())?sprintf("%d",$objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue()):rtrim($objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue());
                 $dtar2 = ltrim($dtar2);
                 if(strlen($dtar2)>0)
                 {
                     if($lk2==0)
                     {
                         $insrt1[$lk2] = "SELECT '$dtar2' as awb, '$dt' as tmps";
                     }else
                     {
                         $insrt1[$lk2] = "SELECT '$dtar2', '$dt'";
                     }
                     $lk2++;
                 }
                 $j++;
             }
             if($lk1>0)
             {
                 $sql2 = "UPDATE excel_rows e JOIN ( ".implode(" UNION ALL ",$insrt)." ) vals ON e.vir_order_id = vals.oid SET e.ret_timestmp = vals.tmps WHERE e.ret_timestmp IS NULL";
                 if ($pdo->query($sql2) === FALSE) 
                 {
                     $fl=1;
                     error_log($sql2,0);
                     $flErr="case 3 update 1";
                     break;
                 }
             }
             if($lk2>0)
             {
                 $sql20 = "UPDATE excel_rows e JOIN ( ".implode(" UNION ALL ",$insrt1)." ) vals ON e.vir_awb = vals.awb SET e.ret_timestmp = vals.tmps WHERE e.ret_timestmp IS NULL";
                 if ($pdo->query($sql20) === FALSE) 
                 {
                     $fl=1;
                     $flErr="case 3 update 2";
                     break;
                 }
             }
                 $s=0;
                 $ups = array();
                 $fire3 = mysqli_query($pdo,"Select count(*) as cnt, detail_id from excel_rows where ret_timestmp='$dt' AND detail_id > 0 group by detail_id");
                 while($row3 = mysqli_fetch_array($fire3))
                 {
                   if($s==0)
                   {
                       $ups[$s] = "SELECT ".$row3['detail_id']." as id, ".$row3['cnt']." as cnt";
                   }else
                   {
                       $ups[$s] = "SELECT ".$row3['detail_id'].", ".$row3['cnt'];
                   }
                   $s++;
                 }
                 $cnt = count($ups);
                 if($cnt>0)
                 {
                     $sql4 = "UPDATE excel_details e JOIN ( ".implode(" UNION ALL ",$ups)." ) vals ON e.id = vals.id SET e.ret_count = e.ret_count + vals.cnt";
                     if ($pdo->query($sql4) === TRUE) 
                     {
                         $err = "$cnt return data updated successfuly!!!";
                     }else
                     {
                         $fl=1;
                         $flErr="case 3 update 3";
                         break;
                     }
                 }else
                 {
                     $fl=1;
                     $flErr="case 3 select 1";
                     break;
                 }
         break;
}
 if($fl==1)
 {
     error_log($flErr,0);
     mysqli_query($pdo,'ROLLBACK');
     $err = "Rollback. Some error found!!!";
 }else if($fl==0)
 {
     mysqli_query($pdo,'COMMIT');
 }
 $_SESSION['ErMsg']=$err;
}catch (Exception $e){
    $_SESSION['ErMsg']=$e;
    error_log($flErr,0);
    error_log($e,0);
    mysqli_query($pdo,'ROLLBACK');
    throw $e;
}
 $pdo->close();
 $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
 $objWriter->save($fileName);
 unlink("../../../uploads/".$_GET['lnk']);
 header('Location: ../../../operation.php');
?>