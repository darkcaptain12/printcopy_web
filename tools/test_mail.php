<?php
// tools/test_mail.php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Core/Mail/MailProviderInterface.php';
require_once __DIR__ . '/../app/Core/Mail/SmtpProvider.php';
require_once __DIR__ . '/../app/Core/MailService.php';

use App\Core\MailService;
use PHPMailer\PHPMailer\PHPMailer;
use Database;

// Basic Setup
$logFile = __DIR__ . '/../storage/logs/mail.log';
$debugLogFile = __DIR__ . '/../storage/logs/smtp_debug.log';
$db = (new Database())->getConnection();

// Fetch Settings
$stmt = $db->query("SELECT * FROM settings WHERE `key` IN ('smtp_host', 'smtp_port', 'smtp_secure', 'smtp_user', 'smtp_pass', 'smtp_from_email', 'smtp_from_name', 'notification_email')");
$settings = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $settings[$row['key']] = $row['value'];
}

echo "--- SMTP Settings Loaded ---\n";
print_r($settings);

$to = $settings['notification_email'] ?? $settings['smtp_user'];
if (!$to) {
    die("FAIL: No recipient email found (notification_email or smtp_user).\n");
}

echo "\n--- Attempting to send to: $to ---\n";

// Custom Debug Implementation to Capture PHPMailer Output
// We need to extend/modify usage slightly to capture low-level SMTP logs if we want them separate,
// but MailService doesn't expose PHPMailer instance directly.
// So we will try to use MailService first. If it fails, we might miss the SMTP debug log unless we hook into it.
// However, the user asked to use existing MailService.
// BUT, the user ALSO asked for "storage/logs/smtp_debug.log".
// Standard MailService likely suppresses debug output.
// To fully satisfy "PHPMailer debug seviyesini aç", we might need to bypass MailService OR modify it temporarily, 
// OR simpler: Just instantiate the SmtpProvider or PHPMailer directly HERE for this test tool to get full debug.
// Let's try direct PHPMailer usage here for *testing* purposes to guarantee we get the debug logs requested,
// using the exact same settings MailService would use.

$mail = new PHPMailer(true);
$debugOutput = "";

try {
    // Enable Debug
    $mail->SMTPDebug = 3; // Detailed debug
    $mail->Debugoutput = function($str, $level) use (&$debugOutput) {
        $debugOutput .= "$level: $str\n";
    };

    // Server settings
    $mail->isSMTP();
    $mail->Host       = $settings['smtp_host'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $settings['smtp_user'];
    $mail->Password   = $settings['smtp_pass'];
    $mail->SMTPSecure = $settings['smtp_secure']; // tls or ssl
    $mail->Port       = $settings['smtp_port'];
    $mail->CharSet    = 'UTF-8';

    // Recipients
    $mail->setFrom($settings['smtp_from_email'], $settings['smtp_from_name']);
    $mail->addAddress($to);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email from PrintCopy Tools';
    $mail->Body    = 'This is a test email sent from <b>tools/test_mail.php</b> to verify SMTP configuration.<br>Time: ' . date('Y-m-d H:i:s');
    $mail->AltBody = 'This is a test email sent from tools/test_mail.php to verify SMTP configuration.';

    $mail->send();
    echo "OK: Message has been sent to $to\n";
    
    // Log Success
    file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] SUCCESS: Test mail sent to $to\n", FILE_APPEND);

} catch (Exception $e) {
    echo "FAIL: Message could not be sent. Mailer Error: {$mail->ErrorInfo}\n";
    
    // Log Error
    file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] ERROR: Test mail failed: {$mail->ErrorInfo}\n", FILE_APPEND);
}

// Write Debug Log
file_put_contents($debugLogFile, "[" . date('Y-m-d H:i:s') . "] --- SMTP DEBUG LOG ---\n" . $debugOutput . "\n------------------------\n", FILE_APPEND);
echo "\nDetailed SMTP logs written to storage/logs/smtp_debug.log\n";
