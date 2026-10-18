
<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/classes/Database.php';

try {
    $database = new Database();
    $pdo = $database->connect();

    echo "เชื่อมต่อ MySQL สำเร็จ!";
} catch (Throwable $e) {
    echo "เกิดข้อผิดพลาด: ";
    echo htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}
?>
