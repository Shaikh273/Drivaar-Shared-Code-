<?php
include 'config.php';
$valus[0]['rid'] = 12;
$valus[0]['pid'] = 13;
$valus[1]['rid1'] = 12;
// $valus[1]['pid1'] = 14;
// $clname = array("catname", "type");
$where = "`ID`=2";
$mysql = new Mysql();
$mysql -> dbConnect();
// echo $my = $mysql -> Delete('catlist', $valus,"Delete",$where);
// $vs1 = array();
// $vs2 = array();
// $cn = count($valus);
// $j=0;
// while($j<$cn){
//     $i=0;
//     foreach($valus[$j] as $key => $val){
//         // print $i."3--0--";
//         $vs2[$i] =$key."=>".$val;
//         $i++;
//     }
//     $vs1[$j] = $vs2;
//     $j++;
// }
// $str = json_encode($vs1);
// echo $str;
// $mysql = new Mysql();
// $mysql -> dbConnect();
// $my = $mysql -> insert('pageid', $valus);




// $mysql -> dbDisconnect();
// $vls = array();
// $i = 0;
// $val1 = "";
// $str = "";
// $clname = "(";

// while($i < count($valus)){
//     $val1 = "(";
//     foreach($valus[$i] as $key => $val){
//         if($i==0){
//           $clname .=  "`".$key."`,";
//         }
//         if(is_int($val) == false){
//           $str = "'";
//           $str .= $val;
//           $str .= "'";
//         }        
//         else{
//             $str = $val;
//         }
//         $val1 .= $str.",";
//         // print $key;
//         // print $str;
//     }
//     // if($i == 0){
//     //     $clname .= ")";
//     // }    // $val1 .= ")";
//     $val1 = substr_replace($val1, "", -1);
//     $val1 .=")";
//     $vls[$i] = $val1;
//     $i++;
    
// }
// $clname = substr_replace($clname, "", -1);
// $clname .=")";
// print $clname;
// $k = 0;
// $cou = count($vls);
// while($k<$cou){
//     print $vls[$k];
//    $k++;
// }
?>