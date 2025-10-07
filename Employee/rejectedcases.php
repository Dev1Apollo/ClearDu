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
        <title><?php echo $ProjectName; ?> | Rejected Case</title>
        <?php include_once './include.php'; ?>
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
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
                                <span>Rejected Case</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">

                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption font-red-sunglo">
                                        <i class="icon-settings font-red-sunglo"></i>
                                        <span class="caption-subject bold uppercase">List of Rejected Case</span>
                                    </div>
                                    <a href="#" onclick="checkb4submit();" class="btn green pull-right margin-bottom-20"><i class="fa fa-file-excel-o"></i></a>
                                </div>
                                <div class="portlet-body form">


                                    <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                        <input type="hidden" value="AddEmployeeLedger" name="action" id="action">

                                        <div class="form-body">
                                            <div class="row">
                                                <!--<div class="col-md-4 ">
                                                    <label for="form_control_1">FOS Status</label>
                                                    <select class="form-control" name="completedstatus" id="completedstatus" class="form-control" required="">
                                                        <option value="">Select Status</option>
                                                         <option value="0">Pending</option>
                                                        <option value="1">Payment Collected</option>   
                                                        <option value="2">Refuse to Pay</option>
                                                        <option value="3">PTP Re-Scheduled</option>
                                                        <option value="4">Customer Not Available</option>
                                                        <option value="6">Customer Not Contactable</option>
                                                        <option value="7">Already Paid</option>
                                                        <option value="10">Short Payment</option>
                                                        <option value="11">Penalty collected</option>
                                                    </select>
                                                </div>-->

                                                <div class="form-group col-md-4">
                                                    <label for="form_control_1">Form Date</label>
                                                    <input type="text" id="FormDate" name="FormDate" class="form-control date-picker" placeholder="Enter The From Date"/>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="form_control_1">TO Date</label>
                                                    <input type="text" id="toDate" name="toDate" class="form-control date-picker" placeholder="Enter The TO Date"/>
                                                </div>
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
                                                    echo '<select class="form-control" name="customer_city_id" id="customer_city_id" required  ">';
                                                    echo "<option value='' >Select Customer City Name</option>";
                                                    while ($rows = mysqli_fetch_array($results)) {
                                                        echo "<option value='" . $rows['locationId'] . "'>" . $rows['locationName'] . "</option>";
                                                    }
                                                    echo "</select>";
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 ">
                                                    <label for="form_control_1">Branch*</label>
                                                    <?php
                                                    $strLocationID = '';
                                                    $user = mysqli_query($dbconn, "SELECT * FROM `agencymanagerlocation`  where  iagencymanagerid='" . $_SESSION['agencysupervisorid'] . "'  ");
                                                    while ($userid = mysqli_fetch_array($user)) {
                                                        $strLocationID = $userid['iLocationId'] . ',' . $strLocationID;
                                                    }
                                                    $strLocationID = rtrim($strLocationID, ", ");
                                                   
                                                    $querys = "SELECT Branch FROM `application` where customer_city_id in  (" . $strLocationID . ") and is_assignto_am ='1' and am_accaptance='1' and fosid > 0 and agencyid='" . $_SESSION['agencyname'] . "' GROUP by Branch";
                                                    //$querys = "SELECT * FROM `location`   where  istatus='1' and isDelete='0'  and locationId in (" . $strLocationID . ") order by  locationId asc";
                                                    $results = mysqli_query($dbconn, $querys);
                                                    echo '<select class="form-control" name="location" id="location" required  ">';
                                                    echo "<option value='' >Select Branch Name</option>";
                                                    while ($rows = mysqli_fetch_array($results)) {
                                                        echo "<option value='" . $rows['Branch'] . "'>" . $rows['Branch'] . "</option>";
                                                    }
                                                    echo "</select>";
                                                    ?>

                                                </div>
                                                <div class="col-md-4 ">
                                                    <label for="form_control_1">FOS*</label>
                                                    <?php
                                                    $querys = "select * from agencymanager  where  istatus='1' and isDelete='0' and type='FOS' and agencyname='".$_SESSION['agencyname']."' ";
                                                    $results = mysqli_query($dbconn, $querys);
                                                    echo '<select class="form-control" name="fosid" id="fosid" required=""  >';
                                                    echo "<option value='' >Select FOS</option>";
                                                    while ($rows = mysqli_fetch_array($results)) {
                                                        $agencyname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT agencyname FROM agency where Agencyid='" . $rows['agencyname'] . "'"));
                                                        echo "<option value='" . $rows['agencymanagerid'] . "'>" . $rows['employeename'] . "</option>";
                                                    }
                                                    echo "</select>";
                                                    ?>
                                                </div>

                                            </div>

                                            <div class="form-actions noborder">
                                                <a href="#" class="btn blue margin-top-10" onclick="PageLoadData(1);">Search</a>
                                                <button type="button" class="btn blue margin-top-10" onClick="checkclose();">Cancel</button>

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



        <!--<div id="myModal12" class="modal fade" role="dialog">
            <div class="modal-dialog">                
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">FOS RE-Assign</h4>
                    </div>
                    <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                        <input type="hidden" value="fosreassign" name="action" id="action">
                        <input type="hidden" value="" name="editapplicationId" id="editapplicationId">
                        <div class="modal-body">

                            <div class="form-group  col-md-12">

                                <?php
                                $strLocationID = '0';
                                $user = mysqli_query($dbconn, "SELECT * FROM `agencymanagerlocation`  where  iagencymanagerid='" . $_SESSION['agencysupervisorid'] . "'  ");
                                while ($userid = mysqli_fetch_array($user)) {
                                    $strLocationID = $userid['iLocationId'] . ',' . $strLocationID;
                                }
                                $strLocationID = rtrim($strLocationID, ", ");

                                // $locationid = $_REQUEST['location'];
                                $agencymanagerid = '0';
                                $result = mysqli_query($dbconn, "select * from agencymanagerlocation  where  istatus='1' and isDelete='0' and iLocationId in  (" . $strLocationID . ") order by iLocationId ASC");
                                while ($row = mysqli_fetch_array($result)) {

                                    $agencymanagerid = $row['iagencymanagerid'] . ',' . $agencymanagerid;
                                }

                                $agencymanagerid = rtrim($agencymanagerid, ", ");

                                $agencyid = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager`  where agencymanagerid='" . $_SESSION['agencysupervisorid'] . "' "));
                                ?>

                                <?php
                                $querys = "select * from agencymanager  where  istatus='1' and isDelete='0' and agencymanagerid in (" . $agencymanagerid . ") and agencyname='" . $agencyid['agencyname'] . "' and type='FOS' ";
                                $results = mysqli_query($dbconn, $querys);
                                echo '<select class="form-control" name="fosid" id="fosid" required=""  >';
                                echo "<option value='' >Select FOS</option>";
                                while ($rows = mysqli_fetch_array($results)) {
                                    $agencyname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT agencyname FROM agency where Agencyid='" . $rows['agencyname'] . "'"));
                                    echo "<option value='" . $rows['agencymanagerid'] . "'>" . $rows['employeename'] . "</option>";
                                }
                                echo "</select>";
                                ?>

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn blue">Submit</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>-->

        <?php include_once './footer.php'; ?>
        <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>

        <script type="text/javascript">

            $(document).ready(function () {
                $("#FormDate").datepicker({
                    format: 'dd-mm-yyyy',
                    autoclose: true,
                    todayHighlight: true,
                    defaultDate: "now",
                    endDate: "now"
                });
                $("#toDate").datepicker({
                    format: 'dd-mm-yyyy',
                    autoclose: true,
                    todayHighlight: true,
                    defaultDate: "now",
                    endDate: "now"
                });
            });
        </script>
        <script type="text/javascript">

            $('#frmparameter').submit(function (e) {
                //alert();
                e.preventDefault();
                var $form = $(this);
                $('#loading').css("display", "block");
                $.ajax({
                    type: 'POST',
                    url: '<?php echo $web_url; ?>Employee/querydata.php',
                    data: $('#frmparameter').serialize(),
                    success: function (response) {
                        // alert(response);
                        console.log(response);
                        //$("#Btnmybtn").attr('disabled', 'disabled');
//                        if (response == 2)
//                        {
                        $('#loading').css("display", "none");
                        $("#Btnmybtn").attr('disabled', 'disabled');
                        window.location.href = '<?php echo $web_url; ?>Employee/asfosAssigned.php';
                        //}
                    }

                });

                // return false;
            });


            function PageLoadData(Page) {
                // var completedstatus = $('#completedstatus').val();
                var FormDate = $('#FormDate').val();
                var toDate = $('#toDate').val();
                var location = $('#location').val();
                var fosid = $('#fosid').val();
                var customer_city_id = $('#customer_city_id').val();
                $('#loading').css("display", "block");

                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url; ?>Employee/ajaxRejectedcases.php",
                    data: {action: 'ListUser', Page: Page, FormDate: FormDate, toDate: toDate, location: location, fosid: fosid,customer_city_id:customer_city_id},
                    success: function (msg) {

                        $("#PlaceUsersDataHere").html(msg);
                        $('#loading').css("display", "none");
                    },
                });
            }// end of filter
         //   PageLoadData(1);

            function showmodal(editid)
            {
                $('#editapplicationId').val(editid);
                $("#myModal12").modal('show');
            }
        </script>
        <script type="text/javascript">

            function checkb4submit()
            {
               
                // var completedstatus = $('#completedstatus').val(); "&completedstatus=" + completedstatus +
                var FormDate = $('#FormDate').val();
                var toDate = $('#toDate').val();
                var location = $('#location').val();
                var fosid = $('#fosid').val();
                 var customer_city_id = $('#customer_city_id').val();
                 
                var strURL = "RejectedCasesReportDownload.php?FormDate=" + FormDate + "&location=" + location +  "&toDate=" + toDate + "&fosid=" +fosid + "&customer_city_id=" + customer_city_id;
                //alert(strURL);           
                window.open(strURL, '_blank');
            }

        </script>

    </body>
</html>