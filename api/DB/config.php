<?php

include 'dbconfig.php';

$webroot = 'http://drivaar.com/home/';
date_default_timezone_set('Europe/London');

class Mysql extends Dbconfig {

    public $connectionString;

    public $dataSet;

    private $sqlQuery;

    protected $databaseName;

    protected $hostName;

    protected $userName;

    protected $passCode;

    function Mysql() {

        $this -> connectionString = NULL;

        $this -> sqlQuery = NULL;

        $this -> dataSet = NULL;

        $dbPara = new Dbconfig();

        $this -> databaseName = $dbPara -> dbName;

        $this -> hostName = $dbPara -> serverName;

        $this -> userName = $dbPara -> userName;

        $this -> passCode = $dbPara ->passCode;

        $dbPara = NULL;

    }

  

    function dbConnect()    {

        $this -> connectionString = new mysqli($this -> serverName,$this -> userName,$this -> passCode, $this -> databaseName);

        return $this -> connectionString;

    }



    function dbDisconnect() {

        $this -> connectionString = NULL;

        $this -> sqlQuery = NULL;

        $this -> dataSet = NULL;

        $this -> databaseName = NULL;

        $this -> hostName = NULL;

        $this -> userName = NULL;

        $this -> passCode = NULL;

    }


    function checkpermission($pageid)
    {
        if(!isset($_SESSION)) 
        {
            session_start();
        }
        if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$pageid]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
        {
            
        }
        else
        {
            if((isset($_SESSION['adt']) && $_SESSION['adt']==1))
            {
                header("location: login.php");
            }
            else
            {
                header("location: login.php");
            }
        }
    }


    function selectAll($tableName)  {

        $this -> sqlQuery = 'SELECT * FROM '.$tableName;

        $this -> dataSet = mysqli_query($this -> connectionString, $this -> sqlQuery);

        return $this -> dataSet;

    }

    

    function logg($valus){

        $vs1 = array();

        $vs2 = array();

        $cn = count($valus);

        $j=0;

        while($j<$cn){
            $i=0;

            foreach($valus[$j] as $key => $val){

                // print $i."3--0--";

                $vs2[$i] =$key."=>".$val;

                $i++;

            }

            $vs1[$j] = $vs2;
            $j++;

        }
        // $myfile = fopen("myfileloginsert.txt", "w") or die("Unable to open file!");
        // fwrite($myfile,  "'".json_encode($vs1)."'");
        // fclose($myfile);
        return $str ="'".json_encode($vs1)."'";
    }


    function SendMessage($emlusername,$emlpassword,$emltitle,$subject,$body,$dataMsg,$email,$name)
    {
        
        require_once('phpmailer/class.phpmailer.php');
        $mail = new PHPMailer(true);            
        $mail->IsSMTP(); 
          try {
          $mail->Host       = "smtp.gmail.com"; 
          $mail->SMTPDebug  = 0;
          $mail->SMTPAuth   = true; 
          $mail->Port       = 587;
          $mail->SMTPSecure = 'tls';     
          $mail->Username   = $emlusername; 
          $mail->Password   = $emlpassword;        
          $mail->AddAddress($email,$name);
          $mail->SetFrom($emlusername,$emltitle);
          $mail->mailtype = 'html';
          $mail->charset = 'iso-8859-1';
          $mail->wordwrap = TRUE;
          $mail->Subject = $subject;
          $mail->AltBody = $body; 
          $mail->MsgHTML($dataMsg);
          $mail->Send();
          
        } catch (phpmailerException $e) {
          //echo $e->errorMessage()."123"; exit;
        } catch (Exception $e) {
          //echo $e->getMessage()."4567";  exit;    
        } 

    }

    // function loggup($tableName, $valus, $clname,$optype, $where){

    //     // $ovalus = "";

    //     $this -> sqlQuery = 'SELECT ';

    //     foreach($clname as $v){

    //         $this -> sqlQuery .=$v.",";

    //     }

    //     $this -> sqlQuery = substr_replace($this -> sqlQuery, "", -1);

    //     $this -> sqlQuery .=' FROM '.$tableName.' WHERE '.$where;

    //     $this -> dataSet = mysqli_query($this -> connectionString, $this -> sqlQuery);        

    //     $i =0;

    //     while($row = mysqli_fetch_array($this -> dataSet)){

    //         foreach($clname as $v){

    //           $ovalus[$i][$v] = $row[$v];

    //           $ovalus[$i][$v] = $row[$v];

    //         }

            

    //     }

    //     $str1 = $this->logg($ovalus);

    //     $str = $this->logg($valus);

    //     $this-> sqlQuery1 = 'INSERT INTO `log`(`userid`,`operation_type`, `old_value`, `new_value`, `tablename`, `where`) VALUES(0,'.$optype.','.$str1.' ,'.$str.','.$tableName.','.$where.')';

    //     mysqli_query($this -> connectionString, $this -> sqlQuery1);

    //     return mysqli_info($this ->connectionString);

    // } 



    function loggi($tableName, $valus, $optype){
        $ovalus = "";
        $str = $this->logg($valus);
        $this -> sqlQuery = 'INSERT INTO `log`(`userid`,`operation_type`, `old_value`, `new_value`, `tablename`) VALUES(0,'.$optype.','.$ovalus.' ,'.$str.','.$tableName.')';
        // $myfile = fopen("myfileloginsert.txt", "w") or die("Unable to open file!");
        // fwrite($myfile,  $str);
        // fclose($myfile);
        mysqli_query($this ->connectionString , $this -> sqlQuery);
        return mysqli_info($this ->connectionString);
     }


    function update($tableName, $valus, $clname, $optype, $where){
      // $this-> loggup($tableName, $valus, $clname, $optype, $where);
       $this-> sqlQuery = 'UPDATE '.$tableName.' SET ';       
       $vls = array();
       $i = 0;
       $val1 = "";
       $str = "";
       $j =0;

       $countvalus = count($valus);

       while($j < $countvalus){

           $i=0;

       foreach($valus[$j] as $key => $val){

           if(is_int($val) == false){

               $str = "'";

               $str .= $val;

               $str .= "'";

           }        

           else{

               $str = $val;

           }

           $val1 .= "`".$key."`=".$str.",";

           $i++;

        }

       $val1 = substr_replace($val1, "", -1);

       $this-> sqlQuery .= $val1;        
       $j++;
       }

       $this-> sqlQuery .= ' WHERE '.$where;

        // $myfile = fopen("myfileupdate.txt", "w") or die("Unable to open file!");
        // fwrite($myfile, $this -> sqlQuery."\n");
        // fclose($myfile);
        
       mysqli_query($this ->connectionString , $this -> sqlQuery);
       return mysqli_info($this ->connectionString);
    }



    function insertre($tableName, $valus){
        $this -> createinsert($tableName, $valus);
        // $this-> loggi($tableName, $valus, 'insert');
        
        // $myfile = fopen("myfile12.txt", "w") or die("Unable to open file!");
        // fwrite($myfile, $this -> sqlQuery."\n");
        // fclose($myfile);
        mysqli_query($this ->connectionString , $this -> sqlQuery);
        //echo $this -> sqlQuery;
        $this -> sqlQuery = NULL;
        return mysqli_insert_id($this -> connectionString);
    }



    function insert($tableName, $valus){
        $this ->  createinsert($tableName, $valus);
        //$this-> loggi($tableName, $valus, 'insert');
        // $myfile = fopen("myfile.txt", "w") or die("Unable to open file!");
        // fwrite($myfile, $this -> sqlQuery."\n");
        // fclose($myfile);
        mysqli_query($this ->connectionString , $this -> sqlQuery);
        // $this -> sqlQuery = NULL;
        return $this -> sqlQuery;
    }

  
    function createinsert($tableName, $valus){

        $this-> sqlQuery = 'INSERT INTO '.$tableName;

        $vls = array();

        $i = 0;

        $val1 = "";

        $str = "";

        $clname = "(";

        $countvalus = count($valus);

        while($i < $countvalus){

        $val1 = "(";

        foreach($valus[$i] as $key => $val){

            if($i==0){

                $clname .=  "`".$key."`,";

            }

            if(is_int($val) == false){

                $str = "'";

                $str .= $val;

                $str .= "'";

            }        

            else{

                $str = $val;

            }

            $val1 .= $str.",";

         }

        $val1 = substr_replace($val1, "", -1);

        $val1 .=")";

        $vls[$i] = $val1;

        $i++;

        }

        $clname = substr_replace($clname, "", -1);

        $clname .=")";

        $this-> sqlQuery .= $clname." VALUES";

        $k = 0;

        $cou = count($vls);

        while($k<$cou){

        $this-> sqlQuery .= $vls[$k] .",";

        $k++;

        }

        $this -> sqlQuery = substr_replace($this -> sqlQuery, "", -1);

        //  $myfile = fopen("myfileinsert12.txt", "w") or die("Unable to open file!");
        // fwrite($myfile, $this -> sqlQuery."\n");
        // fclose($myfile);

        return $this -> sqlQuery;
    }


    function OnduplicateInsert($tableName, $valus, $extra)
    {
        $this-> sqlQuery = 'INSERT INTO '.$tableName;

        $vls = array();

        $i = 0;

        $val1 = "";

        $str = "";

        $clname = "(";

        $countvalus = count($valus);

        while($i < $countvalus){

        $val1 = "(";

        foreach($valus[$i] as $key => $val){

            if($i==0){

                $clname .=  "`".$key."`,";

            }
            if(is_int($val) == true){

                $str = $val;

            }        
            else
            {
                $str = "'";

                $str .= $val;

                $str .= "'";
            }

            $val1 .= $str.",";

         }

        $val1 = substr_replace($val1, "", -1);

        $val1 .=")";

        $vls[$i] = $val1;

        $i++;

        }

        $clname = substr_replace($clname, "", -1);

        $clname .=")";

        $this-> sqlQuery .= $clname." VALUES";

        $k = 0;

        $cou = count($vls);

        while($k<$cou){

        $this-> sqlQuery .= $vls[$k] .",";

        $k++;

        }

        $this -> sqlQuery = substr_replace($this -> sqlQuery, "", -1);

        $this -> sqlQuery = $this -> sqlQuery . $extra; 
        // $myfile = fopen("myfileinsert.txt", "w") or die("Unable to open file!");
        // fwrite($myfile, $this -> sqlQuery."\n");
        // fclose($myfile);
        mysqli_query($this ->connectionString , $this -> sqlQuery);
        // $this -> sqlQuery = NULL;
        return $this -> sqlQuery;
    }

    function selectWhere($tableName,$rowName,$operator,$value,$valueType)   {

        $this -> sqlQuery = 'SELECT * FROM '.$tableName.' WHERE '.$rowName.' '.$operator.' ';

        if($valueType == 'int') {

            $this -> sqlQuery .= $value;

        }

        else if($valueType == 'char')   {

            $this -> sqlQuery .= "'".$value."'";

        }

        $this -> dataSet = mysqli_query($this -> connectionString, $this -> sqlQuery);

        $this -> sqlQuery = NULL;

        return $this -> dataSet;
    }

    
    function selectFreeRun($query) {
        $this -> dataSet = mysqli_query($this -> connectionString, $query);
        return $this -> dataSet;
    }



    function freeRun($query) {

        mysqli_query($this -> connectionString, $query);

        return mysqli_insert_id($this -> connectionString);

        // $last_inserted_id=$mysqli->insert_id; 

        // return $last_inserted_id;
    }

    

    // function sendSMS($contact, $msg)

    // {

    //     $url = "http://sms.infrowsertechnologies.com/http-api.php?username=siys&password=Siys@1234&senderid=SIYSST&route=1&unicode=2&number=$contact&message=$msg";

    //     $ch = curl_init();

    //     curl_setopt($ch,CURLOPT_URL,$url);

    //     curl_setopt($ch, CURLOPT_HEADER, false);

    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

    //     curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    //     $res = curl_exec($ch);

    //     curl_close($ch);

    //     return $msg;

    // }

}

?> 