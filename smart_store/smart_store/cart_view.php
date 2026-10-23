<?php
require_once 'Database.php';
require_once 'Product.php';
require_once 'CustomerOrder.php';
require_once 'Cart.php';

$database = new Database();
$db = $database->getConnection();

$productModel = new Product($db);
$orderModel = new CustomerOrder($db, $productModel);

$message = "";
$messageType = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'update_qty') {
            $pid = intval($_POST['product_id']);
            $qty = intval($_POST['quantity']);
            Cart::update($pid, $qty);
        } elseif ($_POST['action'] === 'remove') {
            $pid = intval($_POST['product_id']);
            Cart::remove($pid);
        } elseif ($_POST['action'] === 'checkout') {
            $customerName = trim($_POST['customer_name']);
            $discountPercent = floatval($_POST['discount_percent'] ?? 0);
            $cartItems = Cart::getItems();

            if (!empty($customerName) && !empty($cartItems)) {
                $orderItems = [];
                foreach ($cartItems as $pid => $qty) {
                    $orderItems[] = [
                        'product_id' => $pid,
                        'quantity' => $qty
                    ];
                }

                $res = $orderModel->createOrder($customerName, $orderItems, $discountPercent, null);

                if ($res['success']) {
                    Cart::clear();
                    $message = $res['message'];
                    $messageType = "success";
                } else {
                    $message = $res['message'];
                    $messageType = "danger";
                }
            } else {
                $message = "กรุณากรอกชื่อผู้สั่งซื้อและเลือกสินค้าในตะกร้าอย่างน้อย 1 รายการ";
                $messageType = "warning";
            }
        }
    }
}

$cartItemsRaw = Cart::getItems();
$cartProducts = [];
$totalSubtotal = 0;

foreach ($cartItemsRaw as $pid => $qty) {
    $p = $productModel->getProductById($pid);
    if ($p) {
        $p['cart_qty'] = $qty;
        $p['item_subtotal'] = $p['price'] * $qty;
        $totalSubtotal += $p['item_subtotal'];
        $cartProducts[] = $p;
    }
}

