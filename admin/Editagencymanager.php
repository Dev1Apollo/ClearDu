<?php
ob_start();
error_reporting(E_ALL);
include_once '../common.php';
$connect = new connect();
include('IsLogin.php');

$result = mysqli_query($dbconn, "SELECT * FROM `agencymanager` WHERE `agencymanagerid`='" . $_REQUEST['token'] . "'");
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
        <title> <?php echo $ProjectName ?> |Edit Agency</title>
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
                            <a href="<?php echo $web_url; ?>admin/LocationEmployee.php">List Of Agency</a>
                            <i class="fa fa-circle"></i>
                        </li>

                        <li>
                            <span> Edit Agency</span>

                        </li>
                    </ul>

                    <div class="page-content-inner">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption font-red-sunglo">
                                            <i class="icon-settings font-red-sunglo"></i>
                                            <span class="caption-subject bold uppercase"> Edit Agency</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">


                                        <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                            <input type="hidden" value="EditAgencymanager" name="action" id="action">
                                            <input type="hidden" value="<?php echo $row['agencymanagerid'] ?>" name="agencymanagerid" id="agencymanagerid">
                                            <div class="form-body">
                                                <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label for="form_control_1">Agency</label><br/>
                                                    <?php
                                                    $querys = "SELECT * FROM `agency`  where isDelete='0'  and  istatus='1' order by  agencyname asc";
                                                    $results = mysqli_query($dbconn, $querys);
                                                    echo '<select class="form-control" name="Agency" id="Agency" required=""  >';
                                                    echo "<option value='' >Select Agency Name</option>";
                                                    while ($rows = mysqli_fetch_array($results)) {
                                                        if ($rows['Agencyid'] == $row['agencyname']) {

                                                            echo "<option value='" . $row['agencyname'] . "' selected>" . $rows['agencyname'] . "</option>";
                                                        } else {
                                                            echo "<option value='" . $rows['Agencyid'] . "'>" . $rows['agencyname'] . "</option>";
                                                        }
                                                    }
                                                    echo "</select>";
                                                    ?>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="form_control_1">Employee  Name</label>
                                                    <input name="employeename" id="employeename" value="<?php echo $row['employeename']; ?>" class="form-control" placeholder="Enter Your  Name" type="text" required="">
                                                </div>



                                                <div class="form-group col-md-4">
                                                    <label for="form_control_1">Employee Type</label>
                                                    <select name="Type" id="Type"  class="form-control" required="">
                                                        <option value="">Select Employee Type</option>

                                                        <option value="Agency Manager" <?php
                                                        if ($row['type'] == 'Agency Manager') {
                                                            echo 'selected';
                                                        }
                                                        ?>>Agency Manager</option>
                                                        <option value="Agency supervisor" <?php
                                                        if ($row['type'] == 'Agency supervisor') {
                                                            echo 'selected';
                                                        }
                                                        ?>>Agency supervisor</option>

                                                        <option value="FOS" <?php
                                                        if ($row['type'] == 'FOS') {
                                                            echo 'selected';
                                                        }
                                                        ?>>FOS</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label for="form_control_1">Login ID</label><div id="errordiv"></div>
                                                    <input value="<?php echo $row['loginId']; ?>" name="LoginID" id="LoginID"  class="form-control" placeholder="Enter Your Login ID." type="text" required="" onblur="return chkLoginId();">
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="form_control_1">Address</label>
                                                    <textarea  name="address" id="address"  class="form-control"  type="text"><?php echo $row['address']; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div id="divlatlong" style="display:none;">
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1"> Latitude </label><div id="errordiv"></div>
                                                        <input name="strLatitude" id="strLatitude" value="<?php echo $row['strLatitude']; ?>" class="form-control" placeholder="Enter Your Latitude." type="text" required="">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1"> Longitude </label><div id="errordiv"></div>
                                                        <input name="strLongitude" id="strLongitude" value="<?php echo $row['strLongitude']; ?>" class="form-control" placeholder="Enter Your Longitude." type="text" required="">
                                                    </div>
                                                </div>
                                            </div>
<!--                                                <div class="form-group col-md-4">
                                                    <label for="form_control_1">Location</label><br/>
                                                    <div id="agencylocationDiv">
                                                        <?php
                                                        $Agency=$row['agencyname'];
                                                        $locationid='0';
                                                        $locid = mysqli_query($dbconn, "SELECT * FROM `agncylocation`  where agencyid ='" . $Agency . "'");
                                                        while ($locations = mysqli_fetch_array($locid)) {
                                                            $locationid = $locations['locationid'] . ',' . $locationid;
                                                        }
                                                        $locationid = rtrim($locationid, ", ");


                                                        $sql_menu = "SELECT * FROM `location` where isDelete='0'  and locationId in (".$locationid.") and  istatus='1' order by locationId asc";
                                                        $result_menu = mysqli_query($dbconn, $sql_menu);
                                                        $i = 1;
                                                        while ($row_menu = mysqli_fetch_array($result_menu)) {
                                                            $Client = "SELECT * FROM `agencymanagerlocation`  where isDelete='0'  and  istatus='1'   and iLocationId='" . $row_menu['locationId'] . "' and  iagencymanagerid='" . $row['agencymanagerid'] . "'";
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
        $(document).ready(function(){
           setlatlong();
        });
        
        $("#Type").change(function(){
           setlatlong();
        });
            
        function setlatlong(){
            var Type = $("#Type").val();
            if(Type == "FOS"){
               $("#divlatlong").show();
           } else {
               $("#divlatlong").hide();
           }
        }
        function getlocation() {

            var Agency = $('#Agency').val();
            var urlp = '<?php echo $web_url; ?>admin/findagencylocation.php?Agency=' + Agency;
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
    <script type="text/javascript">

        


        function checkclose() {
            window.location.href = '<?php echo $web_url; ?>admin/agencymanager.php';
        }

        function chkLoginId(ID)
        {

            var q = $('#LoginID').val();
            var agencymanagerid = $('#agencymanagerid').val();

            var urlp = '<?php echo $web_url; ?>admin/findeditagencymanagerLoginID.php?ID=' + q + '&agencymanagerid=' + agencymanagerid;
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
                    //  alert(response);
                    //  console.log(response);
                    //$("#Btnmybtn").attr('disabled', 'disabled');
                    if (response == 2)
                    {
                        $('#loading').css("display", "none");
                        $("#Btnmybtn").attr('disabled', 'disabled');
                        alert('Edited Sucessfully.');
                        window.location.href = '<?php echo $web_url; ?>admin/agencymanager.php';
                    }
                }

            });
        });

    </script>

</body>
</html>
