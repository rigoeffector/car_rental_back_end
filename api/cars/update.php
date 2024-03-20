<?php
include_once "../../config/config.php";
include_once "../../config/connection.php";
include_once "../../models/cars/index.php"; // Include the CarInfo class
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header('Access-Control-Allow-Methods: PATCH'); // Change the method to PATCH for updates
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With");

// Now you can directly use $conn
$db = $conn;
$carInfo = new CarInfo($db); // Instantiate the CarInfo class

$data = json_decode(file_get_contents("php://input"), true); // Decode JSON as an array

if (isset($data['car_id']) && !empty($data['car_id'])) {
    // Update the record based on the provided data
    if ($carInfo->update($data)) { // Use the update function with the data array
        $response = array(
            "status" => "success",
            "error" => false,
            "success" => true,
            "message" => "Car record updated successfully."
        );
        echo json_encode($response);
    } else {
        $response = array(
            "status" => "error",
            "error" => true,
            "success" => false,
            "message" => "Failed to update car record. Record not found or other error."
        );
        echo json_encode($response);
    }
} else {
    $response = array(
        "status" => "error",
        "error" => true,
        "success" => false,
        "message" => "Car ID is a required field."
    );
    echo json_encode($response);
}
?>