$cartCount = Cart::getTotalCount();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้าสินค้า - Smart Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Prompt', sans-serif; background-color: #f1f5f9; color: #1e293b; }
        .custom-card { background: #ffffff; border-radius: 16px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.04); overflow: hidden; }
        .cart-img { width: 64px; height: 64px; object-fit: cover; border-radius: 10px; }
        .btn-gradient-indigo { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white; border: none; }
        .btn-gradient-indigo:hover { color: white; opacity: 0.95; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3 mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="index.php"><i class="fa-solid fa-store text-indigo me-2"></i>Smart Store</a>
        
        <div class="d-flex align-items-center gap-3">
            <a href="cart_view.php" class="btn btn-warning btn-sm position-relative fw-bold px-3 active">
                <i class="fa-solid fa-cart-shopping me-1"></i> ตะกร้าสินค้า
                <span class="badge bg-danger rounded-pill ms-1"><?= $cartCount ?></span>
            </a>
            <a href="my_orders.php" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-receipt me-1"></i> ประวัติสั่งซื้อ</a>
            <a href="inventory.php" class="btn btn-outline-warning btn-sm fw-bold"><i class="fa-solid fa-boxes-packing me-1"></i> คลังสินค้า</a>
        </div>
    </div>
</nav>

<div class="container py-3 mb-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1"><i class="fa-solid fa-cart-shopping text-primary me-2"></i>ตะกร้าสินค้าของฉัน</h3>
            <p class="text-muted small mb-0">ตรวจสอบรายการสินค้าและยืนยันการชำระเงิน</p>
        </div>
        <a href="index.php" class="btn btn-light rounded-pill px-4 shadow-sm text-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> เลือกซื้อสินค้าเพิ่ม
        </a>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-<?= $messageType ?> alert-dismissible fade show rounded-3 mb-4 shadow-sm">
            <i class="fa-solid <?= $messageType === 'success' ? 'fa-circle-check text-success' : 'fa-triangle-exclamation text-danger' ?> me-2 fs-5 align-middle"></i>
            <span class="align-middle fw-medium"><?= htmlspecialchars($message) ?></span>
            <?php if ($messageType === 'success'): ?>
                <div class="mt-2">
                    <a href="my_orders.php" class="btn btn-sm btn-success rounded-pill me-2"><i class="fa-solid fa-receipt me-1"></i> ดูประวัติการสั่งซื้อ</a>
                    <a href="index.php" class="btn btn-sm btn-outline-secondary rounded-pill">กลับหน้าร้านค้า</a>
                </div>
            <?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($cartProducts) && $messageType !== 'success'): ?>
        <div class="custom-card p-5 text-center text-muted">
            <i class="fa-solid fa-cart-flatbed fs-1 text-secondary mb-3 d-block"></i>
            <h5>ตะกร้าสินค้าของคุณยังว่างอยู่</h5>
            <p class="small mb-4">คุณยังไม่ได้เลือกสินค้าใส่ตะกร้า ไปเลือกสินค้าไอทีที่คุณต้องการกันเลย!</p>
            <a href="index.php" class="btn btn-primary rounded-pill px-4"><i class="fa-solid fa-store me-1"></i> ไปเลือกซื้อสินค้า</a>
        </div>
    <?php elseif (!empty($cartProducts)): ?>
        <div class="row g-4">
            
            <div class="col-lg-8">
                <div class="custom-card p-4">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">รายการสินค้าในตะกร้า (<?= count($cartProducts) ?> รายการ)</h5>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light small">
                                <tr>
                                    <th>สินค้า</th>
                                    <th>ราคา</th>
                                    <th style="width: 130px;">จำนวน</th>
                                    <th class="text-end">ราคารวม</th>
                                    <th class="text-center">ลบ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartProducts as $cp): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="<?= htmlspecialchars($cp['image_url']) ?>" class="cart-img shadow-sm" alt="product">
                                                <div>
                                                    <h6 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($cp['name']) ?></h6>
                                                    <small class="text-muted">รหัส: <?= htmlspecialchars($cp['product_code']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-medium"><?= number_format($cp['price'], 2) ?> ฿</td>
                                        <td>
                                            <form method="POST" class="d-flex align-items-center gap-1">
                                                <input type="hidden" name="action" value="update_qty">
                                                <input type="hidden" name="product_id" value="<?= $cp['id'] ?>">
                                                <input type="number" name="quantity" min="1" max="<?= $cp['stock'] ?>" value="<?= $cp['cart_qty'] ?>" class="form-control form-control-sm text-center" onchange="this.form.submit()">
                                            </form>
                                        </td>
                                        <td class="text-end fw-bold text-primary"><?= number_format($cp['item_subtotal'], 2) ?> ฿</td>
                                        <td class="text-center">
                                            <form method="POST">
                                                <input type="hidden" name="action" value="remove">
                                                <input type="hidden" name="product_id" value="<?= $cp['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0 rounded-circle"><i class="fa-solid fa-trash-can"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="custom-card p-4">
                    <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="fa-solid fa-credit-card me-2 text-primary"></i>สรุปการสั่งซื้อ</h5>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">ยอดรวมสินค้า:</span>
                        <span class="fw-bold"><?= number_format($totalSubtotal, 2) ?> ฿</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span class="text-muted">ค่าจัดส่ง:</span>
                        <span class="text-success fw-bold">ฟรี</span>
                    </div>

                    <form method="POST">
                        <input type="hidden" name="action" value="checkout">

                        <div class="mb-3">
                            <label class="form-label fw-medium text-secondary">ชื่อผู้สั่งซื้อ / ชื่อลูกค้า</label>
                            <input type="text" name="customer_name" class="form-control" placeholder="กรอกชื่อของคุณ" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium text-secondary">ส่วนลดเพิ่มเติม (%)</label>
                            <input type="number" step="0.1" name="discount_percent" value="0" class="form-control">
                        </div>

                        <div class="d-flex justify-content-between mb-4 align-items-center">
                            <span class="fs-5 fw-bold">ยอดชำระรวม:</span>
                            <span class="fs-3 fw-bold text-primary"><?= number_format($totalSubtotal, 2) ?> ฿</span>
                        </div>

                        <button type="submit" class="btn btn-gradient-indigo w-100 py-3 fw-bold fs-5 rounded-3 shadow">
                            <i class="fa-solid fa-circle-check me-2"></i> ยืนยันสั่งซื้อ & ชำระเงิน
                        </button>
                    </form>
                </div>
            </div>

        </div>
    <?php endif; ?>

</div>

</body>
</html>