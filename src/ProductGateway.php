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
}