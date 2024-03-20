<?php
include_once "../../models/roles/index.php";
include_once "../../config/connection.php";
header("Access-Control-Allow-Origin:*");
header("Content-Type:application/json");
header('Access-Control-Allow-Methods:GET');
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With");
// Now you can directly use $conn

$db = $conn;
$role = new Roles($db);
$result = $role->readAll();
// get Row Count 
$num = $result->rowCount();
if ($num > 0) {
    $role = array();
    $role['data'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $role_item = array(
            "role_id" => $role_id,
            "role_name" => $role_name
        );

        // Push to array  
        array_push($role['data'], $role_item);
        // turn it to json mode 
    }

    $response = array(
        "status" => "success",
        "success" => true,
        "error" => false,
        "message" => "Role are fetched successfully", "data" =>$role['data'],
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
        "message" => "Role are not found ",
        
    );
    echo  json_encode(
        $response
    );
}