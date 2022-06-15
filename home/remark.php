<?php               
                    
    $dt = "<div class='card text-left'>
        <label>Remark*</label>
        <textarea type='text' name='remark' id='remark' class='form-control ".$_POST['cls']."' required=''></textarea>
        <br>      
        <label for='input-file-disable-remove'>Choose File</label>
        <input type='file' id='file_".$_POST['cls']."' name='file_".$_POST['cls']."' class='dropify ".$_POST['cls']."' data-show-remove='false' accept='image/*' onchange=\"loadFile(event,'output_".$_POST['cls']."')\" style='padding-bottom: 35px;' required=''/>
        <img id='output_".$_POST['cls']."' height='150px' width='600px' style='outline-style: dashed; outline-color: #ebeced; height: 150px; width: 500px;'/>
        <br>
    </div>";
    echo $dt;
