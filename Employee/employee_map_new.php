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
        <title><?php echo $ProjectName; ?> | Employee Map</title>
        <?php include_once './include.php'; ?>
       
    </head> <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
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
                                <span>Employee Map</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">

                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption font-red-sunglo">
                                        <i class="icon-settings font-red-sunglo"></i>
                                        <span class="caption-subject bold uppercase">List of Employee Map</span>
                                         
                                    </div>
                                    <!--<a href="#" onclick="checkb4submit();" class="btn green pull-right margin-bottom-20"><i class="fa fa-file-excel-o"></i></a>-->
                                </div>
                                <div class="portlet-body form">
                                   
                                    <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                        <input type="hidden" value="AddEmployeeLedger" name="action" id="action">

                                        <div class="form-body">
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <label for="form_control_1">Agency</label>
                                                    <?php
                                                        if ($_SESSION['Type'] == 'Central Manager') {
                                                            $queryCom = "SELECT loginId,employeename FROM `agencymanager` where type='FOS' and istatus=1 and isDelete=0 order by employeename ASC";
                                                            $resultCom = mysqli_query($dbconn, $queryCom);
                                                        } else if ($_SESSION['Type'] == 'Agency Manager') { 
                                                            $queryCom = "SELECT loginId,employeename FROM `agencymanager` where type='FOS' and agencyname='".$_SESSION['agencyname']."' and istatus=1 and isDelete=0 order by employeename ASC";
                                                            $resultCom = mysqli_query($dbconn, $queryCom);
                                                        } else if ($_SESSION['Type'] == 'Agency supervisor') {
                                                            $queryCom = "SELECT loginId,employeename FROM `agencymanager` where type='FOS' and agencyname='".$_SESSION['agencyname']."' and istatus=1 and isDelete=0 order by employeename ASC";
                                                            $resultCom = mysqli_query($dbconn, $queryCom);
                                                        } else if ($_SESSION['Type'] == 'Company Employee') {
                                                            $queryCom = "SELECT loginId,employeename FROM `agencymanager` where type='FOS' and istatus=1 and isDelete=0 order by employeename ASC";
                                                            $resultCom = mysqli_query($dbconn, $queryCom);
                                                        }
                                                        echo '<select class="form-control" name="fosId" id="fosId" required="">';
                                                        echo "<option value=''>Select Employee</option>";
                                                        while ($rowCom = mysqli_fetch_array($resultCom)) {
                                                            echo "<option value='" . $rowCom ['loginId'] . "'>" . $rowCom ['employeename'] . "</option>";
                                                        }
                                                        echo "</select>";
                                                    ?>
                                                </div>
                                                
                                                <div class="form-group col-md-3">
                                                    <label for="form_control_1">Date</label>
                                                    <input type="text" id="strDate" name="strDate" class="form-control date-picker" placeholder="Enter Date" required=""/>
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


        <?php include_once './footer.php'; ?>


        <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>


        <script type="text/javascript">
            $(document).ready(function () {
                $("#strDate").datepicker({
                    format: 'dd-mm-yyyy',
                    autoclose: true,
                    todayHighlight: true,
                    defaultDate: "now",
                    endDate: "now"
                });
            });
        </script>
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
                        url: "<?php echo $web_url; ?>Employee/Ajax_employee_map_new.php",
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

                var fosId = $('#fosId').val();
                var strDate = $('#strDate').val();

                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url; ?>Employee/Ajax_employee_map_new.php",
                    data: {action: 'ListUser', Page: Page, fosId: fosId, strDate: strDate},
                    success: function (msg) {
                        $("#PlaceUsersDataHere").html(msg);
                        $('#loading').css("display", "none");
                    },
                });
            }// end of filter
            //PageLoadData(1);

        </script>
    </body>
</html>