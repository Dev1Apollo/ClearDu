<?php
error_reporting(0);
include('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {
    $where = "where 1=1";
//ptprescheduled=3
    if ($_REQUEST['location'] != NULL || $_REQUEST['State'] != NULL) {




        if ($_REQUEST['location'] != NULL && isset($_REQUEST['location'])) {


            $where.=" and  locationid = '" . $_REQUEST['location'] . "'";
        }
        if ($_REQUEST['State'] != NULL && isset($_REQUEST['State'])) {


            $where.=" and  stateid = '" . $_REQUEST['State'] . "'";
        }
    }

//    $strLocationID = '';
//    $user = mysqli_query($dbconn, "SELECT * FROM `centralmanagerlocation`  where  icentralmanagerid='" . $_SESSION['centralmanagerid'] . "'  ");
//    while ($userid = mysqli_fetch_array($user)) {
//        $strLocationID = $userid['iLocationId'] . ',' . $strLocationID;
//    }
//    $strLocationID = rtrim($strLocationID, ", ");
//locationid in(" . $strLocationID . ") and

    $filterstr = "SELECT * FROM `application`  " . $where . " and   fos_completed='3' and fos_completed_status='3' ";
    $countstr = "SELECT count(*) as TotalRow FROM  `application`  " . $where . " and   fos_completed='3' and fos_completed_status='3'  ";


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
        <form  role="form"  method="POST"  action="" name="frmparameterrr"  id="frmparameterrr" enctype="multipart/form-data">
            <input type="hidden" value="cmptpreassign" name="action" id="action">


            <div class="form-group  col-md-4">

                <?php
                $locationid = '';
                if ($_REQUEST['location'] != '') {

                    $locationid = $_REQUEST['location'];
                } else if ($_REQUEST['State'] != '' && $_REQUEST['location'] == '') {


                    $location = mysqli_query($dbconn, "select * from location  where  istatus='1' and isDelete='0' and stateId='" . $_REQUEST['State'] . "' order by locationId ASC");
                    while ($locations = mysqli_fetch_array($location)) {
                        $locationid = $locations['locationId'] . ',' . $locationid;
                    }
                    $locationid = rtrim($locationid, ", ");
                }
                $locationid = rtrim($locationid, ", ");

                $agancymanager = "SELECT * FROM `agncylocation` where locationid in(" . $locationid . ")  ";
                $results = mysqli_query($dbconn, $agancymanager);

                echo '<select class="form-control" name="agency" id="agency" required=""  >';
                echo "<option value='' >Select Agency Name</option>";
                while ($rows = mysqli_fetch_array($results)) {

                    $agencyname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agency` where Agencyid='" . $rows['agencyid'] . "'"));
                    echo "<option value='" . $agencyname['Agencyid'] . "'>" . $agencyname['agencyname'] . "</option>";
                }
                echo "</select>";
                ?>

            </div>


            <!--            <div class="form-group  col-md-4">
            
            <?php
//                $agancymanager = "SELECT * FROM `agncylocation` where locationid in(" . $strLocationID . ")  ";
//                $results = mysqli_query($dbconn, $agancymanager);
//
//                echo '<select class="form-control" name="agency" id="agency" required=""  >';
//                echo "<option value='' >Select Agency Name</option>";
//                while ($rows = mysqli_fetch_array($results)) {
//
//                    $agencyname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agency` where Agencyid='" . $rows['agencyid'] . "' and isDelete='0' "));
//                    echo "<option value='" . $agencyname['Agencyid'] . "'>" . $agencyname['agencyname'] . "</option>";
//                }
//                echo "</select>";
            ?>
            
                        </div>-->

            <div class="row">
            <div class="form-group col-md-4">
                <input class="btn blue " type="submit" id="Btnmybtn"  value="Submit" name="submit">  
        <!--                <input class="btn blue " type="hidden"   value="<?php echo $_REQUEST['agencymanager']; ?>" name="agencymanagerid" id="agencymanagerid" >  -->

            </div>
            </div>
               
                    <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="tableC">

                        <thead class="tbg">
                            <tr>

                                <th>
                                    <div class="md-checkbox">
                                        <input type="checkbox"  onclick="javascript:CheckAll();" id="check_listall" class="md-check" value="">
                                        <label for="check_listall">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span>
                                        </label>

                                    </div>
                                </th>
                                <th class="all">unique Id</th>
                                <th class="all">Account No</th>
                                <th class="all">Agency</th>
                              
                                <th class="all">PRODUCT</th>
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
                                <th class="all">Installment Overdue Amount</th>
                                <th class="all">Bcc</th>
                                <th class="all">Lpp</th>
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
                                <th class="all">Remarks</th>
                                <th class="all"> Payment Collected Date</th>
                                <th class="all"> Payment Collected Amount</th>
                                <th class="all">ptp datetime</th>
                            </tr>
                        </thead>
                        <tbody>
        <?php
        while ($rowfilter = mysqli_fetch_array($resultfilter)) {
            ?>

                                <tr >

                                    <td>

                                        <div class="md-checkbox">
                                            <input type="checkbox" name="check_list[]" id="check_list<?php echo $i; ?>" class="md-check " value="<?php echo $rowfilter['applicationid']; ?> ">
                                            <label for="check_list<?php echo $i; ?>">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span>
                                            </label>
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
                                        <div class="form-group form-md-line-input "><?php echo $rowfilter['fos_comment']; ?> 
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
                                        <div class="form-group form-md-line-input "><?php echo $rowfilter['ptp_datetime']; ?> 
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

        <script type="text/javascript">


            function checkclose() {
                window.location.href = '<?php echo $web_url; ?>Employee/cmptpreassign.php';
            }

            $('#frmparameterrr').submit(function (e) {

                e.preventDefault();
                var $form = $(this);

                $.ajax({
                    type: 'POST',
                    url: '<?php echo $web_url; ?>Employee/querydata.php',
                    data: $('#frmparameterrr').serialize(),
                    success: function (response) {
                        //alert(response);
                        $("#Btnmybtn").attr('disabled', 'disabled');
                        alert('assign Sucessfully 123.');
                        window.location.href = '<?php echo $web_url; ?>Employee/cmptpreassign.php';
                    }

                });

            });
        </script> 

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




<script>
    function CheckAll()
    {

        if ($('#check_listall').is(":checked"))
        {
            // alert('cheked');
            $('input[type=checkbox]').each(function () {
                $(this).prop('checked', true);
            });
        } else
        {
            //alert('cheked fail');
            $('input[type=checkbox]').each(function () {
                $(this).prop('checked', false);
            });
        }
    }
</script>













