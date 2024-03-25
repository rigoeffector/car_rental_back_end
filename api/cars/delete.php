<?php
require_once "../../config/connection.php";
include_once "../../models/cars/index.php"; // Include the CarInfo class
header("Access-Control-Allow-Origin:*");
header("Content-Type:application/json");
header('Access-Control-Allow-Methods:DELETE');
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With");

// Now you can directly use $conn
$db = $conn;
$carInfo = new CarInfo($db); // Instantiate the CarInfo class

$data = json_decode(file_get_contents("php://input"), true); // Decode JSON as an array

// Check if "id" is set and not empty
if (isset($data['id']) && !empty($data['id'])) {
    // Delete the record based on the provided id
    if ($carInfo->delete($data['id'])) { // Use the delete function
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
    // Handle the case where "id" is missing or empty
    $response = array(
        "status" => "error",
        "error" => true,
        "success" => false,
        "message" => "Car ID is required for deletion."
    );
    echo json_encode($response);
}
?>
