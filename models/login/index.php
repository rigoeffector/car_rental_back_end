<?php
include_once "../../config/config.php";
include_once "../../config/connection.php";
class Login
{

    private $conn;
    public $password;
    public $email;
    public $type;



    public function __construct($db)
    {
        $this->conn = $db;
    }




    public function login( $email, $password)
    {
        try {

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $query = "SELECT u.id, u.fullnames, u.email, u.profile_url, ut.title, ut.id as user_type_id
                FROM users u
                LEFT JOIN user_type ut ON u.user_type = ut.id   WHERE 
                       email = :email AND password = :password
 ";
            



            // Prepare the query
            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);

            // Execute the query
            $stmt->execute();

            // Fetch and return the result
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            return false;
        }
    }
}
