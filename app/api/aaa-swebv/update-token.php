<?php
include '../config.php';

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"));

$uid = $data->usid;
$token = $data->token;

$sql = "SELECT * FROM Users WHERE id=$uid;";
$result = mysqli_query($conn,$sql);
$resultCheck = mysqli_num_rows($result);
if($resultCheck < 1)
{
    http_response_code(404);
    echo json_encode(array("message" => "Wrong User ID"));
}
else if($token!="")
{
    $sql= "INSERT INTO token (user_id,token) VALUES ('$uid','$token');";
    $result = mysqli_query($conn,$sql);
    if($result)
    {
        http_response_code(200);
        echo json_encode(array("message" => "Token Updated Successfully"));
    }
    else
    {
        http_response_code(412);
        echo json_encode(array("message" => "Something Went Wrong"));
    }
   
}
;?>


