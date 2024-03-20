<?php
include_once "../../models/types/index.php";
include_once "../../config/connection.php";
header("Access-Control-Allow-Origin:*");
header("Content-Type:application/json");
header('Access-Control-Allow-Methods:GET');
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With");
// Now you can directly use $conn

$db = $conn;
$type = new UserTypes($db);
$result = $type->readAll();
// get Row Count 
$num = $result->rowCount();
if ($num > 0) {
    $type = array();
    $type['data'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $type_item = array(
            "id" => $id,
            "title" => $title,
            "description" => $description

        );

        // Push to array  
        array_push($type['data'], $type_item);
        // turn it to json mode 
    }

    $response = array(
        "status" => "success",
        "success" => true,
        "error" => false,
        "message" => "types are fetched successfully", "data" => $type['data'],
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
        "message" => "types are not found ",

    );
    echo  json_encode(
        $response
    );
}
