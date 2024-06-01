<?php
include_once 'con.php';

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



$data = json_decode(file_get_contents("php://input"));

$uid=$data->user_id;
$did=$data->device_id;
$temp=$data->temperature;
$hum=$data->humidity;
$on=$data->online;
$bp=$data->batteryPercentage;
$p=$data->power;
$ec=$data->errorCode;
$dh=$data->exteranlDeviceStatus->deHumidifier;
$h=$data->exteranlDeviceStatus->humidifier;
$ac=$data->exteranlDeviceStatus->ac;
$heater=$data->exteranlDeviceStatus->heater;


$gdid=$_GET['deviceid'];
$guid=$_GET['userid'];

if(($gdid!='')||($guid!=''))
{
    if(($gdid!=''))
    {
        $sql = "SELECT * FROM LastData WHERE deviceid='$gdid';";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);
        echo json_encode(array("user_id" => $row['userid'], "device_id" => $row['deviceid'], "temperature" => $row['temperature'], "humidity" => $row['humidity'], "online" => $row['online'], "batteryPercentage" => $row['batteryper'], "power" => $row['power'], "errorCode" => $row['errorcode'], "deHumidifier" => $row['deHumidifier'], "humidifier" => $row['humidifier'], "ac" => $row['ac'], "heater" => $row['heater']));
    }
    else
    {
        $sql = "SELECT * FROM LastData WHERE userid='$guid';";
        $result = mysqli_query($conn,$sql);
        $c=1;
        while($row=mysqli_fetch_assoc($result))
        {
            echo json_encode(array("S.No." => $c, "user_id" => $row['userid'], "device_id" => $row['deviceid'], "temperature" => $row['temperature'], "humidity" => $row['humidity'], "online" => $row['online'], "batteryPercentage" => $row['batteryper'], "power" => $row['power'], "errorCode" => $row['errorcode'], "deHumidifier" => $row['deHumidifier'], "humidifier" => $row['humidifier'], "ac" => $row['ac'], "heater" => $row['heater']));
            $c++;
        }
    }
}
else
{
    $sql = "SELECT * FROM Devices WHERE deviceid='$did';";
    $result = mysqli_query($conn,$sql);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck > 0) 
    {
        $row = mysqli_fetch_assoc($result);
        if($row['userid']==$uid)
        {
            $sql1="UPDATE `LastData` SET `userid`='$uid',`deviceid`='$did',`temperature`='$temp',`humidity`='$hum',`online`='$on',`batteryper`='$bp',`power`='$p',`errorcode`='$ec',`deHumidifier`='$dh',`humidifier`='$h',`ac`='$ac',`heater`='$heater' WHERE deviceid=$did ";
	  	    $result1=mysqli_query($conn, $sql1);
	  	    //to update last data ^^^^^^
            $sql = "INSERT INTO Data(userid, deviceid, temperature, humidity, online, batteryper, power, errorcode, deHumidifier, humidifier, ac, heater) VALUES('$uid','$did','$temp','$hum','$on','$bp','$p','$ec','$dh','$h','$ac','$heater');";
            $insertSuccess = mysqli_query($conn, $sql);
            if($insertSuccess)
            {
                http_response_code(200);
                echo json_encode(array("message" => "Data Entered Successfully"));
            }
            else
            {
                http_response_code(412);
                echo json_encode(array("message" => "Error"));
            }
        }
        else
        {
            http_response_code(412);
            echo json_encode(array("message" => "Device and user is not linked"));
        }
    }
    else
    {
        http_response_code(404);
        echo json_encode(array("message" => "Wrong DeviceID"));
    }
}
?>