<?php
require_once 'Database.php';
require_once 'Product.php';
require_once 'CustomerOrder.php';
require_once 'Cart.php';

$database = new Database();
$db = $database->getConnection();

$productModel = new Product($db);

$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = $productModel->getProductById($productId);

if (!$product) {
    header("Location: index.php");
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_to_cart') {
        $quantity = intval($_POST['quantity']);
        if ($quantity > 0) {
            Cart::add($product['id'], $quantity);
            $message = "เพิ่มสินค้าใส่ตะกร้าเรียบร้อยแล้ว!";
        }
    }
}

$cartCount = Cart::getTotalCount();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - Smart Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Prompt', sans-serif; background-color: #f1f5f9; color: #1e293b; }
        .detail-card { background: white; border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; }
        .product-large-img { width: 100%; height: 380px; object-fit: cover; border-radius: 16px; }
        .btn-gradient-indigo { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white; border: none; border-radius: 12px; }
        .btn-gradient-indigo:hover { color: white; opacity: 0.95; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3 mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="index.php"><i class="fa-solid fa-store text-indigo me-2"></i>Smart Store</a>
        <div class="d-flex align-items-center gap-3">
            <a href="cart_view.php" class="btn btn-warning btn-sm position-relative fw-bold px-3">
                <i class="fa-solid fa-cart-shopping me-1"></i> ตะกร้าสินค้า
                <span class="badge bg-danger rounded-pill ms-1"><?= $cartCount ?></span>
            </a>
            <a href="my_orders.php" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-receipt me-1"></i> ประวัติสั่งซื้อ</a>
            <a href="inventory.php" class="btn btn-outline-warning btn-sm fw-bold"><i class="fa-solid fa-boxes-packing me-1"></i> คลังสินค้า</a>
        </div>
    </div>
</nav>

<div class="container py-3 mb-5">
    
    <a href="index.php" class="btn btn-light rounded-pill px-4 mb-4 text-secondary shadow-sm">
        <i class="fa-solid fa-arrow-left me-2"></i> ย้อนกลับไปหน้าร้านค้า
    </a>

    <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm">
            <i class="fa-solid fa-circle-check text-success me-2 fs-5 align-middle"></i>
            <span class="align-middle fw-medium"><?= htmlspecialchars($message) ?></span>
            <a href="cart_view.php" class="btn btn-sm btn-dark rounded-pill ms-3">ไปที่ตะกร้าสินค้าเพื่อชำระเงิน →</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="detail-card p-4 p-md-5">
        <div class="row g-4 align-items-center">
            
            <div class="col-md-6 text-center">
                <img src="<?= htmlspecialchars($product['image_url']) ?>" class="product-large-img shadow-sm" alt="<?= htmlspecialchars($product['name']) ?>">
            </div>

            <div class="col-md-6">
                <span class="badge bg-light text-primary border px-3 py-2 rounded-pill fs-6 mb-2">
                    <i class="fa-solid fa-tag me-1"></i> <?= htmlspecialchars($product['category_name']) ?>
                </span>
                
                <h2 class="fw-bold text-dark mt-1 mb-2"><?= htmlspecialchars($product['name']) ?></h2>
                <p class="text-muted small">รหัสสินค้า: <code><?= htmlspecialchars($product['product_code']) ?></code></p>
                
                <div class="my-3 p-3 bg-light rounded-3">
                    <span class="text-muted small d-block mb-1">ราคาขาย</span>
                    <h1 class="fw-bold text-primary mb-0"><?= number_format($product['price'], 2) ?> <small class="fs-5 text-muted">บาท</small></h1>
                </div>

                <p class="text-secondary mb-4"><?= htmlspecialchars($product['description'] ?: 'สินค้าไอทีคุณภาพสูง รับประกันศูนย์แท้ 100% พร้อมบริการจัดส่งด่วนทั่วประเทศ') ?></p>

                <div class="d-flex align-items-center gap-3 mb-4">
                    <span class="fw-medium">สถานะสินค้าในคลัง:</span>
                    <?php if ($product['stock'] > 0): ?>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fs-6">
                            <i class="fa-solid fa-circle-check me-1"></i> มีสินค้าพร้อมส่ง (คงเหลือ <?= $product['stock'] ?> ชิ้น)
                        </span>
                    <?php else: ?>
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 fs-6">
                            <i class="fa-solid fa-circle-xmark me-1"></i> สินค้าหมดชั่วคราว
                        </span>
                    <?php endif; ?>
                </div>

                <?php if ($product['stock'] > 0): ?>
                    <form method="POST" class="p-3 border rounded-3 bg-light">
                        <input type="hidden" name="action" value="add_to_cart">
                        
                        <div class="mb-3">
                            <label class="form-label fw-medium text-secondary">จำนวนที่ต้องการสั่งซื้อ</label>
                            <input type="number" name="quantity" min="1" max="<?= $product['stock'] ?>" value="1" class="form-control" required>
                        </div>

                        <div class="row g-2">
                            <div class="col-12">
                                <button type="submit" class="btn btn-gradient-indigo w-100 py-3 fw-bold fs-5 shadow-sm">
                                    <i class="fa-solid fa-cart-plus me-2"></i> เพิ่มสินค้าลงตะกร้า
                                </button>
                            </div>
                        </div>
                    </form>
                <?php else: ?>
                    <button class="btn btn-secondary w-100 py-3 fw-bold fs-5" disabled>
                        <i class="fa-solid fa-ban me-2"></i> สินค้าหมดชั่วคราว ไม่สามารถสั่งซื้อได้
                    </button>
                <?php endif; ?>

            </div>

        </div>
    </div>

</div>

</body>
</html>