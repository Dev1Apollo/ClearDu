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
        <title><?php echo $ProjectName; ?> | District </title>
        <?php include_once './include.php'; ?>
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
                                <a href="<?php echo $web_url;?>Employee/index.php">Home</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <span>District</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-red-sunglo">
                                                <i class="icon-settings font-red-sunglo"></i>
                                                <span class="caption-subject bold uppercase" id="editDistrict">Add District</span>
                                            </div>
                                        </div>
                                        <div class="portlet-body form">
                                            <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                                <input type="hidden" value="Addcmdistrict" name="action" id="action">
                                                <div class="form-body">


                                                    <div class="form-group">
                                                        <label for="form_control_1">District Name</label>
                                                        <input name="District" id="District"  class="form-control" placeholder="Enter Your District Name" required="" type="text">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="form_control_1">Select State</label>
                                                        <?php
                                                        $querys = "SELECT * FROM `state`  where isDelete='0'  and  istatus='1' order by  stateName asc";
                                                        $results = mysqli_query($dbconn,$querys);
                                                        echo '<select class="form-control" name="State" id="State" required="" >';
                                                        echo "<option value='' >Select State Name</option>";
                                                        while ($rows = mysqli_fetch_array($results)) {
                                                            echo "<option value='" . $rows['stateId'] . "'>" . $rows['stateName'] . "</option>";
                                                        }
                                                        echo "</select>";
                                                        ?>
                                                    </div>

<!--                                                    <div class="form-group">
                                                        <label for="form_control_1">Select City</label>
                                                        <div class="txt_field" id="CityDiv">-->
                                                            <?php
//                                                            $queryc = "SELECT * FROM `city`  where isDelete='0'  and  istatus='1' order by  name asc";
//                                                            $resultc = mysql_query($queryc) or die(mysql_error());
//                                                            echo '<select class="form-control" name="City" id="City" required="">';
//                                                            echo "<option value='' >Select City Name</option>";
//                                                            while ($rowc = mysql_fetch_array($resultc)) {
//                                                                echo "<option value='" . $rowc['cityid'] . "'>" . $rowc['name'] . "</option>";
//                                                            }
//                                                            echo "</select>";
                                                            ?>
<!--                                                        </div>
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

                                <div class="col-md-8">
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-red-sunglo">
                                                <i class="icon-settings font-red-sunglo"></i>
                                                <span class="caption-subject bold uppercase">List of District</span>
                                            </div>

                                        </div>
                                        <div class="portlet-body form">
                                            <div class="col-md-6 pull-right">
                                                <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                                    <div class="form-group col-md-9">
                                                        <input type="text" value="" name="Search_Txt" class="form-control" id="Search_Txt" placeholder="Search By Name " required/>

                                                    </div>
                                                    <div class="form-actions  col-md-3">
                                                        <a href="#" class="btn blue pull-right" onclick="PageLoadData(1);">Search</a>
                                                    </div>
                                                </form>
                                            </div>

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




            function checkclose() {
                window.location.href = '';
            }

//            function getcity()
//            {
//                var q = $('#State').val();
//
//                var urlp = '<?php echo $web_url;?>admin/findCity.php?sId=' + q;
//                $.ajax({
//                    type: 'POST',
//                    url: urlp,
//                    success: function (data) {
//                        $('#CityDiv').html(data);
//                    }
//                }).error(function () {
//                    alert('An error occured');
//                });
//
//            }


            $('#frmparameter').submit(function (e) {

                e.preventDefault();
                var $form = $(this);
                $('#loading').css("display", "block");
                $.ajax({
                    type: 'POST',
                    url: '<?php echo $web_url;?>Employee/querydata.php',
                    data: $('#frmparameter').serialize(),
                    success: function (response) {
                        console.log(response);
                        //$("#Btnmybtn").attr('disabled', 'disabled');
                        if (response == 1)
                        {
                            $('#loading').css("display", "none");
                            $("#Btnmybtn").attr('disabled', 'disabled');
                            alert(' Added Sucessfully.');
                            window.location.href = '';
                        }else if (response == 2)
                        {
                            $('#loading').css("display", "none");
                            $("#Btnmybtn").attr('disabled', 'disabled');
                            alert(' Edited Sucessfully.');
                            window.location.href = '';
                        } else
                        {
                            $('#loading').css("display", "none");
                            $("#Btnmybtn").attr('disabled', 'disabled');
                            alert('Invalid Request.');

                            window.location.href = '';
                        }
                    }

                });
            });


            function setEditdata(id)
            {

                $('#errorDIV').css('display', 'none');
                $('#errorDIV').html('');
                $('#loading').css("display", "block");
                $.ajax({
                    type: 'POST',
                    url: '<?php echo $web_url;?>Employee/querydata.php',
                    data: {action: "GetcmDistrict", ID: id},
                    success: function (response) {
                        document.getElementById("editDistrict").innerHTML = "EDIT District";
                        $('#loading').css("display", "none");
                        var json = JSON.parse(response);
                        $('#District').val(json.districtName);
                        $('#State').val(json.stateId);
                     //   $('#City').val(json.cityId);
                        $('#action').val('EditcmDistrict');
                        $('<input>').attr('type', 'hidden').attr('name', 'districtId').attr('value', json.districtId).attr('id', 'districtId').appendTo('#frmparameter');
                    }
                });
            }







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
                        url: "<?php echo $web_url;?>Employee/Ajaxcmdistrict.php",
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
            function PageLoadData(Page) {
                var Search_Txt = $('#Search_Txt').val();
                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url;?>Employee/Ajaxcmdistrict.php",
                    data: {action: 'ListUser', Page: Page, Search_Txt: Search_Txt},
                    success: function (msg) {

                        $("#PlaceUsersDataHere").html(msg);
                        $('#loading').css("display", "none");
                    },
                });
            }// end of filter
            PageLoadData(1);



        </script>
    </body>
</html>