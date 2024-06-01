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
else if($did=='')
{
    http_response_code(404);
    echo json_encode(array("message" => "Device ID cannot be blank"));
}
else
{
    $sql = "SELECT * FROM Devices WHERE deviceid='$did';";
    $result = mysqli_query($conn,$sql);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck > 0)
    {
        http_response_code(412);
        echo json_encode(array("message" => "Device Already Registered With Other User"));
    }
    else
    {
        $sql2 = "SELECT * FROM LastData WHERE deviceid='$did';";
        $result2 = mysqli_query($conn,$sql2);
        $resultCheck2 = mysqli_num_rows($result2);
        if($resultCheck2 == 0)
        {
            http_response_code(404);
            echo json_encode(array("message" => "Wrong Device ID"));
        }
        else
        {
            $sql1= "INSERT INTO LastData(deviceid, userid) VALUES('$did','$uid');";
            $insertSuccess = mysqli_query($conn, $sql1);
            $sql = "INSERT INTO Devices(deviceid, userid) VALUES('$did','$uid');";
            $insertSuccess = mysqli_query($conn, $sql);
            if($insertSuccess)
            {
                http_response_code(200);
                echo json_encode(array("message" => "Device Linked Successfully With User!"));
            }
        }
    }
}
;?>


