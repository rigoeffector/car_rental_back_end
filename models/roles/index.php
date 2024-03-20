<?php
include_once "../../config/config.php";
include_once "../../config/connection.php";


class Roles
{

    private $conn;
    private $table = 'role';
    public $role_id;
    public $role_name;



    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function create($data)
    {
        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM $this->table WHERE role_name = :role_name ");
            $stmt->bindParam(':role_name', $data["role_name"]);


            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                return false;
            }


            $insertStmt = $this->conn->prepare("INSERT INTO $this->table (role_name) 
         VALUES (:role_name)");
            $insertStmt->execute([
                "role_name" => $data["role_name"]
            
            ]);


            return true;
        } catch (PDOException $e) {

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
            $checkStmt = $this->conn->prepare("SELECT COUNT(*) FROM " . $this->table . " WHERE role_id = :role_id");
            $checkStmt->bindParam(':role_id', $data["role_id"]);
            $checkStmt->execute();
            $count = $checkStmt->fetchColumn();

            if ($count === 0) {
                // The record with the specified ID does not exist, return false
                return false;
            }

            // Proceed with the update
            $updateStmt = $this->conn->prepare("UPDATE " . $this->table . " SET
            role_name = :role_name
            WHERE role_id = :role_id");
            $updateStmt->execute([
                "role_name" => $data["role_name"],
                "role_id" => $data["role_id"]

            ]);

            return true; // Return a success indicator if the update was successful.
        } catch (PDOException $e) {
            // Handle any database errors here.
            return false; // Return a failure indicator if an error occurred.
        }
    }


    public function readAll()
    {
        try {

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Define the SQL query with a JOIN statement
            $query = "SELECT*
            FROM role
           
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
            $checkStmt = $this->conn->prepare("SELECT COUNT(*) FROM " . $this->table . " WHERE role_id = :role_id");
            $checkStmt->bindParam(':role_id', $id);
            $checkStmt->execute();
            $count = $checkStmt->fetchColumn();

            if ($count === 0) {
                // The record with the specified ID does not exist, return false
                return false;
            }

            // Proceed with the delete
            $deleteStmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE role_id = :role_id");
            $deleteStmt->bindParam(':role_id', $id);
            $deleteStmt->execute();

            return true; // Return a success indicator if the delete was successful.
        } catch (PDOException $e) {
            // Handle any database errors here.
            return false; // Return a failure indicator if an error occurred.
        }
    }
}
