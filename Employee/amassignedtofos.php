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
        <title><?php echo $ProjectName; ?> |Fos Assign Case</title>
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
                                <span>FOS Assigned Cases</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">
                            <div class="portlet light ">

                                <div class="portlet-title">
                                    <div class="caption font-red-sunglo">
                                        <i class="icon-settings font-red-sunglo"></i>
                                        <span class="caption-subject bold uppercase">List of FOS Assigned Case</span>
                                    </div>
                                </div>
                                <div class="portlet-body form">
                                    <a href="#" onclick="checkb4submit();" class="btn green pull-right margin-bottom-20"><i class="fa fa-file-excel-o"></i></a>
                                      <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                        <input type="hidden" value="AddEmployeeLedger" name="action" id="action">

                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-4 ">
                                                    <label for="form_control_1">Status</label>
                                                    <select class="form-control" name="completedstatus" id="completedstatus" class="form-control" required="">
                                                        <option value="">Select Status</option>
                                                         <option value="0">Pending</option>
                                                        <option value="1">Payment Collected</option>   
                                                        <option value="2">Refuse to Pay</option>
                                                        <option value="3">PTP Re-Scheduled</option>
                                                        <option value="4">Customer Not Available</option>
                                                         <option value="6">Customer Not Contactable</option>
                                                         <option value="7">Already Paid</option>
                                                         <option value="10">Short Payment</option>
                                                         <option value="11">Penalty collected</option>
                                                    </select>
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


            function getlocation() {
                var State = $('#State').val();
                var urlp = '<?php echo $web_url; ?>Employee/findlocation.php?sid=' + State;
                $.ajax({
                    type: 'POST',
                    url: urlp,
                    success: function (data) {
                        $('#locationDiv').html(data);
                    }

                }).error(function () {
                    alert('An error occured');
                });
            }

 function getagency() {
                var location = $('#location').val();
                var urlp = '<?php echo $web_url; ?>Employee/findagency.php?locationid=' + location;
                $.ajax({
                    type: 'POST',
                    url: urlp,
                    success: function (data) {
                        $('#agencyid').html(data);
                    }

                }).error(function () {
                    alert('An error occured');
                });
            }
</script>
   <script type="text/javascript">
            function PageLoadData(Page) {
               
//                var State = $('#State').val();
//                var location = $('#location').val();
//                var agencymanager = $('#agencymanager').val();
                
             
                   var completedstatus = $('#completedstatus').val();
              $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url; ?>Employee/Ajaxamassignedtofos.php",
                    data: {action: 'ListUser', Page: Page ,completedstatus : completedstatus},
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

   function checkb4submit()
            {
                   var completedstatus = $('#completedstatus').val();
                var State = $('#State').val();
                var location = $('#location').val();

                var strURL = "amassignedToFosReportDownload.php?State=" + State + "&location=" + location + "&completedstatus=" + completedstatus;
                //alert(strURL);           
                window.open(strURL, '_blank');
            }




        </script>
      
    </body>
</html>