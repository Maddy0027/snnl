<?php
include '../config.php';

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$data=json_decode(file_get_contents("php://input"));
$did=$data->dvid;
$uid=$data->usid;
$dname=$data->dname;
$sql="SELECT * FROM Users WHERE id='$uid';";
$result=mysqli_query($conn,$sql);
$resultcheck=mysqli_num_rows($result);
if($resultcheck<1)
{
    http_response_code(404);
    echo json_encode(array("message"=>"Wrong UserId"));
}
else if($did=='')
{
    http_response_code(404);
    echo json_encode(array("message"=>"Device Id Cannot Be Blank"));
}
else
{
$sql="SELECT * FROM Devices WHERE userid='$uid' AND deviceid='$did';";
$result=mysqli_query($conn,$sql);
$resultcheck=mysqli_num_rows($result);
if($resultcheck <1)
{
    http_response_code(404);
    echo json_encode(array("message"=>"Device Id And User Id Is Not Linked"));
}
else
{
    $sql="UPDATE Devices SET `devicename`='$dname' WHERE userid='$uid' AND deviceid='$did';";
    $insertSuccess=mysqli_query($conn,$sql);
    if($insertSuccess)
        {
            http_response_code(200);
            echo json_encode(array("message" => "Data Updated"));
        }
        else
        {
            http_response_code(412);
            echo json_encode(array("message" => "Error"));
        }
    
}
}
?>
