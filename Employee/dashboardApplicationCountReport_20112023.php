<?php
ob_start();
error_reporting(E_ALL);
include_once '../common.php';
$connect = new connect();
include('IsLogin.php');

$strLocationID = '0';
// echo "<pre>";
$agencymanagerid = $_SESSION['agencysupervisor']['agencymanagerid'];
$user = mysqli_query($dbconn, "SELECT * FROM `agencymanagerlocation`  where  iagencymanagerid='" . $agencymanagerid . "'  ");
while ($userid = mysqli_fetch_array($user)) {
    $strLocationID = $userid['iLocationId'] . ',' . $strLocationID;
}
$strLocationID = rtrim($strLocationID, ", ");

$useragency = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager` WHERE agencymanagerid ='" . $agencymanagerid . "' "));
if ($_POST['action'] == 'ListUser') {
    $iBankId = 0;
    $where = "";
    if(isset($_REQUEST['bankid']) && $_REQUEST['bankid'] != ""){
        $iBankId = $_REQUEST['bankid'];    
        $where .= " and iBankId='".$_REQUEST['bankid']."'";
    } 
    
    $totalAssigned = mysqli_query($dbconn, "SELECT count(*) as TotalRow,application.am_accaptance FROM  `application` where customer_city_id in  (" . $strLocationID . ") and agencyid='" . $useragency['agencyname'] . "' ". $where ." group by application.am_accaptance");
    $total = 0;
    while ($totalAssignedCase = mysqli_fetch_array($totalAssigned)) {
        if ($totalAssignedCase['am_accaptance'] == 1) {
            $totalcountRetention = $totalAssignedCase['TotalRow'];
        }
        if ($totalAssignedCase['am_accaptance'] == 2) {
            $totalcountWithdraw = $totalAssignedCase['TotalRow'];
        }
        if ($totalAssignedCase['am_accaptance'] == 3) {
            $totalcountReturn = $totalAssignedCase['TotalRow'];
        }
        $total = $totalcountRetention + $totalcountWithdraw + $totalcountReturn;
    }
?>

<div class="row">
    <div class="portlet light">
        <div class="portlet-body form">
            <div class="row">
                <div class="col-md-12" style="margin-top: 15px">
                    <div class="col-md-3 col-xs-12 margin-bottom-10">
                        <div class="dashboard-stat blue">
                            <div class="visual">
                                <i class="fa fa-briefcase fa-icon-medium"></i>
                            </div>
                            <div class="details">
                                <div class="number"> <a style="color: #fff !important;"><?php echo $total; ?> </a> </div>
                                <div class="desc"> Total Allocation </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="portlet light">
                            <div class="portlet-body form">
                                <div class="row">
                                    <div class="col-md-12" style="margin-top: 15px">
                                        <table class="table table-bordered dt-responsive" width="100%">
                                            <tr style="background-color: #3f4296; color: #fff;">
                                                <td colspan=4 class="text-center"><strong>Branch wise Cases</strong></td>
                                            </tr>
                                            <tr style="background-color: #3f4296; color: #fff;">
                                                <td class="text-center"><strong>Location</strong></td>
                                                <td class="text-center"><strong>Allocated</strong></td>
                                                <td class="text-center"><strong>Non Allocated</strong></td>
                                                <td class="text-center"><strong>Total</strong></td>
                                            </tr>
                                            <?php
                                            
                                            $branch = mysqli_query($dbconn, "SELECT SUM(IF(fosid != 0, 1, 0)) AS totalcount,
                                                        SUM(IF(fosid = 0, 1, 0)) AS pendingCount, application.customer_city_id,application.Branch 
                                                        FROM `application` where  customer_city_id in  (" . $strLocationID . ") and application.am_accaptance = 1 and 
                                                        agencyid='" . $useragency['agencyname'] . "' ". $where ." group by application.customer_city_id,application.Branch");
                                            $AllocatedTotal = 0;
                                            $NonAllocatedTotal = 0;
                                            $casesgrandTotal = 0;
                                            while ($branchwish = mysqli_fetch_array($branch)) {
                                                $totalCase = 0;
                                                $totalCase = $branchwish['totalcount']+$branchwish['pendingCount'];
                                                ?>
                                                <tr style="background-color: #258fd7; color: #fff !important;">
                                                    <td class="text-center text-light">
                                                        <?php echo $branchwish['Branch']; ?>
                                                    </td>
                                                    <td class="text-center text-light">
                                                        <?php echo $branchwish['totalcount'];
                                                            $AllocatedTotal += $branchwish['totalcount'];
                                                        ?>
                                                    </td>
                                                    <td class="text-center text-light">
                                                        <?php echo $branchwish['pendingCount']; 
                                                        $NonAllocatedTotal += $branchwish['pendingCount'];
                                                        ?>
                                                    </td>
                                                    <td class="text-center text-light">
                                                        <?php echo $totalCase; 
                                                        $casesgrandTotal += $totalCase;
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            <tr style="background-color: #3f4296; color: #fff;">
                                                <td class="text-center"><strong>Grand Total</strong></td>
                                                <td class="text-center"><strong><?= $AllocatedTotal; ?></strong></td>
                                                <td class="text-center"><strong><?= $NonAllocatedTotal; ?></strong></td>
                                                <td class="text-center"><strong><?= $casesgrandTotal ?></strong></td>
                                            </tr>
                                        </table>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="portlet light">
                            <div class="portlet-body form">
                                <div class="row">
                                    <div class="col-md-12" style="margin-top: 15px">
                                        <table class="table table-bordered dt-responsive" width="100%">
                                            <tr style="background-color: #b9936c; color: #fff;">
                                                <td colspan=4 class="text-center"><strong>Branch wise Allocation</strong></td>
                                            </tr>
                                            <tr style="background-color: #b9936c; color: #fff;">
                                                <td class="text-center"><strong>Location</strong></td>
                                                <td class="text-center"><strong>Visit Done</strong></td>
                                                <td class="text-center"><strong>Visit Pending</strong></td>
                                                <td class="text-center"><strong>Total</strong></td>
                                            </tr>
                                            <?php
                                            
                                            $branch = mysqli_query($dbconn, "SELECT SUM(IF(fos_completed_status != 0, 1, 0)) AS VisitDone,
                                                            SUM(IF(fos_completed_status = 0, 1, 0)) AS VisitPending, application.customer_city_id,application.Branch  
                                                            FROM `application` where  customer_city_id in  (" . $strLocationID . ") and application.am_accaptance = 1 
                                                            and agencyid='" . $useragency['agencyname'] . "' and fosid!= 0 ". $where ." group by application.customer_city_id,application.Branch");
                                            $VisitDoneTotal = 0;
                                            $VisitPendingTotal = 0;
                                            $totalVisit = 0;
                                            while ($branchwish = mysqli_fetch_array($branch)) {
                                                $visitTotal = 0;
                                                $visitTotal = $branchwish['VisitPending'] + $branchwish['VisitDone'];
                                                ?>
                                                <tr style="background-color: #e6e2d3; color: #353535 !important;">
                                                    <td class="text-center text-light">
                                                        <?php echo $branchwish['Branch']; ?>
                                                    </td>
                                                    <td class="text-center text-light">
                                                        <?php echo $branchwish['VisitDone']; 
                                                            $VisitDoneTotal += $branchwish['VisitDone'];
                                                        ?>
                                                    </td>
                                                    <td class="text-center text-light">
                                                        <?php echo $branchwish['VisitPending']; 
                                                            $VisitPendingTotal += $branchwish['VisitPending'];
                                                        ?>
                                                    </td>
                                                    <td class="text-center text-light">
                                                        <?php echo $visitTotal; 
                                                            $totalVisit += $visitTotal;
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            <tr style="background-color: #b9936c; color: #fff;">
                                                <td class="text-center"><strong>Grand Total</strong></td>
                                                <td class="text-center"><strong><?= $VisitDoneTotal; ?></strong></td>
                                                <td class="text-center"><strong><?= $VisitPendingTotal; ?></strong></td>
                                                <td class="text-center"><strong><?= $totalVisit; ?></strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="portlet light">
                            <div class="portlet-body form">
                                <div class="row">
                                    <div class="col-md-12" style="margin-top: 15px">
                                        <table class="table table-bordered dt-responsive" width="100%">
                                            <tr style="background-color: #2bb8c4; color: #fff;">
                                                <td colspan=11 class="text-center"><strong>Branch Wise Visit Status </strong></td>
                                            </tr>
                                            <tr style="background-color: #2bb8c4; color: #fff;">
                                                <td class="text-center"><strong>Location</strong></td>
                                                <td class="text-center"><strong>Payment Collected</strong></td>
                                                <td class="text-center"><strong>Refuse to Pay</strong></td>
                                                <td class="text-center"><strong>PTP Re-sheduled</strong></td>
                                                <td class="text-center"><strong>Customer Not Available</strong></td>
                                                <td class="text-center"><strong>PTP for the day</strong></td>
                                                <td class="text-center"><strong>Already Paid</strong></td>
                                                <td class="text-center"><strong>Broken PTP</strong></td>
                                                <td class="text-center"><strong>Partial Payment Collected</strong></td>
                                                <td class="text-center"><strong>Other</strong></td>
                                                <td class="text-center"><strong>Total</strong></td>
                                                
                                            </tr>
                                            <?php
                                            
                                            $branch = mysqli_query($dbconn, "SELECT SUM(IF(fos_completed_status = 1, 1, 0)) AS PaymentCollected,
                                                        SUM(IF(fos_completed_status = 2, 1, 0)) AS RefusetoPay,
                                                        SUM(IF(fos_completed_status = 3, 1, 0)) AS PTPResheduled,
                                                        SUM(IF(fos_completed_status = 4, 1, 0)) AS CustomerNotAvailable,
                                                        SUM(IF(fos_completed_status = 5, 1, 0)) AS PTPfortheday,
                                                        SUM(IF(fos_completed_status = 7, 1, 0)) AS AlreadyPaid,
                                                        SUM(IF(fos_completed_status = 17, 1, 0)) AS BrokenPTP,
                                                        SUM(IF(fos_completed_status = 18, 1, 0)) AS PartialPaymentCollected,
                                                        SUM(IF(fos_completed_status = 19, 1, 0)) AS Other,
                                                        application.customer_city_id,application.Branch FROM `application` where  
                                                        customer_city_id in  (" . $strLocationID . ") and application.am_accaptance = 1 and 
                                                        agencyid='" . $useragency['agencyname'] . "' and fosid!= 0 ". $where ." group by application.customer_city_id,application.Branch");
                                            $PaymentCollectedTotal= 0;
                                            $RefusetoPayTotal = 0;
                                            $PTPResheduledTotal = 0;
                                            $CustomerNotAvailableTotal = 0;
                                            $PTPforthedayTotal = 0;
                                            $AlreadyPaidTOtal = 0;
                                            $BrokenPTPTotal = 0;
                                            $PartialPaymentCollectedTotal = 0;
                                            $OtherTotal = 0;
                                            $GrandTotal =0;
                                            while ($branchwish = mysqli_fetch_array($branch)) {
                                                $total = 0;
                                                $total = $branchwish['PaymentCollected']+$branchwish['RefusetoPay']+$branchwish['PTPResheduled']+$branchwish['CustomerNotAvailable']+$branchwish['PTPfortheday']+$branchwish['AlreadyPaid']+$branchwish['BrokenPTP']+$branchwish['PartialPaymentCollected']+$branchwish['Other'];
                                                ?>
                                                <tr style="background-color: #DBEEF3; color: #000 !important;">
                                                    <td class="text-center text-light">
                                                        <?php echo $branchwish['Branch']; ?>
                                                    </td>
                                                    <td class="text-center text-light"><?php echo $branchwish['PaymentCollected']; $PaymentCollectedTotal+=$branchwish['PaymentCollected']; ?></td>
                                                    <td class="text-center text-light"><?php echo $branchwish['RefusetoPay']; $RefusetoPayTotal+=$branchwish['RefusetoPay']; ?></td>
                                                    <td class="text-center text-light"><?php echo $branchwish['PTPResheduled']; $PTPResheduledTotal+= $branchwish['PTPResheduled']; ?></td>
                                                    <td class="text-center text-light"><?php echo $branchwish['CustomerNotAvailable']; $CustomerNotAvailableTotal+= $branchwish['CustomerNotAvailable']; ?></td>
                                                    <td class="text-center text-light"><?php echo $branchwish['PTPfortheday']; $PTPforthedayTotal+=$branchwish['PTPfortheday']; ?></td>
                                                    <td class="text-center text-light"><?php echo $branchwish['AlreadyPaid']; $AlreadyPaidTOtal+=$branchwish['AlreadyPaid']; ?></td>
                                                    <td class="text-center text-light"><?php echo $branchwish['BrokenPTP']; $BrokenPTPTotal+=$branchwish['BrokenPTP']; ?></td>
                                                    <td class="text-center text-light"><?php echo $branchwish['PartialPaymentCollected']; $PartialPaymentCollectedTotal+=$branchwish['PartialPaymentCollected']; ?></td>
                                                    <td class="text-center text-light"><?php echo $branchwish['Other']; $OtherTotal+=$branchwish['Other']; ?></td>
                                                    <td class="text-center text-light"><?php echo $total; $GrandTotal+=$total; ?></td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            <tr style="background-color: #2bb8c4; color: #fff;">
                                                <td class="text-center"><strong>Grand Total</strong></td>
                                                <td class="text-center"><strong><?= $PaymentCollectedTotal; ?></strong></td>
                                                <td class="text-center"><strong><?= $RefusetoPayTotal; ?></strong></td>
                                                <td class="text-center"><strong><?= $PTPResheduledTotal; ?></strong></td>
                                                <td class="text-center"><strong><?= $CustomerNotAvailableTotal; ?></strong></td>
                                                <td class="text-center"><strong><?= $PTPforthedayTotal; ?></strong></td>
                                                <td class="text-center"><strong><?= $AlreadyPaidTOtal; ?></strong></td>
                                                <td class="text-center"><strong><?= $BrokenPTPTotal; ?></strong></td>
                                                <td class="text-center"><strong><?= $PartialPaymentCollectedTotal; ?></strong></td>
                                                <td class="text-center"><strong><?= $OtherTotal; ?></strong></td>
                                                
                                                <td class="text-center"><strong><?= $GrandTotal; ?></strong></td>
                                                
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php } ?>