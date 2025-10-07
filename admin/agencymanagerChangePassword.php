<?php
ob_start();
error_reporting(E_ALL);
include_once '../common.php';
$connect = new connect();
include('IsLogin.php');

$result = mysqli_query($dbconn,"SELECT * FROM `agencymanager` WHERE `agencymanagerid`='" . $_REQUEST['token'] . "'");
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
} else {
    echo 'somthig going worng! try again';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $ProjectName; ?> | Agency Change Password </title>
        <?php include_once 'include.php'; ?>
    </head>
    <body class="page-container-bg-solid page-boxed">
        <?php include_once './header.php'; ?>
        <div style="display: none; z-index: 10060;" id="loading">
            <img id="loading-image" src="<?php echo $web_url;?>admin/images/loader1.gif">
        </div>
        <div class="page-container">
            <div class="page-content-wrapper">

                <div class="page-content">
                    <div class="container">
                        <ul class="page-breadcrumb breadcrumb">
                            <li>
                                <a href="<?php echo $web_url;?>admin/index.php">Home</a>
                                <i class="fa fa-circle"></i>
                            </li>
                              <li>
                                  <a href="<?php echo $web_url;?>admin/LocationEmployee.php">List Of Agency</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            
                            <li>
                                <span> Agency Change Password</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-red-sunglo">
                                                <i class="icon-settings font-red-sunglo"></i>
                                                <span class="caption-subject bold uppercase">Agency Change Password</span>

                                            </div>
                                        </div>
                                        <div class="portlet-body form">
                                            <form  role="form"  method="POST"  action="" name="fromchangepassword"  id="fromchangepassword" enctype="multipart/form-data" class="margin-bottom-40">
                                                <input type="hidden" value="AgencyChangePassword" name="action">
                                                <input type="hidden" name="agencymanagerid" id="agencymanagerid" value="<?php echo $row['agencymanagerid'];?>">
                                                <div class="form-body">

                                                   
                                                    <div class="form-group form-md-line-input  col-md-4">
                                                        <label for="form_control_1">New Password</label>
                                                        <input type="password"  id="password" name="password" class="form-control" placeholder="Enter your New Password" required>
                                                    </div>
                                                    <div class="form-group form-md-line-input  col-md-4">
                                                        <label for="form_control_1">Confirm Password</label>
                                                        <input type="password"  id="cpassword" name="cpassword"  class="form-control" placeholder="Enter your Confirm Password" required>
                                                    </div>

                                                </div>
                                                <div class="form-actions noborder">

                                                    <button type="button"  onClick="changepassword();" class="btn blue margin-top-20">Submit</button>
                                                    <button type="button" class="btn blue margin-top-20" onClick="checkclose();">Close</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once './footer.php'; ?>
        <script type="text/javascript">

            function checkclose() {
               
                window.location.href = '<?php echo $web_url;?>admin/agencymanager.php';
               
            }


            function changepassword()
            {
                //var oldps = $.trim($("#oldpassword").val());
                var ps = $.trim($("#password").val());
                var cps = $.trim($("#cpassword").val());
//                if (oldps == '')
//                {
//                    $("#oldpassword").attr("placeholder", "Old password Cannot be Blank");
//                    $("#oldpassword").focus();
//                }
                if (ps != "" && cps != "")
                {
                    if (ps != cps)
                    {
                        $("#cpassword").val('');
                        $("#cpassword").attr("placeholder", "Confirm password Doen't match");
                        $("#cpassword").focus();
                    } else {
                        var data = $('#fromchangepassword').serializeArray();
                        $('#loading').css("display", "block");
                        $.ajax({
                            type: "POST",
                            url: "<?php echo $web_url;?>admin/querydata.php",
                            data: data,
                            success: function (msg) {
                                $('#loading').css("display", "none");
                                var msg = $.trim(msg);
                                if (msg == 'Sucess') {
                                    alert('Successfully Employee Password Changed.')
                                    window.location.href = "<?php echo $web_url;?>admin/agencymanager.php";
                                } 
//                                else if (msg == 'OldNot') {
//                                    alert('Wrong Old Password !')
//                                    window.location.href = "";
//                                }
                                else {

                                }
                            },
                        });
                    }

                } else {
                    if (ps == "")
                        $("#password").attr("placeholder", "Enter New Password please");
                    if (cps == "")
                        $("#cpassword").attr("placeholder", "Enter Confirm password");
                }

            }
        </script>


    </body>
</html>

