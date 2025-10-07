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
        <title><?php echo $ProjectName; ?> | FOS Payment Modify Log </title>
        <?php include_once './include.php'; ?>
    </head>
    <body class="page-container-bg-solid page-boxed">
        <?php include_once './header.php'; ?>
        <div style="display: none; z-index: 10060;" id="loading">
            <img id="loading-image" src="<?php echo $web_url;?>admin/images/loader1.gif">
        </div>
        <div class="page-container">        
            <div class="page-content-wrapper">
               
                <div class="page-content">
                    <div class="container">
                        <ul class="page-breadcrumb breadcrumb">
                            <li>
                                <a href="<?php echo $web_url;?>admin/index.php">Home</a>
                                <i class="fa fa-circle"></i>
                            </li>

                            <li>
                                <span>FOS Payment Modify Log</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">
                            <div class="col-md-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption font-red-sunglo">
                                            <i class="icon-settings font-red-sunglo"></i>
                                            <span class="caption-subject bold uppercase">List of FOS Payment Modify Log</span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="applicationid" id="applicationid" value="<?= $_REQUEST['applicationid'] ?>">
                                    <div id="PlaceUsersDataHere" class="portlet-body table-responsive">

                                    </div>
                                </div>
                            </div>

                        </div>
                        
                        <div class="page-content-inner">
                            <div class="col-md-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption font-red-sunglo">
                                            <i class="icon-settings font-red-sunglo"></i>
                                            <span class="caption-subject bold uppercase">List of FOS Log</span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="applicationid" id="applicationid" value="<?= $_REQUEST['applicationid'] ?>">
                                    <div id="PlaceFOSLogDataHere" class="portlet-body table-responsive">

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
                        url: "<?php echo $web_url;?>admin/AjaxFosApplicationPaymentLog.php",
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
            
            function deletefoshistorydata(task, id){
                var errMsg = '';
                if (task == 'Delete') {
                    errMsg = 'Are you sure to delete?';
                }
                if (confirm(errMsg)) {
                    $('#loading').css("display", "block");
                    $.ajax({
                        type: "POST",
                        url: "<?php echo $web_url;?>admin/AjaxFosApplicationStatusLog.php",
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
            
            function setEditdata(id){
                
                $('#loading').css("display", "block");
                var Payment_Collected_Amount = $('#Payment_Collected_Amount_'+id).val();
                var penal = $('#penal_'+id).val();
                var totalamt = $('#totalamt_'+id).val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url;?>admin/AjaxFosApplicationPaymentLog.php",
                    data: {action: "EditHistory", ID: id,Payment_Collected_Amount: Payment_Collected_Amount,penal: penal, totalamt:totalamt},
                    success: function (msg) {

                        $('#loading').css("display", "none");
                        alert("Updated Successfully.!")
                        window.location.href = '';

                        return false;
                    },
                });
                
            }
            
            
            function PageLoadData(Page) {
                var applicationid = $('#applicationid').val();
                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url;?>admin/AjaxFosApplicationPaymentLog.php",
                    data: {action: 'ListUser', Page: Page, applicationid: applicationid},
                    success: function (msg) {
    
                        $("#PlaceUsersDataHere").html(msg);
                        $('#loading').css("display", "none");
                    },
                });
            }// end of filter
            PageLoadData(1);
            
            function PageLoadFosLodData(Page) {
                var Location = $('#Location').val();
                var applicationid = $('#applicationid').val();
                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url;?>admin/AjaxFosApplicationStatusLog.php",
                    data: {action: 'ListUser', Page: Page, Location: Location, applicationid: applicationid},
                    success: function (msg) {

                        $("#PlaceFOSLogDataHere").html(msg);
                        $('#loading').css("display", "none");
                    },
                });
            }// end of filter
            PageLoadFosLodData(1);

        </script>
    </body>
</html>