<?php
include '../../../config.php';

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"));

$fn = $data->firstname;
$ln = $data->lastname;
$em = $data->email;
$p = $data->password;
$ph=$data->phone;
$pass=md5($p);
$fc1=$data->f_contact1;
$fc2=$data->f_contact2;

$ver = md5($em);

$sql = "SELECT * FROM users WHERE email='$em' OR mobile='$ph';";
$result = mysqli_query($conn,$sql);
$resultCheck = mysqli_num_rows($result);
if($resultCheck > 0)
{
    http_response_code(412);
    echo json_encode(array("message" => "Account already exists with Phone Number/ Email"));
}
else if($fn==''||$ln==''||$em==''||$p==''||$ph==''||$pass==''||$fc1==''||$fc2=='')
{
    http_response_code(412);
    echo json_encode(array("message" => "Fields cannot be empty."));
}
else
{
    $sql = "INSERT INTO users(`f_name`,`l_name`,`email`,`password`,`mobile`,`f_contact1`,`f_contact2`,`kyc_status`,`status`,`ver_status`) VALUES('$fn','$ln','$em','$pass','$ph','$fc1','$fc2','0','1','1');";
    $insertSuccess = mysqli_query($conn, $sql);
    if($insertSuccess) 
    {
        http_response_code(200);
        echo json_encode(array("message" => "Successfully Registered, Please check your e-mail to verify your account !", "First Name" => $fn, "Last Name" => $ln, "Email" => $em, "Mobile Number" => $ph));
        
        $url = $url."verify.php?slug=".$ver;
        $to = $em;
        $subject = 'Email Verification | SNNLoan';
        $message = '<p>Click to verify your email :</br>';
        $message .= '<a href="' .$url. '">Verify Now</a></p>';
        $headers = "From: SNNLoan <verify@snnlloan.com>\r\n";
        $headers .= "Reply-To: verify@snnlloan.com\r\n";
        $headers .= "Content-type: text/html\r\n"; //to make the html work in email
        
        mail($to, $subject, $message, $headers);
    }
    
}


;?>