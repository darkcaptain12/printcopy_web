<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Core\CSRF;
use Database;
use PDO;

class AuthController extends Controller {
    public function __construct() {
        Session::init();
    }

    public function login() {
        if (Session::get('admin_logged_in')) {
            header('Location: /admin/dashboard');
            exit();
        }
        $this->view('admin/auth/login');
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!CSRF::check($_POST['csrf_token'])) {
                die('CSRF Token Mismatch');
            }

            $username = trim($_POST['username']);
            $password = $_POST['password'];

            $db = (new Database())->getConnection();
            $stmt = $db->prepare("SELECT * FROM admin_users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                Session::set('admin_logged_in', true);
                Session::set('admin_id', $user['id']);
                Session::set('admin_username', $user['username']);
                header('Location: /admin/dashboard');
                exit();
            } else {
                Session::setFlash('error', 'Invalid username or password');
                header('Location: /admin/login');
                exit();
            }
        }
    }

    public function logout() {
        Session::destroy();
        header('Location: /admin/login');
        exit();
    }
}
