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
$temp = $data->setTemp;
$hum = $data->setHum;


$sql = "SELECT * FROM Users WHERE id=$uid;";
$result = mysqli_query($conn,$sql);
$resultCheck = mysqli_num_rows($result);
if($resultCheck < 1)
{
    http_response_code(404);
    echo json_encode(array("message" => "Wrong User ID"));
}
else if($did=='')
{
    http_response_code(404);
    echo json_encode(array("message" => "Device ID cannot be blank"));
}
else
{
    $sql = "SELECT * FROM Devices WHERE deviceid='$did' AND userid='$uid';";
    $result = mysqli_query($conn,$sql);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck > 0)
    {
        $sql = "UPDATE Devices SET `set_temp`='$temp',`set_hum`='$hum' WHERE deviceid='$did' AND userid='$uid'";
        $insertSuccess = mysqli_query($conn, $sql);
        if($insertSuccess)
        {
            http_response_code(200);
            echo json_encode(array("message" => "Data Updated"));
        }
        else
        if($insertSuccess)
        {
            http_response_code(412);
            echo json_encode(array("message" => "Error"));
        }
    }
    else
    {
        
            http_response_code(404);
            echo json_encode(array("message" => "Device and user are not linked!"));
        
    }
}
;?>


