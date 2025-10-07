<?php
error_reporting(0);
include('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {

    $where = "where 1=1";

    if ($_REQUEST['location'] != NULL && isset($_REQUEST['location'])) {
        //$state = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `state`  where isDelete='0'  and  istatus='1' and stateId='" . $_REQUEST['State'] . "' order by  stateName asc"));
//        $location = mysqli_fetch_array(mysqli_query($dbconn, "select * from location  where  istatus='1' and isDelete='0' and locationId='" . $_REQUEST['location'] . "' order by locationId ASC"));
//
//        $where.=" and DESTINATION_CITY ='" . $location['locationName'] . "'";
        $where.=" and  locationid = '" . $_REQUEST['location'] . "'";
    }
    $useragency = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager` WHERE agencymanagerid ='" . $_SESSION['agencysupervisorid'] . "' "));

    $strLocationID = '';
    $user = mysqli_query($dbconn, "SELECT * FROM `agencymanagerlocation`  where  iagencymanagerid='" . $_SESSION['agencysupervisorid'] . "' ");
    while ($userid = mysqli_fetch_array($user)) {
        $strLocationID = $userid['iLocationId'] . ',' . $strLocationID;
    }
    $strLocationID = rtrim($strLocationID, ", ");


    $filterstr = "SELECT * FROM `application` " . $where . " and locationid in(" . $strLocationID . ") and   is_assignto_as='1'  and is_assignto_fos='1' and fos_completed='0' and  agencyid='" . $useragency['agencyname'] . "' ";
    $countstr = "SELECT count(*) as TotalRow FROM  `application` " . $where . " and locationid in(" . $strLocationID . ") and   is_assignto_as='1'   and is_assignto_fos='1' and fos_completed='0' and  agencyid='" . $useragency['agencyname'] . "' ";

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
        <link href="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/datatables.js" type="text/javascript"></script>

        <script src="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>

        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <form  role="form"  method="POST"  action="" name="frmparameterrr"  id="frmparameterrr" enctype="multipart/form-data">
            <input type="hidden" value="asreassigntofos" name="action" id="action">
            <div class="row">
                <div class="form-group col-md-4">
                    <?php
                    $locationid = $_REQUEST['location'];
                    $agencymanagerid = '0';
                    $result = mysqli_query($dbconn, "select * from agencymanagerlocation  where  istatus='1' and isDelete='0' and iLocationId=" . $locationid . " order by iLocationId ASC");
                    while ($row = mysqli_fetch_array($result)) {

                        $agencymanagerid = $row['iagencymanagerid'] . ',' . $agencymanagerid;
                    }

                    $agencymanagerid = rtrim($agencymanagerid, ", ");

                    $agencyid = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager`  where agencymanagerid='" . $_SESSION['agencysupervisorid'] . "' "));
                    ?>

                    <div id="agencyid">


                        <?php
                        $querys = "select * from agencymanager  where  istatus='1' and isDelete='0' and agencymanagerid in (" . $agencymanagerid . ") and agencyname='" . $agencyid['agencyname'] . "' and type='FOS' ";
                        $results = mysqli_query($dbconn, $querys);
                        echo '<select class="form-control" name="refosid" id="refosid" required=""  >';
                        echo "<option value='' >Select FOS</option>";
                        while ($rows = mysqli_fetch_array($results)) {
                            $agencyname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT agencyname FROM agency where Agencyid='" . $rows['agencyname'] . "'"));
                            echo "<option value='" . $rows['agencymanagerid'] . "'>" . $agencyname['agencyname'] . $rows['employeename'] . "</option>";
                        }
                        echo "</select>";
                        ?>
                    </div>
                </div>

                <div class="form-group col-md-4">

                    <input class="btn blue " type="submit" id="Btnmybtn"  value="Submit" name="submit">      

                                <!--                <input class="btn blue " type="hidden"   value="<?php echo $_REQUEST['fos'] ?>" name="refosid" id="refosid" >  -->

                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="tableC">

                            <thead class="tbg">
                                <tr>
                                    <th class="all">
                                        <div class="md-checkbox" style="margin-left: 35px">
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
                                    <th class="none">Ref 1 Name</th>
                                    <th class="none">Contact Detail</th>
                                    <th class="none">Ref 2 Name</th>
                                    <th class="none">Contact Detail ref2</th>
                                    <th class="all">Name</th>
                                    <th class="all">Assign DateTime</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                                    ?>

                                    <tr >

                                        <td>
                                            <!--                                <div class="form-group form-md-line-input ">-->
                                            <!--                                    <div class="form-group form-md-checkboxes">-->
                                            <!--                                        <div class="md-checkbox-inline">-->
                                            <div class="md-checkbox" style="margin: 5px 0 0 10px">
                                                <input type="checkbox" name="check_list[]" id="check_list<?php echo $i; ?>" class="md-check " value="<?php echo $rowfilter['applicationid']; ?> ">
                                                <label for="check_list<?php echo $i; ?>">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span>
                                                </label>
                                            </div>
                                            <!--                                         </div>-->
                                            <!--                                    </div>-->
                                            <!--                                </div>-->
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

                                        <td >
                                            <div class="form-group form-md-line-input">
                                                <?php echo $rowfilter['Ref_2_Name'] ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Contact_Detail_ref2']; ?> 
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group form-md-line-input ">
                                                <?php
                                                $asname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager`  where  agencymanagerid='" . $rowfilter['fosid'] . "' "));
                                                echo $asname['employeename'];
                                                ?>
                                            </div>
                                        </td> 

                                        <td>
                                            <div class="form-group form-md-line-input ">
                                                <?php
                                                echo $rowfilter['assign_fos_datetime'];
                                                ?>
                                            </div>
                                        </td> 

                                        <?php
                                        $i++;
                                    }
                                    ?>

                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </form>
        <script type="text/javascript">


            function checkclose() {
                window.location.href = '<?php echo $web_url; ?>Employee/asAssigned.php';
            }

            $('#frmparameterrr').submit(function (e) {

                e.preventDefault();
                var $form = $(this);
                $.ajax({
                    type: 'POST',
                    url: '<?php echo $web_url; ?>Employee/querydata.php',
                    data: $('#frmparameterrr').serialize(),
                    success: function (response) {
                        // alert(response);
                        $("#Btnmybtn").attr('disabled', 'disabled');
                        alert('assign Sucessfully.');
                        window.location.href = '<?php echo $web_url; ?>Employee/asAssigned.php';
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




