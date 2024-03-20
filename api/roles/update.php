<?php
require_once "../../config/connection.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header('Access-Control-Allow-Methods: PATCH'); // Change the method to PUT for updates
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With");
include_once "../../models/roles/index.php";

// Now you can directly use $conn
$db = $conn;
$role = new Roles($db);

$data = json_decode(file_get_contents("php://input"), true); // Decode JSON as an array

if (isset($data['role_id']) && isset($data['role_name']) && !empty($data['role_id']) && !empty($data['role_name'])) {
    // Update the record based on the provided data
    if ($role->update($data)) { // Use the update function with the data array
        $response = array(
            "status" => "success",
            "error" => false,
            "success" => true,
            "message" => "Role is updated successfully"
        );
        echo json_encode($response);
    } else {
        $response = array(
            "status" => "error",
            "error" => true,
            "success" => false,
            "message" => "Role is not updated successfully. Record not found or other error."
        );
        echo json_encode($response);
    }
} else {
    $response = array(
        "status" => "error",
        "error" => true,
        "success" => false,
        "message" => "Role Name & Permission are required fields."
    );
    echo json_encode($response);
}
?>
