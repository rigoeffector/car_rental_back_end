<?php
require_once "../../config/connection.php";
include_once "../../models/requests/index.php"; // Include the UserRequests class
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With");

// Now you can directly use $conn
$db = $conn;
$userRequest = new Requests($db); // Instantiate the UserRequests class

$data = json_decode(file_get_contents("php://input"), true); // Decode JSON as an array

if (!empty($data['user_id']) && !empty($data['service_id']) && !empty($data['car_id'])) { // Check if required fields are provided
    // Prepare data to be sent
    $requestData = [
        "user_id" => $data['user_id'],
        "service_id" => $data['service_id'],
        "car_id" => $data['car_id'],
        "status" => $data['status'] ?? 'PENDING', // Default status is PENDING
        "approved_by" => $data['approved_by'] ?? null,
        "returned_by" => $data['returned_by'] ?? null,
        "passed_by" => $data['passed_by'] ?? null
    ];

    if ($userRequest->create($requestData)) { // Call the create method of UserRequests
        $response = [
            "status" => "success",
            "error" => false,
            "success" => true,
            "message" => "User request created successfully."
        ];
        echo json_encode($response);
    } else {
        $response = [
            "status" => "error",
            "error" => true,
            "success" => false,
            "message" => "You already requested this car"
        ];
        echo json_encode($response);
    }
} else {
    $response = [
        "status" => "error",
        "error" => true,
        "success" => false,
        "message" => "User ID, service ID, and car ID are required fields."
    ];
    echo json_encode($response);
}
?>
