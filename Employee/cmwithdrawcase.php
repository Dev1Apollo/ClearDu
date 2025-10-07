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
        <title><?php echo $ProjectName; ?> |Quick Withdraw Case</title>
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
                                <span>Quick Withdraw Case</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption font-red-sunglo">
                                        <i class="icon-settings font-red-sunglo"></i>
                                        <span class="caption-subject bold uppercase">List of Quick Withdraw Case</span>
                                    </div>
                                </div>
                                <div class="portlet-body form">

                                    <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                        <input type="hidden" value="AddEmployeeLedger" name="action" id="action">
                                        <div class="form-body">
                                            <div class="row">

                                                <div class="col-md-4 ">
                                                    <label for="form_control_1">Branch*</label>
                                                    <?php
//                                                    $strLocationID = '';
//                                                    $user = mysqli_query($dbconn, "SELECT * FROM `centralmanagerlocation`  where  icentralmanagerid='" . $_SESSION['centralmanagerid'] . "'  ");
//                                                    while ($userid = mysqli_fetch_array($user)) {
//                                                        $strLocationID = $userid['iLocationId'] . ',' . $strLocationID;
//                                                    }
//                                                    $strLocationID = rtrim($strLocationID, ", ");
                                                    //and locationId in (" . $strLocationID . ") 
                                                    $querys = "SELECT * FROM `location`   where  istatus='1' and isDelete='0'  order by  locationName asc";
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
                                        </div>
                                        <div class="form-actions noborder">
                                            <a href="#" class="btn blue " onclick="PageLoadData(1);">Search</a>
                                            <button type="button" class="btn blue" onClick="checkclose();">Cancel</button>
                                            <a href="#" onclick="checkb4submit();" class="btn green pull-right margin-bottom-20"><i class="fa fa-file-excel-o"></i></a>
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

    <?php include_once './footer.php'; ?>

    <script type="text/javascript">

        function getagency() {
            var location = $('#location').val();
            var urlp = '<?php echo $web_url; ?>Employee/findagency.php?locationid=' + location;
            $.ajax({
                type: 'POST',
                url: urlp,
                success: function (data) {
                    $('#agencyid').html(data);
                }

            }).error(function () {
                alert('An error occured');
            });
        }
    </script>

    <script type="text/javascript">
        function PageLoadData(Page) {

            var location = $('#location').val();

            $('#loading').css("display", "block");
            $.ajax({
                type: "POST",
                url: "<?php echo $web_url; ?>Employee/Ajaxcmwithdrawcase.php",
                data: {action: 'ListUser', Page: Page,location: location},
                success: function (msg) {
                    $("#PlaceUsersDataHere").html(msg);
                    $('#loading').css("display", "none");
                },
            });
        }
        PageLoadData(1);

        function checkb4submit()
        {
            //var State = $('#State').val();
            var location = $('#location').val();
            var strURL = "cmwithdrawcaseReportDownload.php?location=" + location;
            window.open(strURL, '_blank');
        }

    </script>
</body>
</html>