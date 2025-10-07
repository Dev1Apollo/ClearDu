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
        <title><?php echo $ProjectName; ?> | Month Close </title>
        <?php include_once './include.php'; ?>
           <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
    </head>
    <body class="page-container-bg-solid page-boxed">
        <?php include_once './header.php'; ?>
        <div style="display: none; z-index: 10060;" id="loading">
            <img id="loading-image" src="<?php echo $web_url;?>Employee/images/loader1.gif">
        </div>
        <div class="page-container">        
            <div class="page-content-wrapper">
             
                <div class="page-content">
                    <div class="container">
                        <ul class="page-breadcrumb breadcrumb">
                            <li>
                                <a href="<?php echo $web_url;?>Employee/index.php">Home</a>
                                <i class="fa fa-circle"></i>
                            </li>

                            <li>
                                <span>Month Close</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">




                            
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption font-red-sunglo">
                                            <i class="icon-settings font-red-sunglo"></i>
                                            <span class="caption-subject bold uppercase">List of Month Close</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                            <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                        <input type="hidden" value="AddEmployeeLedger" name="action" id="action">

                                         <div class="form-group col-md-3">
                                            <input type="text" id="FormDate" name="FormDate" class="form-control date-picker" placeholder="Enter The From Date"/>
                                        </div>
                                        <div class="form-body">
                                          
                                            <div class="form-actions noborder">
                                                <a href="#" class="btn blue " onclick="PageLoadData(1);">Search</a>
                                                <button type="button" class="btn blue" onClick="checkclose();">Cancel</button>
<!--                                                <a href="#" onclick="checkb4submit();" class="btn green pull-right margin-bottom-20"><i class="fa fa-file-excel-o"></i></a>-->
                                            </div>

                                        </div>


                                    </form>
                                        
                                        
                                     
                                        
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

            });
       
</script>
         
        
        
        
      
        <script type="text/javascript">
            
            function PageLoadData(Page) {
                
                 var FormDate = $('#FormDate').val();
                var location = $('#location').val();
                var agencymanager = $('#agencymanager').val();
                
                
                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url;?>Employee/Ajaxcm-monthclose.php",
                    data: {action: 'ListUser', Page: Page,FormDate:FormDate},
                    success: function (msg) {

                        $("#PlaceUsersDataHere").html(msg);
                        $('#loading').css("display", "none");
                    },
                });
            }
         //   PageLoadData(1);
            
            
            
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
                        url: "<?php echo $web_url;?>Employee/Ajaxcm-monthclose.php",
                        data: {action: task, ID: id},
                        success: function (msg) {
                         //   alert(msg);
                         console.log(msg);
                            $('#loading').css("display", "none");
                         //   window.location.href = '';

                            return false;
                        },
                    });
                }
                return false;
            }
            
            
            
             function checkb4submit()
            {

                 var State = $('#State').val();
                var location = $('#location').val();

                var strURL = "cmviewwithdrawcaseReportDownload.php?State=" + State + "&location=" + location;
                //alert(strURL);           
                window.open(strURL, '_blank');
            }

        </script>

    </body>
</html>