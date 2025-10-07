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
        <title><?php echo $ProjectName; ?> | Add Agency </title>
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
                                <a href="<?php echo $web_url; ?>Employee/index.php">Home</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <a href="<?php echo $web_url; ?>Employee/LocationEmployee.php">List Of Agency</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <span>Add Agency</span>
                            </li>
                        </ul>
                        <div class="page-content-inner">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-red-sunglo">
                                                <i class="icon-settings font-red-sunglo"></i>
                                                <span class="caption-subject bold uppercase"> Add Agency</span>
                                            </div>
                                        </div>
                                        <div class="portlet-body form">
                                            <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                                <input type="hidden" value="Addagencymanager" name="action" id="action">
                                                <div class="form-body">

                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Agency</label><br/>
                                                        <?php
                                                        $useragency = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager` WHERE agencymanagerid ='" . $_SESSION['agencymanagerid'] . "' "));
                                                        $querys = "SELECT * FROM `agency`  where isDelete='0'  and Agencyid='".$useragency['agencyname']."' and  istatus='1' order by  agencyname asc";
                                                        $results = mysqli_query($dbconn, $querys);
                                                        echo '<select class="form-control" name="Agency" id="Agency" required=""   >';
                                                        echo "<option value='' >Select Agency Name</option>";
                                                        while ($rows = mysqli_fetch_array($results)) {
                                                            echo "<option value='" . $rows['Agencyid'] . "'>" . $rows['agencyname'] . "</option>";
                                                        }
                                                        echo "</select>";
                                                        ?>
                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Employee  Name</label>
                                                        <input name="employeename" id="employeename"  class="form-control" placeholder="Enter Your  Name" type="text" required="">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Employee Type</label>
                                                        <select name="Type" id="Type"  class="form-control" required="">
                                                            <option value="">Select Employee Type</option>
                                                            <option value="Agency Manager">Agency Manager</option>
                                                            <option value="Agency supervisor">Agency supervisor</option>
                                                            <option value="FOS">FOS</option>
                                                        </select> 
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Address</label><div id="errordiv"></div>
                                                        <textarea  name="address" id="address"  class="form-control"  type="text" required="" ></textarea>
                                                    </div> 
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Login ID</label><div id="errordiv"></div>
                                                        <input name="LoginID" id="LoginID"  class="form-control" placeholder="Enter Your Login ID." type="text" required="" onblur="return chkLoginId();">
                                                    </div> 
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Password</label>
                                                        <input name="Password" id="Password"  class="form-control" placeholder="Enter Your Password." type="text" required="">
                                                    </div> 
<!--                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Branch</label><br/>
                                                      
                                                          <div class="md-checkbox">
                                                            <input type="checkbox"  onclick="javascript:CheckAll();" id="check_listall" class="md-check" value="">
                                                            <label for="check_listall">Check All
                                                                <span></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span>
                                                            </label>

                                                        </div>
                                                          <div id="agencylocationDiv">
                                                        <?php
                                                        $sql_menu = "SELECT * FROM `location` where isDelete='0'  and  istatus='1' order by locationId asc";
                                                        $result_menu = mysqli_query($dbconn, $sql_menu);
                                                        $i = 1;
                                                        while ($row_menu = mysqli_fetch_array($result_menu)) {
                                                            echo "<input type='checkbox' name='Location[]' value='" . $row_menu['locationId'] . "' id='Location[]'/>&nbsp" . $row_menu['locationName'];
                                                            $i++;
                                                            echo "<br />";
                                                        }
                                                        ?>  
                                                    </div>
                                                    </div>-->
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

            function getlocation() {

                var Agency = $('#Agency').val();
                var urlp = '<?php echo $web_url; ?>Employee/findagencylocation.php?Agency=' + Agency;
                $.ajax({
                    type: 'POST',
                    url: urlp,
                    success: function (data) {
                        $('#agencylocationDiv').html(data);
                    }

                }).error(function () {
                    alert('An error occured');
                });
            }
        </script>
         <script>
            function CheckAll()
            {

                if ($('#check_listall').is(":checked"))
                {
                    // alert('cheked');
                    $('input[type=checkbox]').each(function () {
                        $(this).prop('checked', true);
                    });
                } else
                {
                    //alert('cheked fail');
                    $('input[type=checkbox]').each(function () {
                        $(this).prop('checked', false);
                    });
                }
            }
        </script>
        
        <script type="text/javascript">
            function checkclose() {
                window.location.href = '<?php echo $web_url; ?>Employee/agencymanager.php';
            }
            function chkLoginId(ID)
            {
                var q = $('#LoginID').val();
                var urlp = '<?php echo $web_url; ?>Employee/findagencymanagerLoginID.php?ID=' + q;
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
                    url: '<?php echo $web_url; ?>Employee/querydata.php',
                    data: $('#frmparameter').serialize(),
                    success: function (response) {
                        //alert(response);
                        console.log(response);
                        //$("#Btnmybtn").attr('disabled', 'disabled');

                        $('#loading').css("display", "none");
                        $("#Btnmybtn").attr('disabled', 'disabled');
                        alert('Location Employee Added Sucessfully.');
                        window.location.href = '<?php echo $web_url; ?>Employee/amManageuser.php';

                        //}
                    }

                });
            });
        </script>
    </body>
</html>