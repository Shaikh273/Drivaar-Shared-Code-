<?php
if (!isset($_SESSION)) {
    session_start();
}
$page_id = '144';
if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
{
    
}
else
{  
  header("location: login.php");    
}
//include('authentication.php');
include('config.php');
$page_title="Profile";
$cid = $_SESSION['cid'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=1024">
    <title><?php echo $page_title;?></title>
    <?php include('head.php');?>
    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
    <link rel="stylesheet" href="countrycode/build/css/demo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="dist/jq.dice-menu.min.css" />
</head>
    <body class="skin-default-dark fixed-layout">
        <?php include('loader.php');?>
        
        <div id="main-wrapper" id="top">
        <?php include('header.php');?>
            
            <div class="page-wrapper content" id="top" >
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card" style="box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
                                <div class="card-header">Profile</div>
                                <div class="card-body">
                                    <form method="post" name="contractorDetailUpdate" id="contractorDetailUpdate" action="" enctype="multipart/form-data">
                                     <?php
                                        include 'DB/config.php';
                                        $mysql = new Mysql();
                                        $mysql -> dbConnect();
                                        $cid=$_SESSION['cid'];
                                        $sql = "SELECT * FROM `tbl_contractor` WHERE `id`=$cid";
                                        $typerow =  $mysql -> selectFreeRun($sql);
                                        $typeresult = mysqli_fetch_array($typerow);
                                        $profile = $typeresult['file'];
                                     ?>
                                     <input type="hidden" name="contractoroldpassword" id="contractoroldpassword" value="<?php echo $typeresult['password'];?>">
                                      <div class="form-row">
                                        <div class="form-group col-md-6">
                                          <label for="inputEmail4">Email Address</label>
                                          <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="<?php echo $typeresult['email'];?>" required="" readonly>
                                        </div>
                                        <div class="form-group col-md-6">
                                          <label for="inputPassword4">Mobile Number</label>
                                          <input type="number" class="form-control" id="contact" name="contact" placeholder="Mobile Number" value="<?php echo $typeresult['contact'];?>" required="">
                                        </div>
                                      </div>
                                       <div class="form-row">
                                          <div class="form-group col-md-6">
                                            <label for="inputAddress">Address</label>
                                            <input type="text" class="form-control" id="address" name="address" placeholder="1234 Main St" value="<?php echo $typeresult['address'];?>">
                                          </div>
                                          <div class="form-group col-md-6">
                                            <label for="inputAddress2">Address 2</label>
                                            <input type="text" class="form-control" id="street_address" name="street_address" placeholder="Apartment, studio, or floor" value="<?php echo $typeresult['street_address'];?>">
                                          </div>
                                       </div>
                                      <div class="form-row">
                                        <div class="form-group col-md-6">
                                          <label for="inputCity">Town/City</label>
                                          <input type="text" class="form-control" id="city" name="city" value="<?php echo $typeresult['city'];?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                          <label for="inputCity">County/District</label>
                                          <input type="text" class="form-control" id="state" name="state" value="<?php echo $typeresult['state'];?>">
                                        </div>
                                      </div>
                                      
                                      <div class="form-row">
                                        <div class="form-group col-md-6">
                                          <label for="inputZip">Zip</label>
                                          <input type="text" class="form-control" id="postcode" name="postcode" value="<?php echo $typeresult['postcode'];?>">
                                        </div>
                                         <div class="form-group col-md-6">
                                          <label for="inputState">Country</label>
                                          <input type="hidden" name="selectcountry" id="selectcountry" value="<?php echo $typeresult['country'];?>">
                                          <select class="form-control" name="country" id="country" value="<?php echo $typeresult['country'];?>">
                                            <?php
                                                $sql = "SELECT * FROM `tbl_country` WHERE `isdelete`=0";
                                                $typerow =  $mysql -> selectFreeRun($sql);
                                                while($typeresult = mysqli_fetch_array($typerow)){
                                            ?>
                                            <option value="<?php echo $typeresult['id'];?>"><?php echo $typeresult['name'];?></option>
                                            <?php
                                                }
                                            //$mysql -> dbDisConnect();
                                            ?>
                                          </select>
                                          <div id="countrymsg"></div>
                                        </div>
                                      </div>
                                      <div class="form-row">
                                        <div class="form-row col-md-6">
                                              <div class="form-group col-md-12 form-row">
                                                  <label for="">Old Password</label>
                                                  <input type="Password" class="form-control" id="oldpassword" name="oldpassword" value="">
                                                  <div class="" id="passwordmsg"></div>
                                              </div>
                                              <div class="form-group col-md-12 form-row">
                                                  <label for="">New Password</label>
                                                  <input type="Password" class="form-control" id="newpassword" name="newpassword" value="" disabled="">
                                              </div>
                                              <div class="form-group col-md-12 form-row">
                                                  <label for="">Confirm Password</label>
                                                  <input type="Password" class="form-control" id="confirmpassword" name="confirmpassword" value="" disabled="">
                                                  <div class="" id="newconfirmpasswordmsg"></div>
                                              </div>
                                          </div>   
                                          <div class="form-row col-md-6">
                                             <div class="form-group col-md-12 form-row">
                                                  <label for="">Profile</label>
                                                  <input type="file" id="file" name="file" value="" class="form-control" placeholder=""  onchange="loadFile(event)" >
                                              </div>
                                              <div class="form-group col-md-12 form-row">
                                                  <iframe src="" id="imgsrc" name="imgsrc" style="height: 100%;"></iframe>
                                              </div>
                                          </div> 
                                      </div>
                                      <button type="submit" class="btn btn-primary" name="update" id="update">Update</button>
                                    </form>
                                </div>      
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('footer.php');?>         
        </div>
<?php include('footerScript.php'); ?>
<script type="text/javascript">

  $("#file").change(function() {
        var file = this.files[0];
        var fileType = file.type;
        var match = ['image/JPEG', 'image/PNG', 'image/JPG', 'image/jpeg', 'image/png', 'image/jpg'];
        if(!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) || (fileType == match[3]) || (fileType == match[4]) || (fileType == match[5])))
        {
            alert('Sorry, only JPG, JPEG, & PNG files are allowed to upload.');
            $('#imgsrc').src('');
            $("#file").val('');
            return false;
        }
    });

  var loadFile = function(event) {
        //$('#imgsrc').removeClass('hidden');
        var image = document.getElementById('imgsrc');
        image.src = URL.createObjectURL(event.target.files[0]);
  };

  $(document).ready(function(){
      var profile = '<?php echo $profile?>';
      if(profile!='')
      {
        $('#imgsrc').attr('src','http://drivaar.com/contractorAdmin/upload/Userprofile/'+profile);
        $('#file').val(profile);
      }

      var selectcountry = $('#selectcountry').val(); 
      $('#country').val(selectcountry);
  });

  // $(document).ready(function() {
  //     $("#contractorDetailUpdate").validate({
  //         rules: {   
  //             email: {
  //                 required: true,
  //                 email: true
  //             },
  //             newpassword: {
  //                 minlength: 8
  //             },
  //             confirmpassword: {
  //                 minlength: 8,
  //                 equalTo: "#newpassword"
  //             }  
  //         },
  //         messages: {
  //             email: {
  //                 required: "Please enter your email address",
  //                 email: "Please enter a valid email address",
  //             },
  //             newpassword: {
  //                 minlength: "Please enter your password minimum eight digits",
  //             },
  //             confirmpassword: {
  //                 minlength: "Please enter your password minimum eight digits",
  //                 equalTo: "Please enter your confirm password as same as password",
  //             }  
  //         },
  //     submitHandler: function(form) {
  //         event.preventDefault();
  //         var country = $('#country').val();
  //         if(country != null)
  //         {
  //             $.ajax({
  //                 url: "loaddata.php",
  //                 type: "POST",
  //                 dataType: "JSON",
  //                 data: $("#contractorDetailUpdate").serialize() + "&action=contractorDetailUpdate",
  //                 success: function(data) {
  //                    if(data.status==1)
  //                     {
  //                         $('input[name=oldpassword').val('');
  //                         $('input[name=confirmpassword').val('');
  //                         $('input[name=newpassword').val('');
  //                         myAlert(data.title + "@#@" + data.message + "@#@success");
  //                     }
  //                     else
  //                     {
  //                         myAlert(data.title + "@#@" + data.message + "@#@danger");
  //                     }
  //                 }
  //             });
              
  //         }else
  //         {
  //             document.getElementById("countrymsg").innerHTML = "Please select country !!!";
  //             document.getElementById('countrymsg').style.color = 'red';
  //         }       
  //     }
  //     });
  // });


  $(document).ready(function(){
        //var webroot="<?php //echo $webroot?>";
        $("#contractorDetailUpdate").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                newpassword: {
                    minlength: 8
                },
                confirmpassword: {
                    minlength: 8,
                    equalTo: "#newpassword"
                }  
            },
            messages: {
                email: {
                    required: "Please enter your email address",
                    email: "Please enter a valid email address",
                },
                newpassword: {
                    minlength: "Please enter your password minimum eight digits",
                },
                confirmpassword: {
                    minlength: "Please enter your password minimum eight digits",
                    equalTo: "Please enter your confirm password as same as password",
                }  
            },
        });

        $("#contractorDetailUpdate").on('submit', function(e){
            e.preventDefault();
            var country = $('#country').val();
            if(country != null)
            {
                $.ajax({
                  type: 'POST',
                  url: 'contractorDetailUpdate.php',
                  data: new FormData(this),
                  dataType: 'json',
                  contentType: false,
                  cache: false,
                  processData:false,
                  success: function(data)
                  { 
                      if(data.status==1)
                      {
                          $('input[name=oldpassword').val('');
                          $('input[name=confirmpassword').val('');
                          $('input[name=newpassword').val('');
                          myAlert(data.title + "@#@" + data.message + "@#@success");
                          window.location.href = "http://drivaar.com/contractorAdmin/logout.php";
                      }
                      else
                      {
                          myAlert(data.title + "@#@" + data.message + "@#@danger");
                      }
                  }
                });
            } 
        });
    });

  $( "#oldpassword" ).change(function() {

      var oldpasswordinput = $(this).val();
      var oldpassword = $('#contractoroldpassword').val();

      if(oldpasswordinput == oldpassword)
      {
        document.getElementById("passwordmsg").innerHTML = "";
        $("#newpassword").prop('disabled', false);
        $("#confirmpassword").prop('disabled', false);     
      }
      else
      {
          document.getElementById("passwordmsg").innerHTML = "Password Is Incorrect !!!";
          $("#newpassword").prop('disabled', true);
          $("#confirmpassword").prop('disabled', true);
      }   
  });

// $( "#confirmpassword" ).change(function() {
// var newpassword = $('#newpassword').val();
// var confirmpassword = $(this).val();
// alert(confirmpassword);
//         //alert(confirmpassword);
//         if(newpassword == confirmpassword)
//         {
//             $("#update").prop('disabled', false);
//             document.getElementById("newconfirmpasswordmsg").innerHTML = "";
//         }    
//         else
//         {
//             //alert(confirmpassword);
//             $("#update").prop('disabled', true);
//             document.getElementById("newconfirmpasswordmsg").innerHTML = "Confirm Password Is Not Match !!!";   
//         }  

// });   
</script>
<script src="dist/jq.dice-menu.js"></script>



</body>

</html>