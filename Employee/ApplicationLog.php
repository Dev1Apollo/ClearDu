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
        <title><?php echo $ProjectName; ?> |Application Log </title>
        <?php include_once './include.php'; ?>
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
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
                                <span>Application Log</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">
 <input type="hidden" name="applicationid" id="applicationid" value="<?php echo $_REQUEST['applicationid'] ?>">
                        <div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption font-red-sunglo">
                                    <i class="icon-settings font-red-sunglo"></i>
                                    <span class="caption-subject bold uppercase">List of Application Log Report</span>
                                </div>
                                  <a class="btn blue pull-right" href="javascript: history.go(-1)">Go Back</a> 
                            </div>
                            <div class="portlet-body form">
                                <?php  //include('ALPSDetailsPage.php');?>
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

    <script>

                                                function checkclose() {
                                                    window.location.href = '<?php echo $web_url; ?>Employee/CancelApplicationAE.php';
                                                }


                                              
                                                function PageLoadData(Page) {
                                                    var applicationid = $('#applicationid').val();
                                                    $('#loading').css("display", "block");
                                                    $.ajax({
                                                        type: "POST",
                                                        url: "<?php echo $web_url; ?>Employee/AjaxApplicationLog.php",
                                                        data: {action: 'ListUser', Page: Page, applicationid: applicationid},
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