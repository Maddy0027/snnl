<?php
include '../../../config.php';

//upload_doc

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if (!isset($_GET['user_id'])) {
    error_log("UserID is not set in the query parameters.");
    http_response_code(412);
    echo json_encode(array("message" => "Please provide User ID!"));
    exit;  // Stop script execution
}


$user_ID = $_GET['user_id'];



// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the "images" field is set and is an array of uploaded files
    if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {
        // Define allowed file types (you can customize this list)
        $allowedFileTypes = array('jpg', 'jpeg', 'png', 'gif');
error_log("In second loop: ");
        
        // Loop through the array of uploaded files
        foreach ($_FILES['images']['name'] as $index => $filename) {
            // Get the file extension for each uploaded file
            $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);
error_log("fileExtension : $fileExtension");

            // Check if the file type is allowed
            if (in_array($fileExtension, $allowedFileTypes)) {
                // Process the uploaded file
                // Define a destination directory to store the uploaded images
                $uploadDir = '../../../upload_doc/gallery/';

                // Generate a unique filename for the uploaded image
                $uniqueFilename = uniqid() . '_' . $filename;

                // Construct the full path to store the uploaded image
                $uploadPath = $uploadDir . $uniqueFilename;

                // Move the uploaded image to the destination directory
                if (move_uploaded_file($_FILES['images']['tmp_name'][$index], $uploadPath)) {
                    // Image uploaded successfully
                    error_log("Image uploaded successfully: $uploadPath");
                    $insertSQL = "INSERT INTO user_images (`user_id`, `image_url`) VALUES ('$user_ID', '$uploadPath')";
                    
            if (mysqli_query($conn, $insertSQL)) {
                // SMS data added successfully.
            } else {
                // Handle the database insertion error here
                echo "Error: " . $insertSQL . "<br>" . mysqli_error($conn);
            }
                    
                    
                    echo 'Image uploaded successfully.';
                    
                } else {
                    // Failed to move the uploaded image
                    error_log("Failed to move the uploaded image.");
                    echo 'Failed to move the uploaded image.';
                }
            } else {
                // File type not allowed
                error_log("File type not allowed: $fileExtension");
                http_response_code(415);  // Unsupported Media Type
                echo json_encode(array("message" => "Unsupported file type."));
            }
        }
    } else {
        // No images uploaded or an error occurred during upload
        error_log("No images uploaded or an error occurred during upload.");
        echo 'No images uploaded or an error occurred during upload.';
    }
} else {
    // Invalid request method
    echo 'Invalid request method. Use POST to upload images.';
}

?>
