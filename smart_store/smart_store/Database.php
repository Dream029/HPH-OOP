<?php
class Database {
    private $host = "localhost";
    private $db_name = "smart_store";
    private $username = "root";
    private $password = "";
    private $conn = null;

    public function getConnection() {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO(
                    "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                    $this->username,
                    $this->password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $exception) {
                die("เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: " . $exception->getMessage());
            }
        }
        return $this->conn;
    }

    public function getSalesSummary() {
        $db = $this->getConnection();
        $orderStmt = $db->query("SELECT COUNT(*) as total_orders, COALESCE(SUM(net_total), 0) as total_revenue FROM orders");
        $salesData = $orderStmt->fetch();
        $prodStmt = $db->query("SELECT COUNT(*) as total_products, COALESCE(SUM(stock), 0) as total_stock FROM products");
        $prodData = $prodStmt->fetch();
        return array_merge($salesData, $prodData);
    }
}
?>
