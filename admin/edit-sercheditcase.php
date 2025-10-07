<?php
ob_start();
error_reporting(E_ALL);
include_once '../common.php';
$connect = new connect();
include('IsLogin.php');

$result = mysqli_query($dbconn, "SELECT * FROM `application` WHERE `applicationid`='" . $_REQUEST['applicationid'] . "'");
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
} else {
    echo 'somthig going worng! try again';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">

        <link rel="shortcut icon" href="images/favicon.png">
        <title> <?php echo $ProjectName ?> |Edit Case</title>
        <?php include_once './include.php'; ?>       
    </head>

    <body class="page-container-bg-solid page-boxed">
        <?php
        include('header.php');
        ?>
        <div style="display: none; z-index: 10060;" id="loading">
            <img id="loading-image" src="<?php echo $web_url; ?>admin/images/loader1.gif">
        </div>
        <div class="page-container">        

            <div class="page-content">
                <div class="container">                    
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="<?php echo $web_url; ?>admin/index.php">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <a href="<?php echo $web_url; ?>admin/LocationEmployee.php">List Of Case</a>
                            <i class="fa fa-circle"></i>
                        </li>

                        <li>
                            <span> Edit Case</span>

                        </li>
                    </ul>

                    <div class="page-content-inner">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption font-red-sunglo">
                                            <i class="icon-settings font-red-sunglo"></i>
                                            <span class="caption-subject bold uppercase"> Edit Case</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">


                                        <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                            <input type="hidden" value="Editcase" name="action" id="action">
                                            <input type="hidden" value="<?php echo $row['applicationid'] ?>" name="applicationid" id="applicationid">
                                            <div class="form-body">


                                                <div class="form-group col-md-4">
                                                    <label for="form_control_1">Status</label>
                                                    <select class="form-control" name="fos_completed_status" id="fos_completed_status" class="form-control" required="">

                                                        <option value="1" <?php
                                                        if ($row['fos_completed_status'] == '1') {
                                                            echo 'selected';
                                                        }
                                                        ?>>Payment Collected</option>   
                                                        <option value="10" <?php
                                                        if ($row['fos_completed_status'] == '10') {
                                                            echo 'selected';
                                                        }
                                                        ?>>Short Payment</option>
                                                        <option value="11" <?php
                                                        if ($row['fos_completed_status'] == '11') {
                                                            echo 'selected';
                                                        }
                                                        ?>>Penalty collected</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="form_control_1">Payment Collected Amount</label>
                                                    <input value="<?php echo $row['Payment_Collected_Amount']; ?>" name="Payment_Collected_Amount" id="Payment_Collected_Amount"  class="form-control"  type="text" required="">
                                                </div>




                                                <div class="form-group col-md-4">
                                                    <label for="form_control_1">Penal</label>
                                                    <input value="<?php echo $row['penal']; ?>" name="penal" id="penal"  class="form-control"  type="text" required="">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="form_control_1">Total Amount</label><div id="errordiv"></div>
                                                    <input value="<?php echo $row['totalamt']; ?>" name="totalamt" id="totalamt"  class="form-control"  type="text" required="">

                                                </div>

                                            </div>

                                            <div class="form-actions noborder">
                                                <input class="btn blue margin-top-20" type="submit" id="Btnmybtn"  value="Submit" name="submit">      
                                                <button type="button" class="btn blue margin-top-20" onClick="checkclose();">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
    <?php include_once './footer.php'; ?>


    <script type="text/javascript">




        function checkclose() {
            window.location.href = '<?php echo $web_url; ?>admin/sercheditcase.php';
        }

        


        $('#frmparameter').submit(function (e) {

            e.preventDefault();
            var $form = $(this);
            $('#loading').css("display", "block");
            $.ajax({
                type: 'POST',
                url: '<?php echo $web_url; ?>admin/querydata.php',
                data: $('#frmparameter').serialize(),
                success: function (response) {
                    // alert(response);
                    console.log(response);
                    //$("#Btnmybtn").attr('disabled', 'disabled');
                    if (response == 2)
                    {
                        $('#loading').css("display", "none");
                        $("#Btnmybtn").attr('disabled', 'disabled');
                        alert(' Edited Sucessfully.');
                        window.location.href = '<?php echo $web_url; ?>admin/sercheditcase.php';
                    }
                }

            });
        });

    </script>

</body>
</html>
