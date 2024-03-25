<?php
require_once "../../config/config.php";
include_once "../../config/connection.php";
include_once "../../models/cars/index.php"; // Include the CarInfo class
header("Access-Control-Allow-Origin:*");
header("Content-Type:application/json");
header('Access-Control-Allow-Methods:POST');
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With");

// Now you can directly use $conn
$db = $conn;
$carInfo = new CarInfo($db); // Instantiate the CarInfo class

$data = json_decode(file_get_contents("php://input"), true); // Decode JSON as an array

if (!empty($data['make']) && !empty($data['model']) && !empty($data['year']) && !empty($data['color']) && !empty($data['vin']) && !empty($data['registration_plate'])  && !empty($data['service_id']) && !empty($data['car_image'])) { // Check if required fields are provided
    // Prepare data to be sent
    $carInfoData = [
        "make" => $data['make'],
        "model" => $data['model'],
        "year" => $data['year'],
        "color" => $data['color'],
        "vin" => $data['vin'],
        "registration_plate" => $data['registration_plate'],
        "service_id" => $data['service_id'],
        "owner_id" => $data['owner_id'],
        "car_image" => $data['car_image']
    ];

    if ($carInfo->create($carInfoData)) { // Call the create method of CarInfo
        $response = [
            "status" => "success",
            "error" => false,
            "success" => true,
            "message" => "Car information record created successfully."
        ];
        echo json_encode($response);
    } else {
        $response = [
            "status" => "error",
            "error" => true,
            "success" => false,
            "message" => "Failed to create car information record."
        ];
        echo json_encode($response);
    }
} else {
    $response = [
        "status" => "error",
        "error" => true,
        "success" => false,
        "message" => "All required fields must be provided."
    ];
    echo json_encode($response);
}
?>
