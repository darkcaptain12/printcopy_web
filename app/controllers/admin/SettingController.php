<?php
namespace App\Controllers\Admin;

use App\Core\Session;
use App\Core\CSRF;
use Database;
use PDO;

class SettingController extends BaseAdminController {
    private $db;

    public function __construct() {
        parent::__construct();
        $this->db = (new Database())->getConnection();
    }

    public function index() {
        $stmt = $this->db->query("SELECT * FROM settings");
        $settingsRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $settings = [];
        foreach($settingsRaw as $s) {
            $settings[$s['key']] = $s['value'];
        }

        ob_start();
        require_once __DIR__ . '/../../views/admin/settings/index.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../../views/admin/layout/main.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!CSRF::check($_POST['csrf_token'])) die('CSRF Token Error');

            // Handle branding uploads
            $uploadDir = __DIR__ . '/../../../public/uploads/branding/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);
            $fileKeys = [
                'site_logo' => 'site_logo',
                'site_favicon' => 'site_favicon',
            ];
            foreach ($fileKeys as $fileField => $settingKey) {
                if (!empty($_FILES[$fileField]['name'])) {
                    $base = time() . '_' . basename($_FILES[$fileField]['name']);
                    $target = $uploadDir . $base;
                    if (move_uploaded_file($_FILES[$fileField]['tmp_name'], $target)) {
                        $_POST[$settingKey] = '/uploads/branding/' . $base;
                    }
                }
            }

            foreach ($_POST as $key => $value) {
                if ($key == 'csrf_token') continue;
                
                // key exists check
                $check = $this->db->prepare("SELECT id FROM settings WHERE `key` = ?");
                $check->execute([$key]);
                if ($check->fetch()) {
                    $stmt = $this->db->prepare("UPDATE settings SET `value` = ? WHERE `key` = ?");
                    $stmt->execute([$value, $key]);
                } else {
                    $insert = $this->db->prepare("INSERT INTO settings (`key`, `value`) VALUES (?, ?)");
                    $insert->execute([$key, $value]);
                }
            }

            Session::setFlash('success', 'Settings updated successfully');
            header('Location: /admin/settings');
            exit();
        }
    }
}
