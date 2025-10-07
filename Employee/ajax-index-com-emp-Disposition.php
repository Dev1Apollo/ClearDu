<?php
error_reporting(0);
include('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');

if ($_POST['action'] == 'ListUser') {

    $filterrt = "SELECT count(*)  as totalcount,str_to_date(strEntryDate,'%d-%m-%Y') as strEntryDate FROM `application`  where application.agencyid='" . $_REQUEST['agencyid'] . "' and application.Bkt ='" . $_REQUEST['bkt'] . "' and application.stateid = '" . $_REQUEST['stateid'] . "'  group by str_to_date(strEntryDate,'%d-%m-%Y')";

    $resultfilterrt = mysqli_query($dbconn, $filterrt);
    if (mysqli_num_rows($resultfilterrt) > 0) {
        $i = 1;
        ?>  
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-body form" >
                    <div class="row" >
                        <div class="col-md-12" style="margin-top: 15px" >
                            <table class="table table-bordered dt-responsive" width="100%">
                                <tr style="background-color: #e53e49; color: #fff;">
                                    <td class="text-center"><strong>Allocation Date</strong></td>
                                    <td class="text-center"><strong>Total Allocation</strong></td>
                                    <td class="text-center"><strong>Returned Cases</strong></td>
                                    <td class="text-center"><strong>Already Paid</strong></td>
                                    <td class="text-center"><strong>Customer Not Available</strong></td>
                                    <td class="text-center"><strong>Customer Not Contactable</strong></td>
                                    <td class="text-center"><strong>FOS Pending</strong></td>
                                    <td class="text-center"><strong>Payment Collected</strong></td>
                                    <td class="text-center"><strong>PTP Re-sheduled</strong></td>
                                    <td class="text-center"><strong>Refuse to Pay</strong></td>                                    
                                    <td class="text-center"><strong>Supervisor Pending</strong></td>
                                </tr>
                                <?php
                                // $Returned = 0;
                                $Total = array(0);
                                while ($Dispositionwise = mysqli_fetch_array($resultfilterrt)) {

                                    $strdate = $Dispositionwise['strEntryDate'];
                                    $Returned = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*) as totalreturn  FROM `application` where application.agencyid='" . $_REQUEST['agencyid'] . "' and application.am_accaptance = '3'  and STR_TO_DATE(application.strEntryDate,'%d-%m-%Y') = STR_TO_DATE('$strdate','%Y-%m-%d') and application.Bkt='" . $_REQUEST['bkt'] . "' and application.stateid = '" . $_REQUEST['stateid'] . "' "));
                                    
                                    
                                    $SupervisorPending = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*) as supending FROM `application` where application.agencyid='" . $_REQUEST['agencyid'] . "' and fosid='0' and is_assignto_as='1' and   STR_TO_DATE(application.strEntryDate,'%d-%m-%Y') = STR_TO_DATE('$strdate','%Y-%m-%d') and application.Bkt='" . $_REQUEST['bkt'] . "' and application.stateid = '" . $_REQUEST['stateid'] . "'  and application.am_accaptance != '3' "));
                                    $fosPending = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*) as fosending FROM `application` where application.agencyid='" . $_REQUEST['agencyid'] . "' and fosid > '0' and is_assignto_as='1' and fos_completed_status = '0' and   STR_TO_DATE(application.strEntryDate,'%d-%m-%Y') = STR_TO_DATE('$strdate','%Y-%m-%d') and application.Bkt='" . $_REQUEST['bkt'] . "' and application.stateid = '" . $_REQUEST['stateid'] . "' and application.am_accaptance != '3'"));
                                    $AlreadyPaid = 0;
                                        $CustomerNotAvailable = 0;
                                        $CustomerNotContactable = 0;
                                        $PaymentCollected = 0;
                                        $PTPResheduled = 0;
                                        $RefusetoPay = 0;
                                    $fos_completed = mysqli_query($dbconn, "SELECT count(*) as totalcount,application.fos_completed_status  FROM `application` where application.agencyid='" . $_REQUEST['agencyid'] . "' and  STR_TO_DATE(application.strEntryDate,'%d-%m-%Y') = STR_TO_DATE('$strdate','%Y-%m-%d') and application.Bkt='" . $_REQUEST['bkt'] . "' and application.stateid = '" . $_REQUEST['stateid'] . "' and application.am_accaptance != '3'  group by application.fos_completed_status");
                                    while ($fos_completed_status = mysqli_fetch_array($fos_completed)) {

                                        if ($fos_completed_status['fos_completed_status'] == 7) {

                                            $AlreadyPaid = $fos_completed_status['totalcount'];
                                        }  if ($fos_completed_status['fos_completed_status'] == 4) {
                                            $CustomerNotAvailable = $fos_completed_status['totalcount'];
                                        }  if ($fos_completed_status['fos_completed_status'] == 6) {
                                            $CustomerNotContactable = $fos_completed_status['totalcount'];
                                        }  if ($fos_completed_status['fos_completed_status'] == 1) {
                                            $PaymentCollected = $fos_completed_status['totalcount'];
                                        }  if ($fos_completed_status['fos_completed_status'] == 3) {
                                            $PTPResheduled = $fos_completed_status['totalcount'];
                                        }  if ($fos_completed_status['fos_completed_status'] == 2) {
                                            $RefusetoPay = $fos_completed_status['totalcount'];
                                        }
                                    }
                                    ?>

                                    <tr style="background-color: #DBEEF3; color: #000 !important ;" >
                                        <td class="text-center text-light"><?php echo $Dispositionwise['strEntryDate']; ?></td>
                                        <td class="text-center text-light">
                                            <?php echo $Dispositionwise['totalcount'];
                                             $Total[0] = $Dispositionwise['totalcount'] + $Total[0];
                                            ?>
                                            
                                        </td>
                                        <td class="text-center text-light"><?php echo $Returned['totalreturn']; ?></td>
                                        <td class="text-center text-light"><?php
                                            echo $AlreadyPaid;
                                            ?>
                                        </td>
                                        <td class="text-center text-light"><?php
                                            echo $CustomerNotAvailable;
                                            //Customer Not Available
                                            ?>
                                        </td>
                                        <td class="text-center text-light"><?php
                                            echo $CustomerNotContactable;
                                            //Customer Not Contactable
                                            ?></td>
                                        <td class="text-center text-light"><?php
                                            //FOS Pending
                                            echo $fosPending['fosending'];
                                            ?></td>
                                        <td class="text-center text-light"><?php
                                            echo $PaymentCollected;
                                            //Payment Collected
                                            ?></td>
                                        <td class="text-center text-light"><?php
                                            echo $PTPResheduled;
                                            //PTP Re-sheduled
                                            ?></td>
                                        <td class="text-center text-light"><?php
                                            echo $RefusetoPay;
                                            //Refuse to Pay
                                            ?></td>
                                        <td class="text-center text-light"><?php echo $SupervisorPending['supending']; ?></td>
                                    </tr>                                  
                                    <?php
                                }
                                ?>
                                    <tr>
                                      <td class="text-center text-light">Total</td>
                                      <td class="text-center text-light"><?php echo $Total[0]; ?></td>
                                    </tr>
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