<?php
error_reporting(0);
include('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {

    $where = "where 1=1";

//    $strLocationID = '0';
//    $user = mysqli_query($dbconn, "SELECT * FROM `centralmanagerlocation`  where  icentralmanagerid='" . $_SESSION['centralmanagerid'] . "'  ");
//    while ($userid = mysqli_fetch_array($user)) {
//        $strLocationID = $userid['iLocationId'] . ',' . $strLocationID;
//    }
//    $strLocationID = rtrim($strLocationID, ", ");
    
     if ($_REQUEST['agency'] != "") {
        
            $where.=" and  agencyid = '" . $_REQUEST['agency'] . "' ";
        
        
    }
    
    
    if ($_REQUEST['completedstatus'] != "") {
        if($_REQUEST['completedstatus'] == 8){
            $where.=" and is_assignto_fos = '1' and fosid > '0' and   fos_completed_status = '0' ";
        }else if($_REQUEST['completedstatus'] == 9){
            $where.=" and is_assignto_fos = '0' and fosid = '0'  ";
        }
        else{
            $where.=" and  fos_completed_status = '" . $_REQUEST['completedstatus'] . "' ";
        }
        
    }
    $FormDate= $_REQUEST['FormDate'];
     if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate'])) {
       
          $where.="  and STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y') ";
     }
     $toDate= $_REQUEST['toDate'];;
     if ($_REQUEST['toDate'] != NULL && isset($_REQUEST['toDate'])) {

          $where.="  and STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('" . $_REQUEST['toDate'] . "','%d-%m-%Y') ";
     }
//customer_city_id in  (" . $strLocationID . ") and
    $filterstr = "SELECT * FROM `application` " . $where . " and  is_assignto_am ='1' and am_accaptance='1' and application.isDelete=0 ";
    $countstr = "SELECT count(*) as TotalRow FROM  `application` " . $where . " and   is_assignto_am ='1' and am_accaptance='1' and application.isDelete=0 ";

    $resrowcount = mysqli_query($dbconn, $countstr);
    $resrowc = mysqli_fetch_array($resrowcount);
    $totalrecord = $resrowc['TotalRow'];
    $per_page = $cateperpaging;
    $total_pages = ceil($totalrecord / $per_page);
    $page = $_REQUEST['Page'] - 1;
    $startpage = $page * $per_page;
    $show_page = $page + 1;



    $filterstr = $filterstr . " LIMIT $startpage, $per_page";
