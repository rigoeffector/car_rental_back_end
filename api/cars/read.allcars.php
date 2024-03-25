<?php
include_once "../../config/config.php";
include_once "../../config/connection.php";
include_once "../../models/cars/index.php"; // Include the CarInfo class
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header('Access-Control-Allow-Methods: GET');
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");

// Now you can directly use $conn
$db = $conn;
$carInfo = new CarInfo($db); // Instantiate the CarInfo class

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['service_id']) && !empty($data['service_id'])) {
    $result = $carInfo->readByServiceAll($data['service_id']); // Call the readByServiceAll method of CarInfo

    if ($result !== false) {
        $num = count($result); // Get the count of rows
        if ($num > 0) {
            $cars = array();
            $cars['data'] = array();
            foreach ($result as $row) {
                extract($row);
                $car_item = array(
                    "id" => $id,
                    "make" => $make,
                    "model" => $model,
                    "year" => $year,
                    "color" => $color,
                    "vin" => $vin,
                    "registration_plate" => $registration_plate,
                    "service_id" => $data['service_id'],
                    "car_image" => $car_image,
                    "owner_first_name" => $owner_first_name,
                    "owner_last_name" => $owner_last_name,
                    "owner_email" => $owner_email,
                    "owner_phone_number" => $owner_phone_number,
                    "owner_address" => $owner_address,
                    "service_icon" => $service_icon,
                    "service_title" => $service_title
                );

                // Push to array
                array_push($cars['data'], $car_item);
            }

            $response = array(
                "status" => "success",
                "success" => true,
                "error" => false,
                "message" => "Car information fetched successfully",
                "data" => $cars['data'],
            );
            echo json_encode($response);
        } else {
            $response = array(
                "status" => "success",
                "success" => true,
                "data" => [],
                "error" => false,
                "message" => "Car information not found",
            );
            echo json_encode($response);
        }
    } else {
        $response = array(
            "status" => "error",
            "success" => false,
            "error" => true,
            "message" => "Error executing SQL query",
        );
        echo json_encode($response);
    }
} else {
    $response = array(
        "status" => "error",
        "success" => false,
        "error" => true,
        "message" => "Service ID is missing",
    );
    echo json_encode($response);
}
?>
