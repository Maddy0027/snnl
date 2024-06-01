<?php
include_once '../../../config.php';

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"));
 
$id = $data->REPAY_ID;

$sql = "SELECT * FROM `loans` WHERE id='$id';";
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
    
    if($row['pay_status']=='1')
    {
        http_response_code(412);
        echo json_encode((array("message"=>"Already Paid!")));
        
    }
    else
    {
        $date=date("Y-m-d");
        mysqli_query($conn,"UPDATE `loans` SET `pay_status`='1',`pay_date`='$date' WHERE `id`='$id'");
        http_response_code(200);
        echo json_encode((array("message"=>"Due amount successfully paid!")));
    }
}
;?>
