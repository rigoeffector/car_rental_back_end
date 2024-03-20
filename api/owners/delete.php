<?php
require_once "../../config/connection.php";
header("Access-Control-Allow-Origin:*");
header("Content-owner:application/json");
header('Access-Control-Allow-Methods:DELETE'); // Use DELETE method for deletion
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-owner,Access-Control-Allow-Methods,Authorization,X-Requested-With");
include_once "../../models/owners/index.php";

// Now you can directly use $conn
$db = $conn;
$owner = new OwnerCar($db);

$data = json_decode(file_get_contents("php://input"), true); // Decode JSON as an array

// Check if "id" is set and not empty
if (isset($data['owner_id']) && !empty($data['owner_id'])) {
    // Delete the record based on the provided ID
    if ($owner->delete($data['owner_id'])) { // Use the delete function
        $response = array(
            "status" => "success",
            "error" => false,
            "success" => true,
            "message" => "owner is deleted successfully"
        );
        echo json_encode($response);
    } else {
        $response = array(
            "status" => "error",
            "error" => true,
            "success" => false,
            "message" => "owner is not deleted successfully. Record not found or other error."
        );
        echo json_encode($response);
    }
} else {
    // Handle the case where "id" is missing or empty
    $response = array(
        "status" => "error",
        "error" => true,
        "success" => false,
        "message" => "owner Id is required for deletion."
    );
    echo json_encode($response);
}
?>