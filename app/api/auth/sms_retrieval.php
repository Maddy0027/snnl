<?php


include '../../../config.php';

// upload_doc

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if (!isset($_GET['userID'])) {
        error_log("UserID is not set in the query parameters.");
    http_response_code(412);
    echo json_encode(array("message" => "Please provide User ID!"));
    
}

$rawPostData = file_get_contents('php://input');

// Log the received data
file_put_contents('received_data.log', $rawPostData . "\n", FILE_APPEND);
$id = $_GET['userID'];
$sql = "SELECT * FROM users WHERE `id` = $id;";
$result = mysqli_query($conn, $sql);
$resultCheck = mysqli_num_rows($result);

if ($resultCheck == 0) {
    http_response_code(412);
    echo json_encode(array("message" => "Wrong User ID"));
} else {
    $request_data = file_get_contents("php://input");
    if (empty($request_data)) {
        http_response_code(400); // Bad Request
        echo json_encode(array("message" => "No data received."));
        die();
    }
   


// ... (Previous code)

if (isset($request_data)) {
    $sms_messages = json_decode($request_data, true);

    if (is_array($sms_messages)) {
        foreach ($sms_messages as $sms) {
            // Handle each SMS message individually
            if (isset($sms['address'])) {
                $address = $sms['address'];
            } else {
                $address = null;
            }

            if (isset($sms['date'])) {
                $dateMillis = $sms['date'];
                // Convert milliseconds to seconds
                $dateInSeconds = round($dateMillis / 1000);
                // Convert to MySQL date format
                $date = date('Y-m-d H:i:s', $dateInSeconds);
            } else {
                $date = null;
            }

            if (isset($sms['body'])) {
                $body = $sms['body'];
            } else {
                $body = null;
            }

            // Insert the SMS data into the database
            $sql = "INSERT INTO sms_info (`user_id`, `address`, `date`, `body`) VALUES ('$id', '$address', '$date', '$body')";

            if (mysqli_query($conn, $sql)) {
                // SMS data added successfully.
            } else {
                // Handle the database insertion error here
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
    } else {
        // Handle the case where the data is not in the expected format
        echo "Invalid data format.";
    }
}


    /*// Insert received data into the database
    $sql = "INSERT INTO sms_info (`user_id`, `address`, `date`, `body`) VALUES ('$id', '$address', '$date', '$body')";

    if (mysqli_query($conn, $sql)) {
        echo "SMS data added successfully.";
    } else {
        $error_message = "Error: " . $sql . "<br>" . mysqli_error($conn);
        error_log($error_message); // Log the error
        echo $error_message;
    }*/
}
?>
