<?php
require_once "../../config/config.php";
include_once "../../models/types/index.php";
header("Access-Control-Allow-Origin:*");
header("Content-Type:application/json");
header('Access-Control-Allow-Methods:POST');
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With");


// Now you can directly use $conn
$db = $conn;
$type = new UserTypes($db);

$data = json_decode(file_get_contents("php://input"), true); // Decode JSON as an array

if (!empty($data['title'])) {
    // prepare data to be sent
    $type->title = $data['title'];
    $type->description = $data['description'];

    if ($type->create($data)) {
        $response = array(
            "status" => "success",
            "error" => false,
            "success" => true,
            "message" => " user type is created successfully"
        );
        echo json_encode($response);
    } else {
        $response = array(
            "status" => "error",
            "error" => true,
            "success" => false,
            "message" => "user type is not created successfully. A record with the same information is already exists."
        );
        echo json_encode($response);
    }
} else {

    $response = array(
        "status" => "error",
        "error" => true,
        "success" => false,
        "message" => "type title is a required field."
    );
    echo json_encode($response);
}
