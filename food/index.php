<?php
require_once 'db.php';

// จัดการการลบข้อมูล
if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM foods WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);
    header("Location: index.php");
    exit;
}

// ดึงข้อมูลอาหาร
$stmt = $pdo->query("SELECT * FROM foods ORDER BY id DESC");
$foods = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ระบบจัดการข้อมูลอาหารและสูตรอาหาร</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        img.food-img {
            width: 120px;
            height: 90px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
    </style>

</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>📋 รายการเมนูอาหารทั้งหมด</h2>

            <a href="manage.php" class="btn btn-primary">
                + เพิ่มเมนูอาหารใหม่
            </a>
        </div>

        <div class="card shadow">

            <div class="card-body p-0">

                <table class="table table-striped table-hover mb-0">

                    <thead class="table-dark">

                        <tr>

                            <th width="15%">รูปอาหาร</th>

                            <th width="20%">ชื่ออาหาร</th>

                            <th width="15%">หมวดหมู่</th>

                            <th width="35%">วัตถุดิบ</th>

                            <th width="15%" class="text-center">จัดการ</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php if (empty($foods)): ?>

                            <tr>

                                <td colspan="5" class="text-center p-4">

                                    ยังไม่มีข้อมูลอาหาร

                                </td>

                            </tr>

                        <?php else: ?>

                            <?php foreach ($foods as $food): ?>

                                <?php

                                $stmtRecipe = $pdo->prepare("SELECT * FROM recipes WHERE food_id=?");

                                $stmtRecipe->execute([$food['id']]);

                                $recipes = $stmtRecipe->fetchAll();

                                ?>

                                <tr>

                                    <td>

                                        <?php if (!empty($food['image'])): ?>

                                            <img class="food-img" src="images/<?= htmlspecialchars($food['image']) ?>">

                                        <?php else: ?>

                                            ไม่มีรูป

                                        <?php endif; ?>

                                    </td>

                                    <td>

                                        <strong>

                                            <?= htmlspecialchars($food['name_th']) ?>

                                        </strong>

                                    </td>

                                    <td>

                                        <span class="badge bg-info text-dark">

                                            <?= htmlspecialchars($food['category']) ?>

                                        </span>

                                    </td>

                                    <td>

                                        <?php if (!empty($recipes)): ?>

                                            <ul class="mb-0">

                                                <?php foreach ($recipes as $r): ?>

                                                    <li>

                                                        <?= htmlspecialchars($r['recipe_name']) ?>

                                                        <?= $r['quantity'] ?>

                                                        <?= htmlspecialchars($r['unit_name']) ?>

                                                    </li>

                                                <?php endforeach; ?>

                                            </ul>

                                        <?php else: ?>

                                            ไม่มีข้อมูลวัตถุดิบ

                                        <?php endif; ?>

                                    </td>

                                    <td class="text-center">

                                        <a href="manage.php?id=<?= $food['id'] ?>" class="btn btn-warning btn-sm">

                                            แก้ไข

                                        </a>

                                        <a href="index.php?delete_id=<?= $food['id'] ?>" class="btn btn-danger btn-sm"
                                            onclick="return confirm('ต้องการลบข้อมูลนี้ใช่หรือไม่ ?')">

                                            ลบ

                                        </a>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</body>

</html>