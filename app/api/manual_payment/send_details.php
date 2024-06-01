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
$txn_num = $_POST['TXN_NUM'];
$loan_id = $_POST['REPAY_ID'];

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
    if(!isset($_FILES['PayScreenshot']['name']) || $txn_num=='' || $loan_id=='')
    {
        http_response_code(412);
        echo json_encode(array("message" => "Fields cannot be empty, Upload all three photos together !"));
    }
    else
    {
        //PayScreenshot
        
        $filename = $_FILES['PayScreenshot']['name'];
        $tempname = $_FILES['PayScreenshot']['tmp_name'];
        $filename = md5($id.time()).$filename;
        $path = "../../../upload_doc/PayScreenshot/".$filename;
        move_uploaded_file($tempname,$path);
        $PayScreenshot=$url."upload_doc/PayScreenshot/".$filename;
        
        $date=date("Y-m-d");
        $sql = "UPDATE `loans` SET `repay_txn_id`='$txn_num',`repay_txn_photo`='$PayScreenshot',`pay_status`='3',`pay_date`='$date' WHERE `id`='$loan_id'";
        $insertSuccess = mysqli_query($conn, $sql);
        if($insertSuccess) 
        {
            http_response_code(200);
            echo json_encode(array("message" => "Documents Uploaded Successfully !"));
        }
        else
        {
            http_response_code(404);
            echo json_encode(array("message" => "Something went wrong!","error" => $conn->error));
        }
        
    }
}

;?>