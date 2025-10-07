<?php
error_reporting(0);
include('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {
    
    $iBankId = 0;
    $where = "";
    if(isset($_REQUEST['bankid']) && $_REQUEST['bankid'] != ""){
        $iBankId = $_REQUEST['bankid'];    
        $where .= " and iBankId='".$_REQUEST['bankid']."'";
    }

    $filterrt = "SELECT count(*) as totalcount,application.fosid FROM `application` where application.agencyid = '" . $_REQUEST['agencyid'] . "' and application.customer_city_id = '" . $_REQUEST['id1'] . "' and application.am_accaptance = 1 ".$where." group by application.fosid";

    $resultfilterrt = mysqli_query($dbconn, $filterrt);
    if (mysqli_num_rows($resultfilterrt) > 0) {
        $i = 1;
        ?>  
        <div class="col-md-3">
            <div class="portlet light">
                <div class="portlet-body form" >
                    <div class="row" >
                        <div class="col-md-12" style="margin-top: 15px" >
                            <table class="table table-bordered dt-responsive" width="100%">
                                <tr style="background-color: #258fd7; color: #fff;">
                                    <td class="text-center"><strong>FOS wise Allocation</strong></td>
                                    <td class="text-center"><strong></strong></td>
                                </tr>
                                <?php
                                $pendig = 0;
                                $PaymentCollected = 0;
                                $RefusetoPay = 0;
                                $PTPResheduled = 0;
                                $CoustomerNotAvailable = 0;
                                $CustomerNotContactable = 0;
                                $AlreadyPaid = 0;
                                while ($Dispositionwise = mysqli_fetch_array($resultfilterrt)) {
                                    $fos = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager` WHERE agencymanagerid ='" . $Dispositionwise['fosid'] . "' "));
                                    ?>
                                    <tr style="background-color: #91d8f7; color: #000 !important ;" >
                                        <td class="text-center text-light">
                                            <a onClick="javascript: return ASfosid('<?php echo $Dispositionwise['fosid']; ?>', '<?php echo $_REQUEST['agencyid']; ?>', '<?php echo $_REQUEST['id1']; ?>',<?= $iBankId ?>);">
                                                <?php echo $fos['employeename']; ?>
                                            </a>
                                        </td>
                                        <td class="text-center text-light">
                                            <a onclick="checkb4submit('<?php echo $_REQUEST['agencyid']; ?>', 'asFOS wise Allocation', '<?php echo $_REQUEST['id1']; ?>','<?php echo $Dispositionwise['fosid'] ?>',<?= $iBankId ?>);">  <?php echo $Dispositionwise['totalcount']; ?></a>
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
        <div id="PlaceasdispositionData"></div>


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

    function checkb4submit(agencyid, counttype, locationid,fosid,bankid)
    {
        var strURL = "cmindexcountReportDownload.php?agencyid=" + agencyid + "&counttype=" + counttype +"&locationid=" + locationid + "&fosid=" + fosid+"&bankid="+ bankid;
        window.open(strURL, '_blank');
    }
</script>
<script>

    function ASfosid(id1, agencyid, locationid) {

        $.ajax({
            type: "POST",
            url: "<?php echo $web_url; ?>Employee/ajax-index-ASDisposition.php",
            data: {action: 'ListUser', id1: id1, agencyid: agencyid, locationid: locationid},
            success: function (msg) {
                $("#PlaceasdispositionData").html(msg);
            },
        });
    }
</script>


