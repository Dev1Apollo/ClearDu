<?php
error_reporting(0);
include('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {
 if ($_REQUEST['appid'] != "") {
         $where = "where 1=1";
            $where.=" and  App_Id = '" . $_REQUEST['appid'] . "' ";               
    
    $filterstr = "SELECT * FROM application  " . $where . "";
    $countstr = "SELECT count(*) as TotalRow FROM application  " . $where . " ";
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
// echo $filterstr;


    $resultfilter = mysqli_query($dbconn, $filterstr);
    if (mysqli_num_rows($resultfilter) > 0) {
        $i = 1;
        ?>  
        <link href="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/datatables.js" type="text/javascript"></script>
        <script src="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>


        <table class="table table-bordered table-hover center table-responsive" width="100%" id="tableC">
            <thead class="tbg">
                <tr>
                    
                    <th class="all">Sr.No</th>
                    <th class="all">Allocation Date</th>
                    <th class="all">Unique Id</th>                 
                    <th class="all">App Id</th>
                    <th class="all">Bkt</th>
                    <th class="all">Customer Name</th>
                    <th class="all">Customer City</th>                    
<!--                    <th class="all">Fos Status</th>-->
                    <th class="all">ptp datetime</th>
                    <th class="all">Alternet Mobile No</th>
<!--                    <th class="all">Pin Code</th>
                    <th class="all">Fos Name</th>
                    <th class="all"> Payment Collected Date</th>
                    <th class="all"> Payment Collected Amount</th>
                    <th class="all">Penal Amount Collected</th>
                    <th class="all">Total Amount Collected</th>
                    <th class="all">Alternate Contact Number</th>-->
                    <th class="all">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                    ?>
                    <tr >
                     
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
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['customer_city']; ?> 
                            </div>
                        </td>
                 <!--        <td>

                           <?php
                           // $status = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `fosstatusdrropdown`  where fosstatusdrropdownid ='" . $rowfilter['fos_completed_status'] . "'"));
                            ?>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $status['status']; ?> 
                            </div>
                        </td>-->
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['ptp_datetime']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['AlternetMobileNo']; ?> 
                            </div>
                        </td>
<!--                        <td>
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
                        </td>-->

                        <td style="width: 5%">                        
                            <?php
                            if ($rowfilter['is_photo_uploaded'] == '1') {
                                ?>
                                <a class="btn blue btn-sm" href="<?php echo $web_url; ?>admin/Zipallimage.php?appID=<?php echo $rowfilter['applicationid']; ?>&date=<?php echo $rowfilter['strEntryDate'] ?>" title="Download Images"><i class="fa fa-download"></i></a>
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

if ($_REQUEST['action'] == 'Delete') {
    $data = array(
        "isDelete" => '1',
        "strEntryDate" => date('d-m-Y H:i:s')
    );
    $where = ' where  	companyemployeeid=' . $_REQUEST['ID'];
    $dealer_res = $connect->updaterecord($dbconn, 'companyemployee', $data, $where);
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


