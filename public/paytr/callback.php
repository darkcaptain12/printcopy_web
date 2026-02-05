<?php
// public/paytr/callback.php

// 1. Silent Configuration
ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: text/plain; charset=utf-8');

$root = dirname(__DIR__, 2);

// 2. Logging Setup
$logDir = $root . '/storage/logs';
if (!is_dir($logDir)) mkdir($logDir, 0775, true);
$logFile = $logDir . '/paytr_callback.log';

function logPaytr($merchant_oid, $status, $order_status_before, $order_status_after, $hash_ok, $mail_ok, $msg) {
    global $logFile;
    $data = [
        'time' => date('Y-m-d H:i:s'),
        'merchant_oid' => $merchant_oid,
        'status' => $status, // PayTR status (success/failed)
        'order_status_before' => $order_status_before,
        'order_status_after' => $order_status_after,
        'hash_ok' => $hash_ok,
        'mail_ok' => $mail_ok,
        'message' => $msg
    ];
    file_put_contents($logFile, json_encode($data, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
}

// 3. Dependencies
if (file_exists($root . '/vendor/autoload.php')) {
    require_once $root . '/vendor/autoload.php';
}

require_once $root . '/config/database.php';

// Ensure Translation Helper is loaded (for mail subject/body)
if (file_exists($root . '/app/Helpers/translate.php')) {
    require_once $root . '/app/Helpers/translate.php';
}

// 4. Method Check
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    exit;
}

// 5. PayTR Credentials (settings)
$dbTmp = (new Database())->getConnection();
$settings = $dbTmp->query("SELECT `key`,`value` FROM settings")->fetchAll(PDO::FETCH_KEY_PAIR);
$merchant_key   = $settings['paytr_merchant_key'] ?? '';
$merchant_salt  = $settings['paytr_merchant_salt'] ?? '';
$paytr_test     = (int)($settings['paytr_test_mode'] ?? 0);

// 6. Validation
$postData = $_POST;
$merchant_oid = $postData['merchant_oid'] ?? '';
$status = $postData['status'] ?? '';
$total_amount = $postData['total_amount'] ?? '';
$hash_posted = $postData['hash'] ?? '';

if (!$merchant_oid || !$status || !$total_amount || !$hash_posted) {
    logPaytr($merchant_oid, $status, null, null, false, false, "ERROR: Missing Parameters");
    echo "OK"; 
    exit;
}

// 7. Hash Check
$hash_str = $merchant_oid . $merchant_salt . $status . $total_amount;
$hash = base64_encode(hash_hmac('sha256', $hash_str, $merchant_key, true));

if ($hash != $hash_posted) {
    logPaytr($merchant_oid, $status, null, null, false, false, "ERROR: Bad Hash");
    echo "OK";
    exit;
}

// 8. Process Order
try {
    $db = (new Database())->getConnection();
    
    // Find Order by order_number (merchant_oid matches order_number)
$stmt = $db->prepare("SELECT * FROM orders WHERE order_number = ? LIMIT 1");
    $stmt->execute([$merchant_oid]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        logPaytr($merchant_oid, $status, null, null, true, false, "ERROR: Order not found");
        echo "OK";
        exit;
    }

    $currentDBStatus = $order['status'];

    if ($status == 'success') {
        // SUCCESS CASE
        if ($currentDBStatus === 'paid') {
            // Idempotent
            logPaytr($merchant_oid, $status, 'paid', 'paid', true, false, "Order already paid");
            echo "OK";
            exit;
        }

        if ($currentDBStatus === 'pending' || $currentDBStatus === 'pending_payment') {
            // Processing
            $update = $db->prepare("UPDATE orders SET status = 'paid', payment_status='paid', updated_at = NOW() WHERE id = ?");
            $update->execute([$order['id']]);
            
            // Mail Logic
            $mailResult = false;
            try {
                // Determine Admin Email
                $stmtSetting = $db->query("SELECT value FROM settings WHERE `key` = 'notification_email'");
                $adminEmail = $stmtSetting->fetchColumn();
                
                if (!$adminEmail) {
                    $stmtSmtp = $db->query("SELECT value FROM settings WHERE `key` = 'smtp_user'");
                    $adminEmail = $stmtSmtp->fetchColumn();
                }

                if ($adminEmail) {
                    // Send Email
                    if (!class_exists('App\Core\MailService') && file_exists($root . '/app/Core/MailService.php')) {
                        require_once $root . '/app/Core/MailService.php';
                    }

                    if (class_exists('App\Core\MailService')) {
                        $subject = "Yeni Sipariş Ödemesi Alındı: " . $order['order_number'];
                        
                        $body = "<h3>Sipariş Detayı</h3>";
                        $body .= "<p><b>Sipariş No:</b> " . htmlspecialchars($order['order_number']) . "</p>";
                        $body .= "<p><b>Tutar:</b> " . number_format($order['total_amount'], 2) . " TL</p>";
                        $body .= "<p><b>Durum:</b> Ödendi</p>";
                        $body .= "<p><b>Müşteri:</b> " . htmlspecialchars($order['customer_name']) . "</p>";
                        $body .= "<p><b>Tarih:</b> " . date('Y-m-d H:i:s') . "</p>";
                        
                        $mailResult = \App\Core\MailService::send($adminEmail, $subject, $body);
                    }
                }
            } catch (Exception $em) {
                // Mail failed, but do not stop flow
                $mailResult = false;
            }

            logPaytr($merchant_oid, $status, 'pending', 'paid', true, $mailResult, "Order marked as paid");
        } else {
            // Order is in another status (e.g. cancelled or shipped), usually shouldn't happen for success callback unless late.
            logPaytr($merchant_oid, $status, $currentDBStatus, $currentDBStatus, true, false, "Order status mismatch (Not pending/paid)");
        }

    } else {
        // FAILED CASE
        $failReason = ($postData['failed_reason_code'] ?? '') . ' - ' . ($postData['failed_reason_msg'] ?? '');
        if ($currentDBStatus === 'pending' || $currentDBStatus === 'pending_payment') {
            $update = $db->prepare("UPDATE orders SET status = 'failed', payment_status='failed', updated_at = NOW() WHERE id = ?");
            $update->execute([$order['id']]);
        }
        logPaytr($merchant_oid, $status, $currentDBStatus, 'failed', true, false, "Payment failed: $failReason");
    }

} catch (Exception $e) {
    logPaytr($merchant_oid, $status, null, null, true, false, "EXCEPTION: " . $e->getMessage());
}

// 9. Response
echo "OK";
exit;
