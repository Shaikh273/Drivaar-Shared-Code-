<!DOCTYPE html>
<html>
<head>
    <title>PHP Signature Pad Example - ItSolutionStuff.com</title>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">
  
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> 
    <link type="text/css" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet"> 
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="../js/jquery.signature.min.js"></script>

    <style>
        .kbw-signature { width: 400px; height: 200px;}
        #sig canvas{
            width: 100% !important;
            height: auto;
        }
    </style>
  
</head>
<body>
  
<div class="container">
<?php
    $id = $_GET['id'];
    error_log($id,0);
    $sid = $_GET['sid'];
    
    if($id == "")
    {
         echo "
        <input type='hidden' name='last_insert_id' id='last_insert_id' value='".$_GET['sid']."'>
        <h4>Signature</h4>
        <h5 class='font-weight-bold mt-2' style='color: red;'>*** Add signature within 10 second ***</h5>
  
        <div class='col-md-12'>
            <label class='' for=''>Signature:</label>
            <br/>
            <div id='sig' class='border'></div>
            <br/>
            <button id='clear'>Clear Signature</button>
            <textarea id='signature64' class='step_5' name='signed' style='display: none'></textarea>
        </div>
  
        <br/>
        <button type='button' class='btn btn-success' value=".$_GET['sid']." onclick='submit2(this.value);'>Submit</button>";
    }
    else
    {
        echo "
        <input type='hidden' name='last_insert_id' id='last_insert_id' value='".$_GET['id']."'>
        <input type='hidden' name='status' id='status' value=''>
        <h4>Signature</h4>
        <h5 class='font-weight-bold mt-2' style='color: red;'>*** Add signature within 10 second ***</h5>
  
        <div class='col-md-12'>
            <label class='' for=''>Signature:</label>
            <br/>
            <div id='sig' class='border'></div>
            <br/>
            <button id='clear'>Clear Signature</button>
            <textarea id='signature64' class='step_5' name='signed' style='display: none'></textarea>
        </div>
  
        <br/>
        <button type='button' class='btn btn-success' value='".$_GET['id']."' onclick='submit(this.value);'>Submit</button>";
        
    }

       
?>
  
    

</div>
  
<script type="text/javascript">
var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG'});
$('#clear').click(function(e) {
    e.preventDefault();
    sig.signature('clear');
    $("#signature64").val('');
});

function submit(val){ 
    sigpad= $("#signature64").val();
    var last_insert_id = val;

    $.ajax({
        url: "InsertData.php", 
        type: "POST", 
        data: {
            'action':'rentalaggreementsignature',
            'last_insert_id':last_insert_id,
            'signed': sigpad,
        }, 
        dataType:"JSON",            
        success: function(result) {
            if(result.status==1)
            {
                $('#status').val(data.status);
                
            }
            else
            {
                // myAlert(result.title + "@#@" + result.message + "@#@danger");
            }
        }
    });
}

function submit2(val)
{ 
    sigpad= $("#signature64").val();
    var last_insert_id = val;

    $.ajax({
        url: "InsertData.php", 
        type: "POST", 
        data: {
            'action':'rentalaggreementsignature2',
            'last_insert_id':last_insert_id,
            'signed': sigpad,
        }, 
        dataType:"JSON",            
        success: function(result) {
            if(result.status==1)
            {
                $('#status').val(data.status);
                
            }
            else
            {
                // myAlert(result.title + "@#@" + result.message + "@#@danger");
            }
        }
    });
}
</script>
  
</body>
</html>