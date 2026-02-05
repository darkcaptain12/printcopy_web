<?php
namespace App\Controllers\Admin;

use Database;
use PDO;

class BannerController extends BaseAdminController
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
        $this->db->exec("CREATE TABLE IF NOT EXISTS banners (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            subtitle VARCHAR(255) DEFAULT NULL,
            image VARCHAR(255) DEFAULT NULL,
            cta_text VARCHAR(100) DEFAULT NULL,
            cta_link VARCHAR(255) DEFAULT NULL,
            sort_order INT DEFAULT 0,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Ensure legacy tables have missing columns (subtitle, cta_text, etc.)
        $columns = $this->db->query("SHOW COLUMNS FROM banners")->fetchAll(PDO::FETCH_COLUMN);
        $needed = [
            'subtitle'   => "ALTER TABLE banners ADD COLUMN subtitle VARCHAR(255) DEFAULT NULL",
            'image'      => "ALTER TABLE banners ADD COLUMN image VARCHAR(255) DEFAULT NULL",
            'cta_text'   => "ALTER TABLE banners ADD COLUMN cta_text VARCHAR(100) DEFAULT NULL",
            'cta_link'   => "ALTER TABLE banners ADD COLUMN cta_link VARCHAR(255) DEFAULT NULL",
            'sort_order' => "ALTER TABLE banners ADD COLUMN sort_order INT DEFAULT 0",
            'is_active'  => "ALTER TABLE banners ADD COLUMN is_active TINYINT(1) DEFAULT 1",
        ];
        foreach ($needed as $col => $sql) {
            if (!in_array($col, $columns, true)) {
                try { $this->db->exec($sql); } catch (\Exception $e) { /* ignore if fails */ }
            }
        }
    }

    public function index()
    {
        $banners = $this->db->query("SELECT * FROM banners ORDER BY sort_order ASC, id DESC")->fetchAll(PDO::FETCH_ASSOC);
        ob_start();
        require __DIR__ . '/../../views/admin/banners/index.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/admin/layout/main.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->saveBanner();
            header('Location: /admin/banners');
            exit;
        }
        $banner = null;
        ob_start();
        require __DIR__ . '/../../views/admin/banners/form.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/admin/layout/main.php';
    }

    public function edit()
    {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->db->prepare("SELECT * FROM banners WHERE id = ?");
        $stmt->execute([$id]);
        $banner = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$banner) {
            header('Location: /admin/banners');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->saveBanner($id);
            header('Location: /admin/banners');
            exit;
        }
        ob_start();
        require __DIR__ . '/../../views/admin/banners/form.php';
        $content = ob_get_clean();
        require __DIR__ . '/../../views/admin/layout/main.php';
    }

    public function delete()
    {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $this->db->prepare("DELETE FROM banners WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: /admin/banners');
    }

    private function saveBanner(int $id = 0): void
    {
        $title = $_POST['title'] ?? '';
        $subtitle = $_POST['subtitle'] ?? '';
        $cta_text = $_POST['cta_text'] ?? '';
        $cta_link = $_POST['cta_link'] ?? '';
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        $imagePath = $_POST['current_image'] ?? '';
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = __DIR__ . '/../../../public/uploads/banners/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);
            $basename = time() . '_' . basename($_FILES['image']['name']);
            $target = $uploadDir . $basename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $imagePath = '/uploads/banners/' . $basename;
            }
        }

        if ($id > 0) {
            $stmt = $this->db->prepare("UPDATE banners SET title=?, subtitle=?, image=?, cta_text=?, cta_link=?, sort_order=?, is_active=? WHERE id=?");
            $stmt->execute([$title, $subtitle, $imagePath, $cta_text, $cta_link, $sort_order, $is_active, $id]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO banners (title, subtitle, image, cta_text, cta_link, sort_order, is_active) VALUES (?,?,?,?,?,?,?)");
            $stmt->execute([$title, $subtitle, $imagePath, $cta_text, $cta_link, $sort_order, $is_active]);
        }
    }
}
