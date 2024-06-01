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
    $row=mysqli_fetch_assoc($result);
    http_response_code(200);
    echo json_encode(array("message" => "Successful.","USER_ID" => $row['id'], "First Name" => $row['f_name'], "Last Name" => $row['l_name'], "Contact Us Email" => $row_site_info['contact_us_email'], "KYC STATUS" => $row['kyc_status']));
}
;?>


