<?php
include_once '../../../config.php';

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"));


    
http_response_code(200);
echo json_encode(array("message" => "Success !","RepaymentUPI" => $row_site_info['repayment_upi'],"RepaymentQR" => $row_site_info['repayment_qr']));


;?>
