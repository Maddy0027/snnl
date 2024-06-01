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
    $row_users= mysqli_fetch_assoc($result);
    $sql = "SELECT * FROM `loans` WHERE `user_id`='$id' ORDER BY id DESC limit 6;";
    $result = mysqli_query($conn,$sql);
    $output=array();
    $count=mysqli_num_rows($result);
    while($row=mysqli_fetch_assoc($result))
    {
        $total=$row['loan_amount']+$row['charges']+$row['late_fees'];
        
        if($row['rec_status']==0)
        {
            $status=0; //admin pay ni kia hai
        }
        else if($row['pay_status']==1)
        {
            $status=1; //completed
        }
        else if($row['pay_status']==2)
        {
            $status=2; //declined
        }
        else if($row['pay_status']==3)
        {
            $status=3; //pending confirmation from admin
        }
        else if($row['pay_status']==0)
        {
            $status=4; //pending payment
        }
        array_push($output, array("REPAY ID"=>$row['id'],"amount_asked"=>$row['loan_amount'],"charges"=>$row['charges'],"due_date"=>$row['due_date'],"Late Fees"=>$row['late_fees'],"PAID STATUS"=>$row['rec_status'],"REPAY STATUS"=>$row['pay_status'],"Total Payable"=>$total,"status"=>$status));
    }
    $kycStatus= $row_users['kyc_status'];
    http_response_code(200);
    echo json_encode((array("KYC STATUS"=>$kycStatus,"count"=>$count,"WithdrawalHistory"=>$output)));
}
;?>
