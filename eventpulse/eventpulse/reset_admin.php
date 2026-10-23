<?php
require_once __DIR__ . '/config/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    $email = 'yomjai1234@gmail.com';
    $password = '12345678';
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // ลบบัญชีเดิมออกก่อนเพื่อป้องกันข้อมูลซ้ำ
    $stmt = $db->prepare("DELETE FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);

    // สร้างบัญชี Admin ใหม่
    $stmt = $db->prepare("INSERT INTO users (name, email, password, role) VALUES ('ผู้ดูแลระบบ', :email, :password, 'admin')");
    $stmt->execute(['email' => $email, 'password' => $hashedPassword]);

    echo "<h2 style='color: green;'>รีเซ็ตบัญชี Admin เรียบร้อยแล้ว!</h2>";
    echo "<p><b>อีเมล:</b> admin@eventpulse.com<br><b>รหัสผ่าน:</b> 123456</p>";
    echo "<a href='index.php?action=login'>กดที่นี่เพื่อเข้าสู่ระบบ</a>";
} catch (Exception $e) {
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
}