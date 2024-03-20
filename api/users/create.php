<?php
require_once "../../config/config.php";
include_once "../../models/users/index.php";
header("Access-Control-Allow-Origin:*");
header("Content-user:application/json");
header('Access-Control-Allow-Methods:POST');
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-user,Access-Control-Allow-Methods,Authorization,X-Requested-With");


// Now you can directly use $conn
$db = $conn;
$user = new Users($db);

$data = json_decode(file_get_contents("php://input"), true); // Decode JSON as an array

if (!empty($data['fullnames']) && !empty($data['email']) && !empty($data['password'])) {
    // prepare data to be sent
    $user->fullnames = $data['fullnames'];
    $user->email = $data['email'];
    $user->password = $data['password'];
    $user->cpassword = $data['cpassword'];
    $user->profile_url = $data['profile_url'];
    $user->user_type = $data['user_type'];

    if ($user->create($data)) {
        $response = array(
            "status" => "success",
            "error" => false,
            "success" => true,
            "message" => " user is created successfully"
        );
        echo json_encode($response);
    } else {
        $response = array(
            "status" => "error",
            "error" => true,
            "success" => false,
            "message" => " user is not created successfully. A record with the same information is already exists."
        );
        echo json_encode($response);
    }
} else {

    $response = array(
        "status" => "error",
        "error" => true,
        "success" => false,
        "message" => " full names, email and password are  required field."
    );
    echo json_encode($response);
}
