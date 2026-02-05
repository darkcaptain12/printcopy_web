<?php
namespace App\Controllers;

use App\Core\Controller;

class ContactController extends Controller {
    public function index() {
        $this->view('contact/index');
    }

    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /contact');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $now = date('Y-m-d H:i:s');

        $errors = [];
        if (!$name) $errors[] = 'Ad Soyad zorunlu';
        if (!$email) $errors[] = 'E-posta zorunlu';
        if (!$message) $errors[] = 'Mesaj zorunlu';

        if ($errors) {
            header('Location: /contact?error=1');
            exit;
        }

        $subject = "[PrintCopy] Yeni İletişim Formu Mesajı";
        $body = "
            <h2>Yeni İletişim Mesajı</h2>
            <p><strong>Ad Soyad:</strong> " . htmlspecialchars($name) . "</p>
            <p><strong>E-posta:</strong> " . htmlspecialchars($email) . "</p>
            <p><strong>Telefon:</strong> " . htmlspecialchars($phone) . "</p>
            <p><strong>Mesaj:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
            <hr>
            <p><small>IP: {$ip}<br>Zaman: {$now}</small></p>
        ";

        $to = 'krgutax@gmail.com';

        // Reply-To as user email
        if (class_exists('\\App\\Core\\MailService')) {
            // MailService currently doesn't expose reply-to; keep it simple.
        }

        $sent = \App\Core\MailService::send($to, $subject, $body);
        $lastErr = method_exists('\\App\\Core\\MailService', 'getLastError') ? \App\Core\MailService::getLastError() : null;

        if ($sent) {
            header('Location: /contact?success=1');
        } else {
            // basit log
            $logDir = __DIR__ . '/../../storage/logs';
            if (!is_dir($logDir)) mkdir($logDir, 0775, true);
            $msg = "[".$now."] Mail gönderilemedi: {$name} - {$email}";
            if ($lastErr) $msg .= " | Hata: {$lastErr}";
            file_put_contents($logDir . '/contact.log', $msg . "\n", FILE_APPEND);
            header('Location: /contact?error=1');
        }
    }
}
