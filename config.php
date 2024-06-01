<?php 

$dbServername = "localhost";
$dbUsername = "snnlloan_db";
$dbPassword = "snnlloan_db";
$dbName = "snnlloan_db";

// create connection
$conn = new mysqli($dbServername,$dbUsername,$dbPassword,$dbName);
// check connection
if($conn -> connect_error) {
    die("connection failed:".$conn->connect_error);
}

// enter your website's url with no '/' at end here
$url = 'https://snnlloan.in/';
$row_site_info=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `site_info` WHERE `id`=1"));

?>