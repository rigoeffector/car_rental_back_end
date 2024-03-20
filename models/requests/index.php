<?php
include_once "../../config/config.php";
include_once "../../config/connection.php";

class Requests
{
    private $conn;
    private $table = 'UserRequests';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create a new user request
    public function create($data)
    {
        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM $this->table WHERE user_id = :user_id AND service_id = :service_id AND car_id = :car_id");
            $stmt->bindParam(':user_id', $data["user_id"]);
            $stmt->bindParam(':service_id', $data["service_id"]);
            $stmt->bindParam(':car_id', $data["car_id"]);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                return false;
            }

            $insertStmt = $this->conn->prepare("INSERT INTO $this->table (user_id, service_id, car_id) VALUES (:user_id, :service_id, :car_id)");
            $insertStmt->execute([
                "user_id" => $data["user_id"],
                "service_id" => $data["service_id"],
                "car_id" => $data["car_id"]
            ]);

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Retrieve all user requests
   // Retrieve all user requests with joined tables
   public function readAll()
   {
       try {
           $query = "SELECT UR.request_id, UR.user_id, UR.service_id, UR.car_id, UR.request_date, UR.status, 
           U.fullnames AS user_username, 
           CS.title AS service_title, CI.make AS car_make, CI.model AS car_model, CI.car_image,
           UB.id AS approved_by,
           UB.fullnames AS approved_by_name,
           UR2.id AS returned_by,
           UR2.fullnames AS returned_by_name,
           UP.id AS passed_by,
           UP.fullnames AS passed_by_name
       FROM UserRequests UR
       LEFT JOIN Users U ON UR.user_id = U.id
       LEFT JOIN car_services CS ON UR.service_id = CS.id
       LEFT JOIN CarInformation CI ON UR.car_id = CI.car_id
       LEFT JOIN Users UB ON UR.approved_by = UB.id
       LEFT JOIN Users UR2 ON UR.returned_by = UR2.id
       LEFT JOIN Users UP ON UR.passed_by = UP.id
       ";
           $stmt = $this->conn->prepare($query);
           $stmt->execute();
           return $stmt;
       } catch (PDOException $e) {
        echo "Error: " . $e->getMessage(); // Output or log the error message
        return false;
    }
   }
   


    // Update a user request
    public function update($data)
    {
        try {
            $query = "UPDATE $this->table 
            SET status = :status,
            approved_by = :approved_by,
            returned_by = :returned_by,
            passed_by = :passed_by 
            WHERE request_id = :request_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':approved_by', $data['approved_by']);
            $stmt->bindParam(':returned_by', $data['returned_by']);
            $stmt->bindParam(':passed_by', $data['passed_by']);
            $stmt->bindParam(':request_id', $data['request_id']);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage(); // Output or log the error message
            return false;
        }
    }

    // Delete a user request
    public function delete($request_id)
    {
        try {
            $query = "DELETE FROM $this->table WHERE request_id = :request_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':request_id', $request_id);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
