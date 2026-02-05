<?php
// tools/paytr_fake_fail.php
// Simulates a PayTR callback with INVALID HASH

require_once __DIR__ . '/../config/database.php';

$db = (new Database())->getConnection();

// Find an order
$stmt = $db->query("SELECT * FROM orders ORDER BY id DESC LIMIT 1");
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    // Just use dummy if no order
    $merchant_oid = 'TEST-DUMMY-123';
    $paytr_amount = "1000";
} else {
    $merchant_oid = $order['order_number'];
    $paytr_amount = (string)($order['total_amount'] * 100);
}

echo "--------------------------------------------------\n";
echo "TEST: BAD HASH SCENARIO\n";
echo "TARGET OID: $merchant_oid\n";

$status = 'success'; // Even if status is success, bad hash should fail
$bad_hash = 'thisisnotarealhash12345';

$postData = [
    'merchant_oid' => $merchant_oid,
    'status'       => $status,
    'total_amount' => $paytr_amount,
    'hash'         => $bad_hash
];

$url = 'http://127.0.0.1:8000/paytr/callback.php';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Response Code: $httpCode\n";
echo "Response Body: $response\n";

if ($response === "OK") {
    echo "SUCCESS: Server returned 'OK' (as expected even for bad hash).\n";
} else {
    echo "FAIL: Server did NOT return 'OK'.\n";
}

echo "Check log file (expecting 'hash_ok': false)...\n";

if (isset($order)) {
    // Verify status did NOT change
    $stmtCheck = $db->prepare("SELECT status FROM orders WHERE id = ?");
    $stmtCheck->execute([$order['id']]);
    $currentStatus = $stmtCheck->fetchColumn();
    
    echo "DB Status: $currentStatus (Should be unchanged)\n";
}
echo "--------------------------------------------------\n";
