<?php
namespace App\Controllers\Admin;

use App\Core\Session;
use App\Core\CSRF;
use Database;
use PDO;

class CategoryController extends BaseAdminController {
    private $db;

    public function __construct() {
        parent::__construct();
        $this->db = (new Database())->getConnection();
    }

    public function index() {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY id DESC");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        ob_start();
        require_once '../app/Views/admin/categories/index.php';
        $content = ob_get_clean();
        require_once '../app/Views/admin/layout/main.php';
    }

    public function create() {
        $stmt = $this->db->query("SELECT * FROM categories WHERE parent_id IS NULL");
        $parents = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $category = null; // For form reusability

        ob_start();
        require_once '../app/Views/admin/categories/form.php';
        $content = ob_get_clean();
        require_once '../app/Views/admin/layout/main.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!CSRF::check($_POST['csrf_token'])) die('CSRF Token Error');

            $name = trim($_POST['name']);
            $slug = $_POST['slug'] ?: strtolower(str_replace(' ', '-', $name)); // Simple slugify
            // Better slugify needed in production but keeping it simple as requested
            $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            $stmt = $this->db->prepare("INSERT INTO categories (name, slug, parent_id, is_active) VALUES (?, ?, ?, ?)");
            try {
                $stmt->execute([$name, $slug, $parent_id, $is_active]);
                Session::setFlash('success', 'Category created successfully');
                header('Location: /admin/categories');
                exit();
            } catch (\PDOException $e) {
                Session::setFlash('error', 'Error creating category: ' . $e->getMessage());
                header('Location: /admin/categories/create');
                exit();
            }
        }
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /admin/categories');
            exit();
        }

        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt_parents = $this->db->query("SELECT * FROM categories WHERE parent_id IS NULL AND id != $id");
        $parents = $stmt_parents->fetchAll(PDO::FETCH_ASSOC);

        ob_start();
        require_once '../app/Views/admin/categories/form.php';
        $content = ob_get_clean();
        require_once '../app/Views/admin/layout/main.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!CSRF::check($_POST['csrf_token'])) die('CSRF Token Error');

            $id = $_POST['id'];
            $name = trim($_POST['name']);
            $slug = $_POST['slug'];
            $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            $stmt = $this->db->prepare("UPDATE categories SET name=?, slug=?, parent_id=?, is_active=? WHERE id=?");
            try {
                $stmt->execute([$name, $slug, $parent_id, $is_active, $id]);
                Session::setFlash('success', 'Category updated successfully');
                header('Location: /admin/categories');
                exit();
            } catch (\PDOException $e) {
                Session::setFlash('error', 'Error updating category');
                header("Location: /admin/categories/edit?id=$id");
                exit();
            }
        }
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->execute([$id]);
            Session::setFlash('success', 'Category deleted');
        }
        header('Location: /admin/categories');
        exit();
    }
}
