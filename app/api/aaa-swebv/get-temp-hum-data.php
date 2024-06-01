<?php
include '../config.php';

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"));

$did = $data->dvid;
$uid = $data->usid;

$sql = "SELECT * FROM Users WHERE id= '$uid';";
$result = mysqli_query($conn,$sql);
$resultCheck = mysqli_num_rows($result);
if($resultCheck < 1)
{
    http_response_code(404);
    echo json_encode(array("message" => "Wrong User ID"));
}
else
{
    $sql = "SELECT * FROM Devices WHERE userid='$uid';";
    $result = mysqli_query($conn,$sql);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck == 0)
    {
        http_response_code(412);
        echo json_encode(array("message" => "No Device connected !"));
    }
    else
    {
    $sql = "SELECT * FROM Devices WHERE userid='$uid' AND deviceid='$did' ;";
    $result = mysqli_query($conn,$sql);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck < 1)
    {
    http_response_code(404);
    echo json_encode(array("message" => "Wrong User ID"));
    }
    else
    {
        $row=mysqli_fetch_assoc($result);
        $mt=$row['set_temp'];
        $mh=$row['set_hum'];
        http_response_code(200);
        echo json_encode(array("set Temp" =>$mt,"Set Humidity" =>$mh));
    }
}
}
;?>


