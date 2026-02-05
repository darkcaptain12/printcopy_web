<?php
namespace App\Core\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class SmtpProvider implements MailProviderInterface {
    
    private $config;
    private ?string $lastError = null;

    public function __construct(array $config) {
        $this->config = $config;
    }

    public function send($to, $subject, $body) {
        // Ensure PHPMailer is autoloaded
        if (!class_exists('\\PHPMailer\\PHPMailer\\PHPMailer')) {
            $autoload = dirname(__DIR__, 3) . '/vendor/autoload.php';
            if (file_exists($autoload)) {
                require_once $autoload;
            }
        }

        $s = $this->config;

        if (empty($s['smtp_host']) || empty($s['smtp_user']) || empty($s['smtp_pass'])) {
            $this->log("SMTP Config Missing");
            return false;
        }

        $mail = new PHPMailer(true);
        try {
            // Server Settings
            $mail->isSMTP();
            $mail->Host       = $s['smtp_host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $s['smtp_user'];
            $mail->Password   = $s['smtp_pass'];
            
            // Encryption Logic
            $secure = $s['smtp_secure'] ?? 'tls';
            if ($secure == 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } else if ($secure == 'tls') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            } else {
                $mail->SMTPAutoTLS = false;
                $mail->SMTPSecure = false;
            }
            
            $mail->Port = $s['smtp_port'] ?? 587;
            $mail->Timeout = 15;
            $mail->CharSet = 'UTF-8';

            // Production: Disable Debug by default
            $mail->SMTPDebug = 0;
            
            // Bypass SSL for Local Dev if needed (Can be configurable)
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ]
            ];

            // Attributes
            $fromEmail = $s['smtp_from_email'] ?? $s['smtp_user'];
            $fromName  = $s['smtp_from_name'] ?? 'PrintCopy';
            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags($body);

            $mail->send();
            $this->lastError = null;
            return true;

        } catch (Exception $e) {
            $this->lastError = $mail->ErrorInfo ?: $e->getMessage();
            $this->log("Mailer Error: " . $this->lastError);
            return false;
        }
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    private function log($msg) {
        $logDir = dirname(__DIR__, 3) . '/storage/logs';
        if (!is_dir($logDir)) mkdir($logDir, 0775, true);
        file_put_contents($logDir . '/mail.log', "[" . date('Y-m-d H:i:s') . "] SMTP: " . $msg . PHP_EOL, FILE_APPEND);
    }
}
