<?php
require_once "../../config/config.php";
include_once "../../models/requests/index.php"; // Include the UserRequests class

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header('Access-Control-Allow-Methods: GET');
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With");

// Now you can directly use $conn
$db = $conn;
$userRequests = new Requests($db); // Instantiate the UserRequests class
$result = $userRequests->readAll(); // Call the readAll method of UserRequests

// Check if the result is not false
if ($result !== false) {
    // Get Row Count
    $num = $result->rowCount();
    if ($num > 0) {
        $userRequestsArr = array();
        $userRequestsArr['data'] = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $userRequestItem = array(
                "request_id" => $id,
                "service_id" => $service_id,
                "service_title" => $service_title,
                "id" => $id,
                "car_make" => $car_make,
                "car_model" => $car_model,
                "car_image" => $car_image,
                "request_date" => $request_date,
                "status" => $status,
                "client" => array("user_id" => $user_id, "fullnames" => $user_username),

                "approved_by" => array("id" => $approved_by, "fullnames" => $approved_by_name),
                "returned_by" => array("id" => $returned_by, "fullnames" => $returned_by_name),
                "passed_by" => array("id" => $passed_by, "fullnames" => $passed_by_name)
            );

            // Push to array
            array_push($userRequestsArr['data'], $userRequestItem);
        }

        $response = array(
            "status" => "success",
            "success" => true,
            "error" => false,
            "message" => "User requests fetched successfully",
            "data" => $userRequestsArr['data'],
        );
        echo json_encode($response);
    } else {
        $response = array(
            "status" => "success",
            "success" => true,
            "data" => [],
            "error" => false,
            "message" => "No user requests found",
        );
        echo json_encode($response);
    }
} else {
    $response = array(
        "status" => "error",
        "success" => false,
        "error" => true,
        "message" => "Failed to fetch user requests. Please try again later.",
    );
    echo json_encode($response);
}
?>
