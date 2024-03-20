<?php
require_once "../../config/config.php";
include_once "../../models/owners/index.php"; // Include the OwnerServices class
header("Access-Control-Allow-Origin:*");
header("Content-Type:application/json");
header('Access-Control-Allow-Methods:POST');
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With");

// Now you can directly use $conn
$db = $conn;
$ownerService = new OwnerCar($db); // Instantiate the OwnerServices class

$data = json_decode(file_get_contents("php://input"), true); // Decode JSON as an array

if (!empty($data['first_name']) && !empty($data['email'])) { // Check if required fields are provided
    // Prepare data to be sent
    $ownerData = [
        "first_name" => $data['first_name'],
        "last_name" => $data['last_name'] ?? null, // Last name is optional
        "email" => $data['email'],
        "phone_number" => $data['phone_number'] ?? null, // Phone number is optional
        "address" => $data['address'] ?? null // Address is optional
    ];

    if ($ownerService->create($ownerData)) { // Call the create method of OwnerServices
        $response = [
            "status" => "success",
            "error" => false,
            "success" => true,
            "message" => "Owner record created successfully."
        ];
        echo json_encode($response);
    } else {
        $response = [
            "status" => "error",
            "error" => true,
            "success" => false,
            "message" => "Failed to create owner record. A record with the same email already exists."
        ];
        echo json_encode($response);
    }
} else {
    $response = [
        "status" => "error",
        "error" => true,
        "success" => false,
        "message" => "First name and email are required fields."
    ];
    echo json_encode($response);
}
?>
