<?php
ob_start();
//error_reporting(0);
include('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');

if ($_POST['action'] == 'ListUser') {
    $where = "where 1=1";
    $strLocationID = '0';
    $stateid = '0';    
    $todate=date('d-m-Y');
    $endDate=date('t-m-Y',strtotime("-1 month", strtotime($todate)));
    $startDate = date("01-m-Y", strtotime("-3 month", strtotime($todate)));
    
    
    if($_REQUEST['agency'] != 0){

        $filterstr= "select App_Id,agencyid,applicationid  from application where App_Id IN(select App_Id from colleted_application where STR_TO_DATE(Payment_Collected_Date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('".$startDate."','%d-%m-%Y') and STR_TO_DATE(Payment_Collected_Date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('".$endDate."','%d-%m-%Y') and colleted_application.fos_completed_status=1 and agencyid=application.agencyid group by App_Id) and agencyid IN (".implode(",",$_REQUEST['agency']).")";
    }else{
        $filterstr= "select App_Id,agencyid,applicationid  from application where App_Id IN(select App_Id from colleted_application where STR_TO_DATE(Payment_Collected_Date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('".$startDate."','%d-%m-%Y') and STR_TO_DATE(Payment_Collected_Date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('".$endDate."','%d-%m-%Y') and colleted_application.fos_completed_status=1 and agencyid=application.agencyid group by App_Id)";
    }
    
    $resultfilter = mysqli_query($dbconn, $filterstr);
    if (mysqli_num_rows($resultfilter) > 0) {
        $i = 1;
        ?>  
        <!-- <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="tableC">

            <thead class="tbg">
                <tr>
                    <th></th>
                    <th class="all">Sr.No</th>
                    <th class="all">Allocation Date</th>
                    <th class="all">Supervisor Assigned Date Time</th>
                    <th class="all">Unique Id</th>
                    <th class="all">Account No</th>
                    <th class="none">Agency</th>                    
                    <th class="all">Product</th>
                    <th class="all">App Id</th>
                    <th class="all">Bkt</th>
                    <th class="all">Customer Name</th>
                    <th class="none">Fathers name</th>
                    <th class="none">Asset Make</th>
                    <th class="all">Branch</th>
                    <th class="all">Customer City</th>                    
                    <th class="none">State</th>
                    <th class="none">Due Month</th>
                    <th class="none">Allocation Date</th>
                    <th class="none">Allocation CODE </th>
                    <th class="none">Bounce Reason</th>
                    <th class="none">Loan amount</th>
                    <th class="none">Loan booking Date</th>
                    <th class="none">Loan maturity date</th>
                    <th class="none">Frist Emi Date</th>
                    <th class="none">Due date</th>
                    <th class="all">Emi Amount Collected</th>
                    <th class="none">Overdue Amount</th>
                    <th class="none">Bcc</th>
                    <th class="none">Lpp</th>
                    <th class="none">Total penlty</th>
                    <th class="none">Principal outstanding</th>
                    <th class="none">Vehicle Registration No</th>
                    <th class="none">Supplier</th>
                    <th class="none">Tenure</th>
                    <th class="all">Customer Address</th>
                    <th class="all">Contact Number</th>
                    <th class="none">Collection Manager </th>
                    <th class="none">State Manager</th>
                    <th class="none">Ref 1 Name</th>
                    <th class="none">Contact Detail</th>
                    <th class="none">Ref 2 Name</th>
                    <th class="none">Contact Detail ref2</th>
                    <th class="all">Fos Comment</th>
                    <th class="all">Fos Status</th>
                    <th class="all">ptp datetime</th>
                    <th class="all">Alternet Mobile No</th>
                    <th class="all">Pin Code</th>
                    <th class="all">Fos Name</th>
                    <th class="all"> Payment Collected Date</th>
                    <th class="all"> Payment Collected Amount</th>
                    <th class="all">Penal Amount Collected</th>
                    <th class="all">Total Amount Collected</th>
                     <th class="all">Alternate Contact Number</th>
                    <th class="all">Action</th>

                </tr>
            </thead>
            <tbody>
        https://astutemanagement.co.in/herocollections/Employee/genStableApp.php#        
        -->
                <?php 
                while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                    $pcDateRes=mysqli_query($dbconn,"SELECT *  FROM `colleted_application` WHERE `App_Id` LIKE '".$rowfilter['App_Id']."' and agencyid='".$rowfilter['agencyid']."' and fos_completed_status=1 and STR_TO_DATE(Payment_Collected_Date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('".$startDate."','%d-%m-%Y') and STR_TO_DATE(Payment_Collected_Date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('".$endDate."','%d-%m-%Y')");
                    $pcDateRow=mysqli_fetch_array($pcDateRes);
                    
                    mysqli_query($dbconn,"update application set LastPaymentDate='".$pcDateRow['Payment_Collected_Date']."' where applicationid='".$rowfilter['applicationid']."'");
                    echo mysqli_affected_rows($dbconn);
                    ?>

                    <!-- <tr >
                        <td></td>
                        <td><?php echo $i; ?></td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['strEntryDate']; ?> 
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
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Due_Month']; ?> 
                            </div>
                        </td>

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
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Frist_Emi_Date']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Due_date']; ?> 

                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Emi_amount']; ?> 
                            </div>
                        </td>
                        <td>
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
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Total_penlty']; ?> 
                            </div>
                        </td>
                        <td>
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
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Customer_Address']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Contact_Number']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Collection_Manager']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['State_Manager']; ?> 
                            </div>
                        </td>

                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Ref_1_Name']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Contact_Detail']; ?> 
                            </div>
                        </td>

                        <td style="width: 10%">
                            <div class="form-group form-md-line-input">  <?php echo $rowfilter['Ref_2_Name'] ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Contact_Detail_ref2']; ?> 
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
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['ptp_datetime']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['AlternetMobileNo']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Pincode']; ?> 
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
                        
                        <td style="width: 5%">
                            <?php
                            if ($rowfilter['fos_completed_status'] != '1' && $rowfilter['fos_completed_status'] != '10' && $rowfilter['fos_completed_status'] != '11' && $rowfilter['fos_completed_status'] != '12'  && $rowfilter['fos_completed_status'] != '13' && $rowfilter['fos_completed_status'] != '14' && $rowfilter['fos_completed_status'] != '15') {
                                ?>
                                <a class="btn btn-sm blue" href="#" onClick="return showmodal('<?php echo $rowfilter['applicationid']; ?>');"  title="Assign to"><i class="fa fa-check"></i></a>
                                <?php
                            }
                            ?>
                            <button type="button" class="btn blue btn-sm" onclick="return showDetail('<?php echo $rowfilter['applicationid']; ?>');"><i class="fa fa-history"></i></button>
                            <?php
                            if($rowfilter['is_photo_uploaded'] == '1'){
                            ?>
                             <a class="btn blue btn-sm" href="<?php echo $web_url; ?>Employee/Zipallimage.php?appID=<?php echo $rowfilter['applicationid']; ?>&date=<?php echo $rowfilter['strEntryDate'] ?>" title="Download Images"><i class="fa fa-download"></i></a>
                             <?php
                            }
                             ?>
                        </td>
           

                        <?php
                        $i++;
                    }
                    ?>

                </tr> -->
            </tbody>
        </table>
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










