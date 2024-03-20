<?php
include_once "../../models/services/index.php";
include_once "../../config/connection.php";
header("Access-Control-Allow-Origin:*");
header("Content-service:application/json");
header('Access-Control-Allow-Methods:GET');
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-service,Access-Control-Allow-Methods,Authorization,X-Requested-With");
// Now you can directly use $conn

$db = $conn;
$service = new CarServices($db);
$result = $service->readAll();
// get Row Count 
$num = $result->rowCount();
if ($num > 0) {
    $service = array();
    $service['data'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $service_item = array(
            "id" => $id,
            "icon" => $car_icon,
            "title" => $title

        );

        // Push to array  
        array_push($service['data'], $service_item);
        // turn it to json mode 
    }

    $response = array(
        "status" => "success",
        "success" => true,
        "error" => false,
        "message" => "services are fetched successfully", "data" => $service['data'],
    );
    echo  json_encode(
        $response
    );
} else {
    $response = array(
        "status" => "success",
        "success" => true,
        "data" => [],
        "error" => false,
        "message" => "services are not found ",

    );
    echo  json_encode(
        $response
    );
}
