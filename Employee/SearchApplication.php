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
        <title><?php echo $ProjectName; ?> |Search Application</title>
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
                                <span>Search Application</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">

                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption font-red-sunglo">
                                        <i class="icon-settings font-red-sunglo"></i>
                                        <span class="caption-subject bold uppercase">List of Search Application</span>
                                    </div>
                                </div>
                                <div class="portlet-body form">
<!--                                    <input type="hidden" name="check_list_header" id="check_list_header" value="<?php echo $_REQUEST['check_list']; ?>">-->
                                    <input type="hidden" name="Search_header" id="Search_header" value="<?php echo $_REQUEST['q']; ?>">


                                    <div id="PlaceUsersDataHere">
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div id="myModal123" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
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

                                if ($_SESSION['Type'] == "Agency supervisor") {
                                    $user = mysqli_query($dbconn, "SELECT * FROM `agencymanagerlocation`  where  iagencymanagerid='" . $_SESSION['agencysupervisorid'] . "'   ");
                                }
                                if ($_SESSION['Type'] == "Central Manager") {
                                    $user = mysqli_query($dbconn, "SELECT * FROM `agencymanagerlocation`  where  iagencymanagerid='" . $_SESSION['centralmanagerid'] . "'  ");
                                }
                                if ($_SESSION['Type'] == "Agency Manager") {
                                    $user = mysqli_query($dbconn, "SELECT * FROM `agencymanagerlocation`  where  iagencymanagerid='" . $_SESSION['agencymanagerid'] . "'  ");
                                }
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
                                if ($_SESSION['Type'] == "Agency supervisor") {
                                    $agencyid = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager`  where agencymanagerid='" . $_SESSION['agencysupervisorid'] . "' "));
                                } else if ($_SESSION['Type'] == "Central Manager") {
                                    $agencyid = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager`  where agencymanagerid='" . $_SESSION['centralmanagerid'] . "' "));
                                } else if ($_SESSION['Type'] == "Agency Manager") {
                                    $agencyid = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager`  where agencymanagerid='" . $_SESSION['agencymanagerid'] . "' "));
                                }
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
        </div>

        <div id="tccommentModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">TC Comment</h4>
                    </div>
                    <form  role="form"  method="POST"  action="" name="tcfrmparameter"  id="tcfrmparameter" enctype="multipart/form-data">
                        <input type="hidden" value="tccomment" name="action" id="action">
                        <input type="hidden" value="" name="applicationid" id="applicationid">
                        <div class="modal-body">
                            <div class="form-group  col-md-6">
                                <label for="form_control_1">PTP Date</label>                             
                                <input type="text" name="ptpredate" id="ptpredate" class="form-control date-picker">
                            </div>
                            <div class="form-group  col-md-6">
                                <label for="form_control_1">Comment</label>                             
                                <textarea type="text" name="comment" id="comment" class="form-control"></textarea>
                            </div>
                            <div class="form-group  col-md-3">
                                Check PTP
                                <input type="checkbox" name="chk_PTP" value="1" id="chk_PTP" class="form-control" />
<!--                                <label for="form_control_1">check PTP</label>                             -->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn blue">Submit</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
        <?php include_once './footer.php'; ?>
        <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>

        <script type="text/javascript">

            $(document).ready(function () {
                $("#ptpredate").datepicker({
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


            $('#tcfrmparameter').submit(function (e) {
                //alert();

                e.preventDefault();
                var $form = $(this);
          //      $('#loading').css("display", "block");
                $.ajax({
                    type: 'POST',
                    url: '<?php echo $web_url; ?>Employee/querydata.php',
                    data: $('#tcfrmparameter').serialize(),
                    success: function (response) {
                        // alert(response);
                        console.log(response);
                        //$("#Btnmybtn").attr('disabled', 'disabled');
//                        if (response == 2)
//                        {
             //           $('#loading').css("display", "none");
              //          $("#Btnmybtn").attr('disabled', 'disabled');

             //           window.location.href = '<?php echo $web_url; ?>Employee/asfosAssigned.php';
                        //}
                    }

                });

                // return false;
            });





            function PageLoadData(Page) {

                //var check_list = $('#check_list_header').val();
                var searchName = $('#Search_header').val();


                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url; ?>Employee/AjaxSearchApplication.php",
                    data: {action: 'ListUser', Page: Page, searchName: searchName},
                    success: function (msg) {

                        $("#PlaceUsersDataHere").html(msg);
                        $('#loading').css("display", "none");
                    },
                });
            }// end of filter
            PageLoadData(1);

            function showmodal(editid)
            {
                $('#editapplicationId').val(editid);
                $("#myModal123").modal('show');
            }
            function showtccomment(applicationid) {
                $('#applicationid').val(applicationid);
                $("#tccommentModal").modal('show');
            }
        </script>
    </body>
</html>