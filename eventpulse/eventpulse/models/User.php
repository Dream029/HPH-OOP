<?php
require_once __DIR__ . '/../config/Database.php';

abstract class User {
    protected ?int $id = null;
    protected string $name;
    protected string $email;
    protected string $password;
    protected string $role;
    protected PDO $db;

    public function __construct(string $name = '', string $email = '', string $password = '', string $role = 'customer') {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->db = Database::getInstance()->getConnection();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getEmail(): string { return $this->email; }
    public function getRole(): string { return $this->role; }

    public static function findByEmail(string $email): ?array {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    abstract public function getDashboardUrl(): string;
}

class Customer extends User {
    public function __construct(string $name = '', string $email = '', string $password = '') {
        parent::__construct($name, $email, $password, 'customer');
    }

    public function register(): bool {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)");
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
        return $stmt->execute([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $hashedPassword,
            'role' => $this->role
        ]);
    }

    public function getDashboardUrl(): string {
        return 'index.php?action=events';
    }
}

class Admin extends User {
    public function __construct(string $name = '', string $email = '', string $password = '') {
        parent::__construct($name, $email, $password, 'admin');
    }

    public function getDashboardUrl(): string {
        return 'index.php?action=create-event';
    }
}
