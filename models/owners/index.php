<?php
include_once "../../config/config.php";
include_once "../../config/connection.php";

class OwnerCar
{
    private $conn;
    private $table = 'Owners';
    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $phone_number;
    public $address;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM $this->table WHERE email = :email");
            $stmt->bindParam(':email', $data["email"]);

            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                return false;
            }

            $insertStmt = $this->conn->prepare("INSERT INTO $this->table (first_name, last_name, email, phone_number, address) 
                                                VALUES (:first_name, :last_name, :email, :phone_number, :address)");
            $insertStmt->execute([
                "first_name" => $data["first_name"],
                "last_name" => $data["last_name"],
                "email" => $data["email"],
                "phone_number" => $data["phone_number"],
                "address" => $data["address"]
            ]);

            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage(); // Output or log the error message
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
                                                first_name = :first_name,
                                                last_name = :last_name,
                                                email = :email,
                                                phone_number = :phone_number,
                                                address = :address
                                                WHERE id = :id");

            $updateStmt->execute([
                "first_name" => $data["first_name"],
                "last_name" => $data["last_name"],
                "email" => $data["email"],
                "phone_number" => $data["phone_number"],
                "address" => $data["address"],
                "id" => $data["id"]
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
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $query = "SELECT * FROM " . $this->table;

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $checkStmt = $this->conn->prepare("SELECT COUNT(*) FROM " . $this->table . " WHERE id = :id");
            $checkStmt->bindParam(':id', $id);
            $checkStmt->execute();
            $count = $checkStmt->fetchColumn();

            if ($count === 0) {
                return false;
            }

            $deleteStmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id = :id");
            $deleteStmt->bindParam(':id', $id);
            $deleteStmt->execute();

            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage(); // Output or log the error message
            return false;
        }
    }
}
?>
