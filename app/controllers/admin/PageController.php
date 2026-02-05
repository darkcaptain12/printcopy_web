<?php
namespace App\Controllers\Admin;

use Database;
use PDO;

class PageController extends BaseAdminController
{
    private PDO $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = (new Database())->getConnection();
        $this->ensureTable();
    }

    private function ensureTable(): void
    {
        $this->db->exec("CREATE TABLE IF NOT EXISTS pages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            content TEXT,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    public function index()
    {
        $pages = $this->db->query("SELECT * FROM pages ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        ob_start();
        require __DIR__ . '/../../views/admin/pages/index.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/admin/layout/main.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->save();
            header('Location: /admin/pages');
            exit;
        }
        $page = null;
        ob_start();
        require __DIR__ . '/../../views/admin/pages/form.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/admin/layout/main.php';
    }

    public function edit()
    {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->db->prepare("SELECT * FROM pages WHERE id = ?");
        $stmt->execute([$id]);
        $page = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$page) {
            header('Location: /admin/pages');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->save($id);
            header('Location: /admin/pages');
            exit;
        }
        ob_start();
        require __DIR__ . '/../../views/admin/pages/form.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/admin/layout/main.php';
    }

    public function delete()
    {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->db->prepare("DELETE FROM pages WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: /admin/pages');
    }

    private function save(int $id = 0): void
    {
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $content = $_POST['content'] ?? '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        if (!$slug) {
            $slug = strtolower(preg_replace('/[^a-z0-9-]+/i', '-', $title));
        }

        if ($id > 0) {
            $stmt = $this->db->prepare("UPDATE pages SET title=?, slug=?, content=?, is_active=? WHERE id=?");
            $stmt->execute([$title, $slug, $content, $is_active, $id]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO pages (title, slug, content, is_active) VALUES (?,?,?,?)");
            $stmt->execute([$title, $slug, $content, $is_active]);
        }
    }
}
