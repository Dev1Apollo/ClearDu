<?php
ob_start();
error_reporting(E_ALL);
include('../common.php');
include('IsLogin.php');
$connect = new connect();
include('User_Paging.php');

if ($_POST['action'] == 'ListUser') {



    $filterstr = "SELECT * FROM `error_application` ";
    $countstr = "SELECT count(*) as TotalRow FROM  `error_application`  ";

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

        <form role="form" method="POST" action="" name="frmparameterrr" id="frmparameterrr" enctype="multipart/form-data">
            <input type="hidden" value="deleteAllocation" name="action" id="action">
            <div class="row">
                <div class=" form-group col-md-9">
                </div>
                <div class=" form-group col-md-1">
                    <input class="btn blue margin-bottom-20" type="submit" id="Btnmybtn" value="Delete" name="submit">
                    <a href="#" onclick="deleteAll();" class="btn red  margin-bottom-20" alt="delete All" title="Delete All"><i class="fa fa-trash"></i></a>
                    <a href="#" onclick="checkb4submit();" class="btn green pull-right margin-bottom-20"><i class="fa fa-file-excel-o"></i></a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="tableC">
                    <thead class="tbg">
                        <tr>
                            <th class="all">
                                <div class="md-checkbox" style="margin-left: 35px">
                                    <input type="checkbox" onclick="javascript:CheckAll();" id="check_listall" class="md-check" value="">
                                    <label for="check_listall">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span>
                                    </label>
                                </div>
                            </th>
                            <th class="all">Sr.No</th>
                            <th class="all">Allocation Date</th>
                            <th class="all">Supervisor Assigned Date Time</th>
                            <th class="none">Unique Id</th>
                            <th class="none">Account No</th>
                            <th class="none">Agency</th>
                            <th class="none">Product</th>
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
                            <th class="none">Emi amount</th>
                            <th class="none">Overdue Amount</th>
                            <th class="none">Bcc</th>
                            <th class="none">Lpp</th>
                            <th class="none">Total penlty</th>
                            <th class="none">Principal outstanding</th>
                            <th class="none">Vehicle Registration No</th>
                            <th class="all">Supplier</th>
                            <th class="none">Tenure</th>
                            <th class="all">Customer Address</th>
                            <th class="all">Contact Number</th>
                            <th class="none">Collection Manager </th>
                            <th class="none">State Manager</th>
                            <th class="none">Ref 1 Name</th>
                            <th class="none">Contact Detail</th>
                            <th class="none">Ref 2 Name</th>
                            <th class="none">Contact Detail ref2</th>
                            <th class="all">Pin code</th>
                            <th class="all">Error</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                                    ?>
                            <tr>
                                <td>
                                    <div class="md-checkbox" style="margin: 5px 0 0 10px">
                                        <input type="checkbox" name="check_list[]" id="check_list<?php echo $i; ?>" class="md-check " value="<?php echo $rowfilter['applicationid']; ?> ">
                                        <label for="check_list<?php echo $i; ?>">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span>
                                        </label>
                                    </div>
                                </td>
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
                                <td>
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
                                <td>
                                    <div class="form-group form-md-line-input "><?php echo $rowfilter['error_upload']; ?>
                                    </div>
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
        </form>
        <script type="text/javascript">
            function checkclose() {
                window.location.href = '<?php echo $web_url; ?>Employee/deleteCEErrorApplication.php';
            }

            $('#frmparameterrr').submit(function(e) {

                e.preventDefault();
                var $form = $(this);
                $.ajax({
                    type: 'POST',
                    url: '<?php echo $web_url; ?>Employee/querydata.php',
                    data: $('#frmparameterrr').serialize(),
                    success: function(response) {
                        //alert(response);
                        $("#Btnmybtn").attr('disabled', 'disabled');
                        alert('Deleted Sucessfully.');
                        window.location.href = '<?php echo $web_url; ?>Employee/deleteCEErrorApplication.php';
                    }
                });
            });
        </script>
        <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.js" type="text/javascript"></script>
        <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>
        <script>
            $(document).ready(function() {
                $('#tableC').DataTable({});
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

<?php
if ($_REQUEST['action'] == 'deleteAll') {
    $deleteErrorAllocationQuery = "delete from error_application ";
    mysqli_query($dbconn, $deleteErrorAllocationQuery);
    echo "1";
    exit;
}
?>


<script>
    function CheckAll() {

        if ($('#check_listall').is(":checked")) {
            // alert('cheked');
            $('input[type=checkbox]').each(function() {
                $(this).prop('checked', true);
            });
        } else {
            //alert('cheked fail');
            $('input[type=checkbox]').each(function() {
                $(this).prop('checked', false);
            });
        }
    }
</script>