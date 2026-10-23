<?php
require_once 'Database.php';
require_once 'Product.php';
require_once 'CustomerOrder.php';
require_once 'Cart.php';

$database = new Database();
$db = $database->getConnection();

$productModel = new Product($db);

// จัดการกดเพิ่มเข้าตะกร้าจากหน้าร้าน
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
    $pid = intval($_POST['product_id']);
    $qty = intval($_POST['quantity'] ?? 1);
    Cart::add($pid, $qty);
    $message = "เพิ่มสินค้าลงตะกร้าเรียบร้อยแล้ว!";
}

$selectedCategory = isset($_GET['category_id']) ? intval($_GET['category_id']) : null;
$categories = $productModel->getAllCategories();
$products = $productModel->getAllProducts($selectedCategory);

$featuredProducts = array_slice($products, 0, 5);
$cartCount = Cart::getTotalCount();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Store - หน้าร้านค้าออนไลน์</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --card-radius: 16px;
            --bg-body: #f1f5f9;
        }
        body { font-family: 'Prompt', sans-serif; background-color: var(--bg-body); color: #1e293b; }
        .custom-card { background: #ffffff; border-radius: var(--card-radius); border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 1.5rem; }
        .product-card { background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; transition: all 0.25s ease; color: inherit; display: flex; flex-direction: column; height: 100%; }
        .product-card:hover { transform: translateY(-6px); box-shadow: 0 12px 25px rgba(79, 70, 229, 0.12); border-color: #a5b4fc; }
        .product-img { width: 100%; height: 180px; object-fit: cover; }
        .cat-btn { border-radius: 20px; font-weight: 500; padding: 0.4rem 1rem; }
        
        .carousel-banner-card {
            background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
            border-radius: 20px;
            padding: 2.5rem;
            color: white;
            min-height: 320px;
        }
        .carousel-product-img {
            max-height: 250px;
            width: 100%;
            object-fit: contain;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.95);
            padding: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        /* แก้ไขบีบปุ่มเลื่อนสไลด์ซ้าย-ขวา ไม่ให้บังปุ่มใส่ตะกร้า */
        .carousel-control-prev, 
        .carousel-control-next {
            width: 5% !important;
            z-index: 5;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3 mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="index.php"><i class="fa-solid fa-store text-indigo me-2"></i>Smart Store</a>
        
        <div class="d-flex align-items-center gap-3">
            <a href="cart_view.php" class="btn btn-warning btn-sm position-relative fw-bold px-3">
                <i class="fa-solid fa-cart-shopping me-1"></i> ตะกร้าสินค้า
                <span class="badge bg-danger rounded-pill ms-1"><?= $cartCount ?></span>
            </a>
            <a href="my_orders.php" class="btn btn-outline-info btn-sm fw-medium"><i class="fa-solid fa-receipt me-1"></i> ประวัติสั่งซื้อ</a>
            <a href="inventory.php" class="btn btn-outline-warning btn-sm fw-bold"><i class="fa-solid fa-boxes-packing me-1"></i> คลังสินค้า</a>
        </div>
    </div>
</nav>

<div class="container pb-5">

    <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm">
            <i class="fa-solid fa-circle-check me-2 fs-5 align-middle"></i>
            <span class="align-middle fw-medium"><?= htmlspecialchars($message) ?></span>
            <a href="cart_view.php" class="btn btn-sm btn-dark rounded-pill ms-3">ไปที่ตะกร้าสินค้าเพื่อชำระเงิน →</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Banner Carousel -->
    <?php if (!empty($featuredProducts)): ?>
        <div id="productAutoCarousel" class="carousel slide mb-4 shadow-lg rounded-4 overflow-hidden" data-bs-ride="carousel" data-bs-interval="3500">
            <div class="carousel-indicators" style="z-index: 10;">
                <?php foreach ($featuredProducts as $index => $fp): ?>
                    <button type="button" data-bs-target="#productAutoCarousel" data-bs-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>"></button>
                <?php endforeach; ?>
            </div>
            <div class="carousel-inner">
                <?php foreach ($featuredProducts as $index => $fp): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <div class="carousel-banner-card">
                            <div class="row align-items-center g-4">
                                <div class="col-md-7 text-white">
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3 fs-6">
                                        <i class="fa-solid fa-fire me-1"></i> สินค้าแนะนำประจำสัปดาห์
                                    </span>
                                    <h2 class="fw-bold mb-2 text-white display-6"><?= htmlspecialchars($fp['name']) ?></h2>
                                    <p class="text-light opacity-90 mb-3 fs-5">
                                        หมวดหมู่: <span class="badge bg-white text-dark"><?= htmlspecialchars($fp['category_name']) ?></span>
                                    </p>
                                    <h3 class="fw-bold text-warning mb-4"><?= number_format($fp['price'], 2) ?> <small class="fs-6 text-white">บาท</small></h3>
                                    
                                    <!-- ดึงชั้นปุ่มขึ้นมาด้วย z-index: 10 ให้คลิกได้ทันที -->
                                    <div class="d-flex gap-2 flex-wrap" style="position: relative; z-index: 10;">
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="action" value="add_to_cart">
                                            <input type="hidden" name="product_id" value="<?= $fp['id'] ?>">
                                            <button type="submit" class="btn btn-warning btn-lg rounded-pill fw-bold px-4 shadow">
                                                <i class="fa-solid fa-cart-plus me-1"></i> ใส่ตะกร้า
                                            </button>
                                        </form>
                                        <a href="product_detail.php?id=<?= $fp['id'] ?>" class="btn btn-light text-primary btn-lg rounded-pill fw-bold px-4 shadow">
                                            ดูรายละเอียด
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-5 text-center">
                                    <img src="<?= htmlspecialchars($fp['image_url']) ?>" class="carousel-product-img" alt="<?= htmlspecialchars($fp['name']) ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#productAutoCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productAutoCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Category Filter Bar -->
    <div class="custom-card p-3 mb-4">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="fw-bold me-2"><i class="fa-solid fa-filter me-1 text-primary"></i> หมวดหมู่สินค้า:</span>
            <a href="index.php" class="btn btn-<?= $selectedCategory === null ? 'primary' : 'light' ?> cat-btn">ทั้งหมด</a>
            <?php foreach ($categories as $cat): ?>
                <a href="index.php?category_id=<?= $cat['id'] ?>" class="btn btn-<?= $selectedCategory == $cat['id'] ? 'primary' : 'light' ?> cat-btn">
                    <?= htmlspecialchars($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Product Grid Showcase -->
    <div class="row g-4 mb-5">
        <div class="col-12">
            <h4 class="fw-bold mb-3"><i class="fa-solid fa-boxes-stacked me-2 text-primary"></i>รายการสินค้าทั้งหมด</h4>
        </div>
        
        <?php if (empty($products)): ?>
            <div class="col-12 text-center py-5 text-muted">
                <i class="fa-solid fa-box-open fs-1 mb-2 d-block"></i>
                ไม่พบสินค้าในหมวดหมู่นี้
            </div>
        <?php else: ?>
            <?php foreach ($products as $p): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card">
                        <a href="product_detail.php?id=<?= $p['id'] ?>">
                            <img src="<?= htmlspecialchars($p['image_url']) ?>" class="product-img" alt="<?= htmlspecialchars($p['name']) ?>">
                        </a>
                        <div class="p-3 d-flex flex-column flex-grow-1">
                            <span class="badge bg-light text-muted border align-self-start mb-2 small"><?= htmlspecialchars($p['category_name']) ?></span>
                            <a href="product_detail.php?id=<?= $p['id'] ?>" class="text-decoration-none text-dark">
                                <h6 class="fw-bold mb-1 text-truncate" title="<?= htmlspecialchars($p['name']) ?>"><?= htmlspecialchars($p['name']) ?></h6>
                            </a>
                            <small class="text-muted mb-2">รหัส: <?= htmlspecialchars($p['product_code']) ?></small>
                            <div class="mt-auto pt-2 border-top">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-primary fs-5"><?= number_format($p['price'], 2) ?> ฿</span>
                                    <?php if ($p['stock'] <= 0): ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">หมด</span>
                                    <?php endif; ?>
                                </div>
                                <?php if ($p['stock'] > 0): ?>
                                    <form method="POST">
                                        <input type="hidden" name="action" value="add_to_cart">
                                        <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-primary w-100 rounded-pill fw-medium">
                                            <i class="fa-solid fa-cart-plus me-1"></i> เพิ่มลงตะกร้า
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>