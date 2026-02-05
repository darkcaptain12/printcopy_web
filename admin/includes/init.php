<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enable Error Reporting for Dev
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Composer Autoload (PHPMailer, etc.)
$autoload = __DIR__ . '/../../vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
}

// DB Connection
require_once __DIR__ . '/../../config/database.php';
$database = new Database();
$pdo = $database->getConnection();

// Helper Functions
require_once __DIR__ . '/../../app/Helpers/translate.php';

function check_auth() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: /admin/login.php");
        exit;
    }
}

function redirect($url) {
    header("Location: " . $url);
    exit;
}

function set_flash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        $type = htmlspecialchars($flash['type'] ?? 'info');
        $msg  = htmlspecialchars($flash['message'] ?? '');
        return "<div class='alert alert-{$type} p-4 mb-4 text-sm text-{$type}-700 bg-{$type}-100 rounded-lg' role='alert'>{$msg}</div>";
    }
    return '';
}

function clean_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// CSRF Protection
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function csrf_token() {
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function verify_csrf() {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF Token Mismatch');
    }
}

// Upload Handler
function upload_image($file, $destination = 'uploads/') {
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $filename = $file['name'];
    $filetmp = $file['tmp_name'];
    $filesize = $file['size'];
    $fileerror = $file['error'];
    
    $fileext = explode('.', $filename);
    $fileactualext = strtolower(end($fileext));

    if (in_array($fileactualext, $allowed)) {
        if ($fileerror === 0) {
            if ($filesize < 5000000) { // 5MB
                $filenewname = uniqid('', true) . "." . $fileactualext;
                $targetDir = __DIR__ . '/../../storage/' . $destination;
                
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                
                $fileDestination = $targetDir . $filenewname;
                if(move_uploaded_file($filetmp, $fileDestination)) {
                    return $destination . $filenewname;
                }
            }
        }
    }
    return false;
}
