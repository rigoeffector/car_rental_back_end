<?php
require_once "../../config/connection.php";
include_once "../../models/requests/index.php"; // Include the UserRequests class

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header('Access-Control-Allow-Methods: PATCH'); // Change the method to PATCH for updates
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With");

// Now you can directly use $conn
$db = $conn;
$userRequests = new Requests($db); // Instantiate the UserRequests class

$data = json_decode(file_get_contents("php://input"), true); // Decode JSON as an array

if (isset($data['id']) && !empty($data['id'])) {
    // Update the record based on the provided data
    if ($userRequests->update($data)) { // Use the update function with the data array
        $response = array(
            "status" => "success",
            "error" => false,
            "success" => true,
            "message" => "User request updated successfully."
        );
        echo json_encode($response);
    } else {
        $response = array(
            "status" => "error",
            "error" => true,
            "success" => false,
            "message" => "Failed to update user request. Record not found or other error."
        );
        echo json_encode($response);
    }
} else {
    $response = array(
        "status" => "error",
        "error" => true,
        "success" => false,
        "message" => "Request ID is a required field."
    );
    echo json_encode($response);
}
?>
