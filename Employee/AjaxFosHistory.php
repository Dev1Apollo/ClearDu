<?php
error_reporting(0);
include('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {


    $filterstr = "SELECT * FROM `foshistory` where appid='" . $_POST['appID'] . "' ";

    $resultfilter = mysqli_query($dbconn,$filterstr);
    if (mysqli_num_rows($resultfilter) > 0) {
        $i = 1;
        ?>  
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />

        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="tableC">

            <thead class="tbg">
                <tr>

                    <th class="desktop">ALPS ID</th>
                    <th class="desktop">FE Name</th>
                    <th class="desktop">Comments</th>
                    <th class="desktop">PTP Date</th>
                    <th class="desktop">Status</th>
                    <th class="desktop">Date</th>
                    <th class="desktop">Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                    ?>
                    <tr>

                        <td>
                            <div class="form-group form-md-line-input "><?php
                                $ALPSId = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `application`  where applicationid='" . $rowfilter['appid'] . "'"));
                                echo $ALPSId['uniqueId'];
                                ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                $FEname = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `agencymanager`  where loginId='" . $rowfilter['fosid'] . "'"));
                                echo $FEname['employeename'];
                                ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['comment']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['ptp_datetime']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                $status = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `fosstatusdrropdown`  where fosstatusdrropdownid='" . $rowfilter['status'] . "'"));
                                echo $status['status'];
                                ?> 
                            </div>
                        </td> 
                        <td>
                            <div class="form-group form-md-line-input "><?php
                            $fossubmit = explode(' ', $rowfilter['strEntryDate']);
                                echo  $fossubmit[0];
                                ?> 
                            </div>
                        </td> 
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                echo $fossubmit[1];
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
        <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.js" type="text/javascript"></script>
        <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>

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
    