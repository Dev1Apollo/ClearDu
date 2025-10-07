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
        <title><?php echo $ProjectName; ?> | Central Manager </title>
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
                                <a href="<?php echo $web_url;?>admin/index.php">Home</a>
                                <i class="fa fa-circle"></i>
                            </li>

                            <li>
                                <span>Central Manager</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">




                            <div class="col-md-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption font-red-sunglo">
                                            <i class="icon-settings font-red-sunglo"></i>
                                            <span class="caption-subject bold uppercase">List of Central Manager</span>
                                        </div>
                                        <a href="<?php echo $web_url;?>admin/Addcentralmanager.php" class="btn blue" style="float: right;" title="Add Central Manager">ADD Central Manager</a>
                                    </div>
                                    <div class="portlet-body form">

                                        <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                            <div class="form-group col-md-3">
                                                <?php
                                                $queryCom = "SELECT * FROM `location`  where isDelete='0'  and  istatus='1' order by  locationName asc";
                                                $resultCom = mysqli_query($dbconn,$queryCom);
                                                echo '<select class="form-control" name="Location" id="Location" required="">';
                                                echo "<option value='' >Select Location Name</option>";
                                                while ($rowCom = mysqli_fetch_array($resultCom)) {
                                                    echo "<option value='" . $rowCom ['locationId'] . "'>" . $rowCom ['locationName'] . "</option>";
                                                }
                                                echo "</select>";
                                                ?>
                                            </div>
                                     
                                            <div class="form-group col-md-3">
                                                <input type="text" value="" name="Search_Txt" class="form-control" id="Search_Txt" placeholder="Search Employee Name " required/>
                                            </div>

                                            <div class="form-actions  col-md-3">
                                                <a href="#" class="btn blue pull-left" onclick="PageLoadData(1);">Search</a>
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

        <?php include_once './footer.php'; ?>
        <script type="text/javascript">




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
                        url: "<?php echo $web_url;?>admin/Ajaxcentralmanager.php",
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

                var Location = $('#Location').val();
              
                var Search_Txt = $('#Search_Txt').val();
                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url;?>admin/Ajaxcentralmanager.php",
                    data: {action: 'ListUser', Page: Page, Location: Location, Search_Txt: Search_Txt},
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