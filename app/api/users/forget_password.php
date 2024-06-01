<?php
include_once '../../../config.php';

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"));

$email = $data->email;

$p=md5($p);

$sql = "SELECT * FROM `users` WHERE `email`='$email';";
$result = mysqli_query($conn,$sql);
$resultCheck = mysqli_num_rows($result);
if($resultCheck == 0)
{
    http_response_code(412);
    echo json_encode(array("message" => "Wrong User Email"));
}
else
{
    http_response_code(200);
    echo json_encode(array("message" =>"Please check your e-mail (spam/inbox) to change your password !"));
    
}
;?>
