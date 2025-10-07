<?php
error_reporting(0);
include('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');

if ($_POST['action'] == 'ListUser') {
        
    if ($_SESSION['Type'] == 'Central Manager') {

        $where = "where 1=1";
        if (isset($_REQUEST['searchName'])) {
            if ($_POST['searchName'] != '') {

                //$where.=" and  Account_No ='" . $_POST['searchName'] . "' or App_Id ='" . $_POST['searchName'] . "' ";
                $where.=" and  Account_No ='" . $_POST['searchName'] . "' ";
            }
        }
        $strLocationID = '0';
        $user = mysqli_query($dbconn, "SELECT * FROM `centralmanagerlocation`  where  icentralmanagerid='" . $_SESSION['centralmanagerid'] . "'  ");
        while ($userid = mysqli_fetch_array($user)) {
            $strLocationID = $userid['iLocationId'] . ',' . $strLocationID;
        }
        $strLocationID = rtrim($strLocationID, ", ");

        $filterstr = "SELECT * FROM `application` " . $where . " and customer_city_id in  (" . $strLocationID . ") and is_assignto_am ='1'   and isDelete=0   ";
        $countstr = "SELECT count(*) as TotalRow FROM  `application` " . $where . " and customer_city_id in  (" . $strLocationID . ") and  is_assignto_am ='1'  and isDelete=0  ";
    }

    if ($_SESSION['Type'] == 'Agency Manager') {

        $where = "where 1=1";

        if (isset($_REQUEST['searchName'])) {
            if ($_POST['searchName'] != '') {

                //$where.=" and  Account_No ='" . $_POST['searchName'] . "' or App_Id ='" . $_POST['searchName'] . "' ";
                $where.=" and  Account_No ='" . $_POST['searchName'] . "' ";
            }
        }

        $useragency = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager` WHERE agencymanagerid ='" . $_SESSION['agencymanagerid'] . "' "));
        $filterstr = "SELECT * FROM `application`  " . $where . " and  is_assignto_am='1' and agencyid='" . $useragency['agencyname'] . "' and isDelete=0   ";
        $countstr = "SELECT count(*) as TotalRow FROM  `application` " . $where . " and  is_assignto_am='1' and agencyid='" . $useragency['agencyname'] . "' and isDelete=0 ";
    }
    if ($_SESSION['Type'] == 'Agency supervisor') {

        $user = mysqli_query($dbconn, "SELECT * FROM `agencymanagerlocation`  where  iagencymanagerid='" . $_SESSION['agencysupervisorid'] . "'  ");
        while ($userid = mysqli_fetch_array($user)) {
            $strLocationID = $userid['iLocationId'] . ',' . $strLocationID;
        }
        $strLocationID = rtrim($strLocationID, ", ");

        $where = "where 1=1";
        if (isset($_REQUEST['searchName'])) {
            if ($_POST['searchName'] != '') {

                //$where.=" and  (Account_No ='" . $_POST['searchName'] . "' or App_Id ='" . $_POST['searchName'] . "' )";
                $where.=" and  Account_No ='" . $_POST['searchName'] . "' ";
            }
        }

        $filterstr = "SELECT * FROM `application` " . $where . " and customer_city_id in  (" . $strLocationID . ") and is_assignto_am ='1'  and agencyid='" . $_SESSION['agencyname'] . "' and am_accaptance NOT IN ('3','2')  and isDelete=0  ";
        $countstr = "SELECT count(*) as TotalRow FROM  `application` " . $where . " and customer_city_id in  (" . $strLocationID . ") and  is_assignto_am ='1' and agencyid='" . $_SESSION['agencyname'] . "' and am_accaptanceNOT IN ('3','2')  and isDelete=0 ";
    }
    $resrowcount = mysqli_query($dbconn, $countstr);
    $resrowc = mysqli_fetch_array($resrowcount);
    $totalrecord = $resrowc['TotalRow'];
    $per_page = $cateperpaging;
    $total_pages = ceil($totalrecord / $per_page);
    $page = $_REQUEST['Page'] - 1;
    $startpage = $page * $per_page;
    $show_page = $page + 1;



    $filterstr = $filterstr . " LIMIT $startpage, $per_page";

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
                        <th class="all">Unique Id</th>
                        <th class="all">Account No</th>
                        <th class="all">Agency</th>
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
                        <th class="all">Emi Amount Collected</th>
                        <th class="all">Overdue Amount</th>
                        <th class="all">Bcc</th>
                        <th class="all">Lpp</th>
                        <th class="none">Total penlty</th>
                        <th class="none">Principal outstanding</th>
                        <th class="none">Vehicle Registration No</th>
                        <th class="none">Supplier</th>
                        <th class="none">Tenure</th>
                        <th class="all">Customer Address</th>
                        <th class="none">Contact Number</th>
                        <th class="none">Collection Manager </th>
                        <th class="none">State Manager</th>
                        <th class="none">Ref 1 Name</th>
                        <th class="none">Contact Detail</th>
                        <th class="none">Ref 2 Name</th>
                        <th class="none">Contact Detail ref2</th>
                        <th class="all">Fos Name</th>
                        <th class="all">Fos Comment</th>
                        <th class="all">Fos Status</th>
                        <th class="all">Pin code</th>
                        <th class="all"> Payment Collected Date</th>
                        <th class="all"> Payment Collected Amount</th>
                        <th class="all">Penal Amount Collected</th>
                        <th class="all">Total Amount Collected</th>
                        <th class="all">Action</th>
                        <th class="all">TC Comment</th>
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
                                <?php
                                if ($_SESSION['Type'] == 'Agency Manager' || $_SESSION['Type'] == 'Agency supervisor') { ?>
                                    <button type="button" class="btn blue btn-sm" title="FOS History" onclick="return showDetailFOS('<?php echo $rowfilter['applicationid']; ?>');"><i class="fa fa-eye"></i></button>
                                    <?php if ($rowfilter['fos_completed_status'] != '1' && $rowfilter['fos_completed_status'] != '10' && $rowfilter['fos_completed_status'] != '11' && $rowfilter['fos_completed_status'] != '12' && $rowfilter['fos_completed_status'] != '13' && $rowfilter['fos_completed_status'] != '14' && $rowfilter['fos_completed_status'] != '15') {
                                        ?>
                                        <a class="btn btn-sm blue" href="#" onClick="return showmodal('<?php echo $rowfilter['applicationid']; ?>');"  title="Assign to"><i class="fa fa-check"></i></a>
                                        <?php
                                    }
                                } else { 
                                        if ($rowfilter['fos_completed_status'] != '1' && $rowfilter['fos_completed_status'] != '10' && $rowfilter['fos_completed_status'] != '11' && $rowfilter['fos_completed_status'] != '12' && $rowfilter['fos_completed_status'] != '13' && $rowfilter['fos_completed_status'] != '14' && $rowfilter['fos_completed_status'] != '15') {
                                        ?>
                                        <a class="btn btn-sm blue" href="#" onClick="return showmodal('<?php echo $rowfilter['applicationid']; ?>');"  title="Assign to"><i class="fa fa-check"></i></a>
                                        <?php
                                    }
                                    ?>
                                    <button type="button" class="btn blue btn-sm" onclick="return showDetail('<?php echo $rowfilter['applicationid']; ?>');"><i class="fa fa-history"></i></button>
                                    <button type="button" class="btn blue btn-sm" title="FOS History" onclick="return showDetailFOS('<?php echo $rowfilter['applicationid']; ?>');"><i class="fa fa-eye"></i></button>
                                    <a class="btn blue btn-sm" href="<?php echo $web_url; ?>Employee/ApplicationLog.php?applicationid=<?php echo $rowfilter['applicationid']; ?>" title="ApplictionLog Details"><i class="fa fa-file-text-o"></i></a> 
                                <?php } ?>
                                    
                                
                            </td>

                            <td>
                                <?php
                                if ($rowfilter['fos_completed_status'] != '1' && $rowfilter['fos_completed_status'] != '10' && $rowfilter['fos_completed_status'] != '11' && $rowfilter['fos_completed_status'] != '12' && $rowfilter['fos_completed_status'] != '13' && $rowfilter['fos_completed_status'] != '14' && $rowfilter['fos_completed_status'] != '15') {
                                    ?>
                                    <a class="btn btn-sm blue" href="#" onClick="return showtccomment('<?php echo $rowfilter['applicationid']; ?>');"  title="TC Comment"><i class="fa fa-comment"></i></a>
                                    <?php
                                }
                                ?>                                                                
                            </td>

                            <?php
                            $i++;
                        }
                        ?>
                    </tr>
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


<script type="text/javascript">
    function showDetail(Id)
    {
        $('#myModal').modal('show');
        $.ajax({
            type: "POST",
            url: "<?php echo $web_url; ?>Employee/AjaxAppointmentHistory.php",
            data: {action: 'ListUser', appID: Id},
            success: function (msg) {
                // alert(msg);
                $('#headerModal').html('Appointment History');
                $('#PlaceUsersDataHere123').html(msg);
            }
        });

        return false;
    }
    function showDetailFOS(Id)
    {
        $('#myModal').modal('show');
        $.ajax({
            type: "POST",
            url: "<?php echo $web_url; ?>Employee/AjaxFosHistory.php",
            data: {action: 'ListUser', appID: Id},
            success: function (msg) {
                // alert(msg);
                $('#headerModal').html('FOS History');
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
                <h4 class="modal-title" id="headerModal">Appointment History</h4>

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
