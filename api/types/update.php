<?php
require_once "../../config/connection.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header('Access-Control-Allow-Methods: PATCH'); // Change the method to PUT for updates
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With");
include_once "../../models/types/index.php";

// Now you can directly use $conn
$db = $conn;
$type = new UserTypes($db);

$data = json_decode(file_get_contents("php://input"), true); // Decode JSON as an array

if (isset($data['id']) && isset($data['title']) && !empty($data['id']) && !empty($data['title'])) {
    // Update the record based on the provided data
    if ($type->update($data)) { // Use the update function with the data array
        $response = array(
            "status" => "success",
            "error" => false,
            "success" => true,
            "message" => "type is updated successfully"
        );
        echo json_encode($response);
    } else {
        $response = array(
            "status" => "error",
            "error" => true,
            "success" => false,
            "message" => "type is not updated successfully. Record not found or other error."
        );
        echo json_encode($response);
    }
} else {
    $response = array(
        "status" => "error",
        "error" => true,
        "success" => false,
        "message" => "type title and id are required fields."
    );
    echo json_encode($response);
}
?>
