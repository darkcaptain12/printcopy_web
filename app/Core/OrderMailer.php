<?php
namespace App\Core;

use App\Core\Mailer;
use App\Core\MailService;
use Database;
use PDO;

class OrderMailer {
    
    public static function sendOrderConfirmation($orderId) {
        $db = (new Database())->getConnection();
        
        // Fetch Order Details
        $stmt = $db->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) return false;

        // Fetch Items
        $stmt = $db->prepare("SELECT oi.*, p.name FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE order_id = ?");
        $stmt->execute([$orderId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch Settings for Notification Email
        $stmt = $db->query("SELECT value FROM settings WHERE `key` = 'notification_email'");
        $adminEmail = $stmt->fetchColumn();

        // 1. Send to Customer
        $customerSubject = "Siparişiniz Alındı - #" . $order['order_number'];
        $customerBody = EmailTemplates::customerOrderSuccess($order, $items);
        MailService::send($order['customer_email'], $customerSubject, $customerBody);

        // 2. Send to Admin
        if ($adminEmail) {
            $adminSubject = "Yeni Sipariş: #" . $order['order_number'];
            $adminBody = EmailTemplates::adminNewOrder($order, $items);
            MailService::send($adminEmail, $adminSubject, $adminBody);
        }

        return true;
    }
}
