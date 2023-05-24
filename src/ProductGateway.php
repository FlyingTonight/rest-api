<?php
class ProductGateway
{
    private PDO $conn;
    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }
    public function getAll():array
    {
        $sql = "SELECT * FROM item";

        $stmt = $this->conn->query($sql);

        $data = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $data[] = $row;
        }
        return $data;
    }
    public function create(array $data):string
    {
        $sql = "INSERT INTO items (name, phone, key)
                VALUES (:name, :phone :key)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindValue(":phone", $data["phone"]?? 0, PDO::PARAM_INT);
        $stmt->bindValue("key", $data["key"]?? 0, PDO::PARAM_INT);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    public function get(string $id): array
    {
        $sql = "SELECT * FROM item WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($data === false) {
        
            return []; 
        }
        
        return $data;
    }
    public function update(array $current, array $new):int
    {
        $sql = "UPDATE product
                SET name = :name, phone = :phone, key = :key
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":name",$new["name"] ?? $current["name"],
        PDO::PARAM_STR);
        $stmt->bindValue(":phone",$new["phone"] ?? $current["phone"],
        PDO::PARAM_INT);
        $stmt->bindValue(":key",$new["key"] ?? $current["key"],
        PDO::PARAM_STR);

        $stmt->bindValue(":id", $current["id"],PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }
    public function delete(string $id):int
    {
        $sql = "DELETE FROM item
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }
}