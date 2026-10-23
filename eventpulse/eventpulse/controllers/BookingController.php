<?php
require_once __DIR__ . '/../models/Event.php';
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/PaymentStrategy.php';

class BookingController {
    public function book(): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventId = (int)($_POST['event_id'] ?? 0);
            $quantity = (int)($_POST['quantity'] ?? 1);
            $paymentType = $_POST['payment_type'] ?? 'promptpay';

            $event = Event::findById($eventId);
            if (!$event) {
                die("ไม่พบข้อมูลอีเวนต์");
            }

            if ($quantity <= 0 || $quantity > $event->getAvailableSeats()) {
                header("Location: index.php?action=event-detail&id={$eventId}&error=invalid_qty");
                exit;
            }

            $totalPrice = $event->getPrice() * $quantity;

            if ($paymentType === 'credit_card') {
                $cardNumber = $_POST['card_number'] ?? '4111111111111111';
                $paymentStrategy = new CreditCardPayment($cardNumber);
            } else {
                $paymentStrategy = new PromptPayPayment("081-234-5678");
            }

            if ($paymentStrategy->processPayment($totalPrice)) {
                try {
                    $booking = new Booking($_SESSION['user_id'], $eventId, $quantity, $totalPrice, $paymentStrategy);
                    if ($booking->save()) {
                        header('Location: index.php?action=my-bookings&success=1');
                        exit;
                    }
                } catch (Exception $e) {
                    die("เกิดข้อผิดพลาดในการจอง: " . $e->getMessage());
                }
            }
        }
    }

    public function myBookings(): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $bookings = Booking::getByUserId($_SESSION['user_id']);
        require __DIR__ . '/../views/bookings/index.php';
    }

    public function cancel(): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $bookingId = (int)($_GET['id'] ?? 0);
        if ($bookingId > 0) {
            if (Booking::cancelBooking($bookingId, $_SESSION['user_id'])) {
                header('Location: index.php?action=my-bookings&cancelled=1');
                exit;
            }
        }

        header('Location: index.php?action=my-bookings&cancel_error=1');
        exit;
    }
}
