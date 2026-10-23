<?php
require_once 'Database.php';
require_once 'Product.php';

$database = new Database();
$db = $database->getConnection();
$productModel = new Product($db);

$message = "";
$messageType = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_product') {
    $code = trim($_POST['product_code']);
    $catId = intval($_POST['category_id']);
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $imageUrl = trim($_POST['image_url']);
    $description = trim($_POST['description']);

    if ($code && $catId > 0 && $name && $price > 0 && $stock >= 0) {
        if ($productModel->addProduct($code, $catId, $name, $price, $stock, $imageUrl, $description)) {
            $message = "เพิ่มสินค้าใหม่เข้าคลังสำเร็จ!";
            $messageType = "success";
        } else {
            $message = "เกิดข้อผิดพลาด รหัสสินค้าอาจซ้ำในระบบ";
            $messageType = "danger";
        }
    } else {
        $message = "กรุณากรอกข้อมูลให้ถูกต้องครบถ้วน";
        $messageType = "warning";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_product') {
    $id = intval($_POST['product_id']);
    $code = trim($_POST['product_code']);
    $catId = intval($_POST['category_id']);
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $imageUrl = trim($_POST['image_url']);
    $description = trim($_POST['description']);

    if ($id > 0 && $code && $catId > 0 && $name && $price > 0 && $stock >= 0) {
        if ($productModel->updateProduct($id, $code, $catId, $name, $price, $stock, $imageUrl, $description)) {
            $message = "อัปเดตข้อมูลสินค้าเรียบร้อยแล้ว!";
            $messageType = "success";
        } else {
            $message = "เกิดข้อผิดพลาด ไม่สามารถแก้ไขสินค้าได้";
            $messageType = "danger";
        }
    } else {
        $message = "กรุณากรอกข้อมูลแก้ไขให้ครบถ้วน";
        $messageType = "warning";
    }
}

$categories = $productModel->getAllCategories();
$products = $productModel->getAllProducts();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการคลังสินค้า - Smart Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Prompt', sans-serif; background-color: #f1f5f9; color: #1e293b; }
        .custom-card { background: white; border-radius: 16px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 1.5rem; }
        .card-header-admin { background: #1e293b; color: white; padding: 1.25rem 1.5rem; font-weight: 600; }
        .product-img-thumb { width: 48px; height: 48px; object-fit: cover; border-radius: 8px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><i class="fa-solid fa-boxes-packing text-warning me-2"></i>Smart Store (จัดการคลังสินค้า)</a>
        <div class="d-flex align-items-center gap-3">
            <a href="index.php" class="btn btn-outline-light btn-sm"><i class="fa-solid fa-store me-1"></i> หน้าร้านค้า</a>
        </div>
    </div>
</nav>

<div class="container py-4">

    <?php if ($message): ?>
        <div class="alert alert-<?= $messageType ?> alert-dismissible fade show rounded-3 mb-4">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- ฟอร์มเพิ่มสินค้าใหม่เข้าคลัง -->
        <div class="col-lg-4">
            <div class="custom-card">
                <div class="card-header-admin">
                    <i class="fa-solid fa-square-plus me-2 text-warning"></i> เพิ่มสินค้าใหม่เข้าคลังสินค้า
                </div>
                <div class="card-body p-4">
                    <form method="POST">
                        <input type="hidden" name="action" value="add_product">
                        <div class="mb-3">
                            <label class="form-label fw-medium text-secondary">หมวดหมู่สินค้า</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- เลือกหมวดหมู่ --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium text-secondary">รหัสสินค้า (Product Code)</label>
                            <input type="text" name="product_code" class="form-control" placeholder="เช่น P09" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium text-secondary">ชื่อสินค้า</label>
                            <input type="text" name="name" class="form-control" placeholder="ชื่อสินค้า" required>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-medium text-secondary">ราคา (บาท)</label>
                                <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-medium text-secondary">จำนวนสต็อก</label>
                                <input type="number" name="stock" class="form-control" placeholder="0" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium text-secondary">URL รูปภาพสินค้า</label>
                            <input type="url" name="image_url" class="form-control" placeholder="https://example.com/image.jpg">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium text-secondary">รายละเอียดสินค้า</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="กรอกรายละเอียดสเปกสินค้า..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-warning w-100 fw-bold">
                            <i class="fa-solid fa-plus me-1"></i> บันทึกเข้าคลังสินค้า
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- ตารางรายการสินค้า + ปุ่มกดแก้ไข -->
        <div class="col-lg-8">
            <div class="custom-card">
                <div class="card-header-admin d-flex justify-content-between align-items-center">
                    <span><i class="fa-solid fa-warehouse me-2 text-warning"></i> รายการสินค้าในคลัง</span>
                    <span class="badge bg-warning text-dark"><?= count($products) ?> รายการ</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>รูปภาพ</th>
                                <th>รหัส/ชื่อสินค้า</th>
                                <th>หมวดหมู่</th>
                                <th>ราคา</th>
                                <th>สต็อก</th>
                                <th class="text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $p): ?>
                                <tr>
                                    <td>
                                        <img src="<?= htmlspecialchars($p['image_url']) ?>" class="product-img-thumb" alt="Product">
                                    </td>
                                    <td>
                                        <code class="fw-bold text-dark"><?= htmlspecialchars($p['product_code']) ?></code><br>
                                        <span class="fw-medium"><?= htmlspecialchars($p['name']) ?></span>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($p['category_name']) ?></span></td>
                                    <td><?= number_format($p['price'], 2) ?> ฿</td>
                                    <td>
                                        <?php if ($p['stock'] > 0): ?>
                                            <span class="badge bg-success"><?= $p['stock'] ?> ชิ้น</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">หมดสต็อก</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#editModal<?= $p['id'] ?>">
                                            <i class="fa-solid fa-pen-to-square me-1"></i> แก้ไข
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal แก้ไขข้อมูลสินค้า -->
                                <div class="modal fade" id="editModal<?= $p['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content rounded-4">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-header-title fw-bold mb-0"><i class="fa-solid fa-pen-to-square me-2"></i>แก้ไขข้อมูลสินค้า (<?= htmlspecialchars($p['product_code']) ?>)</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body p-4">
                                                    <input type="hidden" name="action" value="edit_product">
                                                    <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label fw-medium text-secondary">หมวดหมู่สินค้า</label>
                                                        <select name="category_id" class="form-select" required>
                                                            <?php foreach ($categories as $cat): ?>
                                                                <option value="<?= $cat['id'] ?>" <?= $p['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-medium text-secondary">รหัสสินค้า</label>
                                                        <input type="text" name="product_code" class="form-control" value="<?= htmlspecialchars($p['product_code']) ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-medium text-secondary">ชื่อสินค้า</label>
                                                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($p['name']) ?>" required>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-6">
                                                            <label class="form-label fw-medium text-secondary">ราคา (บาท)</label>
                                                            <input type="number" step="0.01" name="price" class="form-control" value="<?= $p['price'] ?>" required>
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label fw-medium text-secondary">จำนวนสต็อก</label>
                                                            <input type="number" name="stock" class="form-control" value="<?= $p['stock'] ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-medium text-secondary">URL รูปภาพสินค้า</label>
                                                        <input type="url" name="image_url" class="form-control" value="<?= htmlspecialchars($p['image_url']) ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-medium text-secondary">รายละเอียดสินค้า</label>
                                                        <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($p['description'] ?? '') ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">ยกเลิก</button>
                                                    <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="fa-solid fa-save me-1"></i> บันทึกการแก้ไข</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>