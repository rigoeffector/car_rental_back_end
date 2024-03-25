<?php
include_once "../../config/config.php";
include_once "../../config/connection.php";
class CarServices
{

    private $conn;
    private $table = 'car_services';
    public $id;
    public $car_icon;
    public $title;
    public $user_type_id;
   



    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function create($data)
    {
        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM $this->table WHERE title = :title");
            $stmt->bindParam(':title', $data["title"]);
        

            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                return false;
            }


            $insertStmt = $this->conn->prepare("INSERT INTO $this->table (car_icon, title, user_type_id) 
         VALUES (:car_icon, :title,:user_type_id)");
            $insertStmt->execute([
                "car_icon" => $data["car_icon"],
                "title" => $data["title"],
                "user_type_id"=> $data["user_type_id"]
               

            ]);

            return true;
        } catch (PDOException $e) {
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
            car_icon = :car_icon,
            title = :title,
            user_type_id =:user_type_id
          
            WHERE id = :id");

            $updateStmt->execute([
                "car_icon" => $data["car_icon"],
                "title" => $data["title"] ,
                "user_type_id"=> $data["user_type_id"],
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
            $query = "SELECT cs.id, cs.car_icon, cs.title, cs.user_type_id, ut.title  as user_type  FROM car_services cs

            LEFT JOIN user_type ut ON cs.user_type_id = ut.id";


            // Prepare and execute the query
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage(); // Output or log the error message
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
