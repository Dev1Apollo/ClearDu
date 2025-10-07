<?php
error_reporting(0);
include('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {

    $filterstr = "SELECT * FROM `applicationlog` where app_id='" . $_REQUEST['applicationid'] . "'  ORDER BY `applicationlog`.`applicationlogid` DESC ";
    // $filterstr = "SELECT * FROM `applicationlog` where appId='" . $_REQUEST['appID'] . "' order by applicationLogId desc";
    $countstr = "SELECT count(*) as TotalRow * FROM `applicationlog` where app_id='" . $_REQUEST['applicationid'] . "'  ORDER BY `applicationlog`.`applicationlogid` DESC ";

    $resrowcount = mysqli_query($dbconn,$countstr);
    $resrowc = mysqli_fetch_array($resrowcount);
    $totalrecord = $resrowc['TotalRow'];
    $per_page = $cateperpaging;
    $total_pages = ceil($totalrecord / $per_page);
    $page = $_REQUEST['Page'] - 1;
    $startpage = $page * $per_page;
    $show_page = $page + 1;



    $filterstr = $filterstr . " LIMIT $startpage, $per_page";
// echo $filterstr;


    $resultfilter = mysqli_query($dbconn,$filterstr);
    if (mysqli_num_rows($resultfilter) > 0) {
        $i = 1;
        ?>  
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />

        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="tableC">

            <thead class="tbg">
                <tr>
                    <th class="desktop">ALPS ID</th>
                    <th class="desktop">Employee Type</th>
<!--                    <th class="desktop">Type</th>-->
                    <th class="desktop">Name</th>
                    <th class="desktop">Application Process</th>
                    <th class="desktop">Enter Date</th>

                </tr>
            </thead>
            <tbody>
                <?php
                while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                    ?>
                    <tr>
                        <td>
                            <div class="form-group form-md-line-input ">
                                <?php
                              $name = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `agencymanager`  where agencymanagerid='" . $rowfilter['emp_id'] . "'"));
                                $app = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `application`  where applicationid='" . $rowfilter['app_id'] . "'"));
                                echo $app['uniqueId'];
                                ?>
                            </div>
                        </td> 
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['emp_type']; ?> 
                            </div>
                        </td> 
                     
<!--                         <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['EmployeeName']; ?>
                            </div>
                        </td>-->
                        
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $name['employeename']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['action_name']; ?> 
                            </div>
                        </td>
                           <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['strEntryDate']; ?>
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
        <script>
            $(document).ready(function () {
                $('#tableC').DataTable({
                });
            });
        </script>
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



<?