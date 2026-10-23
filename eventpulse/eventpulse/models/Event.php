<?php
require_once __DIR__ . '/../config/Database.php';

class Event {
    private ?int $id = null;
    private string $title;
    private string $category;
    private string $description;
    private string $location;
    private string $eventDate;
    private float $price;
    private int $totalSeats;
    private int $availableSeats;
    private string $imageUrl;
    private PDO $db;

    public function __construct(array $data = []) {
        $this->db = Database::getInstance()->getConnection();
        if (!empty($data)) {
            $this->id = isset($data['id']) ? (int)$data['id'] : null;
            $this->title = $data['title'] ?? '';
            $this->category = $data['category'] ?? 'คอนเสิร์ต';
            $this->description = $data['description'] ?? '';
            $this->location = $data['location'] ?? '';
            $this->eventDate = $data['event_date'] ?? '';
            $this->price = (float)($data['price'] ?? 0.0);
            $this->totalSeats = (int)($data['total_seats'] ?? 0);
            $this->availableSeats = (int)($data['available_seats'] ?? $this->totalSeats);
            $this->imageUrl = $data['image_url'] ?? '';
        }
    }

    public function getId(): ?int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function getCategory(): string { return $this->category; }
    public function getDescription(): string { return $this->description; }
    public function getLocation(): string { return $this->location; }
    public function getEventDate(): string { return $this->eventDate; }
    public function getPrice(): float { return $this->price; }
    public function getTotalSeats(): int { return $this->totalSeats; }
    public function getAvailableSeats(): int { return $this->availableSeats; }
    public function getImageUrl(): string { return $this->imageUrl; }

    public function save(): bool {
        $stmt = $this->db->prepare("INSERT INTO events (title, category, description, location, event_date, price, total_seats, available_seats, image_url) VALUES (:title, :category, :description, :location, :event_date, :price, :total_seats, :available_seats, :image_url)");
        return $stmt->execute([
            'title' => $this->title,
            'category' => $this->category,
            'description' => $this->description,
            'location' => $this->location,
            'event_date' => $this->eventDate,
            'price' => $this->price,
            'total_seats' => $this->totalSeats,
            'available_seats' => $this->totalSeats,
            'image_url' => $this->imageUrl
        ]);
    }

    public static function getAll(?string $category = null): array {
        $db = Database::getInstance()->getConnection();
        if ($category && $category !== 'ทั้งหมด') {
            $stmt = $db->prepare("SELECT * FROM events WHERE category = :category ORDER BY event_date ASC");
            $stmt->execute(['category' => $category]);
            return $stmt->fetchAll();
        }
        $stmt = $db->query("SELECT * FROM events ORDER BY event_date ASC");
        return $stmt->fetchAll();
    }

    public static function getCategories(): array {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT DISTINCT category FROM events ORDER BY category ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function findById(int $id): ?Event {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM events WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        return $data ? new Event($data) : null;
    }

    public static function deleteById(int $id): bool {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM events WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function updateAvailableSeats(int $quantity): bool {
        if ($this->availableSeats < $quantity) {
            return false;
        }
        $this->availableSeats -= $quantity;
        $stmt = $this->db->prepare("UPDATE events SET available_seats = :available_seats WHERE id = :id");
        return $stmt->execute([
            'available_seats' => $this->availableSeats,
            'id' => $this->id
        ]);
    }

    public static function restoreSeats(int $eventId, int $quantity): bool {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE events SET available_seats = available_seats + :quantity WHERE id = :id");
        return $stmt->execute([
            'quantity' => $quantity,
            'id' => $eventId
        ]);
    }
}
