<?php
require_once "../../config/connection.php";
include_once "../../models/cars/index.php"; // Include the CarInfo class
header("Access-Control-Allow-Origin:*");
header("Content-owner:application/json");
header('Access-Control-Allow-Methods:DELETE'); // Use DELETE method for deletion
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-owner,Access-Control-Allow-Methods,Authorization,X-Requested-With");

// Now you can directly use $conn
$db = $conn;
$carInfo = new CarInfo($db); // Instantiate the CarInfo class

$data = json_decode(file_get_contents("php://input"), true); // Decode JSON as an array

// Check if "car_id" is set and not empty
if (isset($data['car_id']) && !empty($data['car_id'])) {
    // Delete the record based on the provided car_id
    if ($carInfo->delete($data['car_id'])) { // Use the delete function
        $response = array(
            "status" => "success",
            "error" => false,
            "success" => true,
            "message" => "Car record deleted successfully."
        );
        echo json_encode($response);
    } else {
        $response = array(
            "status" => "error",
            "error" => true,
            "success" => false,
            "message" => "Failed to delete car record. Record not found or other error."
        );
        echo json_encode($response);
    }
} else {
    // Handle the case where "car_id" is missing or empty
    $response = array(
        "status" => "error",
        "error" => true,
        "success" => false,
        "message" => "Car ID is required for deletion."
    );
    echo json_encode($response);
}
?>
