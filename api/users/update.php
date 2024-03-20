<?php
require_once "../../config/connection.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header('Access-Control-Allow-Methods: PATCH'); // Change the method to PUT for updates
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With");
include_once "../../models/users/index.php";

// Now you can directly use $conn
$db = $conn;
$user = new Users($db);

$data = json_decode(file_get_contents("php://input"), true); // Decode JSON as an array

if (isset($data['id']) && isset($data['fullnames']) && !empty($data['email']) && !empty($data['email'])) {
    // Update the record based on the provided data
    if ($user->update($data)) { // Use the update function with the data array
        $response = array(
            "status" => "success",
            "error" => false,
            "success" => true,
            "message" => "user is updated successfully"
        );
        echo json_encode($response);
    } else {
        $response = array(
            "status" => "error",
            "error" => true,
            "success" => false,
            "message" => "user is not updated successfully. Record not found or other error."
        );
        echo json_encode($response);
    }
} else {
    $response = array(
        "status" => "error",
        "error" => true,
        "success" => false,
        "message" => "User full name & email are required fields."
    );
    echo json_encode($response);
}
?>
