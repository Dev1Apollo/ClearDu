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
        <title><?php echo $ProjectName; ?> | Auto Assign Fos </title>
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
                                <span>Auto Assign Fos</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">





                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption font-red-sunglo">
                                        <i class="icon-settings font-red-sunglo"></i>
                                        <span class="caption-subject bold uppercase">List of Auto Assign Fos</span>
                                    </div>
                                </div>
                                <div class="portlet-body form">





                                    <div class="col-md-6">
                                        <h4 style="color : #f03f2a; font-weight: bold" id="errorlog">

                                        </h4>
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

            function PageLoadData(Page) {

//                 var FormDate = $('#FormDate').val();
//                var location = $('#location').val();
//                var agencymanager = $('#agencymanager').val();


                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url; ?>Employee/Ajaxautoassignfos.php",
                    data: {action: 'ListUser', Page: Page},
                    success: function (msg) {

                        $("#PlaceUsersDataHere").html(msg);
                        $('#loading').css("display", "none");
                    },
                });
            }
            PageLoadData(1);


            function deletedata(task)
            {

                var errMsg = '';
                if (task == 'Delete') {
                    errMsg = 'Are you sure to Assign FOS?';
                }
                if (confirm(errMsg)) {
                    $('#loading').css("display", "block");
                    $.ajax({
                        type: "POST",
                        url: "<?php echo $web_url; ?>Employee/Ajaxautoassignfos.php",
                        data: {action: task},
                        success: function (msg) {

                            $('#loading').css("display", "none");
                            alert(msg);
                            console.log(msg);
                            window.location.href = '';
                            return false;
                        },
                    });
                }
                return false;
            }
        </script>

    </body>
</html>