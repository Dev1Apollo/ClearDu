<?php
ob_start();
error_reporting(E_ALL);
header('Content-Type: application/json');
include('../common.php');
$connect = new connect();
$request_body = @file_get_contents('php://input');
$obj = json_decode($request_body);

if (count($obj)>0) {
    $data=$obj->data;
   // print_r($data);
    $hashKey='astuteCRM';
    $user=$data->user;
    $randomPSW=$data->randNumber;
    $hash_string=$user.'|'.$randomPSW.'|'.$hashKey;
    $strHash=strtolower(hash('sha512', $hash_string));
    if($data->hash===$strHash){
        $filterstr ="select * from agency where istatus=1 and isDelete=0";
        $result1 = mysqli_query($dbconn, $filterstr);
        if (mysqli_num_rows($result1) > 0) {
            while ($row = mysqli_fetch_assoc($result1)) {
                $output['agecnylist'] [] = $row;
            }
            $output['message'] = 'Data Found';
            $output['success'] = '1';
        }else{
            $output['message'] = 'No Data Found';
        $output['success'] = '0';
        }
    }else{
        $output['message'] = 'Invalid Request';
        $output['success'] = '0';
    }
  
    
    
}else{
    $output['message'] = 'Invalid Request';
    $output['success'] = '0';
}
print(json_encode($output));
?>