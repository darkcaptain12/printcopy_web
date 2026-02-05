<?php
namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Database;
use PDO;

// Check if vendor autoload exists
if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
}

class Mailer {
    public static function send($to, $subject, $body) {
        $logDir = __DIR__ . '/../../storage/logs';
        if (!is_dir($logDir) && !mkdir($logDir, 0775, true) && !is_dir($logDir)) {
            error_log("Cannot create log directory");
            return false;
        }
        $logFile = $logDir . '/mail.log';
        $debugLogFile = $logDir . '/smtp_debug.log';

        // Fetch Settings
        $settings = [];
        try {
            $db = (new Database())->getConnection();
            $stmt = $db->query("SELECT * FROM settings");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $settings[$row['key']] = $row['value'];
            }
        } catch (\Exception $e) {
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - DB Error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            return false;
        }

        // Apply Defaults / Fallbacks
        $host = !empty($settings['smtp_host']) ? $settings['smtp_host'] : 'smtp.gmail.com';
        $port = !empty($settings['smtp_port']) ? $settings['smtp_port'] : 587;
        $username = $settings['smtp_user'] ?? '';
        $password = $settings['smtp_pass'] ?? '';
        
        // Gmail Specific: From Email must match Authenticated User (mostly)
        $fromEmail = !empty($settings['smtp_from_email']) ? $settings['smtp_from_email'] : $username;
        $fromName  = !empty($settings['smtp_from_name']) ? $settings['smtp_from_name'] : 'PrintCopy';

        if (empty($username) || empty($password)) {
             file_put_contents($logFile, date('Y-m-d H:i:s') . " - SMTP Credentials Missing." . PHP_EOL, FILE_APPEND);
             return false;
        }

        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = $host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $username;
            $mail->Password   = $password;
            
            // Security Settings
            if (isset($settings['smtp_secure']) && $settings['smtp_secure'] == 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            $mail->Port       = $port;

            // Robustness Settings
            $mail->Timeout = 15;
            $mail->SMTPKeepAlive = false;

            // SSL Options for Local Dev (Bypass Verification)
            // Essential for XAMPP/Localhost to connect to Gmail
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];

            // Debug Logging (Detailed)
            $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Level 2
            $mail->Debugoutput = function ($str, $level) use ($debugLogFile) {
                file_put_contents(
                    $debugLogFile,
                    '[' . date('Y-m-d H:i:s') . '] ' . trim($str) . PHP_EOL,
                    FILE_APPEND
                );
            };

            // Recipient
            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            return true;

        } catch (Exception $e) {
            $errorMsg = $mail->ErrorInfo;
            
            // Clean up error message for log
            $logMsg = date('Y-m-d H:i:s') . " - Mailer Error: {$errorMsg}" . PHP_EOL;

            if (strpos($errorMsg, 'Username and Password not accepted') !== false) {
                 $logMsg .= " [HINT] Gmail Auth Failed. Check App Password and ensure 2FA is on." . PHP_EOL;
            }

            file_put_contents($logFile, $logMsg, FILE_APPEND);
            return false;
        }
    }
}
