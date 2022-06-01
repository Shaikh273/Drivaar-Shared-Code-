<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 include "../../../config.php";
 function uptbl()
 {
     global $connection;
     $testid=$_GET['id'];
     $sql="UPDATE `advance_test` SET `status`=1 WHERE `test_id`=$testid";
     $fire=mysqli_query($connection,$sql);
     
     
 }
 
function readarray($arx)
{
    global $connection;
    $testid=$_GET['id'];
    $str=explode("&*&",$arx);
    $id=$str[0];
    $name=$str[1];
     if($str[2]=="" || $str[2]==NULL)
     {
         $phy=0;
     }
     else
     {
         $phy=$str[2];
     }
     
      if($str[3]=="" || $str[3]==NULL)
     {
         $chm=0;
     }
     else
     {
         $chm=$str[3];
     }
     
      if($str[4]=="" || $str[4]==NULL)
     {
         $mb=0;
     }
     else
     {
         $mb=$str[4];
     }
     $total=$phy+$chm+$mb;
    $sql="UPDATE `student_advance_test` SET `total_get_marks`=$total,`ph_get_marks`=$phy,`cm_get_marks`=$chm,`bio_or_mth_get_marks`=$mb  WHERE `test_id`=$testid AND `student_id`=$id";
    echo "$sql";
    $fire=mysqli_query($connection,$sql);
   // echo "$testid-------";
    
    
}
 
require '../Classes/PHPExcel.php';
require '../Classes/PHPExcel/IOFactory.php';
require '../Classes/PHPExcel/Writer/Excel2007.php';

/**
 * Description of excl
 *
 * @author Abuzer
 */

$fileType = 'Excel2007';
$fileName = "../../../uploads/".$_GET['lnk'];
$shname = $_GET['id'];

$objPHPExcel = PHPExcel_IOFactory::createReader($fileType);
$objPHPExcel = $objPHPExcel->load($fileName);
if(file_exists($fileName))
{
error_log("File-------------------".$fileName,0);
}
$objPHPExcel->setActiveSheetIndexbyName($shname);


$dtar = array();
$j=1;
$k=0;
$point = 'B1';
while($objPHPExcel->getActiveSheet()->getCell($point)->getValue()!=NULL)
{
$n=0;
foreach (range('B', 'F') as $i){    
    $point = $i.$j;
    error_log($k."--".$n,0);
    $dtar[$k][$n]=$objPHPExcel->getActiveSheet()->getCell($point)->getValue();
    
    error_log($k."--".$n,0);
    echo $dtar[$k][$n]."   ";
    
    // $dtc=$dtar[$k][$n];
    //   if($dtc=="Studentd id" || $dtc=="Name" || $dtc=="Physics (180)" || $dtc=="chemistry (180)" || $dtc=="Bio (380)")
    //   {}
    //   else
    //   {
    //       echo "<script>alert('$dtc');</script>";
    //   }
    
    
    $n++;
}
$j++;
$point = 'B'.$j;
$k++;
echo "<br>";
}

$rows = count($dtar);
//echo "rows--$rows";
  for($i=1;$i<$rows;$i++)
  {     $str="";
       for($j=0;$j<5;$j++)
       {
           $k=0;
           $dt=$dtar[$i][$j];
           $ar[$k]=$dt;
           $k++;
            if($str=="")
            {
                $str=$dt;
            }
            else
            {
                $str.="&*&".$dt;
            }
        //   echo "----$dt";
           
       }
       readarray($str);
  }
  


 
uptbl();



//echo $objPHPExcel->getActiveSheet()->getCell('A2')->getValue();

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save($fileName);


header('Location: ../../../feed_advance.php');
?>
