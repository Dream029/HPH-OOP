<?php
class CustomerOrder {
    private $db;
    private $productModel;

    public function __construct($dbConnection, $productModel) {
        $this->db = $dbConnection;
        $this->productModel = $productModel;
    }

    public function createOrder($customerName, $items, $discountPercent = 0, $userId = null) {
        if (empty($items)) {
            return ["success" => false, "message" => "กรุณาเลือกรายการสินค้าอย่างน้อย 1 รายการ"];
        }

        try {
            $this->db->beginTransaction();

            $subtotal = 0;
            $orderItemsData = [];

            foreach ($items as $item) {
                $product = $this->productModel->getProductById($item['product_id']);
                if (!$product || $product['stock'] < $item['quantity']) {
                    $this->db->rollBack();
                    return ["success" => false, "message" => "สินค้า '" . ($product['name'] ?? 'ไม่พบ') . "' มีสต็อกไม่พอ"];
                }

                $itemSubtotal = $product['price'] * $item['quantity'];
                $subtotal += $itemSubtotal;

                $orderItemsData[] = [
                    'product_id' => $product['id'],
                    'quantity' => $item['quantity'],
                    'price' => $product['price'],
                    'subtotal' => $itemSubtotal
                ];
            }

            $discount = $subtotal * ($discountPercent / 100);
            $netTotal = $subtotal - $discount;
            $orderNo = "ORD-" . date("YmdHis") . "-" . rand(100, 999);

            $stmt = $this->db->prepare("INSERT INTO orders (order_no, user_id, customer_name, subtotal, discount_percent, net_total) VALUES (:order_no, :uid, :cname, :subtotal, :discount, :net)");
            $stmt->execute([
                ':order_no' => $orderNo,
                ':uid' => $userId,
                ':cname' => $customerName,
                ':subtotal' => $subtotal,
                ':discount' => $discountPercent,
                ':net' => $netTotal
            ]);

            $orderId = $this->db->lastInsertId();

            $itemStmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, subtotal) VALUES (:oid, :pid, :qty, :price, :sub)");
            foreach ($orderItemsData as $itemData) {
                $itemStmt->execute([
                    ':oid' => $orderId,
                    ':pid' => $itemData['product_id'],
                    ':qty' => $itemData['quantity'],
                    ':price' => $itemData['price'],
                    ':sub' => $itemData['subtotal']
                ]);

                $this->productModel->updateStock($itemData['product_id'], $itemData['quantity']);
            }

            $this->db->commit();
            return ["success" => true, "message" => "สั่งซื้อเรียบร้อย! เลขที่ใบสั่งซื้อ $orderNo ยอดสุทธิ " . number_format($netTotal, 2) . " บาท", "order_no" => $orderNo];

        } catch (Exception $e) {
            $this->db->rollBack();
            return ["success" => false, "message" => "เกิดข้อผิดพลาด: " . $e->getMessage()];
        }
    }

    public function getAllOrders() {
        $stmt = $this->db->prepare("SELECT * FROM orders ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getOrdersByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE user_id = :uid ORDER BY id DESC");
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll();
    }

    public function getOrderItems($orderId) {
        $stmt = $this->db->prepare("SELECT oi.*, p.name as product_name, p.image_url FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = :oid");
        $stmt->execute([':oid' => $orderId]);
        return $stmt->fetchAll();
    }
}
?>
