<?php
ob_start();
error_reporting(E_ALL);
include_once '../common.php';
$connect = new connect();
include('IsLogin.php');

$result = mysqli_query($dbconn, "SELECT * FROM `agencymanager` WHERE `agencymanagerid`='" . $_REQUEST['token'] . "'");
//if (mysqli_num_rows($result) > 0) {
$row = mysqli_fetch_array($result);
//}
//} else {
//    echo 'somthig going worng! try again';
//    exit();
//}
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
                                        <input type="hidden" value="<?php echo $_REQUEST['token'] ?>" name="agencyid" id="agencyid">

                                        <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                            <input type="hidden" value="agancyuserlocationstatedistrict" name="action" id="action">
                                            <input type="hidden" value="<?php echo $_REQUEST['token'] ?>" name="agencymanagerid" id="agencymanagerid">
                                            <input type="hidden" name="district" value="0">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Select State</label>
                                                        <?php
                                                        $querys = "SELECT * FROM `state`  where isDelete='0'  and  istatus='1' order by  stateName asc";
                                                        $results = mysqli_query($dbconn, $querys);
                                                        echo '<select class="form-control" name="State" id="State" required="" onchange="getlocation();" >';
                                                        echo "<option value='' >Select State Name</option>";
                                                        while ($rows = mysqli_fetch_array($results)) {

                                                            echo "<option value='" . $rows['stateId'] . "'>" . $rows['stateName'] . "</option>";
                                                        }
                                                        echo "</select>";
                                                        ?>
                                                    </div>

                                                    <!--                                                    <div class="form-group col-md-4">
                                                                                                            <label for="form_control_1">Select District</label>
                                                                                                            <div id="districtDiv">-->

                                                    <?php
//                                                            $querys = "SELECT * FROM `district`  where isDelete='0'  and  istatus='1' order by  districtName asc";
//                                                            $results = mysqli_query($dbconn, $querys);
//                                                            echo '<select class="form-control" name="district" id="district" required="" onchange="getlocation();">';
//                                                            echo "<option value='' >Select district Name</option>";
//                                                            while ($rows = mysqli_fetch_array($results)) {
//
//                                                                echo "<option value='" . $rows['districtId'] . "'>" . $rows['districtName'] . "</option>";
//                                                            }
//                                                            echo "</select>";
                                                    ?>
                                                    <!--                                                        </div>
                                                                                                        </div>-->
                                                    <div class="form-group col-md-12">
                                                        <label for="form_control_1">Select Location</label>
                                                        <input type="hidden" name="Location" value="0"  />  
                                                        <div id="locationDiv">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <input class="btn blue margin-top-20" type="submit" id="Btnmybtn"  value="Submit" name="submit">      
                                                        <button type="button" class="btn blue margin-top-20" onClick="checkclose();">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>


                                        </form>
                                        <div id="PlaceUsersDataHere">

                                        </div>
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
//        function finddistrict()
//        {
//            var q = $('#State').val();
//
//            var urlp = '<?php echo $web_url; ?>admin/finddistrict.php?sId=' + q;
//            $.ajax({
//                type: 'POST',
//                url: urlp,
//                success: function (data) {
//                    $('#districtDiv').html(data);
//                }
//            }).error(function () {
//                alert('An error occured');
//            });
//
//        }
        function getlocation() {
            var agencyid = $('#agencyid').val();
//            var district = $('#district').val();
            var sId = $('#State').val();
            var agencymanagerid = $('#agencymanagerid').val();

            var urlp = '<?php echo $web_url; ?>admin/findlocation.php?sId=' + sId + '&agencyid=' + agencyid + '&agencymanagerid=' + agencymanagerid;
            $.ajax({
                type: 'POST',
                url: urlp,
                success: function (data) {
                    $('#locationDiv').html(data);
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




        $('#frmparameter').submit(function (e) {
            var agencyid = $('#agencyid').val();
            e.preventDefault();
            var $form = $(this);
            $('#loading').css("display", "block");
            $.ajax({
                type: 'POST',
                url: '<?php echo $web_url; ?>admin/querydata.php',
                data: $('#frmparameter').serialize(),
                success: function (response) {
                    // alert(response);
                    //  console.log(response);
                    //$("#Btnmybtn").attr('disabled', 'disabled');
                    if (response == 1)
                    {
                        $('#loading').css("display", "none");
                        $("#Btnmybtn").attr('disabled', 'disabled');
                        alert('Edited Sucessfully.');
                        window.location.href = '<?php echo $web_url; ?>admin/agencyuserlocationassign.php?token=' + agencyid;
                    }
                }

            });
        });


        function PageLoadData(Page) {
            var agencyid = $('#agencyid').val();

            $('#loading').css("display", "block");
            $.ajax({
                type: "POST",
                url: "<?php echo $web_url; ?>admin/Ajaxagencyuserstatedistrictlocation.php",
                data: {action: 'ListUser', Page: Page, agencyid: agencyid},
                success: function (msg) {

                    $("#PlaceUsersDataHere").html(msg);
                    $('#loading').css("display", "none");
                },
            });
        }// end of filter
        PageLoadData(1);


        function deletedata(task, id)
        {

            var errMsg = '';
            if (task == 'Delete') {
                errMsg = 'Are you sure to delete?';
            }
            if (confirm(errMsg)) {
                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url; ?>admin/Ajaxagencyuserstatedistrictlocation.php",
                    data: {action: task, ID: id},
                    success: function (msg) {

                        $('#loading').css("display", "none");
                        window.location.href = '';

                        return false;
                    },
                });
            }
            return false;
        }


    </script>

</body>
</html>
