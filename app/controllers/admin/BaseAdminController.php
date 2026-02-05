<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use Database;
use PDO;

class BaseAdminController extends Controller {
    public function __construct() {
        Session::init();
        if (!Session::get('admin_logged_in')) {
            header('Location: /admin/login');
            exit();
        }
    }
}
