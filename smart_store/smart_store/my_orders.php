<?php
require_once 'Database.php';
require_once 'User.php';
require_once 'Product.php';
require_once 'CustomerOrder.php';

$database = new Database();
$db = $database->getConnection();

$productModel = new Product($db);
$orderModel = new CustomerOrder($db, $productModel);

$currentUser = User::currentUser();

if (!$currentUser) {
    header("Location: login.php");
    exit();
}

$myOrders = $orderModel->getOrdersByUserId($currentUser['id']);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการสั่งซื้อของฉัน - Smart Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Prompt', sans-serif; background-color: #f1f5f9; color: #1e293b; }
        .custom-card { background: #ffffff; border-radius: 16px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.04); overflow: hidden; }
        .order-item-img { width: 42px; height: 42px; object-fit: cover; border-radius: 8px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3 mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="index.php"><i class="fa-solid fa-store text-indigo me-2"></i>Smart Store</a>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white small"><i class="fa-solid fa-user-circle me-1"></i> <?= htmlspecialchars($currentUser['fullname']) ?></span>
            <a href="index.php" class="btn btn-outline-light btn-sm"><i class="fa-solid fa-store me-1"></i> หน้าร้านค้า</a>
            <a href="logout.php" class="btn btn-danger btn-sm"><i class="fa-solid fa-right-from-bracket"></i> ออกจากระบบ</a>
        </div>
    </div>
</nav>

<div class="container py-3 mb-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1"><i class="fa-solid fa-receipt text-primary me-2"></i>ประวัติการสั่งซื้อของฉัน</h3>
            <p class="text-muted small mb-0">รายการคำสั่งซื้อทั้งหมดที่ผูกกับบัญชีคุณ (<?= htmlspecialchars($currentUser['fullname']) ?>)</p>
        </div>
        <a href="index.php" class="btn btn-primary rounded-pill px-4">
            <i class="fa-solid fa-cart-plus me-1"></i> เลือกซื้อสินค้าเพิ่ม
        </a>
    </div>

    <?php if (empty($myOrders)): ?>
        <div class="custom-card p-5 text-center text-muted">
            <i class="fa-solid fa-receipt fs-1 text-secondary mb-3 d-block"></i>
            <h5>คุณยังไม่มีประวัติการสั่งซื้อสินค้า</h5>
            <p class="small mb-3">เมื่อคุณทำการสั่งซื้อสินค้า รายการออเดอร์จะมาปรากฏในหน้านี้</p>
            <a href="index.php" class="btn btn-outline-primary rounded-pill px-4">ไปเลือกซื้อสินค้า</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($myOrders as $order): ?>
                <?php $items = $orderModel->getOrderItems($order['id']); ?>
                <div class="col-12">
                    <div class="custom-card">
                        <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <span class="badge bg-primary me-2">เลขที่ออเดอร์</span>
                                <code class="fw-bold text-dark fs-6"><?= htmlspecialchars($order['order_no']) ?></code>
                                <span class="text-muted small ms-2"><i class="fa-solid fa-calendar me-1"></i> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?> น.</span>
                            </div>
                            <div>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-bold">
                                    <i class="fa-solid fa-circle-check me-1"></i> ทำรายการสำเร็จ
                                </span>
                            </div>
                        </div>
                        <div class="p-3">
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0">
                                    <thead class="text-muted small border-bottom">
                                        <tr>
                                            <th>รายการสินค้า</th>
                                            <th class="text-center">ราคา/ชิ้น</th>
                                            <th class="text-center">จำนวน</th>
                                            <th class="text-end">ราคารวม</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <img src="<?= htmlspecialchars($item['image_url']) ?>" class="order-item-img">
                                                        <span class="fw-medium text-dark"><?= htmlspecialchars($item['product_name']) ?></span>
                                                    </div>
                                                </td>
                                                <td class="text-center"><?= number_format($item['price'], 2) ?> ฿</td>
                                                <td class="text-center"><span class="badge bg-light text-dark border"><?= $item['quantity'] ?></span></td>
                                                <td class="text-end fw-bold"><?= number_format($item['subtotal'], 2) ?> ฿</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="p-3 bg-light border-top text-end">
                            <span class="text-muted small me-3">รวมเงิน: <strong><?= number_format($order['subtotal'], 2) ?> ฿</strong></span>
                            <?php if ($order['discount_percent'] > 0): ?>
                                <span class="text-danger small me-3">ส่วนลด (<?= $order['discount_percent'] ?>%): <strong>-<?= number_format($order['subtotal'] * ($order['discount_percent']/100), 2) ?> ฿</strong></span>
                            <?php endif; ?>
                            <span class="fs-5 fw-bold text-success ms-2">ยอดชำระสุทธิ: <?= number_format($order['net_total'], 2) ?> ฿</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

</body>
</html>
