<?php
error_reporting(0);
include('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {

    $filterrt = "SELECT count(*) as totalagency,application.agencyid,application.agency FROM `application` where  application.stateid = '" . $_REQUEST['id1'] . "'  group by application.agency,application.agencyid";


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
                                    <td class="text-center"><strong>Agency wise Allocation</strong></td>
                                    <td class="text-center"><strong></strong></td>
                                </tr>
                                <?php
                                //  $Agency = mysqli_query($dbconn, "SELECT count(*),application.agencyid,application.agency FROM `application` where application.stateid = 34 and application.is_assignto_as = 1 group by application.agency,application.agencyid");
                                while ($Agencywise = mysqli_fetch_array($resultfilterrt)) {
                                    ?>
                                    <tr style="background-color: #91d8f7; color: #000 !important ;" >
                                        <td class="text-center text-light">
                                            <a href="#" style="color: #fff" onClick="javascript: return Comempagencyid('<?php echo $Agencywise['agencyid']; ?>', '<?php echo $_REQUEST['id1']; ?>');">
                                                <?php echo $Agencywise['agency']; ?>
                                            </a>
                                        </td>
                                        <td class="text-center text-light">
                                            <a style="color: #fff" ><?php echo $Agencywise['totalagency']; ?></a>
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
        <div id="PlacebktData"></div>

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
<script>
    function Comempagencyid(agencyid, stateid) {
        $.ajax({
            type: "POST",
            url: "<?php echo $web_url; ?>Employee/ajax-index-com-emp-bkt.php",
            data: {action: 'ListUser', agencyid: agencyid, stateid: stateid},
            success: function (msg) {
                $("#PlacebktData").html(msg);
            },
        });
    }
</script>
        
<!--<script>
    function Comempagencyid(agencyid, stateid) {
        $.ajax({
            type: "POST",
            url: "<?php echo $web_url; ?>Employee/ajax-index-com-emp-Disposition.php",
            data: {action: 'ListUser', agencyid: agencyid, stateid: stateid},
            success: function (msg) {
                $("#PlaceDispositionData").html(msg);
            },
        });
    }
</script>-->

