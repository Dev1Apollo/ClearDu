<?php
ob_start();
error_reporting(E_ALL);
include_once '../common.php';
$connect = new connect();
include('IsLogin.php');

$strLocationID = '0';
// echo "<pre>";
// print_r($_SESSION);exit;
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
    
    if(isset($_REQUEST['cycleId']) && $_REQUEST['cycleId'] != ""){
        $cycleId = $_REQUEST['cycleId'];    
        $where .= " and cycle='".$_REQUEST['cycleId']."'";
    }
    $totalAssigned = mysqli_query($dbconn, "SELECT count(*) as TotalRow,application.am_accaptance FROM  `application` where customer_city_id in  (" . $strLocationID . ") and agencyid='" . $useragency['agencyname'] . "' ". $where ." group by application.am_accaptance");
    // $total = 0;
    // $totalcountRetention = 0;
    // $totalcountWithdraw = 0;
    // $totalcountReturn = 0;
    // while ($totalAssignedCase = mysqli_fetch_array($totalAssigned)) {
    //     if ($totalAssignedCase['am_accaptance'] == 1) {
    //         $totalcountRetention = $totalAssignedCase['TotalRow'];
    //     }
    //     if ($totalAssignedCase['am_accaptance'] == 2) {
    //         $totalcountWithdraw = $totalAssignedCase['TotalRow'];
    //     }
    //     if ($totalAssignedCase['am_accaptance'] == 3) {
    //         $totalcountReturn = $totalAssignedCase['TotalRow'];
    //     }
    //     $total = $totalcountRetention + $totalcountWithdraw + $totalcountReturn;
    // }
    
    if ($_SESSION['Type'] == 'Central Manager') {
        $strLocationID = '0';
        // echo "SELECT * FROM `centralmanagerlocation`  where  icentralmanagerid='" . $_SESSION['centralmanagerid'] . "'";
        $user = mysqli_query($dbconn, "SELECT * FROM `centralmanagerlocation`  where  icentralmanagerid='" . $_SESSION['centralmanagerid'] . "'");
        while ($userid = mysqli_fetch_array($user)) {
            $strLocationID = $userid['iLocationId'] . ',' . $strLocationID;
        }

        $strLocationID = rtrim($strLocationID, ", ");
        $totalAssignedcomplited = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*) as TotalRow FROM  `application` where  (fos_completed_status = '1' || fos_completed_status = '10' )  ". $where ." "));
        $totalAssignedapps = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*) as TotalRow FROM  `application` where 1=1 ". $where ." "));
        
        // $pinalamt = mysqli_fetch_array(mysqli_query($dbconn, "SELECT sum(penal) as penal ,sum(Bcc) as bcc ,sum(Lpp) as lpp FROM  `application` where   is_assignto_am='1' and  am_accaptance ='1'  and fosid >= 0 ". $where ." "));
        // $penalamt = isset($pinalamt['penal']) ? $pinalamt['penal'] : 0;
        // $lpp = isset($pinalamt['lpp']) ? $pinalamt['lpp'] : 0;
        // $bcc = isset($pinalamt['bcc']) ? $pinalamt['bcc']: 0;
        // $totalbcclpp = $lpp + $bcc;
        // $pinalper = ($penalamt * 100) / $totalbcclpp;
        $pinalper = 0;

        $complitedapp = $totalAssignedcomplited['TotalRow'];
        $totalAssignedapp = $totalAssignedapps['TotalRow'];
        $paid = ($complitedapp * 100) / $totalAssignedapp;
        
        $totalAssigned = mysqli_query($dbconn, "SELECT count(*) as TotalRow,application.am_accaptance FROM  `application` where 1=1 ". $where ."  group by application.am_accaptance");
        $totalcountRetention = 0;
        $totalcountWithdraw = 0;
        $totalcountReturn = 0;
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
        <div class="col-md-3 col-xs-12 margin-bottom-10">
            <div class="dashboard-stat blue">
                <div class="visual">
                    <i class="fa fa-briefcase fa-icon-medium"></i>
                </div>
                <div class="details">
                    <div class="number"> <a style="color: #fff !important;" onclick="checkb4caTotalAllocation('<?php echo $strLocationID; ?>', 'Total Allocation',<?= $iBankId ?>);"><?php echo $total; ?> </a></div>
                    <div class="desc"> Total Allocation </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-xs-12 margin-bottom-10">
            <div class="dashboard-stat red">
                <div class="visual">
                    <i class="fa fa-briefcase fa-icon-medium"></i>
                </div>
                <div class="details">
                    <div class="number"> <a style="color: #fff !important;" onclick="checkb4caWithdraw('<?php echo $strLocationID; ?>', 'Withdraw',<?= $iBankId ?>);"> <?php echo $totalcountWithdraw; ?> </a></div>
                    <div class="desc"> Withdraw Case </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-xs-12 margin-bottom-10">
            <div class="dashboard-stat green">
                <div class="visual">
                    <i class="fa fa-briefcase fa-icon-medium"></i>
                </div>
                <div class="details">
                    <div class="number"> <a style="color: #fff !important;" onclick="checkb4caReturn('<?php echo $strLocationID; ?>', 'Return',<?= $iBankId ?>);">  <?php echo $totalcountReturn; ?> </a></div>
                    <div class="desc"> Return Case </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-xs-12 margin-bottom-10">
            <div class="dashboard-stat red">
                <div class="visual">
                    <i class="fa fa-briefcase fa-icon-medium"></i>
                </div>
                <div class="details">
                    <div class="number"> <?php echo round($paid, 2); ?></div>
                    <div class="desc"> Percentage Achieved Summary - Paid </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-xs-12 margin-bottom-10">
            <div class="dashboard-stat yellow">
                <div class="visual">
                    <i class="fa fa-briefcase fa-icon-medium"></i>
                </div>
                <div class="details">
                    <div class="number"> <?php echo round($pinalper, 2); ?></div>
                    <div class="desc"> Percentage Achieved Summary - Penal </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="portlet light">
                <div class="portlet-body form">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 15px">
                            <table class="table table-bordered dt-responsive" width="100%">
                                <tr style="background-color: #3f4296; color: #fff;">
                                    <td class="text-center"><strong>State wise Allocation</strong></td>
                                    <td class="text-center"><strong></strong></td>
                                </tr>
                                <?php
                                //customer_city_id in  (" . $strLocationID . ") and
                                $state = mysqli_query($dbconn, "SELECT count(*) as totalcount  ,application.stateid,application.State FROM `application` where  application.am_accaptance = 1 ". $where ." group by application.stateid,application.State");
                                while ($statewish = mysqli_fetch_array($state)) {
                                    ?>
                                    <tr style="background-color: #258fd7; color: #fff !important;">
                                        <td class="text-center text-light">
                                            <a href="#" style="color: #fff" onClick="javascript: return CAstateid('<?php echo $statewish['stateid']; ?>', '<?php echo $strLocationID; ?>',<?= $iBankId ?>);" >
                                                <?php echo $statewish['State']; ?>
                                            </a>
                                        </td>
                                        <td class="text-center text-light">
                                            <a style="color: #fff !important;" onclick="checkb4caState('<?php echo $statewish['stateid']; ?>', 'State wise Allocation', '<?php echo $strLocationID; ?>',<?= $iBankId ?>);"> <?php echo $statewish['totalcount']; ?></a>
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

        <div id="PlaceagencyData">  
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="portlet light">
                <div class="portlet-body form">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 15px">
                            <table class="table table-bordered dt-responsive" width="100%">
                                <tr style="background-color: #2bb8c4; color: #fff;">
                                    <td class="text-center"><strong>Bucket wise Allocation</strong></td>
                                    <td class="text-center"></td>
                                </tr>
                                <?php
                                //locationId in  (" . $strLocationID . ") and
                                $bkt = mysqli_query($dbconn, "SELECT count(*) as totalcount,application.Bkt FROM `application`  where  application.am_accaptance = 1 ". $where ." group by application.Bkt");
                                while ($bktwish = mysqli_fetch_array($bkt)) {
                                    ?>
                                    <tr style="background-color: #DBEEF3; color: #000 !important;">
                                        <td class="text-center text-light">
                                            <a href="#" onClick="javascript: return CAbkt('<?php echo $bktwish['Bkt']; ?>', '<?php echo $strLocationID; ?>',<?= $iBankId ?>);">
                                                <?php echo $bktwish['Bkt']; ?>
                                            </a>
                                        </td>
                                        <td class="text-center text-light">
                                            <a onclick="checkb4bkt('<?php echo $bktwish['Bkt']; ?>', 'Bucket wise Allocation', '<?php echo $strLocationID; ?>',<?= $iBankId ?>);"><?php echo $bktwish['totalcount']; ?></a>
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
        <div id="PlaceCAbktData"></div>
    </div>
<?php } 
if ($_SESSION['Type'] == 'Agency Manager') { 
    $strLocationID = '0';
    $user = mysqli_query($dbconn, "SELECT * FROM `agencymanagerlocation`  where  iagencymanagerid='" . $agencymanagerid . "'  ");
    while ($userid = mysqli_fetch_array($user)) {
        $strLocationID = $userid['iLocationId'] . ',' . $strLocationID;
    }
    $strLocationID = rtrim($strLocationID, ", ");


    $useragency = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager` WHERE agencymanagerid ='" . $agencymanagerid . "' "));
    $totalAssigned = mysqli_query($dbconn, "SELECT count(*) as TotalRow,application.am_accaptance FROM  `application` where customer_city_id in  (" . $strLocationID . ") and agencyid='" . $useragency['agencyname'] . "' ". $where ." group by application.am_accaptance");

    $totalAssignedcomplited = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*) as TotalRow FROM  `application` where customer_city_id in  (" . $strLocationID . ") and agencyid='" . $useragency['agencyname'] . "'  and am_accaptance='1' and (fos_completed_status = '1' || fos_completed_status = '10' ) ". $where ." "));
    $totalAssignedapps = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*) as TotalRow FROM  `application` where customer_city_id in  (" . $strLocationID . ") and agencyid='" . $useragency['agencyname'] . "' and  am_accaptance='1' ". $where ." "));

    // $pinalamt = mysqli_fetch_array(mysqli_query($dbconn, "SELECT sum(penal) as penal ,sum(Bcc) as bcc ,sum(Lpp) as lpp FROM  `application` where   is_assignto_am='1' and customer_city_id in  (" . $strLocationID . ") and  agencyid='" . $useragency['agencyname'] . "' and am_accaptance ='1'  and fosid >= 0 ". $where .""));

    // $penalamt = $pinalamt['penal'];
    // $lpp = $pinalamt['lpp'];
    // $bcc = $pinalamt['bcc'];
    // $totalbcclpp = $lpp + $bcc;
    // $pinalper = ($penalamt * 100) / $totalbcclpp;
    $pinalper = 0;

    $complitedapp = $totalAssignedcomplited['TotalRow'];
    $totalAssignedapp = $totalAssignedapps['TotalRow'];
    $paid = ($complitedapp * 100) / $totalAssignedapp;

    $totalcountRetention = 0;
    $totalcountWithdraw = 0;
    $totalcountReturn = 0;
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
        <div class="col-md-3 col-xs-12 margin-bottom-10">
            <div class="dashboard-stat blue">
                <div class="visual">
                    <i class="fa fa-briefcase fa-icon-medium"></i>
                </div>
                <div class="details">
                    <div class="number"> <a style="color: #fff !important;" onclick="checkb4caTotalAllocation('<?php echo $strLocationID; ?>', 'amTotal Allocation',<?= $iBankId ?>);"><?php echo $total; ?> </a> </div>
                    <div class="desc"> Total Allocation </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-xs-12 margin-bottom-10">
            <div class="dashboard-stat red">
                <div class="visual">
                    <i class="fa fa-briefcase fa-icon-medium"></i>
                </div>
                <div class="details">
                    <div class="number"> <a style="color: #fff !important;" onclick="checkb4caWithdraw('<?php echo $strLocationID; ?>', 'amWithdraw',<?= $iBankId ?>);"> <?php echo $totalcountWithdraw; ?> </a></div>
                    <div class="desc"> Withdraw Case </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-xs-12 margin-bottom-10">
            <div class="dashboard-stat green">
                <div class="visual">
                    <i class="fa fa-briefcase fa-icon-medium"></i>
                </div>
                <div class="details">
                    <div class="number"><a style="color: #fff !important;" onclick="checkb4caReturn('<?php echo $strLocationID; ?>', 'amReturn',<?= $iBankId ?>);">  <?php echo $totalcountReturn; ?> </a></div>
                    <div class="desc"> Return Case </div>
                </div>
            </div>
        </div>
    
        <div class="col-md-3 col-xs-12 margin-bottom-10">
            <div class="dashboard-stat yellow">
                <div class="visual">
                    <i class="fa fa-briefcase fa-icon-medium"></i>
                </div>
                <div class="details">
                    <div class="number"> <a style="color: #fff !important;" onclick="checkb4caRetention('<?php echo $strLocationID; ?>', 'amRetention',<?= $iBankId ?>);"> <?php echo $totalcountRetention; ?></a> </div>
                    <div class="desc"> Retention </div>
                </div>
            </div>
        </div>
    
        <div class="col-md-3 col-xs-12 margin-bottom-10">
            <div class="dashboard-stat red">
                <div class="visual">
                    <i class="fa fa-briefcase fa-icon-medium"></i>
                </div>
                <div class="details">
                    <div class="number"> <?php echo round($paid, 2); ?></div>
                    <div class="desc"> Percentage Achieved Summary - Paid </div>
                </div>
            </div>
        </div>
    
        <div class="col-md-3 col-xs-12 margin-bottom-10">
            <div class="dashboard-stat yellow">
                <div class="visual">
                    <i class="fa fa-briefcase fa-icon-medium"></i>
                </div>
                <div class="details">
                    <div class="number"> <?php echo round($pinalper, 2); ?></div>
                    <div class="desc"> Percentage Achieved Summary - Penal </div>
                </div>
    
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="portlet light">
                <div class="portlet-body form">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 15px">
                            <table class="table table-bordered dt-responsive" width="100%">
                                <tr style="background-color: #3f4296; color: #fff;">
                                    <td class="text-center"><strong>Branch wise Allocation</strong></td>
                                    <td class="text-center"><strong></strong></td>
                                </tr>
                                <?php
                                $branch = mysqli_query($dbconn, "SELECT count(*) as totalcount  ,application.customer_city_id,application.Branch FROM `application` where  customer_city_id in  (" . $strLocationID . ") and application.am_accaptance = 1 and agencyid='" . $useragency['agencyname'] . "' ". $where ." group by application.customer_city_id,application.Branch");
                                while ($branchwish = mysqli_fetch_array($branch)) {
                                    ?>
                                    <tr style="background-color: #258fd7; color: #fff !important;">
                                        <td class="text-center text-light">
                                            <a href="#" style="color: #fff" onClick="javascript: return AMBranchid('<?php echo $branchwish['customer_city_id']; ?>',<?= $iBankId ?>);">
                                                <?php echo $branchwish['Branch']; ?>
                                            </a>
                                        </td>
                                        <td class="text-center text-light">
                                            <a style="color: #fff" onclick="checkb4asBranch('<?php echo $branchwish['customer_city_id']; ?>', 'amBranch wise Allocation',<?= $iBankId ?>);"> <?php echo $branchwish['totalcount']; ?> </a>
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
            <div id="PlaceAMsupervisorwishData"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="portlet light">
                <div class="portlet-body form">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 15px">
                            <table class="table table-bordered dt-responsive" width="100%">
                                <tr style="background-color: #2bb8c4; color: #fff;">
                                    <td class="text-center"><strong>Bucket wise Allocation</strong></td>
                                    <td class="text-center"></td>
                                </tr>
                                <?php
                                $bkt = mysqli_query($dbconn, "SELECT count(*) as totalcount,application.Bkt FROM `application`  where customer_city_id in  (" . $strLocationID . ") and application.am_accaptance = 1 and agencyid='" . $useragency['agencyname'] . "' ". $where ." group by application.Bkt");
                                while ($bktwish = mysqli_fetch_array($bkt)) {
    //                                                            
                                    ?>
                                    <tr style="background-color: #DBEEF3; color: #000 !important;">
                                        <td class="text-center text-light">
                                            <a href="#" onClick="javascript: return AMbkt('<?php echo $bktwish['Bkt']; ?>', '<?php echo $strLocationID; ?>', '<?php echo $useragency['agencyname']; ?>',<?= $iBankId ?>);">
                                                <?php echo $bktwish['Bkt']; ?>
                                            </a>
                                        </td>
                                        <td class="text-center text-light">
                                            <a onclick="checkb4bkt('<?php echo $bktwish['Bkt']; ?>', 'amBucket wise Allocation', '<?php echo $strLocationID; ?>',<?= $iBankId ?>);">  <?php echo $bktwish['totalcount']; ?> </a>
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
        <div id="PlaceAMbktData"></div>
    </div>
<?php } if ($_SESSION['Type'] == 'Agency supervisor') { 
    $useragency = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager` WHERE agencymanagerid ='" . $_SESSION['agencysupervisorid'] . "' "));

    $strLocationID = '0';
    $user = mysqli_query($dbconn, "SELECT * FROM `agencymanagerlocation`  where  iagencymanagerid='" . $_SESSION['agencysupervisorid'] . "' ");
    while ($userid = mysqli_fetch_array($user)) {
        $strLocationID = $userid['iLocationId'] . ',' . $strLocationID;
    }
    $strLocationID = rtrim($strLocationID, ", ");
    $totalAssigned = mysqli_query($dbconn, "SELECT count(*) as TotalRow,application.am_accaptance FROM  `application` where customer_city_id in  (" . $strLocationID . ") and agencyid='" . $useragency['agencyname'] . "' ". $where ." group by application.am_accaptance");

    $totalAssignedcomplited = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*) as TotalRow FROM  `application` where customer_city_id in  (" . $strLocationID . ") and agencyid='" . $useragency['agencyname'] . "'  and am_accaptance='1'  and (fos_completed_status = '1' || fos_completed_status = '10' ) ". $where ." "));
    $totalAssignedapps = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*) as TotalRow FROM  `application` where customer_city_id in  (" . $strLocationID . ") and agencyid='" . $useragency['agencyname'] . "'  and am_accaptance='1' ". $where ." "));

    // $pinalamt = mysqli_fetch_array(mysqli_query($dbconn, "SELECT sum(penal) as penal ,sum(Bcc) as bcc ,sum(Lpp) as lpp FROM  `application` where   is_assignto_am='1' and customer_city_id in  (" . $strLocationID . ") and agencyid='" . $useragency['agencyname'] . "' and am_accaptance ='1' ". $where ." "));
    
    // $penalamt = $pinalamt['penal'];
    // $lpp = $pinalamt['lpp'];
    // $bcc = $pinalamt['bcc'];
    // $totalbcclpp = $lpp + $bcc;
    // $pinalper = ($penalamt * 100) / $totalbcclpp;
    $pinalper = 0;
    
    $complitedapp = $totalAssignedcomplited['TotalRow'];
    $totalAssignedapp = $totalAssignedapps['TotalRow'];
    $paid = ($complitedapp * 100) / $totalAssignedapp;

    $totalcountRetention = 0;
    $totalcountWithdraw = 0;
    $totalcountReturn = 0;
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
        <div class="col-md-3 col-xs-12 margin-bottom-10">
            <div class="dashboard-stat blue">
                <div class="visual">
                    <i class="fa fa-briefcase fa-icon-medium"></i>
                </div>
                <div class="details">
                    <div class="number"> <a style="color: #fff !important;" onclick="checkb4caTotalAllocation('<?php echo $strLocationID; ?>', 'asTotal Allocation',<?= $iBankId ?>);"> <?php echo $total; ?> </a></div>
                    <div class="desc"> Total Allocation </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-xs-12 margin-bottom-10">
            <div class="dashboard-stat red">
                <div class="visual">
                    <i class="fa fa-briefcase fa-icon-medium"></i>
                </div>
                <div class="details">
                    <div class="number"> <a style="color: #fff !important;" onclick="checkb4caWithdraw('<?php echo $strLocationID; ?>', 'asWithdraw',<?= $iBankId ?>);">  <?php echo $totalcountWithdraw; ?> </a> </div>
                    <div class="desc"> Withdraw Case </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-xs-12 margin-bottom-10">
            <div class="dashboard-stat green">
                <div class="visual">
                    <i class="fa fa-briefcase fa-icon-medium"></i>
                </div>
                <div class="details">
                    <div class="number"> <a style="color: #fff !important;" onclick="checkb4caReturn('<?php echo $strLocationID; ?>', 'asReturn',<?= $iBankId ?>);"> <?php echo $totalcountReturn; ?></a> </div>
                    <div class="desc"> Return Case </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-xs-12 margin-bottom-10">
            <div class="dashboard-stat yellow">
                <div class="visual">
                    <i class="fa fa-briefcase fa-icon-medium"></i>
                </div>
                <div class="details">
                    <div class="number"> <a style="color: #fff !important;" onclick="checkb4caRetention('<?php echo $strLocationID; ?>', 'asRetention',<?= $iBankId ?>);"> <?php echo $totalcountRetention; ?> </a></div>
                    <div class="desc"> Retention </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-xs-12 margin-bottom-10">
            <div class="dashboard-stat red">
                <div class="visual">
                    <i class="fa fa-briefcase fa-icon-medium"></i>
                </div>
                <div class="details">
                    <div class="number"> <?php echo round($paid, 2); ?></div>
                    <div class="desc"> Percentage Achieved Summary - Paid </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-xs-12 margin-bottom-10">
            <div class="dashboard-stat yellow">
                <div class="visual">
                    <i class="fa fa-briefcase fa-icon-medium"></i>
                </div>
                <div class="details">
                    <div class="number"><?php echo round($pinalper, 2); ?></div>
                    <div class="desc"> Percentage Achieved Summary - Penal </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="portlet light">
                <div class="portlet-body form">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 15px">
                            <table class="table table-bordered dt-responsive" width="100%">
                                <tr style="background-color: #3f4296; color: #fff;">
                                    <td class="text-center"><strong>Branch wise Allocation</strong></td>
                                    <td class="text-center"><strong></strong></td>
                                </tr>
                                <?php
                                $branch = mysqli_query($dbconn, "SELECT count(*) as totalcount  ,application.customer_city_id,application.Branch FROM `application` where  customer_city_id in  (" . $strLocationID . ") and application.am_accaptance = 1 and agencyid='" . $useragency['agencyname'] . "' ". $where ." group by application.customer_city_id,application.Branch");
                                while ($branchwish = mysqli_fetch_array($branch)) {
                                    ?>
                                    <tr style="background-color: #258fd7; color: #fff !important;">
                                        <td class="text-center text-light">
                                            <a href="#" style="color: #fff" onClick="javascript: return ASBranchid('<?php echo $branchwish['customer_city_id']; ?>', '<?php echo $useragency['agencyname']; ?>',<?= $iBankId ?>);">
                                                <?php echo $branchwish['Branch']; ?>
                                            </a>
                                        </td>
                                        <td class="text-center text-light">
                                            <a style="color: #fff" onclick="checkb4asBranch('<?php echo $branchwish['customer_city_id']; ?>', 'asBranch wise Allocation',<?= $iBankId ?>);"> <?php echo $branchwish['totalcount']; ?></a>
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
        <div id="PlaceAsfoswishData"></div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="portlet light">
                <div class="portlet-body form">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 15px">
                            <table class="table table-bordered dt-responsive" width="100%">
                                <tr style="background-color: #2bb8c4; color: #fff;">
                                    <td class="text-center"><strong>Bucket wise Allocation</strong></td>
                                    <td class="text-center"></td>
                                </tr>
                                <?php
                                $bkt = mysqli_query($dbconn, "SELECT count(*) as totalcount,application.Bkt FROM `application`  where customer_city_id in  (" . $strLocationID . ") and application.am_accaptance = 1 and agencyid='" . $useragency['agencyname'] . "' ". $where ." group by application.Bkt");
                                while ($bktwish = mysqli_fetch_array($bkt)) {
                                    ?>
                                    <tr style="background-color: #DBEEF3; color: #000 !important;">
                                        <td class="text-center text-light">
                                            <a href="#" onClick="javascript: return ASbkt('<?php echo $bktwish['Bkt']; ?>', '<?php echo $strLocationID; ?>', '<?php echo $useragency['agencyname']; ?>',<?= $iBankId ?>);">
                                                <?php echo $bktwish['Bkt']; ?>
                                            </a>
                                        </td>
                                        <td class="text-center text-light">
                                            <a onclick="checkb4bkt('<?php echo $bktwish['Bkt']; ?>', 'asBucket wise Allocation', '<?php echo $strLocationID; ?>',<?= $iBankId ?>);">  <?php echo $bktwish['totalcount']; ?></a>
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
        <div id="PlaceAsbktData"></div>
    </div>
<?php } 
if ($_SESSION['Type'] == 'Company Employee') { 
    $totalAssigned = mysqli_query($dbconn, "SELECT count(*) as TotalRow,application.am_accaptance FROM  `application` where 1=1 ". $where ."  group by application.am_accaptance");
    $totalcountRetention = 0;
    $totalcountWithdraw = 0;
    $totalcountReturn = 0;
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
        <div class="col-md-3 col-xs-12 margin-bottom-10">
            <div class="dashboard-stat blue">
                <div class="visual">
                    <i class="fa fa-briefcase fa-icon-medium"></i>
                </div>
                <div class="details">
                    <div class="number"> <a style="color: #fff !important;" ><?php echo $total; ?> </a></div>
                    <div class="desc"> Total Allocation </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="portlet light">
                <div class="portlet-body form">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 15px">
                            <table class="table table-bordered dt-responsive" width="100%">
                                <tr style="background-color: #3f4296; color: #fff;">
                                    <td class="text-center"><strong>State wise Allocation</strong></td>
                                    <td class="text-center"><strong></strong></td>
                                </tr>

                                <?php
                                $state = mysqli_query($dbconn, "SELECT count(*) as totalcount  ,application.stateid,application.State FROM `application` where 1=1 ". $where ."  group by application.stateid,application.State");
                                while ($statewish = mysqli_fetch_array($state)) {
                                    ?>
                                    <tr style="background-color: #258fd7; color: #fff !important;">
                                        <td class="text-center text-light">
                                            <a href="#" style="color: #fff" onClick="javascript: return companyemployeestateid('<?php echo $statewish['stateid']; ?>',<?= $iBankId ?>);" >
                                                <?php echo $statewish['State']; ?>
                                            </a>
                                        </td>
                                        <td class="text-center text-light">
                                            <a style="color: #fff !important;"> <?php echo $statewish['totalcount']; ?></a>
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

        <div id="PlaceagencyData">  
        </div>

    </div>
<?php } ?>
<?php if ($_SESSION['Type'] == 'Agency Manager' || $_SESSION['Type'] == 'Agency supervisor') { ?>
<div class="row">
    <div class="portlet light">
        <div class="portlet-body form">
            <div class="row">
                <div class="col-md-12" style="margin-top: 15px">
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
                    
                    <div class="col-md-12">
                        <div class="portlet light">
                            <div class="portlet-body form">
                                <div class="row">
                                    <div class="col-md-12" style="margin-top: 15px">
                                        <table class="table table-bordered dt-responsive" width="100%">
                                            <tr style="background-color: #2bb8c4; color: #fff;">
                                                <td colspan=11 class="text-center"><strong>FOS Paid Summary </strong></td>
                                            </tr>
                                            <tr style="background-color: #2bb8c4; color: #fff;">
                                                <td class="text-center"><strong>FOS Name</strong></td>
                                                <td class="text-center"><strong>Total Cases</strong></td>
                                                <td class="text-center"><strong>Paid Cases</strong></td>
                                                <td class="text-center"><strong>Unpaid Cases</strong></td>
                                                <td class="text-center"><strong>Paid %</strong></td>
                                            </tr>
                                            <?php
                                            
                                            // $branch = mysqli_query($dbconn, "SELECT SUM(IF(fos_completed_status = 1, 1, 0)) AS PaymentCollected,
                                            //             SUM(IF(fos_completed_status = 2, 1, 0)) AS RefusetoPay,
                                            //             SUM(IF(fos_completed_status = 3, 1, 0)) AS PTPResheduled,
                                            //             SUM(IF(fos_completed_status = 4, 1, 0)) AS CustomerNotAvailable,
                                            //             SUM(IF(fos_completed_status = 5, 1, 0)) AS PTPfortheday,
                                            //             SUM(IF(fos_completed_status = 7, 1, 0)) AS AlreadyPaid,
                                            //             SUM(IF(fos_completed_status = 17, 1, 0)) AS BrokenPTP,
                                            //             SUM(IF(fos_completed_status = 18, 1, 0)) AS PartialPaymentCollected,
                                            //             SUM(IF(fos_completed_status = 19, 1, 0)) AS Other,
                                            //             application.customer_city_id,application.Branch FROM `application` where  
                                            //             customer_city_id in  (" . $strLocationID . ") and application.am_accaptance = 1 and 
                                            //             agencyid='" . $useragency['agencyname'] . "' and fosid!= 0 ". $where ." group by application.customer_city_id,application.Branch");
                                            
                                            $branch = mysqli_query($dbconn, "select agencymanager.employeename,
                                                (select count(*) from application where application.fosid = agencymanager.agencymanagerid and application.fos_completed_status=1
                                                and application.iStatus=1 and application.isDelete=0 and customer_city_id in  (" . $strLocationID . ") ". $where .") as Collected,
                                                (select count(*) from application where application.fosid = agencymanager.agencymanagerid and application.fos_completed_status != 1 
                                                and application.iStatus=1 and application.isDelete=0  and customer_city_id in  (" . $strLocationID . ") ". $where .") as OtherStatus,
                                                (select count(*) from application where application.fosid = agencymanager.agencymanagerid and application.iStatus=1 and 
                                                application.isDelete=0 and customer_city_id in  (" . $strLocationID . ") ". $where .") as TotalCount from agencymanager 
                                                where type='FOS' and agencymanager.agencymanagerid in (select agencymanagerlocation.iagencymanagerid from agencymanagerlocation 
                                                where  agencymanager.agencymanagerid=agencymanagerlocation.iagencymanagerid)  and agencyname='" . $useragency['agencyname'] . "' 
                                                and agencymanager.istatus=1 and agencymanager.isDelete=0 order by agencymanager.employeename asc");
                                            while ($branchwish = mysqli_fetch_array($branch)) {
                                                $TotalCount = $branchwish['TotalCount'];
                                                $Collected = $branchwish['Collected'];
                                                $OtherStatus = $branchwish['OtherStatus'];
                                                $paidPercentage = 0;
                                                if($TotalCount != 0 && $Collected != 0){
                                                    $paidPercentage = round(($Collected / $TotalCount) * 100,2) . " %";
                                                } else {
                                                    $paidPercentage = round(0,2);
                                                }
                                                if($branchwish['TotalCount'] > 0){
                                                ?>
                                                <tr style="background-color: #DBEEF3; color: #000 !important;">
                                                    <td class="text-light"><?php echo $branchwish['employeename']; ?></td>
                                                    <td class="text-center text-light"><?php echo $branchwish['TotalCount']; ?></td>
                                                    <td class="text-center text-light"><?php echo $branchwish['Collected']; ?></td>
                                                    <td class="text-center text-light"><?php echo $branchwish['OtherStatus']; ?></td>
                                                    <td class="text-center text-light"><?php echo $paidPercentage; ?></td>
                                                </tr>
                                                <?php
                                                }
                                            }
                                            ?>
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
<?php } 
} ?>


