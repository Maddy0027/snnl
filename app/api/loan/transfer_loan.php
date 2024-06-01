<?php
include_once '../../../config.php';

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"));

$id = $data->USER_ID;
$ts = $data->tenure_selected;
$charges = $data->charges;
$amount = $data->amount_asked;
$dueDate = $data->due_date;
$total=$amount-$charges;
$sql = "SELECT * FROM `users` WHERE id='$id';";
$result = mysqli_query($conn,$sql);
$resultCheck = mysqli_num_rows($result);
$date = date("Y-m-d");
if($resultCheck == 0)
{
    http_response_code(412);
    echo json_encode(array("error"=>"1","message" => "Wrong User ID"));
}
else if($ts==''||$charges==''||$amount=='')
{
    http_response_code(412);
    echo json_encode(array("error"=>"1","message" => "Fields Cannot be empty"));
}
else
{
    $row=mysqli_fetch_assoc($result);
    $withdrawal_limit=$row['withdrawal_limit'];
    
    
    if($amount>$withdrawal_limit)
    {
        http_response_code(412);
        echo json_encode(array("error"=>"1","message" => "Amount exceeds the withdrawal limit!"));
    }
    else
    {
        $sql = "SELECT * FROM `loans` WHERE `user_id`='$id' AND `pay_status`='0' ORDER BY id DESC;";
        $result = mysqli_query($conn,$sql);
        $resultCheck = mysqli_num_rows($result);
        if($resultCheck == 0)
        {
            if(mysqli_query($conn,"INSERT INTO loans(`user_id`,`loan_amount`,`loan_date`,`tenure`,`due_date`,`late_fees`,`charges`,`total`,`pay_status`,`rec_status`) VALUES('$id','$amount','$date','$ts','$dueDate','0','$charges','$total','0','0');"))
            {
                http_response_code(200);
                echo json_encode(array("error"=>"0","message"=>"Loan amount successfully requested!"));
            }
            else
            {
                http_response_code(412);
                echo json_encode(array("error"=>$conn->error,"Something went wrong!"));
            }
        }
        else
        {
            http_response_code(412);
            echo json_encode(array("error"=>"1","message"=>"Please repay your old loan first!"));
        }
        
    }
}
;?>
