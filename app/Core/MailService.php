<?php
namespace App\Core;

use App\Core\Mail\MailProviderInterface;
use App\Core\Mail\SmtpProvider;
use Database;
use PDO;

class MailService {
    
    private static $settings = null;
    private static ?string $lastError = null;
    
    /**
     * Load settings from Database once (Cache)
     */
    private static function loadSettings() {
        if (self::$settings !== null) {
            return true;
        }

        try {
            // Ensure Database class is loaded
            if (!class_exists('Database')) {
                $root = dirname(__DIR__, 2);
                if (file_exists($root . '/config/database.php')) {
                    require_once $root . '/config/database.php';
                }
            }
            
            $db = (new Database())->getConnection();
            $stmt = $db->query("SELECT * FROM settings");
            $data = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[$row['key']] = $row['value'];
            }
            self::$settings = $data;
            return true;
        } catch (\Exception $e) {
            self::log("DB Config Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Factory Method to get Provider
     * Defaults to SMTP for now, but ready for expansion
     */
    private static function getProvider(array $config): MailProviderInterface {
        // Future: Switch based on $config['mail_driver']
        // $driver = $config['mail_driver'] ?? 'smtp';
        // if ($driver === 'mailgun') return new MailgunProvider($config);
        
        return new SmtpProvider($config);
    }

    /**
     * Send Email
     * @param string $to Recipient Email
     * @param string $subject Email Subject
     * @param string $bodyHTML HTML Body
     * @return boolean
     */
    public static function send($to, $subject, $bodyHTML) {
        if (!self::loadSettings()) {
            self::$lastError = 'Settings could not be loaded';
            return false;
        }

        $provider = self::getProvider(self::$settings);
        try {
            $ok = $provider->send($to, $subject, $bodyHTML);
            self::$lastError = $ok ? null : ($provider->getLastError() ?? 'Unknown SMTP error');
            return $ok;
        } catch (\Throwable $e) {
            self::$lastError = $e->getMessage();
            self::log("Send exception: " . $e->getMessage());
            return false;
        }
    }

    public static function getLastError(): ?string
    {
        return self::$lastError;
    }

    /**
     * Log errors generic
     */
    private static function log($message) {
        $logDir = dirname(__DIR__, 2) . '/storage/logs';
        if (!is_dir($logDir)) mkdir($logDir, 0775, true);
        
        file_put_contents(
            $logDir . '/mail.log', 
            "[" . date('Y-m-d H:i:s') . "] Service: " . $message . PHP_EOL, 
            FILE_APPEND
        );
    }
}
