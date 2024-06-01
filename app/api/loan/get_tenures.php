<?php
include_once '../../../config.php';

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"));

$id = $data->USER_ID;

$sql = "SELECT * FROM `users` WHERE id='$id';";
$result = mysqli_query($conn,$sql);
$resultCheck = mysqli_num_rows($result);
if($resultCheck == 0)
{
    http_response_code(412);
    echo json_encode(array("message" => "Wrong User ID"));
}
else
{
    $row=mysqli_fetch_assoc($result);
    $withdrawal_limit=$row['withdrawal_limit'];
    $sql = "SELECT * FROM tenures;";
    $result = mysqli_query($conn,$sql);
    $output=array();
    while($row=mysqli_fetch_assoc($result))
    {
        array_push($output, array("days" => $row['days'],"charges" => $row['charges']));
        
    }
    
    $sql = "SELECT * FROM `loans` WHERE `user_id`='$id' AND `pay_status`='0' ORDER BY id DESC;";
    $result = mysqli_query($conn,$sql);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck == 0)
    {
        $already_flag=0;
    }
    else
    {
        $already_flag=1;
    }
    http_response_code(200);
    echo json_encode(array("DUE STATUS"=>$already_flag,"withdrawal_limit"=>$withdrawal_limit,"tenures"=>$output));
}
;?>
