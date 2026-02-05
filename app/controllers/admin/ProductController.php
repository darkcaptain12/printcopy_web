<?php
namespace App\Controllers\Admin;

use App\Core\Session;
use App\Core\CSRF;
use App\Core\Upload;
use Database;
use PDO;

class ProductController extends BaseAdminController {
    private $db;

    public function __construct() {
        parent::__construct();
        $this->db = (new Database())->getConnection();
    }

    public function index() {
        $stmt = $this->db->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        ob_start();
        require_once '../app/Views/admin/products/index.php';
        $content = ob_get_clean();
        require_once '../app/Views/admin/layout/main.php';
    }

    public function create() {
        $categories = $this->db->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
        $product = null;

        ob_start();
        require_once '../app/Views/admin/products/form.php';
        $content = ob_get_clean();
        require_once '../app/Views/admin/layout/main.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!CSRF::check($_POST['csrf_token'])) die('CSRF Token Error');

            $name = trim($_POST['name']);
            $slug = $_POST['slug'] ?: strtolower(str_replace(' ', '-', $name));
            $category_id = $_POST['category_id'];
            $price = $_POST['price'];
            $description = $_POST['description'];
            $short_description = $_POST['short_description'] ?? '';
            $stock_status = $_POST['stock_status'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            $imagePath = null;
            if (!empty($_FILES['image']['name'])) {
                $imagePath = Upload::image($_FILES['image']);
            } elseif (!empty($_POST['image_url'])) {
                $imagePath = trim($_POST['image_url']);
            }

            $stmt = $this->db->prepare("INSERT INTO products (name, slug, category_id, price, short_description, description, image, stock_status, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            try {
                $stmt->execute([$name, $slug, $category_id, $price, $short_description, $description, $imagePath, $stock_status, $is_active]);
                Session::setFlash('success', 'Product created successfully');
                header('Location: /admin/products');
                exit();
            } catch (\PDOException $e) {
                Session::setFlash('error', 'Error: ' . $e->getMessage());
                header('Location: /admin/products/create');
                exit();
            }
        }
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /admin/products');
            exit();
        }

        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $categories = $this->db->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

        ob_start();
        require_once '../app/Views/admin/products/form.php';
        $content = ob_get_clean();
        require_once '../app/Views/admin/layout/main.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!CSRF::check($_POST['csrf_token'])) die('CSRF Token Error');

            $id = $_POST['id'];
            $name = trim($_POST['name']);
            $slug = $_POST['slug'];
            $category_id = $_POST['category_id'];
            $price = $_POST['price'];
            $description = $_POST['description'];
            $short_description = $_POST['short_description'] ?? '';
            $stock_status = $_POST['stock_status'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            $currentProduct = $this->db->query("SELECT image FROM products WHERE id = $id")->fetch();
            $imagePath = $currentProduct['image'];

            if (!empty($_FILES['image']['name'])) {
                $newImage = Upload::image($_FILES['image']);
                if ($newImage) {
                    $imagePath = $newImage;
                }
            } elseif (!empty($_POST['image_url'])) {
                $imagePath = trim($_POST['image_url']);
            }

            $stmt = $this->db->prepare("UPDATE products SET name=?, slug=?, category_id=?, price=?, short_description=?, description=?, image=?, stock_status=?, is_active=? WHERE id=?");
            try {
                $stmt->execute([$name, $slug, $category_id, $price, $short_description, $description, $imagePath, $stock_status, $is_active, $id]);
                Session::setFlash('success', 'Product updated successfully');
                header('Location: /admin/products');
                exit();
            } catch (\PDOException $e) {
                Session::setFlash('error', 'Error: ' . $e->getMessage());
                header("Location: /admin/products/edit?id=$id");
                exit();
            }
        }
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if($id) {
             $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
             $stmt->execute([$id]);
             Session::setFlash('success', 'Product deleted');
        }
        header('Location: /admin/products');
        exit();
    }
}
