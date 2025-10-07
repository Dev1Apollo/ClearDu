<?php
error_reporting(0);
include('../common.php');
include('IsLogin.php');
$connect = new connect();
//include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {


    $filterstr = "SELECT * FROM `application` ";


    $resultfilter = mysqli_query($dbconn, $filterstr);
    if (mysqli_num_rows($resultfilter) > 0) {
        $i = 1;
        ?>  
       
        <?php
        // echo "<h1> Total Record is :". $totalrecord . "</h1>";
        ?>
        <a  class="btn blue" onClick="javascript: return deletedata('Delete');"   title="Move To">Auto Assign</a>

       
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
}//list user

if ($_REQUEST['action'] == 'Delete') {
$date=date('d-m-Y H:i:s');
  $querybefor = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*) as TotalRow FROM  `application` where fosid > 0"));
    $count1 = $querybefor['TotalRow'];
   
  //  $query = mysqli_query($dbconn, "update application set application.is_assignto_fos='1',assign_fos_datetime='".$date."' , application.fosid = (select colleted_application.fosid from colleted_application WHERE application.Account_No = colleted_application.Account_No and colleted_application.agencyid=application.agencyid order by colleted_application.applicationid desc limit 1)   WHERE  fosid = 0");
    $application=mysqli_query($dbconn, "select * from  application where  is_assignto_fos='0' and fosid = 0");
    while($row=  mysqli_fetch_assoc($application)){
        $Collectedapp=mysqli_query($dbconn,"select colleted_application.fosid from colleted_application WHERE  colleted_application.Account_No='".$row['Account_No']."' and colleted_application.agencyid='".$row['agencyid']."' order by colleted_application.applicationid desc limit 1");
        if(mysqli_num_rows($Collectedapp)> 0){
            $Collectedapps=  mysqli_fetch_assoc($Collectedapp);
            $query = mysqli_query($dbconn, "update application set application.is_assignto_fos='1',assign_fos_datetime='".$date."' , application.fosid = '".$Collectedapps['fosid']."'   WHERE  fosid = 0 and applicationid='".$row['applicationid']."'");
        }
        
    }
   
    $queryafter = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*) as TotalRow FROM  `application` where fosid > 0"));
    $count2 = $queryafter['TotalRow'];

    $totalupdated = $count2 - $count1;  
    $query1 = mysqli_query($dbconn, "update application set application.is_assignto_fos='0' WHERE  fosid = 0");
   //echo "update application set application.is_assignto_as='1',assign_fos_datetime='".$date."' , application.fosid = (select colleted_application.fosid from colleted_application WHERE application.Account_No = colleted_application.Account_No order by colleted_application.applicationid desc limit 1)   WHERE  fosid = 0";
    echo 'total Assign Cases : '. $totalupdated; 
  
    }
    ?>
   

