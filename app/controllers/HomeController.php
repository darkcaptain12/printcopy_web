<?php
namespace App\Controllers;

use App\Core\Controller;
use PDO;

class HomeController extends Controller {
    
    public function index() {
        $db = (new \Database())->getConnection();
        
        $data = [
            'categories' => [],
            'products' => [],
            'settings' => [],
            'banners' => []
        ];

        try {
            // Fetch Categories
            $stmt = $db->query("SELECT * FROM categories WHERE is_active = 1 LIMIT 8");
            if ($stmt) {
                $data['categories'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (\Exception $e) {
            // Table might not exist or empty
        }

        try {
            // Fetch Newest Products
            $stmt = $db->query("SELECT * FROM products WHERE is_active = 1 ORDER BY id DESC LIMIT 8");
            if ($stmt) {
                $data['products'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (\Exception $e) {
            // Table might not exist
        }

        try {
            // Fetch Settings
            $stmt = $db->query("SELECT * FROM settings");
            if ($stmt) {
                $settingsAll = $stmt->fetchAll(PDO::FETCH_ASSOC);
                // Convert to key-value pair for easier access if needed, or just pass as list
                // For now, let's assume it's a key-value structure or we just pass the first row if it's a single row config
                // Often settings tables are key, value. Let's assume list for now.
                $data['settings'] = $settingsAll;
            }
        } catch (\Exception $e) {
            // Table might not exist
        }

        // Banners (Optional)
        try {
            $stmt = $db->query("SELECT * FROM banners WHERE is_active = 1 ORDER BY sort_order ASC");
            if ($stmt) {
                $data['banners'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (\Exception $e) {
            // Table might not exist
        }

        // Add page title
        $data['title'] = "PrintCopy - Profesyonel Baskı Çözümleri";

        // View rendering
        // Assuming we can pass data to view. Controller base class should handle extract($data).
        // If not using a template engine, we will extract manually in the view or controller helper.
        // Checking Controller base class usage in ProductController: $this->view('products/index', ['products' => $products...]);
        $this->view('home/index', $data);
    }
}
