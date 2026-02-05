<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Cart;
use PDO;

class ProductController extends Controller {
    
    public function index() {
        $db = (new \Database())->getConnection();
        
        // Filters
        $category_slug = $_GET['category'] ?? null;
        $search = $_GET['q'] ?? null;
        $sort = $_GET['sort'] ?? 'newest';
        
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug FROM products p JOIN categories c ON p.category_id = c.id WHERE p.is_active = 1";
        $params = [];
        
        if ($category_slug) {
            $sql .= " AND c.slug = ?";
            $params[] = $category_slug;
        }
        
        if ($search) {
            $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        // Sorting
        switch ($sort) {
            case 'price_asc':
                $sql .= " ORDER BY p.price ASC";
                break;
            case 'price_desc':
                $sql .= " ORDER BY p.price DESC";
                break;
            case 'newest':
            default:
                $sql .= " ORDER BY p.id DESC";
                break;
        }
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $categories = $db->query("SELECT * FROM categories WHERE is_active = 1")->fetchAll(PDO::FETCH_ASSOC);
        
        $this->view('products/index', [
            'products' => $products, 
            'categories' => $categories,
            'selected_category' => $category_slug,
            'search_query' => $search,
            'selected_sort' => $sort
        ]);
    }

    public function detail($slug) {
        $db = (new \Database())->getConnection();
        $stmt = $db->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug FROM products p JOIN categories c ON p.category_id = c.id WHERE p.slug = ? AND p.is_active = 1");
        $stmt->execute([$slug]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product) {
            header("HTTP/1.0 404 Not Found");
            echo "Product not found"; 
            exit;
        }
        
        $this->view('products/detail', ['product' => $product]);
    }
}
