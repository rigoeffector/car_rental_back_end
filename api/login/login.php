<?php
require_once "../../config/config.php";
// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");
    exit();
}


header("Access-Control-Allow-Origin:*");
header("Content-Type:application/json");
header('Access-Control-Allow-Methods:POST');
header("Access-Control-Allow-Headers:Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods,Authorization,X-Requested-With");
include_once "../../models/login/index.php";

// Now you can directly use $conn
$db = $conn;
$employee = new Login($db);

$data = json_decode(file_get_contents("php://input"));

// Check if Email and password are provided

if (!empty($data->email) && !empty($data->password)) {
    $email = $data->email;
    $password = $data->password;
    $type = $data->type;

    // Call your login function with the provided credentials
    $result = $employee->login($type, $email, $password);

    if ($result) {
        // If login is successful, send a success response with the user data
        $response = array(
            "status" => "success",
            "error" => false,
            "success" => true,
            "message" => "Login successful",
            "data" => $result
        );
        echo json_encode($response);
    } else {
        // If login fails, send an error response
        $response = array(
            "status" => "error",
            "error" => true,
            "success" => false,
            "message" => "Login failed. Invalid credentials."
        );
        http_response_code(401); // Set HTTP status code to 401 (Unauthorized)
        echo json_encode($response);
    }
} else {
    // Handle the case where username or password is missing
    $response = array(
        "status" => "error",
        "error" => true,
        "success" => false,
        "message" => "Email and password are required fields."
    );
    http_response_code(400); // Set HTTP status code to 400 (Bad Request)
    echo json_encode($response);
}
