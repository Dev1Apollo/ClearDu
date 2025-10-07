<?php
ob_start();
error_reporting(E_ALL);
include('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {

    $where = "where 1=1";

    $FormDate= $_REQUEST['FormDate'];;
     if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate'])) {

         // and month(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Month(STR_TO_DATE('" . $currentmonth . "','%d-%m-%Y')) and Year(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Year(STR_TO_DATE('" . $currentmonth . "','%d-%m-%Y'))  
          $where.="  and month(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Month(STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y')) and Year(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Year(STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y'))  ";
   

    $filterstr = "SELECT * FROM `application` " . $where . "  ";
    $countstr = "SELECT count(*) as TotalRow FROM  `application` " . $where . " ";
 
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

        <?php 
        echo "<h1> Total Record is :". $totalrecord . "</h1>";
        ?>
        <a  class="btn blue" onClick="javascript: return deletedata('Delete', '<?php echo $FormDate; ?>');"   title="Move To">Move</a>
        
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
}
    
    if ($_REQUEST['action'] == 'Delete') {     
        //`LAN_NUMBER`,`Due_Month`, `Frist_Emi_Date`,`Installment_Overdue_Amount`,`Bcc`, `Lpp`,`Principal_outstanding`, `Vehicle_Registration_No`, `Supplier`, `Tenure`,`Collection_Manager`, `State_Manager`,`location`,`assign_am_datetime`, `agencymanagerid`,`is_assignto_as`,  `agencysupervisorid`, `LastPaymentDate`,`updatedate`,`annexureid`,`visit_address`,`excel_return_date`,
        // echo "INSERT INTO `colleted_application`(`applicationid`, `uniqueId`, `agency`, `excelfilename`, `excelnameid`, `locationid`, `stateid`, `PRODUCT`, `SrNo`, `Account_No`, `App_Id`, `Bkt`, `Customer_Name`, `Fathers_name`, `Asset_Make`, `Branch`, `State`, `Allocation_Date`, `Allocation_CODE`, `Bounce_Reason`, `Loan_amount`, `Loan_booking_Date`, `Loan_maturity_date`, `Due_date`, `Emi_amount`,  `Total_penlty`, `Customer_Address`, `Contact_Number`, `Ref_1_Name`, `Contact_Detail`, `Ref_2_Name`, `Contact_Detail_ref2`, `is_assignto_am`, `agencyid`, `am_accaptance`,  `assign_as_datetime`,  `is_assignto_fos`, `assign_fos_datetime`, `fosid`, `fos_completed`, `fos_completed_status`, `fos_comment`, `AlternetMobileNo`, `ptp_datetime`, `fos_submit_datetime`, `Payment_Collected_Date`, `Payment_Collected_Amount`, `penal`, `totalamt`, `Pincode`, `reason`, `runsheet`, `runsheetsequnce`, `PTP_Date`, `PTP_Amount`, `Time_Slot`,`customer_city`,`customer_city_id`,`withdraw_date`,`return_date`,`withdraw_reason`,`alternate_contact_number`,`is_photo_uploaded`,`error_upload`,`strEntryDate`, `strIP`,`Bank_Name`,`cycle`,`Total_Pos_Amount`,`Business_Address`,`Area_Name`) select `applicationid`, `uniqueId`, `agency`, `excelfilename`, `excelnameid`, `locationid`, `stateid`, `PRODUCT`, `SrNo`, `Account_No`, `App_Id`, `Bkt`, `Customer_Name`, `Fathers_name`, `Asset_Make`, `Branch`, `State`, `Allocation_Date`, `Allocation_CODE`, `Bounce_Reason`, `Loan_amount`, `Loan_booking_Date`, `Loan_maturity_date`, `Due_date`, `Emi_amount`,  `Total_penlty`, `Customer_Address`, `Contact_Number`, `Ref_1_Name`, `Contact_Detail`, `Ref_2_Name`, `Contact_Detail_ref2`, `is_assignto_am`, `agencyid`, `am_accaptance`,  `assign_as_datetime`,  `is_assignto_fos`, `assign_fos_datetime`, `fosid`, `fos_completed`, `fos_completed_status`, `fos_comment`, `AlternetMobileNo`, `ptp_datetime`, `fos_submit_datetime`, `Payment_Collected_Date`, `Payment_Collected_Amount`, `penal`, `totalamt`, `Pincode`, `reason`, `runsheet`, `runsheetsequnce`, `PTP_Date`, `PTP_Amount`, `Time_Slot`,`customer_city`,`customer_city_id`,`withdraw_date`,`return_date`,`withdraw_reason`,`alternate_contact_number`,`is_photo_uploaded`,`error_upload`,`strEntryDate`, `strIP`,`Bank_Name`,`cycle`,`Total_Pos_Amount`,`Business_Address`,`Area_Name` FROM application where Month(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Month(STR_TO_DATE('" . $_REQUEST['ID'] . "','%d-%m-%Y')) and Year(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Year(STR_TO_DATE('" . $_REQUEST['ID'] . "','%d-%m-%Y')) ";
        $query = mysqli_query($dbconn,"INSERT INTO `colleted_application`(`applicationid`, `uniqueId`, `agency`, `excelfilename`, `excelnameid`, `locationid`, `stateid`, `PRODUCT`, `Account_No`, `App_Id`, `Bkt`, `Customer_Name`, `Fathers_name`, `Asset_Make`, `Branch`, `State`, `Allocation_Date`, `Allocation_CODE`, `Bounce_Reason`, `Loan_amount`, `Loan_booking_Date`, `Loan_maturity_date`, `Due_date`, `Emi_amount`,  `Total_penlty`, `Customer_Address`, `Contact_Number`, `Ref_1_Name`, `Contact_Detail`, `Ref_2_Name`, `Contact_Detail_ref2`, `is_assignto_am`, `agencyid`, `am_accaptance`,  `assign_as_datetime`,  `is_assignto_fos`, `assign_fos_datetime`, `fosid`, `fos_completed`, `fos_completed_status`, `fos_comment`, `AlternetMobileNo`, `ptp_datetime`, `fos_submit_datetime`, `Payment_Collected_Date`, `Payment_Collected_Amount`, `penal`, `totalamt`, `Pincode`, `reason`, `runsheet`, `runsheetsequnce`, `PTP_Date`, `PTP_Amount`, `Time_Slot`,`customer_city`,`customer_city_id`,`withdraw_date`,`return_date`,`withdraw_reason`,`alternate_contact_number`,`is_photo_uploaded`,`error_upload`,`strEntryDate`, `strIP`,`Bank_Name`,`cycle`,`Total_Pos_Amount`,`Business_Address`,`Area_Name`) select `applicationid`, `uniqueId`, `agency`, `excelfilename`, `excelnameid`, `locationid`, `stateid`, `PRODUCT`, `Account_No`, `App_Id`, `Bkt`, `Customer_Name`, `Fathers_name`, `Asset_Make`, `Branch`, `State`, `Allocation_Date`, `Allocation_CODE`, `Bounce_Reason`, `Loan_amount`, `Loan_booking_Date`, `Loan_maturity_date`, `Due_date`, `Emi_amount`,  `Total_penlty`, `Customer_Address`, `Contact_Number`, `Ref_1_Name`, `Contact_Detail`, `Ref_2_Name`, `Contact_Detail_ref2`, `is_assignto_am`, `agencyid`, `am_accaptance`,  `assign_as_datetime`,  `is_assignto_fos`, `assign_fos_datetime`, `fosid`, `fos_completed`, `fos_completed_status`, `fos_comment`, `AlternetMobileNo`, `ptp_datetime`, `fos_submit_datetime`, `Payment_Collected_Date`, `Payment_Collected_Amount`, `penal`, `totalamt`, `Pincode`, `reason`, `runsheet`, `runsheetsequnce`, `PTP_Date`, `PTP_Amount`, `Time_Slot`,`customer_city`,`customer_city_id`,`withdraw_date`,`return_date`,`withdraw_reason`,`alternate_contact_number`,`is_photo_uploaded`,`error_upload`,`strEntryDate`, `strIP`,`Bank_Name`,`cycle`,`Total_Pos_Amount`,`Business_Address`,`Area_Name` FROM application where Month(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Month(STR_TO_DATE('" . $_REQUEST['ID'] . "','%d-%m-%Y')) and Year(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Year(STR_TO_DATE('" . $_REQUEST['ID'] . "','%d-%m-%Y')) ");
        if($query){
           $delete= mysqli_query($dbconn,"DELETE FROM `application` WHERE month(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Month(STR_TO_DATE('" . $_REQUEST['ID'] . "','%d-%m-%Y')) and Year(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Year(STR_TO_DATE('" . $_REQUEST['ID'] . "','%d-%m-%Y')) ");
        }
  
}
  
