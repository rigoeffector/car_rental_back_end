<?php
include_once "../../config/config.php";
include_once "../../config/connection.php";
class Users
{

    private $conn;
    private $table = 'users';
    public $id;
    public $fullnames;
    public $email;
    public $password;
    public $cpassword;
    public $profile_url;
    public $user_type;
  



    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function create($data)
    {
        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM $this->table WHERE fullnames= :fullnames AND email= :email");
            $stmt->bindParam(':fullnames', $data["fullnames"]);
            $stmt->bindParam(':email', $data["email"]);

            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                return false;
            }


            $insertStmt = $this->conn->prepare("INSERT INTO $this->table (fullnames, email, password, cpassword,profile_url,user_type) 
         VALUES (:fullnames, :email,:password,:cpassword, :profile_url, :user_type)");
            $insertStmt->execute([
                "fullnames" => $data["fullnames"],
                "email" => $data["email"],
                "password" => $data["password"],
                "cpassword" => $data["cpassword"],
                "profile_url" => $data["profile_url"],
                "user_type" => $data["user_type"]
            ]);

            return true;
        }catch (PDOException $e) {
            echo "Error: " . $e->getMessage(); // Output or log the error message
            return false;
        }
    }

    // Update function in Employees class
    public function update($data)
    {
        try {
            // Set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Check if the record to be updated exists
            $checkStmt = $this->conn->prepare("SELECT COUNT(*) FROM " . $this->table . " WHERE id = :id");
            $checkStmt->bindParam(':id', $data["id"]);
            $checkStmt->execute();
            $count = $checkStmt->fetchColumn();

            if ($count === 0) {
                // The record with the specified ID does not exist, return false
                return false;
            }

            // Proceed with the update
            $updateStmt = $this->conn->prepare("UPDATE " . $this->table . " SET
            fullnames = :fullnames,
            email = :email,
            password = :password,
            cpassword=:cpassword,
            profile_url=:profile_url,
            user_type=:user_type
            WHERE id = :id");

            $updateStmt->execute([
                "fullnames" => $data["fullnames"],
                "email" => $data["email"],  
                "password" => $data["password"],
                "cpassword" => $data["cpassword"],
                "profile_url" => $data["profile_url"],
                "user_type" => $data["user_type"],
                "id" => $data["id"]

            ]);

            return true; // Return a success indicator if the update was successful.
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage(); // Output or log the error message
            return false;
        }
    }


    public function readAll()
    {
        try {

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Define the SQL query with a JOIN statement
            $query = "SELECT users.*, user_type.title AS type_title
            FROM users
            LEFT JOIN user_type ON users.user_type = user_type.id
            ";


            // Prepare and execute the query
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt;
        } catch (PDOException $e) {

            return false;
        }
    }



    // Delete function in Employees class
    public function delete($id)
    {
        try {
            // Set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Check if the record to be deleted exists
            $checkStmt = $this->conn->prepare("SELECT COUNT(*) FROM " . $this->table . " WHERE id = :id");
            $checkStmt->bindParam(':id', $id);
            $checkStmt->execute();
            $count = $checkStmt->fetchColumn();

            if ($count === 0) {
                // The record with the specified ID does not exist, return false
                return false;
            }

            // Proceed with the delete
            $deleteStmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id = :id");
            $deleteStmt->bindParam(':id', $id);
            $deleteStmt->execute();

            return true; // Return a success indicator if the delete was successful.
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage(); // Output or log the error message
            return false;
        }
    }
}
