<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($email) || empty($password)) {
                $error = "กรุณากรอกอีเมลและรหัสผ่านให้ครบถ้วน";
                require __DIR__ . '/../views/auth/login.php';
                return;
            }

            $userData = User::findByEmail($email);
            if ($userData && password_verify($password, $userData['password'])) {
                $_SESSION['user_id'] = $userData['id'];
                $_SESSION['user_name'] = $userData['name'];
                $_SESSION['user_role'] = $userData['role'];

                header('Location: index.php?action=events');
                exit;
            } else {
                $error = "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
                require __DIR__ . '/../views/auth/login.php';
                return;
            }
        }
        require __DIR__ . '/../views/auth/login.php';
    }

    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($name) || empty($email) || empty($password)) {
                $error = "กรุณากรอกข้อมูลให้ครบทุกช่อง";
                require __DIR__ . '/../views/auth/register.php';
                return;
            }

            if (User::findByEmail($email)) {
                $error = "อีเมลนี้มีอยู่ในระบบแล้ว";
                require __DIR__ . '/../views/auth/register.php';
                return;
            }

            $customer = new Customer($name, $email, $password);
            if ($customer->register()) {
                header('Location: index.php?action=login&success=1');
                exit;
            } else {
                $error = "เกิดข้อผิดพลาดในการลงทะเบียน";
                require __DIR__ . '/../views/auth/register.php';
                return;
            }
        }
        require __DIR__ . '/../views/auth/register.php';
    }

    public function logout(): void {
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
}
