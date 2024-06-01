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
    $row_users=mysqli_fetch_assoc($result);
    
    $sql = "SELECT * FROM `loans` WHERE `user_id`='$id' ORDER BY id DESC;";
    $result = mysqli_query($conn,$sql);
    $row=mysqli_fetch_assoc($result);
    
    if($row['pay_status']=='3')
    {
        http_response_code(200);
        echo json_encode(array("message" => "Successful.","USER_ID" => $row['user_id'],"DUE STATUS" => '2',"PAY ID" => $row['id'] ,"Due Date" => $row['due_date'],"LoanAmount" => $row['loan_amount'],"Late Fees" => $row['late_fees'], "Charges" => $row['charges'], "Total" => $row['total'],"Contact Us Email" => $row_site_info['contact_us_email'], "KYC STATUS" => $row_users['kyc_status']));
    }
    else if($row['pay_status'] == '0')
    {
        http_response_code(200);
        echo json_encode(array("message" => "Successful.","USER_ID" => $row['user_id'],"DUE STATUS" => '1',"PAY ID" => $row['id'] ,"Due Date" => $row['due_date'],"LoanAmount" => $row['loan_amount'],"Late Fees" => $row['late_fees'], "Charges" => $row['charges'], "Total" => $row['total'],"Contact Us Email" => $row_site_info['contact_us_email'], "KYC STATUS" => $row_users['kyc_status']));
    }
    else
    {
        http_response_code(200);
        echo json_encode(array("message" => "No Due","USER_ID" => $row_users['id'],"DUE STATUS" => '0',"Contact Us Email" => $row_site_info['contact_us_email'], "KYC STATUS" => $row_users['kyc_status']));
    }
}
;?>
