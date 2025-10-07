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
        <title><?php echo $ProjectName; ?> | Update Fos Status </title>
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
                                <span>Update Fos Status</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">

                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption font-red-sunglo">
                                        <i class="icon-settings font-red-sunglo"></i>
                                        <span class="caption-subject bold uppercase">List of Update Fos Status</span>
                                         
                                    </div>
                                    <!--<a href="#" onclick="checkb4submit();" class="btn green pull-right margin-bottom-20"><i class="fa fa-file-excel-o"></i></a>-->
                                </div>
                                <div class="portlet-body form">
                                   
                                    <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                        <input type="hidden" value="AddEmployeeLedger" name="action" id="action">

                                        <div class="form-body">
                                            <div class="row">
                                                <div class="form-group col-md-3">
                                                    <label for="form_control_1"> Account No </label>
                                                    <input type="text" id="Account_No" name="Account_No" class="form-control date-picker" placeholder="Enter The Account No"/>
                                                </div>
                                                <!--<div class="form-group col-md-3">-->
                                                <!--    <label for="form_control_1">Form Date</label>-->
                                                <!--    <input type="text" id="FormDate" name="FormDate" class="form-control date-picker" placeholder="Enter The From Date"/>-->
                                                <!--</div>-->
                                                <!--<div class="form-group col-md-3">-->
                                                <!--    <label for="form_control_1">TO Date</label>-->
                                                <!--    <input type="text" id="toDate" name="toDate" class="form-control date-picker" placeholder="Enter The TO Date"/>-->
                                                <!--</div>-->
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
    
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
        
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn blue btn-md pull-right" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Change Status</h4>
        
                    </div>
                    <form  role="form"  method="POST"  action="" name="frmchangefosstatus"  id="frmchangefosstatus" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="hidden" value="changefosstatus" name="action" id="action">
                            <input type="hidden" id="iApplicationid" name="iApplicationid" class="form-control"/>
                            
                            <input type="hidden" id="Bank_Name" name="Bank_Name" class="form-control"/>
                            <input type="hidden" id="Emi_amount" name="Emi_amount" class="form-control"/>
                            <input type="hidden" id="Total_Pos_Amount" name="Total_Pos_Amount" class="form-control"/>
                            <input type="hidden" id="PRODUCT" name="PRODUCT" class="form-control"/>
                            
                            <div class="form-body">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="form_control_1"> Comment </label>
                                        <input type="text" id="strComment" name="strComment" class="form-control" placeholder="Enter The Comment"/>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="form_control_1"> Alternet Mobile No. </label>
                                        <input type="text" id="AlternetMobileNo" name="AlternetMobileNo" maxlength="10" minlength="10" pattern="[6-9]{1}[0-9]{9}" class="form-control" placeholder="Enter The Alternet Mobile"/>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="form_control_1"> Status </label>
                                        <select type="text" id="fos_completed" name="fos_completed" class="form-control" onchange="changestatus();">
                                            <option value="">Select Status</option>
                                            <?php
                                                $fosStatus = mysqli_query($dbconn,"SELECT * FROM `fosstatusdrropdown` where isDelete =0 and istatus=1 and fosstatusdrropdownid not in (17,5)");
                                                while($rowStatus = mysqli_fetch_assoc($fosStatus)){
                                            ?>    
                                                <option value="<?= $rowStatus['fosstatusdrropdownid']; ?>"><?= $rowStatus['status']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div id="divPayment" style="display:none;">
                                        <div class="form-group col-md-4">
                                            <label for="form_control_1"> EMI Amount Collected </label>
                                            <input type="text" id="Payment_Collected_Amount" onkeyup="totalAmountCalculation();" onkeypress="return onlyNumber(event);" pattern="[0-9]{10}" name="Payment_Collected_Amount" class="form-control" placeholder="Enter The EMI Amount Collected"/>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="form_control_1"> Penal Amount Collected  </label>
                                            <input type="text" id="penal" name="penal" onkeyup="totalAmountCalculation();" onkeypress="return onlyNumber(event);" pattern="[0-9]{10}" class="form-control" placeholder="Enter The Penal Amount Collected"/>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="form_control_1"> Total Amount Collected  </label>
                                            <input type="text" id="totalamt" name="totalamt" class="form-control" onkeypress="return onlyNumber(event);" pattern="[0-9]{10}" placeholder="Enter The Total Amount Collected"/>
                                        </div>
                                    </div>
                                    <div id="divPTPDate" style="display:none;">
                                        <div class="form-group col-md-4">
                                            <label for="form_control_1">PTP Date</label>
                                            <input type="text" id="ptp_datetime" name="ptp_datetime" class="form-control date-picker" placeholder="Enter The PTP Date"/>
                                        </div>
                                    </div>
                                </div>
                                <!--<div class="form-actions noborder">-->
                                <!--    <input type="button" class="btn blue margin-top-10" onClick="submitstatus();">Submit</button>-->
            
                                <!--</div>-->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" onclick="submitchangefosstatus();" class="btn blue btn-sm">Submit</button>
                        </div>  
                    </form>
                </div>
        
            </div>
        </div>
    
        <?php include_once './footer.php'; ?>
        
        <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
        <script type="text/javascript">
    
            $(document).ready(function () {
                $("#ptp_datetime").datepicker({
                    format: 'dd-mm-yyyy',
                    autoclose: true,
                    todayHighlight: true,
                    defaultDate: "now",
                    startDate: "now"
                });
                // $("#FormDate").datepicker({
                //     format: 'dd-mm-yyyy',
                //     autoclose: true,
                //     todayHighlight: true,
                //     defaultDate: "now",
                //     endDate: "now"
                // });
                // $("#toDate").datepicker({
                //     format: 'dd-mm-yyyy',
                //     autoclose: true,
                //     todayHighlight: true,
                //     defaultDate: "now",
                //     endDate: "now"
                // });
                
            });
    
    
        </script>
        <script type="text/javascript">
            function showDetail(Id,Bank_Name,Emi_amount,Total_Pos_Amount,PRODUCT)
            {
                $('#myModal').modal('show');
                $("#iApplicationid").val(Id);
                $("#Bank_Name").val(Bank_Name);
                $("#Emi_amount").val(Emi_amount);
                $("#Total_Pos_Amount").val(Total_Pos_Amount);
                $("#PRODUCT").val(PRODUCT);
                return false;
            }
            
            function changestatus(){
                var fos_completed = $("#fos_completed").val();
                if(fos_completed == 1 || fos_completed == 18){
                    $("#divPayment").show();
                    $("#divPTPDate").hide();
                } else if(fos_completed == 3){
                    $("#divPayment").hide();
                    $("#divPTPDate").show();
                } else {
                    $("#divPayment").hide();
                    $("#divPTPDate").hide();
                }
            }
            
            function totalAmountCalculation(){
                var Payment_Collected_Amount = $("#Payment_Collected_Amount").val();
                var penal = $("#penal").val();
                if(Payment_Collected_Amount == ""){
                    Payment_Collected_Amount = 0;
                } 
                if(penal == ""){
                    penal = 0;
                } 
                var totalAmt = (Payment_Collected_Amount  * 1) + (penal * 1);
                $("#totalamt").val(totalAmt);
            }
            function onlyNumber(evt) {
                var charCode = (evt.which) ? evt.which : event.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57)){
                        return false;
                    }
                return true;
            }
            
            function submitchangefosstatus(){
                $('#loading').css("display", "block");
                var fos_completed = $("#fos_completed").val();
                if(fos_completed == 1){
                    var Payment_Collected_Amount = $("#Payment_Collected_Amount").val();
                    var totalamt = $("#totalamt").val();
                    var Bank_Name = $("#Bank_Name").val();
                    var Emi_amount = $("#Emi_amount").val();
                    if(Bank_Name == 'RBL'){
                        if(Payment_Collected_Amount < Emi_amount){
                            alert('Amount Enter is less then EMI Amount');
                            return false;
                        }
                    }
                    var Total_Pos_Amount = $("#Total_Pos_Amount").val();
                    var PRODUCT = $("#PRODUCT").val();
                    if(PRODUCT == 'Recovery'){
                        if(totalamt < Total_Pos_Amount){
                            alert('Amount Enter is less then POS Amount');
                            return false;
                        }
                    }
                }
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url; ?>Employee/Ajaxupdatefosstatusbycm.php",
                    data: $('#frmchangefosstatus').serialize(),
                    success: function (msg) {
                        alert("Status Updated Successfully.");
                        window.location.href="";
                    },
                });
            }
        </script> 

        <script type="text/javascript">

            function PageLoadData(Page) {
                // var completedstatus = $('#completedstatus').val();
                // var agency = $('#agency').val();
                // var FormDate = $('#FormDate').val();
                // var toDate = $('#toDate').val();
                var Account_No = $("#Account_No").val();
                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url; ?>Employee/Ajaxupdatefosstatusbycm.php",
                    data: {action: 'ListUser', Page: Page, Account_No: Account_No},
                    success: function (msg) {

                        $("#PlaceUsersDataHere").html(msg);
                        $('#loading').css("display", "none");
                    },
                });
            }// end of filter
            //PageLoadData(1);

            // function showDetailhold(appID)
            // {
            //     $('#appID').val(appID);
            //     $("#myModalhold").modal('show');
            // }
        </script>
        <script type="text/javascript">

            // function checkb4submit()
            // {

            //     var FormDate = $('#FormDate').val();
            //     var toDate = $('#toDate').val();
            //      var agency = $('#agency').val();
            //     var completedstatus = $('#completedstatus').val();
            //     var strURL = "cmassigncaseReportDownload.php?FormDate=" + FormDate + "&toDate=" + toDate + "&completedstatus=" + completedstatus + "&agency=" + agency;
            //     //alert(strURL);           
            //     window.open(strURL, '_blank');
            // }

        </script>
    </body>
</html>