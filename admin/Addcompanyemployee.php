<?php
ob_start();
error_reporting(E_ALL);
include_once '../common.php';
$connect = new connect();
include('IsLogin.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $ProjectName; ?> | Add Company Employee </title>
        <?php include_once 'include.php'; ?>
    </head>
    <body class="page-container-bg-solid page-boxed">
        <?php include_once './header.php'; ?>
        <div style="display: none; z-index: 10060;" id="loading">
            <img id="loading-image" src="<?php echo $web_url; ?>admin/images/loader1.gif">
        </div>
        <div class="page-container">
            <div class="page-content-wrapper">
                <div class="page-content">
                    <div class="container">
                        <ul class="page-breadcrumb breadcrumb">
                            <li>
                                <a href="<?php echo $web_url; ?>admin/index.php">Home</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <a href="<?php echo $web_url; ?>admin/LocationEmployee.php">List Of Company Employee</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <span>Add Company Employee</span>
                            </li>
                        </ul>
                        <div class="page-content-inner">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-red-sunglo">
                                                <i class="icon-settings font-red-sunglo"></i>
                                                <span class="caption-subject bold uppercase"> Add Company Employee</span>
                                            </div>
                                        </div>
                                        <div class="portlet-body form">
                                            <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                                <input type="hidden" value="Addcompanyemployee" name="action" id="action">
                                                <div class="form-body">

                                                 

                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Employee  Name</label>
                                                        <input name="employeename" id="employeename"  class="form-control" placeholder="Enter Your  Name" type="text" required="">
                                                    </div>
                                                   
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Mobile</label>
                                                        <input name="mobile" id="mobile"  class="form-control" placeholder="Enter Your  Mobile" type="text" >
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Address</label><div id="errordiv"></div>
                                                        <textarea  name="address" id="address"  class="form-control"  type="text"  ></textarea>
                                                    </div> 
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Login ID</label><div id="errordiv"></div>
                                                        <input name="LoginID" id="LoginID"  class="form-control" placeholder="Enter Your Login ID." type="text" required="" onblur="return chkLoginId();">
                                                    </div> 
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Password</label>
                                                        <input name="Password" id="Password"  class="form-control" placeholder="Enter Your Password." type="text" required="">
                                                    </div> 

                                                </div>
                                                <div class="form-actions noborder">
                                                    <input class="btn blue margin-top-20" type="submit" id="Btnmybtn"  value="Submit" name="submit">      
                                                    <button type="button" class="btn blue margin-top-20" onClick="checkclose();">Cancel</button>
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
                window.location.href = '<?php echo $web_url; ?>admin/agencymanager.php';
            }
            function chkLoginId(ID)
            {
                var q = $('#LoginID').val();
                var urlp = '<?php echo $web_url; ?>admin/findcompanyemployeeLoginID.php?ID=' + q;
                $.ajax({
                    type: 'POST',
                    url: urlp,
                    success: function (data) {
                        if (data == 0)
                        {
                            $('#errordiv').html('');
                        } else
                        {
                            $('#errordiv').html(data);
                            $('#LoginID').val('');
                        }
                    }
                }).error(function () {
                    alert('An error occured');
                });
            }
            $('#frmparameter').submit(function (e) {
                e.preventDefault();
                var $form = $(this);
                $('#loading').css("display", "block");
                $.ajax({
                    type: 'POST',
                    url: '<?php echo $web_url; ?>admin/querydata.php',
                    data: $('#frmparameter').serialize(),
                    success: function (response) {
                        alert(response);
                        console.log(response);
                        //$("#Btnmybtn").attr('disabled', 'disabled');

                        $('#loading').css("display", "none");
                        $("#Btnmybtn").attr('disabled', 'disabled');
                        alert('Location Employee Added Sucessfully.');
                        window.location.href = '<?php echo $web_url; ?>admin/companyemployee.php';

                        //}
                    }

                });
            });
        </script>
    </body>
</html>