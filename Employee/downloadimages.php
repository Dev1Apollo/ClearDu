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
                                    <!--<a href="#" onclick="checkb4submit();" class="btn green pull-right margin-bottom-20"><i class="fa fa-file-excel-o"></i></a>-->
                                </div>
                                <div class="portlet-body form">
                                     <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                        <input type="hidden" value="AddEmployeeLedger" name="action" id="action">

                                        <div class="form-body">
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <label for="form_control_1">Account No</label>
                                                    <input type="text" id="appid" name="appid" class="form-control" placeholder="Enter Account No"/>
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
       
        <script type="text/javascript">

            function PageLoadData(Page) {
                 var appid = $('#appid').val();
                $('#loading').css("display", "block");
               
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url; ?>Employee/Ajaxdownloadimages.php",
                    data: {action: 'ListUser', Page: Page, appid:appid},
                    success: function (msg) {
                        $("#PlaceUsersDataHere").html(msg);
                        $('#loading').css("display", "none");
                    },
                });
            }// end of filter
            PageLoadData(1);

           
        </script>
<!--        <script type="text/javascript">

            function checkb4submit()
            {
               
                var completedstatus = $('#completedstatus').val();
                var FormDate = $('#FormDate').val();
                var toDate = $('#toDate').val();
                var location = $('#location').val();
                var fosid = $('#fosid').val();
                 var customer_city_id = $('#customer_city_id').val();
                 
                var strURL = "asfosassigncaseReportDownload.php?FormDate=" + FormDate + "&location=" + location + "&completedstatus=" + completedstatus + "&toDate=" + toDate + "&fosid=" +fosid + "&customer_city_id=" + customer_city_id;
                //alert(strURL);           
                window.open(strURL, '_blank');
            }

        </script>-->

    </body>
</html>