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
        <title><?php echo $ProjectName; ?> | Assign Case</title>
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
                                <span>Assign Case</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">

                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption font-red-sunglo">
                                        <i class="icon-settings font-red-sunglo"></i>
                                        <span class="caption-subject bold uppercase">List of Assign Case</span>
                                         
                                    </div>
                                    <a href="#" onclick="checkb4submit();" class="btn green pull-right margin-bottom-20"><i class="fa fa-file-excel-o"></i></a>
                                </div>
                                <div class="portlet-body form">
                                   
                                    <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                        <input type="hidden" value="AddEmployeeLedger" name="action" id="action">

                                        <div class="form-body">
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <label for="form_control_1">Agency</label>
                                                    <?php
                                                    $queryCom = "SELECT * FROM  `agency` inner  join `statemanageAgency` on agency.Agencyid=statemanageAgency.iAgencyId  where agency.isDelete='0'  and  agency.istatus='1' and iStateManagerId='".$_SESSION['iStateManagerId']."' order by  Agencyid asc;";
                                                    $resultCom = mysqli_query($dbconn, $queryCom);
                                                    echo '<select class="form-control" name="agency" id="agency" required="">';
                                                    echo "<option value='' >Select Agency Name</option>";
                                                    while ($rowCom = mysqli_fetch_array($resultCom)) {
                                                        echo "<option value='" . $rowCom ['Agencyid'] . "'>" . $rowCom ['agencyname'] . "</option>";
                                                    }
                                                    echo "</select>";
                                                    ?>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="form_control_1">Status</label>
                                                    <select class="form-control" name="completedstatus" id="completedstatus" class="form-control" required="">
                                                        <option value="">Select Status</option>
                                                        <option value="1">Payment Collected</option>   
                                                        <option value="2">Refuse to Pay</option>
                                                        <option value="3">PTP Re-Scheduled</option>
                                                        <option value="4">Customer Not Available</option>
                                                        <option value="6">Customer Not Contactable</option>
                                                        <option value="7">Already Paid</option>
                                                        <option value="8">Pending with FOS</option>
                                                        <option value="9">Pending with supervisor</option>
                                                        <option value="10">Short Payment</option>
                                                        <option value="11">Penalty collected</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <label for="form_control_1">Form Date</label>
                                                    <input type="text" id="FormDate" name="FormDate" class="form-control date-picker" placeholder="Enter The From Date"/>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="form_control_1">TO Date</label>
                                                    <input type="text" id="toDate" name="toDate" class="form-control date-picker" placeholder="Enter The TO Date"/>
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

            function PageLoadData(Page) {
                var completedstatus = $('#completedstatus').val();
                 var agency = $('#agency').val();
                var FormDate = $('#FormDate').val();
                var toDate = $('#toDate').val();
                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url; ?>Employee/Ajaxsmassigncase.php",
                    data: {action: 'ListUser', Page: Page, completedstatus: completedstatus, FormDate: FormDate, toDate: toDate,agency:agency},
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
        </script>
        <script type="text/javascript">

            function checkb4submit()
            {

                var FormDate = $('#FormDate').val();
                var toDate = $('#toDate').val();
                 var agency = $('#agency').val();
                var completedstatus = $('#completedstatus').val();
                var strURL = "smassigncaseReportDownload.php?FormDate=" + FormDate + "&toDate=" + toDate + "&completedstatus=" + completedstatus + "&agency=" + agency;
                window.open(strURL, '_blank');
            }

        </script>
    </body>
</html>