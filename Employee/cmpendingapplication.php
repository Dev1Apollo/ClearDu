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
        <title><?php echo $ProjectName; ?> |Pending Application</title>
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
                                <span>Pending Application</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">
                            <div class="portlet light ">

                                <div class="portlet-title">
                                    <div class="caption font-red-sunglo">
                                        <i class="icon-settings font-red-sunglo"></i>
                                        <span class="caption-subject bold uppercase">List of Pending Application</span>
                                    </div>
                                </div>
                                <div class="portlet-body form">

                                    <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                        <input type="hidden" value="AddEmployeeLedger" name="action" id="action">

                                        <div class="form-body">
                                            <div class="row">


                                                <div class="form-group  col-md-4">
                                                    <label for="form_control_1">Select State</label>
                                                    <?php
                                                    
                                                     $strLocationID = '0';
                                                     $stateid='0';
                                                    $user = mysqli_query($dbconn, "SELECT * FROM `centralmanagerlocation`  where  icentralmanagerid='" . $_SESSION['centralmanagerid'] . "'  ");
                                                    while ($userid = mysqli_fetch_array($user)) {
                                                        $strLocationID = $userid['iLocationId'] . ',' . $strLocationID;
                                                    }
                                                    $strLocationID = rtrim($strLocationID, ", ");
                                                    
                                                    $location=mysqli_query($dbconn, "SELECT * FROM `location`  where  locationId in  (" . $strLocationID . ") ");
                                                     while ($locationid = mysqli_fetch_array($location)) {
                                                        $stateid = $locationid['stateId'] . ',' . $stateid;
                                                    }
                                                    $stateid = rtrim($stateid, ", ");
                                                    
                                                    $querys = "SELECT * FROM `state`  where isDelete='0' and stateId in (".$stateid.")  and  istatus='1' order by  stateName asc";
                                                    $results = mysqli_query($dbconn, $querys);
                                                    echo '<select class="form-control" name="State" id="State" required="" onchange="getlocation();" >';
                                                    echo "<option value='' >Select State Name</option>";
                                                    while ($rows = mysqli_fetch_array($results)) {
                                                        echo "<option value='" . $rows['stateId'] . "'>" . $rows['stateName'] . "</option>";
                                                    }
                                                    echo "</select>";
                                                    ?>
                                                </div>

                                                <div class="col-md-4 ">
                                                    <label for="form_control_1">Location*</label>
                                                     <div id="locationDiv">

                                                    <select class="form-control" name="location" id="location" class="form-control" required="">
                                                        <option value="">--Select location--</option>

                                                    </select>     
                                                     </div>
                                                    <?php
//                                                    $strLocationID = '';
//                                                    $user = mysqli_query($dbconn, "SELECT * FROM `centralmanagerlocation`  where  icentralmanagerid='" . $_SESSION['centralmanagerid'] . "'  ");
//                                                    while ($userid = mysqli_fetch_array($user)) {
//                                                        $strLocationID = $userid['iLocationId'] . ',' . $strLocationID;
//                                                    }
//                                                    $strLocationID = rtrim($strLocationID, ", ");
//                                                    $querys = "SELECT * FROM `location`   where  istatus='1' and isDelete='0'  and locationId in (" . $strLocationID . ") order by  locationId asc";
//                                                    $results = mysqli_query($dbconn, $querys);
//                                                    echo '<select class="form-control" name="location" id="location" required onchange="getagency(); ">';
//                                                    echo "<option value='' >Select location Name</option>";
//                                                    while ($rows = mysqli_fetch_array($results)) {
//                                                        echo "<option value='" . $rows['locationId'] . "'>" . $rows['locationName'] . "</option>";
//                                                    }
//                                                    echo "</select>";
                                                    ?>



                                                    <!--                                                    </div>-->

                                                </div>

<!--                                                <div class="form-group  col-md-4">
                                                    <label for="form_control_1">Select Agency Manager</label>
                                                    <div id="agencyid">

                                                        <select class="form-control" name="agencymanager" id="agencymanager" class="form-control" required="">
                                                            <option value="">--Select Agency manager--</option>

                                                        </select> -->

                                                        <?php
//                                                    $querys = "SELECT * FROM `agencymanagerlocation`  where isDelete='0'  and  istatus='1' order by  stateName asc";
//                                                    $results = mysqli_query($dbconn, $querys);
//                                                    echo '<select class="form-control" name="agencymanager" id="agencymanager" required=""  >';
//                                                    echo "<option value='' >Select Agency Name</option>";
//                                                    while ($rows = mysqli_fetch_array($results)) {
//                                                        echo "<option value='" . $rows['agencymanagerid'] . "'>" . $rows['employeename'] . "</option>";
//                                                    }
//                                                    echo "</select>";
                                                        ?>
<!--                                                    </div>
                                                </div>-->

                                            </div>
                                            <div class="form-actions noborder">
                                                <a href="#" class="btn blue " onclick="PageLoadData(1);">Search</a>
                                                <button type="button" class="btn blue" onClick="checkclose();">Cancel</button>
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


            function getlocation() {
                var State = $('#State').val();
                var urlp = '<?php echo $web_url; ?>Employee/findlocation.php?sid=' + State;
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

                var State = $('#State').val();
                var location = $('#location').val();
//                var agencymanager = $('#agencymanager').val();



                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url; ?>Employee/Ajaxcmpendingapplication.php",
                    data: {action: 'ListUser', Page: Page, location: location,State:State},
                    success: function (msg) {

                        $("#PlaceUsersDataHere").html(msg);
                        $('#loading').css("display", "none");
                    },
                });
            }// end of filter
            PageLoadData(1);

            //function showDetailhold(appID)
            //            {
            //                $('#appID').val(appID);
            //                $("#myModalhold").modal('show');
            //            }
            
            
             function checkb4submit()
            {

                 var State = $('#State').val();
                var location = $('#location').val();

                var strURL = "cmpendingapplicationReportDownload.php?State=" + State + "&location=" + location;
                //alert(strURL);           
                window.open(strURL, '_blank');
            }


        </script>
       
        
        

    </body>
</html>