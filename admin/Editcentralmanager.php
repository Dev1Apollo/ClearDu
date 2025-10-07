<?php
ob_start();
error_reporting(E_ALL);
include_once '../common.php';
$connect = new connect();
include('IsLogin.php');

$result = mysqli_query($dbconn, "SELECT * FROM `centralmanager` WHERE `centralmanagerid`='" . $_REQUEST['token'] . "'");
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
        <meta charset="utf-8">

        <link rel="shortcut icon" href="images/favicon.png">
        <title> <?php echo $ProjectName ?> |Edit Central Manager</title>
        <?php include_once './include.php'; ?>       
    </head>

    <body class="page-container-bg-solid page-boxed">
        <?php
        include('header.php');
        ?>
        <div style="display: none; z-index: 10060;" id="loading">
            <img id="loading-image" src="<?php echo $web_url; ?>admin/images/loader1.gif">
        </div>
        <div class="page-container">        

            <div class="page-content">
                <div class="container">                    
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="<?php echo $web_url; ?>admin/index.php">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <a href="<?php echo $web_url; ?>admin/LocationEmployee.php">List Of Central Manager</a>
                            <i class="fa fa-circle"></i>
                        </li>

                        <li>
                            <span> Edit Central Manager</span>

                        </li>
                    </ul>

                    <div class="page-content-inner">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption font-red-sunglo">
                                            <i class="icon-settings font-red-sunglo"></i>
                                            <span class="caption-subject bold uppercase"> Edit Central Manager</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">


                                        <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                            <input type="hidden" value="Editcentralmanager" name="action" id="action">
                                            <input type="hidden" value="<?php echo $row['centralmanagerid'] ?>" name="centralmanagerid" id="centralmanagerid">
                                            <div class="form-body">



                                                <div class="form-group col-md-4">
                                                    <label for="form_control_1">Employee Name</label>
                                                    <input value="<?php echo $row['employeeName']; ?>" name="Employee" id="Employee"  class="form-control" placeholder="Enter Your Employee Name" type="text" required="">
                                                </div>

                                                <!--                                                <div class="form-group col-md-4">
                                                                                                    <label for="form_control_1">Employee Type</label>
                                                                                                    <select name="Type" id="Type"  class="form-control" required="">
                                                                                                        <option value="">Select Employee Type</option>
                                                                                                       
                                                                                                        <option value="Field Executive" <?php
                                                if ($row['type'] == 'Field Executive') {
                                                    echo 'selected';
                                                }
                                                ?>>Field Executive</option>
                                                                                                        <option value="Operator" <?php
                                                if ($row['type'] == 'Operator') {
                                                    echo 'selected';
                                                }
                                                ?>>Operator</option>
                                                                                                    </select>
                                                                                                </div>-->



                                                <div class="form-group col-md-4">
                                                    <label for="form_control_1">Can Add Agency</label>
                                                    <select name="canaddagency" id="canaddagency"  class="form-control">
                                                        <option value="0"<?php if ($row['canaddagency'] == '0') {
                                                    echo 'selected';
                                                } ?>>NO</option>
                                                        <option value="1" <?php if ($row['canaddagency'] == '1') {
                                                    echo 'selected';
                                                } ?>>YES</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="form_control_1">Login ID</label><div id="errordiv"></div>
                                                    <input value="<?php echo $row['loginId']; ?>" name="LoginID" id="LoginID"  class="form-control" placeholder="Enter Your Login ID." type="text" required="" onblur="return chkLoginId();">
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="form_control_1">Email</label>
                                                    <input value="<?php echo $row['email']; ?>" name="Email" id="Email"  class="form-control" placeholder="Enter Your Email Address" type="text">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="form_control_1">Phone No.</label>
                                                    <input value="<?php echo $row['phoneNo']; ?>"  name="Phone" id="Phone"  class="form-control" placeholder="Enter Your Phone No." pattern="[0-9]{11}" type="text">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="form_control_1">Mobile No.</label>
                                                    <input value="<?php echo $row['mobileNo']; ?>" name="Mobile" id="Mobile"  class="form-control" placeholder="Enter Your Mobile No." pattern="[7-9]{1}[0-9]{9}" type="text">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="form_control_1">Location</label><br/>
                                                    <div class="md-checkbox">
                                                        <input type="checkbox"  onclick="javascript:CheckAll();" id="check_listall" class="md-check" value="">
                                                        <label for="check_listall">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span>
                                                        </label>

                                                    </div>
                                                    <?php
                                                    $sql_menu = "SELECT * FROM `location` where isDelete='0'  and  istatus='1' order by locationId asc";
                                                    $result_menu = mysqli_query($dbconn, $sql_menu);
                                                    $i = 1;
                                                    while ($row_menu = mysqli_fetch_array($result_menu)) {
                                                        $Client = "SELECT * FROM `centralmanagerlocation`  where isDelete='0'  and  istatus='1' and iLocationId='" . $row_menu['locationId'] . "' and  icentralmanagerid='" . $row['centralmanagerid'] . "'";
                                                        $resultC = mysqli_query($dbconn, $Client);
                                                        ?>
                                                        <input type='checkbox' name='Location<?php echo $row_menu['locationId']; ?>' value="<?php echo $row_menu['locationId']; ?>"<?php
                                                               if (mysqli_num_rows($resultC) > 0) {
                                                                   echo "checked";
                                                               }
                                                               ?> id='Location<?php echo $row_menu['locationId']; ?>' />&nbsp <?php echo $row_menu['locationName']; ?>
                                                               <?php
                                                               $i++;
                                                               echo "<br />";
                                                           }
                                                           ?>  
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
            window.location.href = '<?php echo $web_url; ?>admin/centralmanager.php';
        }

        function chkLoginId(ID)
        {

            var q = $('#LoginID').val();
            var centralmanagerid = $('#centralmanagerid').val();

            var urlp = '<?php echo $web_url; ?>admin/findeditcentralmanagerLoginID.php?ID=' + q + '&centralmanagerid=' + centralmanagerid;
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
                    // alert(response);
                    console.log(response);
                    //$("#Btnmybtn").attr('disabled', 'disabled');
                    if (response == 2)
                    {
                        $('#loading').css("display", "none");
                        $("#Btnmybtn").attr('disabled', 'disabled');
                        alert(' Employee Edited Sucessfully.');
                        window.location.href = '<?php echo $web_url; ?>admin/centralmanager.php';
                    }
                }

            });
        });

    </script>

</body>
</html>
