<?php
error_reporting(0);
include('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {
    $where = "where 1=1";
    if (isset($_REQUEST['Location'])) {
        if ($_POST['Location'] != '') {

            //$where.=" and  locationemployeelocation.iLocationId='" . $_POST['Location'] . "'";
            $where .= " and `statemanager`.`iStateManagerId` in (SELECT iStatemanagerId FROM `statemanagerlocation` where `statemanagerlocation`.`iLocationId`  = '" . $_POST['Location'] . "')";
        }
    }

    if (isset($_REQUEST['Search_Txt'])) {
        if ($_POST['Search_Txt'] != '') {

            $where.=" and  employeeName like '%$_POST[Search_Txt]%'";
        }
    }

    $filterstr = "SELECT * FROM `statemanager`  " . $where . " and isDelete='0'  and  istatus='1' order by  iStateManagerId desc";
    $countstr = "SELECT count(*) as TotalRow FROM `statemanager`  " . $where . " and isDelete='0' and  istatus='1' ";

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
                    <th class="all">Employee Name</th>
                    <th class="desktop">Details</th>
                    <th class="desktop">Login Id</th>
                    <th class="desktop">Assign Branch</th>
                    <th class="desktop">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                    ?>
                    <tr>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['strEmployeeName']; ?> 
                            </div>
                        </td> 


                        <td>
                            <div class="form-group form-md-line-input "><?php
                                if ($rowfilter['strEmail'] != '' && $rowfilter['strPhoneNo'] != '' && $rowfilter['strMobileNo'] != '') {
                                    echo 'E:' . $rowfilter['strEmail'] . ' <br>P:' . $rowfilter['strPhoneNo'] . ' <br>M:' . $rowfilter['strMobileNo'];
                                } else if ($rowfilter['strEmail'] != '' && $rowfilter['phone'] != '') {
                                    echo 'E:' . $rowfilter['strEmail'] . ' <br>P:' . $rowfilter['phone'];
                                } else if ($rowfilter['strPhoneNo'] != '' && $rowfilter['strMobileNo'] != '') {
                                    echo 'P:' . $rowfilter['strPhoneNo'] . ' <br>M:' . $rowfilter['strMobileNo'];
                                } else if ($rowfilter['strEmail'] != '') {
                                    echo 'E:' . $rowfilter['strEmail'];
                                } else if ($rowfilter['strPhoneNo'] != '') {
                                    echo 'P:' . $rowfilter['strPhoneNo'];
                                } else if ($rowfilter['strMobileNo'] != '') {
                                    echo 'M:' . $rowfilter['strMobileNo'];
                                } else {
                                    echo '<center>-</center>';
                                }
                                ?>

                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['loginId']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                $location = "SELECT * FROM `statemanagerlocation`  where isDelete='0'  and  istatus='1' and iStatemanagerId='" . $rowfilter['iStateManagerId'] . "'";
                                $resultL = mysqli_query($dbconn, $location);
                                $count = 1;
                                while ($rowl = mysqli_fetch_array($resultL)) {
                                    $Category = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `location`  where isDelete='0'  and  istatus='1' and locationId='" . $rowl['iLocationId'] . "' order by locationId asc"));
                                    if ($count == 1) {
                                        echo $Category['locationName'];
                                    } else {
                                        echo ', ' . $Category['locationName'];
                                    }
                                    $count++;
                                }
                                ?>   
                            </div>
                        </td>

                                                                                                                        <!--                        <td>
                                                                                                                                                    <div class="form-group form-md-line-input "><?php echo $rowfilter['strEntryDate']; ?> 
                                                                                                                                                    </div>
                                                                                                                                                </td> -->
                        <td style="width: 20%">
                            <div class="form-group form-md-line-input">

                                <a  class="btn blue" href="<?php echo $web_url; ?>admin/statemanagerChangePassword.php?token=<?php echo $rowfilter['iStateManagerId']; ?>" title="Change Password"><i class="fa-key fa"></i></a>
                                <a  class="btn blue" href="<?php echo $web_url; ?>admin/Editstatemanager.php?token=<?php echo $rowfilter['iStateManagerId']; ?>" title="Edit"><i class="fa fa-edit iconshowFirst"></i></i></a>
                                <a  class="btn blue" onClick="javascript: return deletedata('Delete', '<?php echo $rowfilter['iStateManagerId']; ?>');"   title="Delete"><i class="fa fa-trash-o iconshowFirst"></i></a>
                                <a  class="btn blue" href="<?php echo $web_url;?>admin/statemanagerlocationassign.php?token=<?php echo $rowfilter['iStateManagerId']; ?>" title="Edit"><i class="fa fa-plus iconshowFirst"></i></i></a>
                            </div>
                        </td>

            <?php
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
    $where = ' where  	iStateManagerId=' . $_REQUEST['ID'];
    $dealer_res = $connect->updaterecord($dbconn, 'statemanager', $data, $where);
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


