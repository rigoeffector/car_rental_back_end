<?php
include_once "../../models/owners/index.php"; // Include the OwnerServices class
include_once "../../config/connection.php";
header("Access-Control-Allow-Origin:*");
header("Content-Type:application/json");
header('Access-Control-Allow-Methods:GET');
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With");

// Now you can directly use $conn
$db = $conn;
$ownerService = new OwnerCar($db); // Instantiate the OwnerServices class
$result = $ownerService->readAll(); // Call the readAll method of OwnerServices

// Get Row Count
$num = $result->rowCount();
if ($num > 0) {
    $owners = array();
    $owners['data'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $owner_item = array(
            "id" => $id,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
            "phone_number" => $phone_number,
            "address" => $address
        );

        // Push to array
        array_push($owners['data'], $owner_item);
    }

    $response = array(
        "status" => "success",
        "success" => true,
        "error" => false,
        "message" => "Owners are fetched successfully",
        "data" => $owners['data'],
    );
    echo json_encode($response);
} else {
    $response = array(
        "status" => "success",
        "success" => true,
        "data" => [],
        "error" => false,
        "message" => "Owners not found",
    );
    echo json_encode($response);
}
?>
