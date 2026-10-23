<?php
class Product {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getAllCategories() {
        $stmt = $this->db->prepare("SELECT * FROM categories ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllProducts($categoryId = null) {
        if ($categoryId) {
            $stmt = $this->db->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.category_id = :cid ORDER BY p.id DESC");
            $stmt->execute([':cid' => $categoryId]);
        } else {
            $stmt = $this->db->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }

    public function addProduct($code, $categoryId, $name, $price, $stock, $imageUrl, $description = '') {
        if (empty($imageUrl)) {
            $imageUrl = 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=300&auto=format&fit=crop&q=80';
        }
        $stmt = $this->db->prepare("INSERT INTO products (product_code, category_id, name, price, stock, image_url, description) VALUES (:code, :cat_id, :name, :price, :stock, :img, :desc)");
        return $stmt->execute([
            ':code' => $code,
            ':cat_id' => $categoryId,
            ':name' => $name,
            ':price' => $price,
            ':stock' => $stock,
            ':img' => $imageUrl,
            ':desc' => $description
        ]);
    }

    public function updateProduct($id, $code, $categoryId, $name, $price, $stock, $imageUrl, $description = '') {
        if (empty($imageUrl)) {
            $imageUrl = 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=300&auto=format&fit=crop&q=80';
        }
        $stmt = $this->db->prepare("UPDATE products SET product_code = :code, category_id = :cat_id, name = :name, price = :price, stock = :stock, image_url = :img, description = :desc WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':code' => $code,
            ':cat_id' => $categoryId,
            ':name' => $name,
            ':price' => $price,
            ':stock' => $stock,
            ':img' => $imageUrl,
            ':desc' => $description
        ]);
    }

    public function getProductById($id) {
        $stmt = $this->db->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function updateStock($id, $quantity) {
        $stmt = $this->db->prepare("UPDATE products SET stock = stock - :qty WHERE id = :id AND stock >= :qty");
        return $stmt->execute([':qty' => $quantity, ':id' => $id]);
    }
}
?>
