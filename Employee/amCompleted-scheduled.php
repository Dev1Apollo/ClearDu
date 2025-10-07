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
        <title><?php echo $ProjectName; ?> | Collected</title>
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
                                <span>Collected</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">





                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption font-red-sunglo">
                                        <i class="icon-settings font-red-sunglo"></i>
                                        <span class="caption-subject bold uppercase">List of Collected</span>
                                    </div>
                                    <a href="#" onclick="checkb4submit();" class="btn green pull-right "><i class="fa fa-file-excel-o"></i></a>
                                </div>
                                <div class="portlet-body form">


<!--                                    <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                        <input type="hidden" value="AddEmployeeLedger" name="action" id="action">

                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-4 ">
                                                    <label for="form_control_1">Completed Status</label>
                                                    <select class="form-control" name="completedstatus" id="completedstatus" class="form-control" required="">
                                                        <option value="">Select Completed Status</option>
                                                         <option value="">Select Completed Status</option>
                                                      <option value="Payment Collected">Payment Collected</option>
                                                        <option value="Refuse To Pay">Refuse to Pay</option>
                                                        <option value="Customer Not Available">Coustomer Not Available</option>
                                                        <option value="Non-Servie Area">Non-Servie Area</option>
                                                         <option value="Customer Not Contactable">Customer Not Contactable</option>
                                                         <option value="Already Paid">Already Paid</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-actions noborder">
                                                <a href="#" class="btn blue margin-top-10" onclick="PageLoadData(1);">Search</a>
                                                <button type="button" class="btn blue margin-top-10" onClick="checkclose();">Cancel</button>
                                                <a href="#" onclick="checkb4submit();" class="btn green pull-right margin-bottom-20"><i class="fa fa-file-excel-o"></i></a>
                                            </div>
                                        </div>
                                    </form>-->
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
                
                var completedstatus = $('#completedstatus').val();

                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url; ?>Employee/AjaxamCompleted-scheduled.php",
                    data: {action: 'ListUser', Page: Page,completedstatus:completedstatus},
                    success: function (msg) {

                        $("#PlaceUsersDataHere").html(msg);
                        $('#loading').css("display", "none");
                    },
                });
            }// end of filter
            PageLoadData(1);
        </script>
        <script type="text/javascript">
            function checkb4submit()
            {
                var completedstatus = $('#completedstatus').val();

                var strURL = "amCompleted-scheduledReportDownload.php?completedstatus=" + completedstatus ;
                //alert(strURL);           
                window.open(strURL, '_blank');
            }
        </script>
    </body>
</html>