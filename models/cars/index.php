<?php
include_once "../../config/connection.php";

class CarInfo
{
    private $conn;
    private $table = 'CarInformation';
    public $id;
    public $make;
    public $model;
    public $year;
    public $color;
    public $vin;
    public $registration_plate;
    public $service_id;
    public $car_image;
    public $is_available;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM $this->table WHERE vin = :vin OR registration_plate = :registration_plate");
            $stmt->bindParam(':vin', $data["vin"]);
            $stmt->bindParam(':registration_plate', $data["registration_plate"]);

            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                return false;
            }

            $insertStmt = $this->conn->prepare("INSERT INTO $this->table (make, model, year, color, vin, registration_plate, owner_id, service_id, car_image) 
                                                VALUES (:make, :model, :year, :color, :vin, :registration_plate,:owner_id,  :service_id, :car_image)");
            $insertStmt->execute([
                "make" => $data["make"],
                "model" => $data["model"],
                "year" => $data["year"],
                "color" => $data["color"],
                "vin" => $data["vin"],
                "registration_plate" => $data["registration_plate"],
                "service_id" => $data["service_id"],
                "owner_id" => $data["owner_id"],
                "car_image" => $data["car_image"]
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function update($data)
    {
        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $checkStmt = $this->conn->prepare("SELECT COUNT(*) FROM " . $this->table . " WHERE id = :id");
            $checkStmt->bindParam(':id', $data["id"]);
            $checkStmt->execute();
            $count = $checkStmt->fetchColumn();
    
            if ($count === 0) {
                return false;
            }
    
            $updateStmt = $this->conn->prepare("UPDATE " . $this->table . " SET
                                                make = :make,
                                                model = :model,
                                                year = :year,
                                                color = :color,
                                                vin = :vin,
                                                registration_plate = :registration_plate,
                                                id = :id,
                                                service_id = :service_id,
                                                car_image = :car_image,
                                                is_available=:is_available
                                                WHERE id = :id");
    
            $updateStmt->execute([
                "make" => $data["make"],
                "model" => $data["model"],
                "year" => $data["year"],
                "color" => $data["color"],
                "vin" => $data["vin"],
                "registration_plate" => $data["registration_plate"],
                "id" => $data["id"],
                "service_id" => $data["service_id"],
                "car_image" => $data["car_image"],
                "id" => $data["id"],
                "is_available"=>$data["is_available"]
            ]);
    
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage(); // Output or log the error message
            return false;
        }
    }
    
    public function readAll()
    {
        try {
            $query = "SELECT ci.id, ci.make, ci.model, ci.year, ci.color, ci.vin, ci.registration_plate, 
            ci.id, ci.service_id, ci.car_image, ci.is_available,
            o.first_name as owner_first_name, o.last_name as owner_last_name,  o.id as owner_id,
            o.email as owner_email, o.phone_number as owner_phone_number, o.address as owner_address,
            cs.id as service_id, cs.car_icon as service_icon, cs.title as service_title
     FROM $this->table ci
     LEFT JOIN Owners o ON ci.owner_id = o.id
     LEFT JOIN car_services cs ON ci.service_id = cs.id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            if ($stmt) {
                return $stmt;
            } else {
                // Log error or return false
                error_log("Error executing SQL query: " . print_r($stmt->errorInfo(), true));
                return false;
            }
        } catch (PDOException $e) {
            // Log error or return false
            error_log("PDOException in readAll method: " . $e->getMessage());
            return false;
        }
    }
    
    public function readByServiceAll($service_id)
    {
        try {
            $query = "SELECT ci.id, ci.make, ci.model, ci.year, ci.color, ci.vin, ci.registration_plate, 
                ci.car_image, o.first_name as owner_first_name, o.last_name as owner_last_name,
                o.id as owner_id, o.email as owner_email, o.phone_number as owner_phone_number,
                o.address as owner_address, cs.car_icon as service_icon, cs.title as service_title
                FROM CarInformation ci
                LEFT JOIN Owners o ON ci.owner_id = o.id
                LEFT JOIN car_services cs ON ci.service_id = cs.id
                WHERE ci.service_id = :service_id";
    
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':service_id', $service_id);
            $stmt->execute();
    
            if ($stmt) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                // Log error or return false
                error_log("Error executing SQL query: " . print_r($stmt->errorInfo(), true));
                return false;
            }
        } catch (PDOException $e) {
            // Log error or return false
            error_log("PDOException in readAll method: " . $e->getMessage());
            return false;
        }
    }
    
    

    public function delete($id)
    {
        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $deleteStmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = :id");
            $deleteStmt->bindParam(':id', $id);
            $deleteStmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
