<?php
error_reporting(0);
include('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {

//   $where = "where 1=1";
//
//     if ($_REQUEST['State'] != NULL && isset($_REQUEST['State']) && $_REQUEST['location'] != NULL && isset($_REQUEST['location']))
//         
//         $state= mysqli_fetch_array (mysqli_query ($dbconn,"SELECT * FROM `state`  where isDelete='0'  and  istatus='1' and stateId='".$_REQUEST['State']."' order by  stateName asc"));
//         $location= mysqli_fetch_array (mysqli_query ($dbconn,"select * from location  where  istatus='1' and isDelete='0' and locationId='".$_REQUEST['location']."' order by locationId ASC"));
//         
//        $where.=" and STATE  = '" . $state['stateName'] . "' and location ='". $location['locationName'] . "'";


    $useragency = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager` WHERE agencymanagerid ='" . $_SESSION['agencymanagerid'] . "' "));
    $filterstr = "SELECT * FROM `application`  where  is_assignto_am='1' and agencyid='" . $useragency['agencyname'] . "' and is_assignto_as='1' and am_accaptance ='1' and fosid = 0 ";
    $countstr = "SELECT count(*) as TotalRow FROM  `application` where  is_assignto_am='1' and agencyid='" . $useragency['agencyname'] . "' and am_accaptance ='1' and is_assignto_as='1' and fosid = 0 ";

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
        <link href="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />

        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="tableC">

            <thead class="tbg">
                <tr>

                    <th></th>
                    <th class="all">Sr.No</th>
                    <th class="all">Allocation Date</th>
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
                    <th class="all">Emi amount</th>
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

                    <th class="none">Ref_1_Name</th>
                    <th class="none">Contact_Detail</th>
                    <th class="none">Ref_2_Name</th>
                    <th class="none">Contact_Detail_ref2</th>
                    <th class="all">Pin code</th>
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
                            <div class="form-group form-md-line-input">
                                <?php echo $rowfilter['Ref_2_Name'] ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Contact_Detail_ref2']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Pincode']; ?> 
                            </div>
                        </td>
                        <?php
                        $i++;
                    }
                    ?>

                </tr>
            </tbody>
        </table>
        </form>

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


<?php
//if ($_REQUEST['action'] == 'return') {
//
//    $data = array
//        (
//        'am_accaptance' => '3',
//    );
//    $where = ' where applicationid = ' . $_REQUEST['ID'];
//    $upd = $connect->updaterecord($dbconn,'application', $data, $where);
//
//    $data = array(
//        "app_id" => $_REQUEST['ID'],
//        "emp_id" => $_SESSION['agencymanagerid'],
//        "emp_type" => $_SESSION['Type'],
//        "action_name" => 'am return case',
//        "strEntryDate" => date('d-m-Y H:i:s'),
//        "strIP" => $_SERVER['REMOTE_ADDR']
//    );
//    $dealer_res = $connect->insertrecord($dbconn, 'applicationlog', $data);
//}
?>







