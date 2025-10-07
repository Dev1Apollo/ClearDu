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
        <title><?php echo $ProjectName; ?> | Pick Up Done</title>
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
                                <span>Pick Up Done</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">





                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption font-red-sunglo">
                                        <i class="icon-settings font-red-sunglo"></i>
                                        <span class="caption-subject bold uppercase">List of Pick Up Done</span>
                                    </div>
                                </div>
                                <div class="portlet-body form">
                                    <a href="#" onclick="checkb4submit();" class="btn green pull-right margin-bottom-20"><i class="fa fa-file-excel-o"></i></a>
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

                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url; ?>Employee/Ajaxampickupdone.php",
                    data: {action: 'ListUser', Page: Page},
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

                var State = $('#State').val();
                var location = $('#location').val();

                var strURL = "ampickupdoneReportDownload.php?State=" + State + "&location=" + location;
                //alert(strURL);           
                window.open(strURL, '_blank');
            }


        </script>
    </body>
</html>