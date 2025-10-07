<?php
error_reporting(0);
include('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {
    $where = "where 1=1";
   

    if (isset($_REQUEST['applicationid'])) {
        if ($_POST['applicationid'] != '') {
            $where.=" and appid ='".$_POST['applicationid']."'";
        }
    }

    $filterstr = "SELECT *,(select fosstatusdrropdown.status from fosstatusdrropdown where fosstatusdrropdown.fosstatusdrropdownid=foshistory.status) as AppStatus,(select agencymanager.employeename from agencymanager where agencymanager.loginId=foshistory.fosid) as FOSName FROM `foshistory`  " . $where . " and isDelete='0'  and  istatus='1' order by foshistoryid desc";
    $countstr = "SELECT count(*) as TotalRow FROM `foshistory`  " . $where . "  and isDelete='0' and  istatus='1' ";

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

        
        <table class="table table-bordered table-hover center" width="100%" id="tableC">
            <thead class="tbg">
                <tr>
                    <th class="desktop">ALPS ID</th>
                    <th class="desktop">FE Name</th>
                    <th class="desktop">Comments</th>
                    <th class="desktop">PTP Date</th>
                    <th class="desktop">Status</th>
                    <th class="desktop">Date</th>
                    <th class="desktop">Time</th>
                    <th class="all">Action</th>
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
                                echo $rowfilter['FOSName'];
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
                                echo $rowfilter['AppStatus'];
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
                         <td>
                            <a class="btn  blue" onClick="javascript: return deletefoshistorydata('Delete', '<?php echo $rowfilter['foshistoryid']; ?>');"   title="Delete"><i class="fa fa-trash-o iconshowFirst"></i></a>
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
    // $data = array(
    //     "isDelete" => '1',
    //     "strEntryDate" => date('d-m-Y H:i:s')
    // );
    // $where = ' where foshistoryid=' . $_REQUEST['ID'];
    // $dealer_res = $connect->updaterecord($dbconn, 'foshistory', $data, $where);
    mysqli_query($dbconn, "delete from foshistory where foshistoryid=". $_REQUEST['ID']);
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



<?php

function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir . "/" . $object) == "dir")
                    rrmdir($dir . "/" . $object);
                else
                    unlink($dir . "/" . $object);
            }
        }
        reset($objects);
        rmdir($dir);
    }
}
?>								  


