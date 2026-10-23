<?php
session_start();

require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/EventController.php';
require_once __DIR__ . '/controllers/BookingController.php';

$action = $_GET['action'] ?? 'events';

$authController = new AuthController();
$eventController = new EventController();
$bookingController = new BookingController();

switch ($action) {
    case 'login':
        $authController->login();
        break;
    case 'register':
        $authController->register();
        break;
    case 'logout':
        $authController->logout();
        break;
    case 'events':
        $eventController->index();
        break;
    case 'event-detail':
        $eventController->detail();
        break;
    case 'create-event':
        $eventController->create();
        break;
    case 'delete-event':
        $eventController->delete();
        break;
    case 'book':
        $bookingController->book();
        break;
    case 'my-bookings':
        $bookingController->myBookings();
        break;
    case 'cancel-booking':
        $bookingController->cancel();
        break;
    default:
        $eventController->index();
        break;
}
