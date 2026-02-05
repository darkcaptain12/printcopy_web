<?php
// PayTR Callback Handler

// 1. Initial Setup & Robust Logging
$logDir = __DIR__ . '/../storage/logs';
if (!is_dir($logDir) && !mkdir($logDir, 0775, true) && !is_dir($logDir)) {
    // If we can't create or access logs, use PHP error log
    error_log("PayTR Error: Cannot access storage/logs.");
}
$logFile = $logDir . '/paytr_callback.log';

$timestamp = date('Y-m-d H:i:s');
$ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
$rawPost = file_get_contents('php://input');
$postData = $_POST; // PayTR sends x-www-form-urlencoded

// Construct log line
$logData = [
    'time' => $timestamp,
    'ip' => $ip,
    'post' => $postData,
    'raw' => $rawPost
];
$logLine = json_encode($logData) . PHP_EOL;

// Append to log file
if (file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX) === false) {
    error_log("PayTR Error: Make sure storage/logs is writable. Data: " . json_encode($postData));
}

// 2. Validate Request Method
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    die('');
}

require_once __DIR__ . '/../config/database.php';

use Database;
use PDO;

$post = $_POST;

// Creds
$merchant_key   = 'RykZKeQ1KXQgfPY9';
$merchant_salt  = 'aSSEsgwJGqkW4gT2';

// 3. Validate Hash
if (!isset($post['merchant_oid']) || !isset($post['status']) || !isset($post['total_amount']) || !isset($post['hash'])) {
    file_put_contents($logFile, "{$timestamp} - ERROR: Missing Parameters" . PHP_EOL, FILE_APPEND);
    die('PAYTR notification failed: missing parameters');
}

$hash = base64_encode(hash_hmac('sha256', $post['merchant_oid'] . $merchant_salt . $post['status'] . $post['total_amount'], $merchant_key, true));

if ($hash != $post['hash']) {
    file_put_contents($logFile, "{$timestamp} - ERROR: Bad Hash. Calculated: {$hash}, Received: {$post['hash']}" . PHP_EOL, FILE_APPEND);
    die('PAYTR notification failed: bad hash');
}

// 4. Process Status
try {
    $database = new Database();
    $db = $database->getConnection();

    if ($post['status'] == 'success') {
        $stmt = $db->prepare("SELECT * FROM orders WHERE merchant_oid = ?");
        $stmt->execute([$post['merchant_oid']]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order && $order['payment_status'] != 'paid') {
            // Update Order
            $update = $db->prepare("UPDATE orders SET status = 'paid', payment_status = 'paid', paytr_status = 'success', paytr_total_amount = ? WHERE merchant_oid = ?");
            $update->execute([$post['total_amount'], $post['merchant_oid']]);
            
            file_put_contents($logFile, "{$timestamp} - SUCCESS: Order #{$order['order_number']} marked payload." . PHP_EOL, FILE_APPEND);
            
            // Send Email (via Helper)
            require_once __DIR__ . '/../app/Core/Mailer.php';
            $subject = "Order #{$order['order_number']} Confirmed";
            $message = "<h1>Thank you!</h1><p>Order validated.</p>";
            // Attempt to send, log error if fails inside Mailer
            \App\Core\Mailer::send($order['customer_email'], $subject, $message);
        } else {
             file_put_contents($logFile, "{$timestamp} - INFO: Order already paid or not found." . PHP_EOL, FILE_APPEND);
        }

    } else {
        // Payment Failed
        $update = $db->prepare("UPDATE orders SET status = 'cancelled', payment_status = 'failed', failed_reason_code = ?, failed_reason_msg = ? WHERE merchant_oid = ?");
        $update->execute([$post['failed_reason_code'] ?? '', $post['failed_reason_msg'] ?? '', $post['merchant_oid']]);
        
        file_put_contents($logFile, "{$timestamp} - FAIL: Order {$post['merchant_oid']} failed." . PHP_EOL, FILE_APPEND);
    }
} catch (Exception $e) {
    file_put_contents($logFile, "{$timestamp} - EXCEPTION: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
    error_log("PayTR Exception: " . $e->getMessage());
}

// 5. Acknowledge
echo "OK";
exit;
