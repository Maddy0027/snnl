<?php
include '../config.php';

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"));

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
        $ar=array();
        $ar_name=array();
        while($row=mysqli_fetch_assoc($result))
        {
            array_push($ar,$row['deviceid']);
            if($row['devicename']!='')
            array_push($ar_name,$row['devicename']);
            else
            array_push($ar_name,$row['deviceid']);
        }
        http_response_code(200);
        echo json_encode(array("USER ID" => $uid, "Device ID List" => $ar, "Device Name List" => $ar_name));
        
    }
}
;?>


 