<?php
ob_start();
error_reporting(E_ALL);
include_once '../common.php';
$connect = new connect();
include('IsLogin.php');
?>
<!DOCTYPE html>

<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title><?php echo $ProjectName; ?> |Return Case</title>
        <?php include_once './include.php'; ?>

    </head>
    <body class="page-container-bg-solid page-boxed">
        <?php include_once './header.php'; ?>
        <div style="display: none; z-index: 10060;" id="loading">
            <img id="loading-image" src="<?php echo $web_url; ?>Employee/images/loader1.gif">
        </div>
        <div class="page-container">        
            <div class="page-content-wrapper">

                <div class="page-content">
                    <div class="container">
                        <ul class="page-breadcrumb breadcrumb">
                            <li>
                                <a href="<?php echo $web_url; ?>Employee/index.php">Home</a>
                                <i class="fa fa-circle"></i>
                            </li>

                            <li>
                                <span>Return Case</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">
                            <div class="portlet light ">

                                <div class="portlet-title">
                                    <div class="caption font-red-sunglo">
                                        <i class="icon-settings font-red-sunglo"></i>
                                        <span class="caption-subject bold uppercase">List of Case</span>
                                    </div>
                                </div>
                                <div class="portlet-body form">
                                        
                               
                                    
                                            <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                            <input type="hidden" value="TestUploadApplicationAdminEmployee" name="action" id="action">



                                            <div class="form-body">

                                                <div class=" col-md-12">
<!--                                                    <div class="form-group">
                                                        <label for="exampleInputFile1">Excel File Name</label><br />
                                                        <input type="text"  id="name" name="name"  required=""/>

                                                    </div>-->
                                                  
                                                    <div class="form-group">
                                                        <label for="exampleInputFile1">Excel File Upload</label><br />
                                                        <input type="file"  id="gallery" name="gallery" class="btn blue" required=""/>
                                                        <input type="hidden" name="galeryID" ID="galeryID" />
                                                    </div>
                                                    <div id="ImageGallery" style="display:none;">  </div>
                                                </div>    

                                            </div>

                                            <div class="form-actions noborder">
                                                <input class="btn blue " type="submit" id="Btnmybtn"  value="Submit" name="submit">      
                                                <button type="button" class="btn blue " onClick="checkclose();">Cancel</button>
                                            </div>

                                        </form>
                                        
                                           <div class="col-md-6">
                                        <h4 style="color : #f03f2a; font-weight: bold" id="errorlog">

                                        </h4>
                                    </div>
                                    
                                    
                                    
                                    <div id="PlaceUsersDataHere">
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
                window.location.href = 'TestCEUploadApplication.php';
            }


            $('#frmparameter').submit(function (e) {

                e.preventDefault();
                var $form = $(this);
                $('#loading').css("display", "block");
                $.ajax({
                    type: 'POST',
                    url: '<?php echo $web_url; ?>Employee/bulkassign-fos-excel-upload.php',
                    data: $('#frmparameter').serialize(),
                    success: function (response) {
                        console.log(response);
                        $('#loading').css("display", "none");
                      //alert(response);
                        if (response == 0)
                        {
                            $('#loading').css("display", "none");
                            alert('Upload Application add Sucessfully.');
                            //window.location.href = 'asreturncase-excel.php';
                            
                        } else {
                            $('#loading').css("display", "none");
                            $("#errorlog").html(response);
                            // window.location.href = '';
                        }
                    }

                });
            });




            $(document).ready(function ()
            {
                $("#gallery").on('change', function ()
                {
                     $('#loading').css("display", "block");
                    var galeryID = 0;
                    galeryID = galeryID + 1;

                    $("#galeryID").val(galeryID);
                    $("#ImageGallery").html('<img src="<?php echo $web_url; ?>Employee/images/loader1.gif" alt="Uploading...."/>');

                    var formData = new FormData($('form#frmparameter')[0]);
                    $.ajax({
                        type: "POST",
                        url: "<?php echo $web_url; ?>Employee/uploadExcelTemp.php",
                        processData: false,
                        contentType: false,
                        data: formData,
                        success: function (msg) {
                             $('#loading').css("display", "none");
                            $("#ImageGallery").show();
                            $("#ImageGallery").html(msg);

                        },
                    });
                    // $("#addTreatmentForm").attr('action', '');
                });
            });

        </script>       
        
        
       
     
 
      
    </body>
</html>