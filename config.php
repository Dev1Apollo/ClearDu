<?php
ob_start();
session_start();
date_default_timezone_set("Asia/Calcutta");
$website_name = "ClearDu";
$ProjectName = "ClearDu";

 if ($_SERVER['SERVER_NAME'] == 'field.cleardu.com' || $_SERVER['SERVER_NAME'] == 'www.field.cleardu.com') {

    $dbhost = "localhost";
    $dbuser = "fieldadmin";
    $dbpass = "URr?Sf3BzbQo";
    $dbname = "cleardu-field";
    $web_url = 'https://' . $_SERVER['SERVER_NAME'] . '/ClearDu/';
    $dbconn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die('Could not connect: ' . mysqli_connect_error());

    $cateperpaging = 50;

    
}
?>
 
