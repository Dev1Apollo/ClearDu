<?php
ob_start();
error_reporting(0);
include_once '../common.php';
$connect = new connect();
include('IsLogin.php');
?>
<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $ProjectName; ?> | Allocation</title>
        <?php include_once './include.php'; ?>
    </head>
    <body class="page-container-bg-solid page-boxed">
        <?php include_once './header.php'; ?>
        <div style="display: none; z-index: 10060;" id="loading">
            <img id="loading-image" src="<?php echo $web_url; ?>Employee/images/loader1.gif">
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
                                <span>Allocation</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">

                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption font-red-sunglo">
                                        <i class="icon-settings font-red-sunglo"></i>
                                        <span class="caption-subject bold uppercase">List of View Allocation</span>
                                    </div>
                                </div>
                                <div class="portlet-body form">

                                    <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                        <input type="hidden" value="AddEmployeeLedger" name="action" id="action">

                                        <div class="form-body">
                                            <div class="row">

                                                <div class="col-md-4 ">
                                                    <label for="form_control_1">Customer City*</label>
                                                    <?php
                                                    $strLocationID = '';
                                                    $user = mysqli_query($dbconn, "SELECT * FROM `agencymanagerlocation`  where  iagencymanagerid='" . $_SESSION['agencysupervisorid'] . "'  ");
                                                    while ($userid = mysqli_fetch_array($user)) {
                                                        $strLocationID = $userid['iLocationId'] . ',' . $strLocationID;
                                                    }
                                                    $strLocationID = rtrim($strLocationID, ", ");
                                                    $querys = "SELECT * FROM `location`   where  istatus='1' and isDelete='0'  and locationId in (" . $strLocationID . ") order by  locationId asc";
                                                    $results = mysqli_query($dbconn, $querys);
                                                    echo '<select class="form-control" name="location" id="location" required onchange="getagency(); ">';
                                                    echo "<option value='' >Select Branch Name</option>";
                                                    while ($rows = mysqli_fetch_array($results)) {
                                                        echo "<option value='" . $rows['locationId'] . "'>" . $rows['locationName'] . "</option>";
                                                    }
                                                    echo "</select>";
                                                    ?>

                                                </div>

                                            </div>
                                            <div class="form-actions noborder">
                                                <a href="#" class="btn blue margin-top-10" onclick="PageLoadData(1);">Search</a>
                                                <button type="button" class="btn blue margin-top-10" onClick="checkclose();">Cancel</button>
                                                <a href="#" onclick="checkb4submit();" class="btn green pull-right margin-bottom-20"><i class="fa fa-file-excel-o"></i></a>
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


        <?php include_once './footer.php'; ?>


        <script type="text/javascript">

            function PageLoadData(Page) {

                var State = $('#State').val();
                var location = $('#location').val();
                var fos = $('#fos').val();

                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url; ?>Employee/AjaxasAllocation.php",
                    data: {action: 'ListUser', Page: Page, State: State, location: location, fos: fos},
                    success: function (msg) {

                        $("#PlaceUsersDataHere").html(msg);
                        $('#loading').css("display", "none");
                    },
                });
            }// end of filter
            // PageLoadData(1);

        </script>
        <script type="text/javascript">
            function checkb4submit()
            {
                var State = $('#State').val();
                var location = $('#location').val();
                var strURL = "asAllocationReportDownload.php?State=" + State + "&location=" + location;
                //alert(strURL);           
                window.open(strURL, '_blank');
            }
        </script>
    </body>
</html>
