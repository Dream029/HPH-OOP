<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Cart {
    public static function init() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public static function add($productId, $quantity = 1) {
        self::init();
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
    }

    public static function update($productId, $quantity) {
        self::init();
        if ($quantity <= 0) {
            self::remove($productId);
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
    }

    public static function remove($productId) {
        self::init();
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
    }

    public static function clear() {
        $_SESSION['cart'] = [];
    }

    public static function getItems() {
        self::init();
        return $_SESSION['cart'];
    }

    public static function getTotalCount() {
        self::init();
        $count = 0;
        foreach ($_SESSION['cart'] as $qty) {
            $count += $qty;
        }
        return $count;
    }
}
?>