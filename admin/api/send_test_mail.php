<?php
// admin/api/send_test_mail.php
require_once __DIR__ . '/../../admin/includes/init.php';
require_once __DIR__ . '/../../app/Core/MailService.php';
require_once __DIR__ . '/../../app/Core/Mail/SmtpProvider.php';
require_once __DIR__ . '/../../app/Core/Mail/MailProviderInterface.php';

header('Content-Type: application/json');

check_auth();

$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
    exit;
}

$notificationEmail = $pdo->query("SELECT value FROM settings WHERE `key`='notification_email'")->fetchColumn();

if (!$notificationEmail) {
    echo json_encode(['success' => false, 'message' => 'Notification Email not set in settings.']);
    exit;
}

$subject = "Admin SMTP Test";
$body = "<h1>SMTP Test</h1><p>This is a test email sent from the Admin Panel via AJAX.</p><p>Time: " . date('Y-m-d H:i:s') . "</p>";

try {
    if (\App\Core\MailService::send($notificationEmail, $subject, $body)) {
        echo json_encode(['success' => true, 'message' => "Test mail sent"]);
    } else {
        $err = \App\Core\MailService::getLastError() ?? 'SMTP error';
        echo json_encode(['success' => false, 'message' => "SMTP error: $err"]);
    }
} catch (\Throwable $e) {
    echo json_encode(['success' => false, 'message' => "SMTP error: " . $e->getMessage()]);
}
