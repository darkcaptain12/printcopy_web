<?php
namespace App\Core;

class Cart {
    public static function init() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public static function add($product_id, $quantity = 1, $price = 0, $name = '', $image = '') {
        self::init();
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product_id,
                'quantity' => $quantity,
                'price' => $price,
                'name' => $name,
                'image' => $image
            ];
        }
    }

    public static function update($product_id, $quantity) {
        self::init();
        if (isset($_SESSION['cart'][$product_id])) {
            if ($quantity <= 0) {
                unset($_SESSION['cart'][$product_id]);
            } else {
                $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            }
        }
    }

    public static function remove($product_id) {
        self::init();
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    public static function getItems() {
        self::init();
        return $_SESSION['cart'];
    }

    public static function getTotal() {
        self::init();
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public static function count(): int {
        self::init();
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += (int)($item['quantity'] ?? 0);
        }
        return $total;
    }

    public static function clear() {
        self::init();
        $_SESSION['cart'] = [];
    }
}
