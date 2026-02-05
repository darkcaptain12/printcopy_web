<?php
namespace App\Controllers\Admin;

use App\Core\Session;
use App\Core\CSRF;
use App\Core\Upload;
use Database;
use PDO;

class ContentController extends BaseAdminController {
    private $db;

    public function __construct() {
        parent::__construct();
        $this->db = (new Database())->getConnection();
    }

    public function index() {
        header('Location: /admin/content/blogs');
    }

    // --- BLOGS ---
    public function blogs() {
        $stmt = $this->db->query("SELECT * FROM blogs ORDER BY id DESC");
        $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ob_start();
        require_once '../app/Views/admin/content/blogs/index.php';
        $content = ob_get_clean();
        require_once '../app/Views/admin/layout/main.php';
    }

    public function createBlog() {
        $blog = null;
        ob_start();
        require_once '../app/Views/admin/content/blogs/form.php';
        $content = ob_get_clean();
        require_once '../app/Views/admin/layout/main.php';
    }

    public function storeBlog() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!CSRF::check($_POST['csrf_token'])) die('CSRF Token Error');
            $title = trim($_POST['title']);
            $slug = $_POST['slug'] ?: strtolower(str_replace(' ', '-', $title));
            $content = $_POST['content'];
            $image = !empty($_FILES['image']['name']) ? Upload::image($_FILES['image']) : null;
            
            $stmt = $this->db->prepare("INSERT INTO blogs (title, slug, content, image) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $slug, $content, $image]);
            Session::setFlash('success', 'Blog post created');
            header('Location: /admin/content/blogs');
            exit();
        }
    }

    public function editBlog() {
        $id = $_GET['id'];
        $stmt = $this->db->prepare("SELECT * FROM blogs WHERE id = ?");
        $stmt->execute([$id]);
        $blog = $stmt->fetch(PDO::FETCH_ASSOC);
        ob_start();
        require_once '../app/Views/admin/content/blogs/form.php';
        $content = ob_get_clean();
        require_once '../app/Views/admin/layout/main.php';
    }

    public function updateBlog() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $title = $_POST['title'];
            $slug = $_POST['slug'];
            $content = $_POST['content'];
            
            $blog = $this->db->query("SELECT image FROM blogs WHERE id=$id")->fetch();
            $image = $blog['image'];
            if (!empty($_FILES['image']['name'])) {
                $image = Upload::image($_FILES['image']);
            }

            $stmt = $this->db->prepare("UPDATE blogs SET title=?, slug=?, content=?, image=? WHERE id=?");
            $stmt->execute([$title, $slug, $content, $image, $id]);
            Session::setFlash('success', 'Blog post updated');
            header('Location: /admin/content/blogs');
            exit();
        }
    }

    public function deleteBlog() {
        $id = $_GET['id'];
        $this->db->query("DELETE FROM blogs WHERE id=$id");
        Session::setFlash('success', 'Blog deleted');
        header('Location: /admin/content/blogs');
        exit();
    }

    // --- PAGES ---
    public function pages() {
        $stmt = $this->db->query("SELECT * FROM pages ORDER BY id ASC");
        $pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ob_start();
        require_once '../app/Views/admin/content/pages/index.php';
        $content = ob_get_clean();
        require_once '../app/Views/admin/layout/main.php';
    }

    public function editPage() {
        $id = $_GET['id'];
        $stmt = $this->db->prepare("SELECT * FROM pages WHERE id = ?");
        $stmt->execute([$id]);
        $page = $stmt->fetch(PDO::FETCH_ASSOC);
        ob_start();
        require_once '../app/Views/admin/content/pages/form.php';
        $content = ob_get_clean();
        require_once '../app/Views/admin/layout/main.php';
    }

    public function updatePage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $title = $_POST['title'];
            $content = $_POST['content']; // JSON or HTML
            
            $stmt = $this->db->prepare("UPDATE pages SET title=?, content=? WHERE id=?");
            $stmt->execute([$title, $content, $id]);
            Session::setFlash('success', 'Page updated');
            header('Location: /admin/content/pages');
            exit();
        }
    }

    // --- MENUS ---
    public function menus() {
        $stmt = $this->db->query("SELECT * FROM menus ORDER BY position, sort_order ASC");
        $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ob_start();
        require_once '../app/Views/admin/content/menus/index.php';
        $content = ob_get_clean();
        require_once '../app/Views/admin/layout/main.php';
    }
    
    // Simplification: Direct Add/Delete for menus in index or separate method?
    // Let's keep it simple for now, just listing. Detailed implementation can be added if requested specifically.
    // Assuming user can edit menus directly via SQL or a simple future form.
    // Adding a simple 'storeMenu' and 'deleteMenu' for completeness.

    public function storeMenu() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
             $title = $_POST['title'];
             $link = $_POST['link'];
             $position = $_POST['position'];
             $sort_order = $_POST['sort_order'];
             
             $stmt = $this->db->prepare("INSERT INTO menus (title, link, position, sort_order) VALUES (?, ?, ?, ?)");
             $stmt->execute([$title, $link, $position, $sort_order]);
             header('Location: /admin/content/menus');
        }
    }

    public function deleteMenu() {
        $id = $_GET['id'];
        $this->db->query("DELETE FROM menus WHERE id=$id");
        header('Location: /admin/content/menus');
    }
}
