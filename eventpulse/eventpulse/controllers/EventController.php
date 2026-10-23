<?php
require_once __DIR__ . '/../models/Event.php';

class EventController {
    public function index(): void {
        $selectedCategory = $_GET['category'] ?? 'ทั้งหมด';
        $events = Event::getAll($selectedCategory);
        $allEventsForSlider = Event::getAll(); // For slider
        $categories = Event::getCategories();
        require __DIR__ . '/../views/events/index.php';
    }

    public function detail(): void {
        $id = (int)($_GET['id'] ?? 0);
        $event = Event::findById($id);

        if (!$event) {
            header('Location: index.php?action=events');
            exit;
        }

        require __DIR__ . '/../views/events/detail.php';
    }

    public function create(): void {
        if (($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: index.php?action=events');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $category = trim($_POST['category'] ?? 'คอนเสิร์ต');
            $description = trim($_POST['description'] ?? '');
            $location = trim($_POST['location'] ?? '');
            $eventDate = trim($_POST['event_date'] ?? '');
            $price = (float)($_POST['price'] ?? 0);
            $totalSeats = (int)($_POST['total_seats'] ?? 0);
            $imageUrl = trim($_POST['image_url'] ?? 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=800');

            if (empty($title) || empty($location) || empty($eventDate) || $price <= 0 || $totalSeats <= 0) {
                $error = "กรุณากรอกข้อมูลงานให้ถูกต้องและครบถ้วน";
                require __DIR__ . '/../views/events/create.php';
                return;
            }

            $event = new Event([
                'title' => $title,
                'category' => $category,
                'description' => $description,
                'location' => $location,
                'event_date' => $eventDate,
                'price' => $price,
                'total_seats' => $totalSeats,
                'image_url' => $imageUrl
            ]);

            if ($event->save()) {
                header('Location: index.php?action=events&created=1');
                exit;
            } else {
                $error = "เกิดข้อผิดพลาดในการบันทึกข้อมูล";
            }
        }

        require __DIR__ . '/../views/events/create.php';
    }

    public function delete(): void {
        if (($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: index.php?action=events');
            exit;
        }

        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            Event::deleteById($id);
        }

        header('Location: index.php?action=events&deleted=1');
        exit;
    }
}
