<?php
include_once '../config.php';

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"));

$email = $data->email;
$password = $data->password;

$p=md5($password);

$sql = "SELECT * FROM Users WHERE email='$email';";
$result = mysqli_query($conn,$sql);
$resultCheck = mysqli_num_rows($result);
$row = mysqli_fetch_assoc($result);

if($row['password']==$p) 
{ 
    http_response_code(200);
    echo json_encode(array("message" => "Login Successful.","USER ID" => $row['id'], "First Name" => $row['first_name'], "Last Name" => $row['last_name'], "Email" => $row['email'], "Mobile Number" => $row['phone']));
} 
else
{
    http_response_code(404);
    echo json_encode(array("message" => "Wrong email or password"));
}

;?>