// echo $filterstr;


    $resultfilter = mysqli_query($dbconn, $filterstr);
    if (mysqli_num_rows($resultfilter) > 0) {
        $i = 1;
        ?>  
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="tableC">

            <thead class="tbg">
                <tr>
                    <th></th>
                    <th class="all">Sr.No</th>
                    <th class="all">Allocation Date</th>
                    <th class="all">Bank Name</th>
                    <th class="all">Supervisor Assigned Date Time</th>
                    <th class="all">Unique Id</th>
                    <th class="all">Account No</th>
                    <th class="all">Agency</th>
                    <th class="none">Product</th>
                    <th class="all">App Id</th>
                    <th class="all">Bkt</th>
                    <th class="all">Customer Name</th>
                    <th class="none">Fathers name</th>
                    <th class="none">Asset Make</th>
                    <th class="all">Branch</th>
                    <th class="all">Customer City</th>
                    <th class="none">State</th>
                    <th class="none">Cycle</th>
                    <!--<th class="none">Due Month</th>-->
                    <th class="none">Allocation Date</th>
                    <th class="none">Allocation CODE </th>
                    <th class="none">Bounce Reason</th>
                    <th class="none">Loan amount</th>
                    <th class="none">Loan booking Date</th>
                    <th class="none">Loan maturity date</th>
                    <!--<th class="none">Frist Emi Date</th>-->
                    <th class="none">Due date</th>
                    <th class="all">Emi/Collectible Amount</th>
                    <th class="all">Total POS Amount</th>
                    <!--<th class="all">Overdue Amount</th>-->
                    <!--<th class="all">Bcc</th>-->
                    <!--<th class="all">Lpp</th>-->
                    <th class="none">Total penlty</th>
                    <!--<th class="none">Principal outstanding</th>-->
                    <!--<th class="none">Vehicle Registration No</th>-->
                    <!--<th class="none">Supplier</th>-->
                    <!--<th class="none">Tenure</th>-->
                    <th class="all">Residence Address</th>
                    <th class="all">Business Address</th>
                    <th class="none">Contact Number</th>
                    <!--<th class="none">Collection Manager </th>-->
                    <!--<th class="none">State Manager</th>-->
                    <th class="none">Ref 1 Name</th>
                    <th class="none">Contact Detail</th>
                    <th class="none">Ref 2 Name</th>
                    <th class="none">Contact Detail ref2</th>
                    <th class="none">Area Name</th>
                    <th class="all">Fos Name</th>
                    <th class="all">Fos Comment</th>
                    <th class="all">Fos Status</th>
                    <th class="all">Fos Other Remarks</th>
                    <th class="none">Pin code</th>
                    <th class="all"> Payment Collected Date</th>
                    <th class="all"> Payment Collected Amount</th>
                    <th class="all">Penal Amount Collected</th>
                    <th class="all">Total Amount Collected</th>
                    <th class="all">Alternate Contact Number</th>
                    <th class="all">Action</th>

                </tr>
            </thead>
            <tbody>
                <?php
                while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                    ?>

                    <tr >
                        <td></td>
                        <td><?php echo $i; ?></td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['strEntryDate']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Bank_Name']; ?> 
                            </div>
                        </td> 
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['assign_as_datetime']; ?> 
                            </div>
                        </td>
                        
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['uniqueId']; ?> 
                            </div>
                        </td> 


                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Account_No']; ?> 
                            </div>
                        </td> 
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['agency']; ?> 
                            </div>
                        </td> 

                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['PRODUCT']; ?> 
                            </div>
                        </td> 
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['App_Id']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Bkt']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Customer_Name']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Fathers_name']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Asset_Make']; ?> 
                            </div>
                        </td>


                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Branch']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['customer_city']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['State']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['cycle']; ?> 
                            </div>
                        </td>
                        <!-- <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Due_Month']; ?> 
                            </div>
                        </td> -->

                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Allocation_Date']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Allocation_CODE']; ?> 
                            </div>
                        </td>

                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Bounce_Reason']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Loan_amount']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Loan_booking_Date']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Loan_maturity_date']; ?> 
                            </div>
                        </td>
                        <!-- <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Frist_Emi_Date']; ?> 
                            </div>
                        </td> -->
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Due_date']; ?> 

                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Emi_amount']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Total_Pos_Amount']; ?> 
                            </div>
                        </td>
                        <!-- <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Installment_Overdue_Amount']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Bcc']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Lpp']; ?> 
                            </div>
                        </td> -->
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Total_penlty']; ?> 
                            </div>
                        </td>
                        <!-- <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Principal_outstanding']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Vehicle_Registration_No']; ?>

                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Supplier']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Tenure']; ?> 
                            </div>
                        </td> -->
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Customer_Address']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Business_Address']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Contact_Number']; ?> 
                            </div>
                        </td>
                        <!-- <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Collection_Manager']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['State_Manager']; ?> 
                            </div>
                        </td> -->

                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Ref_1_Name']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Contact_Detail']; ?> 
                            </div>
                        </td>

                        <td style="width: 10%">
                            <div class="form-group form-md-line-input">
                                <?php echo $rowfilter['Ref_2_Name'] ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Contact_Detail_ref2']; ?> 
                            </div>
                        </td>
                        <td>                             
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Area_Name']; ?> 
                            </div>
                        </td>
                        <?php
                        $fos = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager`  where agencymanagerid ='" . $rowfilter['fosid'] . "'"));
                        ?>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $fos['employeename']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['fos_comment']; ?> 
                            </div>
                        </td>
                        <?php
                        $status = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `fosstatusdrropdown`  where fosstatusdrropdownid ='" . $rowfilter['fos_completed_status'] . "'"));
                        ?>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $status['status']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['strFosOtherRemark']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Pincode']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Payment_Collected_Date']; ?> 
                            </div>
                        </td> 
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Payment_Collected_Amount']; ?> 
                            </div>
                        </td> 
                          <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['penal']; ?> 
                            </div>
                        </td> 
                         <td>
                             
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['totalamt']; ?> 
                            </div>
                        </td> 
                        <td>
                             
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['alternate_contact_number']; ?> 
                            </div>
                        </td> 
                        <td>
                              
                            <button type="button" class="btn blue btn-sm" onclick="return showDetail('<?php echo $rowfilter['applicationid']; ?>');"><i class="fa fa-history"></i></button>
                            <a class="btn blue btn-sm" href="<?php echo $web_url; ?>Employee/ApplicationLog.php?applicationid=<?php echo $rowfilter['applicationid']; ?>" title="ApplictionLog Details"><i class="fa fa-file-text-o"></i></a> 
                        </td>

                        <?php
                        $i++;
                    }
                    ?>

                </tr>
            </tbody>
        </table>
        </div>
        <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.js" type="text/javascript"></script>
        <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>
        <script>
                            $(document).ready(function () {
                                $('#tableC').DataTable({
                                });
                            });
        </script>
        <?php
    } else {
        ?>
        <div class="row">
            <div class="col-lg-12 col-md-12  col-xs-12 col-sm-12 padding-5 bottom-border-verydark">
                <div class="alert alert-info clearfix profile-information padding-all-10 margin-all-0 backgroundDark">
                    <h1 class="font-white text-center"> No Data Found ! </h1>
                </div>   
            </div>
        </div>
        <?php
    }
}
?>
<?php if ($totalrecord > $per_page) { ?>
    <div class="row">
        <div class="col-lg-12 col-md-12  col-xs-12 col-sm-12 padding-5 bottom-border-verydark" style="text-align: center;">
            <div class="form-actions noborder">
                <?php
                echo '<div class="pagination">';

                if ($totalrecord > $per_page) {
                    echo paginate($reload = '', $show_page, $total_pages);
                }
                echo "</div>";
                ?>
            </div>
        </div>
    </div>
<?php } ?>






<script type="text/javascript">

    function phoneNumberAdd(appID) {
        // alert(id);

        var phoneNumber = $('#phoneNumber' + appID).val();
        //alert(sequenceNo);
        $.ajax({
            type: "POST",
            url: "queryDataAdminEmployee.php",
            data: {action: 'SubmitPhoneNumber', appID: appID, phoneNumber: phoneNumber},
            success: function (msg) {
                if (msg == 1) {
                    alert('Submit Sucessfully.');
                } else {
                    alert('Please Fillup The Phone Number.');
                }
                window.location.href = '<?php echo $web_url; ?>Employee/AllocatedApplicationAE.php';


            },
        });

    }


</script>  



<script type="text/javascript">

    function officeNumberAdd(appID) {
        // alert(id);

        var officeNumber = $('#officeNumber' + appID).val();
        //alert(sequenceNo);
        $.ajax({
            type: "POST",
            url: "queryDataAdminEmployee.php",
            data: {action: 'SubmitOfficeNo', appID: appID, officeNumber: officeNumber},
            success: function (msg) {
                if (msg == 1) {
                    alert('Submit Sucessfully.');
                } else {
                    alert('Please Fillup the Office No.');
                }
                window.location.href = '<?php echo $web_url; ?>Employee/AllocatedApplicationAE.php';


            },
        });

    }


</script>  

<!--<button type="button" class="btn blue btn-sm" onclick="return showDetail('<?php echo $rowfilter['appID']; ?>');"><i class="fa fa-history"></i></button>-->
<script type="text/javascript">
    function showDetail(Id)
    {
        $('#myModal').modal('show');
        $.ajax({
            type: "POST",
            url: "<?php echo $web_url; ?>Employee/AjaxFosHistory.php",
            data: {action: 'ListUser', appID: Id},
            success: function (msg) {
                // alert(msg);
                $('#PlaceUsersDataHere123').html(msg);
            }
        });

        return false;
    }
</script> 
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn blue btn-md pull-right" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">FOS History</h4>

            </div>
            <div class="modal-body">

                <span id="PlaceUsersDataHere123"></span>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn blue btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>








