<?php
include_once "../../models/users/index.php";
include_once "../../config/connection.php";
header("Access-Control-Allow-Origin:*");
header("Content-Type:application/json");
header('Access-Control-Allow-Methods:GET');
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With");
// Now you can directly use $conn

$db = $conn;
$user = new Users($db);
$result = $user->readAll();
// get Row Count 
$num = $result->rowCount();
if ($num > 0) {
    $user = array();
    $user['data'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $user_item = array(
            "id" => $id,
            "names" => $fullnames,
            "email" => $email,
            "profile" => $profile_url,
            "type" => $type_title
        );

        // Push to array  
        array_push($user['data'], $user_item);
        // turn it to json mode 
    }

    $response = array(
        "status" => "success",
        "success" => true,
        "error" => false,
        "message" => "users are fetched successfully", "data" => $user['data'],
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
        "message" => "users are not found ",

    );
    echo  json_encode(
        $response
    );
}
