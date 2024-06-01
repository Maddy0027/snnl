<?php
include '../../../config.php';

//upload_doc

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



if (!isset($_POST['USER_ID'])) 
{
    http_response_code(412);
    echo json_encode(array("message" => "Please provide User ID !"));
    die();
}

$id = $_POST['USER_ID'];
$pan_num = $_POST['PAN_NUM'];

$sql = "SELECT * FROM users WHERE `id`=$id;";
$result = mysqli_query($conn,$sql);
$resultCheck = mysqli_num_rows($result);
if($resultCheck==0)
{
    http_response_code(412);
    echo json_encode(array("message" => "Wrong User ID"));
}
else
{
    $sql = "SELECT * FROM kycDocs WHERE user_id='$id';";
    $result = mysqli_query($conn,$sql);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck > 0)
    {
        http_response_code(412);
        echo json_encode(array("message" => "Documents Already Added !"));
    }
    else if(!isset($_FILES['uploadAadharF']['name']) || !isset($_FILES['uploadAadharB']['name']) || !isset($_FILES['uploadPan']['name']) || $pan_num=='')
    {
        http_response_code(412);
        echo json_encode(array("message" => "Fields cannot be empty, Upload all three photos together !"));
    }
    else
    {
        //aadharF
        
        $filename = $_FILES['uploadAadharF']['name'];
        $tempname = $_FILES['uploadAadharF']['tmp_name'];
        $filename = md5($id.time()).$filename;
        $path = "../../../upload_doc/aadharF/".$filename;
        move_uploaded_file($tempname,$path);
        $aadharF=$url."upload_doc/aadharF/".$filename;
        
        //aadharB
        
        $filename = $_FILES['uploadAadharB']['name'];
        $tempname = $_FILES['uploadAadharB']['tmp_name'];
        $filename = md5($id.time()).$filename;
        $path = "../../../upload_doc/aadharB/".$filename;
        move_uploaded_file($tempname,$path);
        $aadharB=$url."upload_doc/aadharB/".$filename;
        
        //pancard
        
        $filename = $_FILES['uploadPan']['name'];
        $tempname = $_FILES['uploadPan']['tmp_name'];
        $filename = md5($id.time()).$filename;
        $path = "../../../upload_doc/pancard/".$filename;
        move_uploaded_file($tempname,$path);
        $pancard=$url."upload_doc/pancard/".$filename;

        $filename = $_FILES['uploadSelfie']['name'];
        $tempname = $_FILES['uploadSelfie']['tmp_name'];
        $filename = md5($id.time()).$filename;
        $path = "../../../upload_doc/selfie/".$filename;
        move_uploaded_file($tempname,$path);
        $selfie =$url."upload_doc/selfie/".$filename;        
        
        
        //passbook
        if(isset($_FILES['uploadPassbook']['name']))
        {
        $filename = $_FILES['uploadPassbook']['name'];
        $tempname = $_FILES['uploadPassbook']['tmp_name'];
        $filename = md5($id.time()).$filename;
        $path = "../../../upload_doc/passbook/".$filename;
        move_uploaded_file($tempname,$path);
        $passbook=$url."upload_doc/passbook/".$filename;
        }
        else
        $passbook="";
        
        $sql = "INSERT INTO kycDocs(`user_id`,`aadharF`,`aadharB`,`pancard`,`pan_number`,`passbook`,`selfie`) VALUES('$id','$aadharF','$aadharB','$pancard','$pan_num','$passbook','$selfie');";
        $insertSuccess = mysqli_query($conn, $sql);
        if($insertSuccess) 
        {
            http_response_code(200);
            echo json_encode(array("message" => "Documents Uploaded Successfully !"));
        }
        else
        {
            http_response_code(404);
    $error_message = mysqli_error($conn);

    // Log the error to a file
    error_log("SQL Error: " . $error_message, 3, "error.log");  // 3 means append to the log file

    echo json_encode(array("message" => "Something went wrong! Check the server logs for details."));
        }
        
    }
}

;?>