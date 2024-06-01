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
    $sql = "SELECT * FROM Devices WHERE deviceid='$did' AND userid='$uid';";
    $result = mysqli_query($conn,$sql);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck == 0)
    {
        http_response_code(412);
        echo json_encode(array("message" => "Device and users are not linked!"));
    }
    else
    {
        $sql1= "SELECT * FROM LastData WHERE deviceid='$did';";
        $insertSuccess = mysqli_query($conn, $sql1);
        $row=mysqli_fetch_assoc($insertSuccess);
        
            
        $did=$row['deviceid'];
        $temp=$row['temperature'];
        $hum=$row['humidity'];
        $on=$row['online'];
        $power=$row['power'];
        $error=$row['errorcode'];
        $dehum=$row['deHumidifier'];
        $humi=$row['humidifier'];
        $ac=$row['ac'];
        $hea=$row['heater'];
        $ts=$row['timestamp'];
        $ud=$row['updationDate'];
        $batper=$row['bat_per'];
        $st=$row['ser_tsmp'];
        
        $sql2= "SELECT * FROM Devices WHERE deviceid='$did';";
        $res2 = mysqli_query($conn, $sql2);
        $row2=mysqli_fetch_assoc($res2);
        $dname=$row2['devicename'];
	  	
        http_response_code(200);
        echo json_encode(array("message" => "Device extracted Successfully!", "device name"=>"$dname", "user id" => $uid, "device id" => $did, "temperature" => $temp, "humidity" => $hum , "Online status" => $on, "Battery Percentage" => $batper, "Power" => $power, "Error Code" => $error, "deHumidifier" => $dehum, "humidifier" => $humi, "heater" => $hea , "ac" => $ac, "Time Stamp" => $ts ,"Server Side Timestamp"=> $st , "Updation Date" => $ud));
        
    }
}
;?>


