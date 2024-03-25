<?php
require_once "../../config/config.php";
include_once "../../models/services/index.php";
header("Access-Control-Allow-Origin:*");
header("Content-Type:application/json");
header('Access-Control-Allow-Methods:POST');
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With");


// Now you can directly use $conn
$db = $conn;
$service = new CarServices($db);

$data = json_decode(file_get_contents("php://input"), true); // Decode JSON as an array

if (!empty($data['title'])&&!empty($data['title']) ) {
    // prepare data to be sent
    $service->car_icon = $data['car_icon'];
    $service->title = $data['title'];

    if ($service->create($data)) {
        $response = array(
            "status" => "success",
            "error" => false,
            "success" => true,
            "message" => "service is created successfully"
        );
        echo json_encode($response);
    } else {
        $response = array(
            "status" => "error",
            "error" => true,
            "success" => false,
            "message" => "service is not created successfully. A record with the same information is already exists."
        );
        echo json_encode($response);
    }
} else {

    $response = array(
        "status" => "error",
        "error" => true,
        "success" => false,
        "message" => "service title and icon are required fields."
    );
    echo json_encode($response);
}
