<?php
include '../config.php';

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"));

$fn = $data->firstname;
$ln = $data->lastname;
$em = $data->email;
$p = $data->password;
$ph=$data->phone;

$pass=md5($p);



$sql = "SELECT * FROM Users WHERE email='$em' OR phone='$ph';";
$result = mysqli_query($conn,$sql);
$resultCheck = mysqli_num_rows($result);
if($resultCheck > 0)
{
    http_response_code(412);
    echo json_encode(array("message" => "Account already existed with Phone Number/ Email"));
}
else if($fn==''||$ln==''||$em==''||$p==''||$ph=='')
{
    http_response_code(404);
    echo json_encode(array("message" => "Fields cannot be empty."));
}
else
{
    $sql = "INSERT INTO Users(first_name, last_name, email, password, phone) VALUES('$fn','$ln','$em','$pass','$ph');";
    $insertSuccess = mysqli_query($conn, $sql);
    if($insertSuccess) 
    {
        http_response_code(200);
        echo json_encode(array("message" => "Successfully Registered.", "First Name" => $fn, "Last Name" => $ln, "Email" => $em, "Mobile Number" => $ph));
    }
    
}


;?>