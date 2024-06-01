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
$type = $data->type;

$ts1='0';
$ts2='9999999999';
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
        if($type=='week')
        {
            $sql1= "SELECT * FROM day_avg_data WHERE deviceid='$did' ORDER BY id DESC;";
            $insertSuccess = mysqli_query($conn, $sql1);
            $ar=array();
            $i=1;
            while($row=mysqli_fetch_assoc($insertSuccess))
            {
                if($i>7)
                    break;
                $did=$row['deviceid'];
                $temp=$row['temperature'];
                $hum=$row['humidity'];
                $bp=$row['bat_per'];
                $ts=$row['ser_tsmp'];
                $ud=$row['updationDate'];
             
                array_push($ar,array("Day" => $i, "user id" => $uid, "device id" => $did, "temperature" => $temp, "humidity" => $hum , "Battery Percentage" => $bp, "Time Stamp" => $ts , "Updation Date" => $ud));
                $i=$i+1;
            }
        }
        else if($type=='month')
        {
            $sql1= "SELECT * FROM day_avg_data WHERE deviceid='$did' ORDER BY id DESC;";
            $insertSuccess = mysqli_query($conn, $sql1);
            $ar=array();
            $i=1;
            while($row=mysqli_fetch_assoc($insertSuccess))
            {
                if($i>30)
                    break;
                $did=$row['deviceid'];
                $temp=$row['temperature'];
                $hum=$row['humidity'];
                $bp=$row['bat_per'];
                $ts=$row['ser_tsmp'];
                $ud=$row['updationDate'];
             
                array_push($ar,array("Day" => $i, "user id" => $uid, "device id" => $did, "temperature" => $temp, "humidity" => $hum , "Battery Percentage" => $bp, "Time Stamp" => $ts , "Updation Date" => $ud));
                $i=$i+1;
            }
        }
        else if($type=='3_month')
        {
            $sql1= "SELECT * FROM day_avg_data WHERE deviceid='$did' ORDER BY id DESC;";
            $insertSuccess = mysqli_query($conn, $sql1);
            $ar=array();
            $i=1;
            while($row=mysqli_fetch_assoc($insertSuccess))
            {
                if($i>90)
                    break;
                $did=$row['deviceid'];
                $temp=$row['temperature'];
                $hum=$row['humidity'];
                $bp=$row['bat_per'];
                $ts=$row['ser_tsmp'];
                $ud=$row['updationDate'];
             
                array_push($ar,array("Day" => $i, "user id" => $uid, "device id" => $did, "temperature" => $temp, "humidity" => $hum , "Battery Percentage" => $bp, "Time Stamp" => $ts , "Updation Date" => $ud));
                $i=$i+1;
            }
        }
        else
        {
            $ar=array();
            array_push($ar,array("message" => "Invalid type"));
        }
        http_response_code(200);
        echo json_encode($ar);
        
    }
}
;?>


