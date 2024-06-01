<?php
include_once '../../../config.php';

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"));

$id = $data->USER_ID;

$sql = "SELECT * FROM `users` WHERE id='$id';";
$result = mysqli_query($conn,$sql);
$resultCheck = mysqli_num_rows($result);
if($resultCheck == 0)
{
    http_response_code(412);
    echo json_encode(array("message" => "Wrong User ID"));
}
else
{
    $sql = "SELECT * FROM bank_details WHERE user_id='$id';";
    $result = mysqli_query($conn,$sql);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck == 0)
    {
        http_response_code(200);
        echo json_encode(array("message" => "Bank Not Updated !","Bank Uploaded" => "0"));
    }
    else
    {
        $row_bank= mysqli_fetch_assoc($result);
        $ab = $row_bank['bank_name'];
        $af = $row_bank['ifsc_code'];
        $pc = $row_bank['acc_no'];
        $pn = $row_bank['bank_city'];
        
        http_response_code(200);
        echo json_encode(array("message" => "Success !","Bank Uploaded" => "1","Bank Name" => $ab,"IFSC Code" => $af,"Account Number" => $pc,"Bank City" => $pn));
    }
}
;?>
