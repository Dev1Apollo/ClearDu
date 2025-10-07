<?php
error_reporting(0);
include('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {

    //echo "SELECT count(*)  as totalcount,application.fos_completed_status FROM `application` where locationId in  (" . $_REQUEST['locationid'] . ") and agencyid='" . $_REQUEST['agencyid'] . "' and application.am_accaptance = 1 and application.Bkt = '" . $_REQUEST['id1'] . "' group by application.fos_completed_status";

    $filterrt = "SELECT count(*)  as totalcount,application.fos_completed_status FROM `application` where customer_city_id in  (" . $_REQUEST['locationid'] . ") and agencyid='" . $_REQUEST['agencyid'] . "' and application.am_accaptance = 1 and application.Bkt = '" . $_REQUEST['id1'] . "' group by application.fos_completed_status";

    $resultfilterrt = mysqli_query($dbconn, $filterrt);
    if (mysqli_num_rows($resultfilterrt) > 0) {
        $i = 1;
        ?>  

        <div class="col-md-6">
            <div class="portlet light">
                <div class="portlet-body form" >
                    <div class="row" >
                        <div class="col-md-12" style="margin-top: 15px" >
                            <table class="table table-bordered dt-responsive" width="100%">
                                <tr style="background-color: #e53e49; color: #fff;">
                                    <td class="text-center"><strong>Disposition</strong></td>
                                    <td class="text-center"><strong></strong></td>
                                </tr>
                                <?php
                                //  $Agency = mysqli_query($dbconn, "SELECT count(*),application.agencyid,application.agency FROM `application` where application.stateid = 34 and application.is_assignto_as = 1 group by application.agency,application.agencyid");

                                while ($Dispositionwise = mysqli_fetch_array($resultfilterrt)) {
                                    ?>
                                    <tr style="background-color: #DBEEF3; color: #000 !important ;" >
                                        <td class="text-center text-light">
                                            <a href="#">
                                                <?php
                                                if ($Dispositionwise['fos_completed_status'] == 0) {
                                                    echo "Pending Case";
                                                } else if ($Dispositionwise['fos_completed_status'] == 1) {
                                                    echo "Payment Collected";
                                                } else if ($Dispositionwise['fos_completed_status'] == 2) {
                                                    echo "Refuse to Pay";
                                                } else if ($Dispositionwise['fos_completed_status'] == 3) {
                                                    echo "PTP Re-sheduled";
                                                } else if ($Dispositionwise['fos_completed_status'] == 4) {
                                                    echo "Coustomer Not Available";
                                                } else if ($Dispositionwise['fos_completed_status'] == 6) {
                                                    echo "Customer Not Contactable";
                                                } else if ($Dispositionwise['fos_completed_status'] == 7) {
                                                    echo "Already Paid";
                                                } else if ($Dispositionwise['fos_completed_status'] == 10) {
                                                    echo "Short Payment";
                                                }
                                                   else if ($Dispositionwise['fos_completed_status'] == 11) {
                                                    echo "Penalty collected";
                                                }
                                                ?>
                                            </a>
                                        </td>
                                        <td class="text-center text-light">
                                            <a onclick="checkb4submit('<?php echo $_REQUEST['id1']; ?>', 'asbkt Disposition', '<?php echo $Dispositionwise['fos_completed_status']; ?>', '<?php echo $_REQUEST['locationid']; ?>');">    <?php echo $Dispositionwise['totalcount']; ?></a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>









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

    function checkb4submit(bkt, counttype, fos_completed_status, locationid)
    {
        var strURL = "cmindexcountReportDownload.php?bkt=" + bkt + "&counttype=" + counttype + "&fos_completed_status=" + fos_completed_status + "&locationid=" + locationid;
        window.open(strURL, '_blank');
    }
</script>
