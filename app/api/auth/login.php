<?php
include_once '../../../config.php';

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"));

$email = $data->email;
$password = $data->password;

$p=md5($password);

$sql = "SELECT * FROM `users` WHERE email='$email';";
$result = mysqli_query($conn,$sql);
$resultCheck = mysqli_num_rows($result);
$row = mysqli_fetch_assoc($result);

if($row['password']==$p) 
{
    if($row['status']=='0')
    {
        http_response_code(412);
        echo json_encode(array("message" => "Your Account is locked, please contact Bank"));
    }
    else if($row['ver_status']!='1')
    {
        http_response_code(412);
        echo json_encode(array("message" => "Please verify your email to continue. Check your email (spam/inbox) to verify !"));
    }
    else
    {
        $id=$row['id'];
        
        $res_kyc_check=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `kycDocs` WHERE `user_id`='$id';"));
        if($res_kyc_check['aadharF']!='' && $res_kyc_check['aadharB']!='' && $res_kyc_check['pancard']!='' && $res_kyc_check['pan_number']!='')
        $kycUploaded=1;
        else
        $kycUploaded=0;
        
        http_response_code(200);
        echo json_encode(array("message" => "Login Successful.","USER_ID" => $row['id'], "First Name" => $row['f_name'], "Last Name" => $row['l_name'], "Email" => $row['email'], "Mobile Number" => $row['mobile'], "kycDocUploadSt" => $kycUploaded));
    }
} 
else
{
    http_response_code(412);
    echo json_encode(array("message" => "Wrong email or password"));
}

;?>


