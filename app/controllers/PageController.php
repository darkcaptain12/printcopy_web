<?php
namespace App\Controllers;

use App\Core\Controller;
use Database;
use PDO;

class PageController extends Controller
{
    public function detail($slug)
    {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare("SELECT * FROM pages WHERE slug = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$slug]);
        $page = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$page) {
            header("HTTP/1.0 404 Not Found");
            echo "Sayfa bulunamadı";
            return;
        }
        $this->view('pages/detail', ['page' => $page]);
    }
}
