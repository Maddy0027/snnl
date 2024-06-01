<?php
include '../../../config.php';

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"));

$id = $data->USER_ID;
$bank_name = $data->bank_name;
$ifsc = $data->ifsc;
$acc_no = $data->acc_no;
$city = $data->city;

$sql = "SELECT * FROM users WHERE `id`=$id;";
$result = mysqli_query($conn,$sql);
$resultCheck = mysqli_num_rows($result);
if($resultCheck==0)
{
    http_response_code(412);
    echo json_encode(array("message" => "Wrong User ID"));
}
else
{

$sql = "SELECT * FROM bank_details WHERE user_id='$id';";
$result = mysqli_query($conn,$sql);
$resultCheck = mysqli_num_rows($result);
if($resultCheck == 0)
{
    http_response_code(412);
    echo json_encode(array("message" => "Details Not Added, please add it first to update !"));
}
else if($id==''||$bank_name==''||$ifsc==''||$acc_no==''||$city=='')
{
    http_response_code(412);
    echo json_encode(array("message" => "Fields cannot be empty."));
}
else
{
    $sql = "UPDATE bank_details SET `bank_name`='$bank_name',`ifsc_code`='$ifsc',`acc_no`='$acc_no',`bank_city`='$city' WHERE `user_id`='$id';";
    $insertSuccess = mysqli_query($conn, $sql);
    if($insertSuccess) 
    {
        http_response_code(200);
        echo json_encode(array("message" => "Bank Details Updated Successfully !"));
    }
    else
    {
        http_response_code(404);
        echo json_encode(array("message" => "Something went wrong!","error" => $conn->error));
    }
    
}

}

;?>