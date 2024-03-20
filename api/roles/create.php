<?php
require_once "../../config/config.php";
include_once "../../models/roles/index.php";
header("Access-Control-Allow-Origin:*");
header("Content-Type:application/json");
header('Access-Control-Allow-Methods:POST');
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With");


// Now you can directly use $conn
$db = $conn;
$role = new Roles($db);

$data = json_decode(file_get_contents("php://input"), true); // Decode JSON as an array

if (!empty($data['role_name']) && !empty($data['permission_id'])) {
    // prepare data to be sent
    $role->role_name = $data['role_name'];
   

    if ($role->create($data)) {
        $response = array(
            "status" => "success",
            "error" => false,
            "success" => true,
            "message" => " Role is added successfully"
        );
        echo json_encode($response);
    } else {
        $response = array(
            "status" => "error",
            "error" => true,
            "success" => false,
            "message" => "Role is not created successfully. A record with the same information is already exists."
        );
        echo json_encode($response);
    }
} else {

    $response = array(
        "status" => "error",
        "error" => true,
        "success" => false,
        "message" => "Role name and Description are required fields."
    );
    echo json_encode($response);
}
