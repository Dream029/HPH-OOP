<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/PaymentStrategy.php';

class Booking {
    private ?int $id = null;
    private string $bookingCode;
    private int $userId;
    private int $eventId;
    private int $quantity;
    private float $totalPrice;
    private string $paymentMethod;
    private string $status;
    private PDO $db;

    public function __construct(int $userId, int $eventId, int $quantity, float $totalPrice, PaymentStrategyInterface $paymentStrategy) {
        $this->db = Database::getInstance()->getConnection();
        $this->userId = $userId;
        $this->eventId = $eventId;
        $this->quantity = $quantity;
        $this->totalPrice = $totalPrice;
        $this->bookingCode = 'EVT-' . strtoupper(substr(uniqid(), -6));
        $this->paymentMethod = $paymentStrategy->getPaymentMethodName();
        $this->status = 'confirmed';
    }

    public function getBookingCode(): string { return $this->bookingCode; }

    public function save(): bool {
        $this->db->beginTransaction();
        try {
            $event = Event::findById($this->eventId);
            if (!$event || $event->getAvailableSeats() < $this->quantity) {
                throw new Exception("จำนวนที่นั่งไม่เพียงพอสำหรับการจอง");
            }

            if (!$event->updateAvailableSeats($this->quantity)) {
                throw new Exception("ไม่สามารถอัปเดตจำนวนที่นั่งได้");
            }

            $stmt = $this->db->prepare("INSERT INTO bookings (booking_code, user_id, event_id, quantity, total_price, payment_method, status) VALUES (:booking_code, :user_id, :event_id, :quantity, :total_price, :payment_method, :status)");
            $stmt->execute([
                'booking_code' => $this->bookingCode,
                'user_id' => $this->userId,
                'event_id' => $this->eventId,
                'quantity' => $this->quantity,
                'total_price' => $this->totalPrice,
                'payment_method' => $this->paymentMethod,
                'status' => $this->status
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public static function getByUserId(int $userId): array {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT b.*, e.title as event_title, e.event_date, e.location, e.category 
            FROM bookings b 
            JOIN events e ON b.event_id = e.id 
            WHERE b.user_id = :user_id 
            ORDER BY b.created_at DESC
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public static function cancelBooking(int $bookingId, int $userId): bool {
        $db = Database::getInstance()->getConnection();
        $db->beginTransaction();
        try {
            $stmt = $db->prepare("SELECT * FROM bookings WHERE id = :id AND user_id = :user_id AND status = 'confirmed'");
            $stmt->execute(['id' => $bookingId, 'user_id' => $userId]);
            $booking = $stmt->fetch();

            if (!$booking) {
                throw new Exception("ไม่พบรายการจอง หรือรายการนี้ถูกยกเลิกไปแล้ว");
            }

            // Update booking status
            $updateStmt = $db->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = :id");
            $updateStmt->execute(['id' => $bookingId]);

            // Restore seats
            Event::restoreSeats((int)$booking['event_id'], (int)$booking['quantity']);

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            return false;
        }
    }
}
