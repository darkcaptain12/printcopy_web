<?php
// tools/paytr_fake_success.php
// Simulates a successful PayTR payment callback

require_once __DIR__ . '/../config/database.php';

$db = (new Database())->getConnection();

// 1. Ensure a PENDING Order exists (or create one)
$stmt = $db->query("SELECT * FROM orders WHERE status = 'pending' ORDER BY id DESC LIMIT 1");
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "No pending order found. Creating a temporary test order...\n";
    $rand = rand(1000, 9999);
    $orderNumber = "TEST-ORD-$rand";
    $amount = 150.00;
    
    $stmtIns = $db->prepare("INSERT INTO orders (order_number, total_amount, status, customer_name, customer_email, customer_phone, address, created_at) VALUES (?, ?, 'pending', 'Test User', 'test@example.com', '5551234567', 'Test Address', NOW())");
    $stmtIns->execute([$orderNumber, $amount]);
    $orderId = $db->lastInsertId();
    
    // Fetch it back
    $stmt = $db->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
}

$merchant_oid = $order['order_number'];
$total_amount_tl = $order['total_amount'];
$paytr_amount = (string)($total_amount_tl * 100); // Kuruş conversion

echo "--------------------------------------------------\n";
echo "TARGET ORDER: $merchant_oid (ID: {$order['id']})\n";
echo "STATUS: {$order['status']}\n";
echo "AMOUNT: $total_amount_tl TL ($paytr_amount Kurus)\n";

// 2. Prepare PayTR Parameters
$merchant_key   = 'RykZKeQ1KXQgfPY9';
$merchant_salt  = 'aSSEsgwJGqkW4gT2';
$status         = 'success';

$hash_str = $merchant_oid . $merchant_salt . $status . $paytr_amount;
$hash = base64_encode(hash_hmac('sha256', $hash_str, $merchant_key, true));

$postData = [
    'merchant_oid' => $merchant_oid,
    'status'       => $status,
    'total_amount' => $paytr_amount,
    'hash'         => $hash
];

echo "Sending POST request...\n";

// 3. Send Request
$url = 'http://127.0.0.1:8001/paytr/callback.php';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Response Code: $httpCode\n";
echo "Response Body: $response\n";

if ($response !== "OK") {
    echo "FAIL: Expected 'OK', got '$response'\n";
} else {
    echo "SUCCESS: Response matches.\n";
}

// 4. Verify DB Status
$stmtCheck = $db->prepare("SELECT status FROM orders WHERE id = ?");
$stmtCheck->execute([$order['id']]);
$newStatus = $stmtCheck->fetchColumn();

echo "DB Status: $newStatus\n";

if ($newStatus === 'paid') {
    echo "TEST PASSED: Order status changed to 'paid'.\n";
} else {
    echo "TEST FAILED: Order status is '$newStatus'.\n";
}
echo "--------------------------------------------------\n";
