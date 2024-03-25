<?php
require_once "../../config/connection.php";
include_once "../../models/owners/index.php"; // Include the OwnerServices class
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header('Access-Control-Allow-Methods: PATCH'); // Change the method to PATCH for updates
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With");

// Now you can directly use $conn
$db = $conn;
$ownerService = new OwnerCar($db); // Instantiate the OwnerServices class

$data = json_decode(file_get_contents("php://input"), true); // Decode JSON as an array

if (isset($data['id']) && isset($data['first_name']) && !empty($data['id']) && !empty($data['first_name'])) {
    // Update the record based on the provided data
    if ($ownerService->update($data)) { // Use the update function with the data array
        $response = array(
            "status" => "success",
            "error" => false,
            "success" => true,
            "message" => "Owner record updated successfully."
        );
        echo json_encode($response);
    } else {
        $response = array(
            "status" => "error",
            "error" => true,
            "success" => false,
            "message" => "Failed to update owner record. Record not found or other error."
        );
        echo json_encode($response);
    }
} else {
    $response = array(
        "status" => "error",
        "error" => true,
        "success" => false,
        "message" => "Owner ID and first name are required fields."
    );
    echo json_encode($response);
}
?>
