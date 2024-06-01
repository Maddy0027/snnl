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
$ts1 = $data->tsmp1;
$ts2 = $data->tsmp2;


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
    if($resultCheck < 0)
    {
        http_response_code(412);
        echo json_encode(array("message" => "Device and users are not linked!"));
    }
    else
    {
        $sql1= "SELECT * FROM Data WHERE deviceid='$did' and ser_tsmp>='$ts1' and ser_tsmp<='$ts2';";
        $insertSuccess = mysqli_query($conn, $sql1);
        $ar=array();
        while($row=mysqli_fetch_assoc($insertSuccess))
        {
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
            $ts=$row['ser_tsmp'];
            $ud=$row['updationDate'];
         
            array_push($ar,array("message" => "Device extracted Successfully!", "user id" => $uid, "device id" => $did, "temperature" => $temp, "humidity" => $hum , "Online status" => $on, "Battery Percentage" => $batper, "Power" => $power, "Error Code" => $error, "deHumidifier" => $dehum, "humidifier" => $humi, "heater" => $hea , "ac" => $ac, "Time Stamp" => $ts , "Updation Date" => $ud));
        }
        
        http_response_code(200);
        echo json_encode($ar);
        
    }
}
;?>


