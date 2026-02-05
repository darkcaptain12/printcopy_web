<?php
namespace App\Controllers\Admin;

use Database;
use PDO;

class DashboardController extends BaseAdminController {
    public function index() {
        $db = (new Database())->getConnection();
        
        $stats = [
            'products' => $db->query("SELECT COUNT(*) FROM products")->fetchColumn(),
            'orders' => $db->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
            'categories' => $db->query("SELECT COUNT(*) FROM categories")->fetchColumn(),
            'revenue' => $db->query("SELECT SUM(total_amount) FROM orders WHERE status = 'paid' OR status = 'shipped'")->fetchColumn() ?: 0,
            'pending_orders' => $db->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn(),
        ];

        // Fetch Last 10 Orders
        $stmt = $db->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 10");
        $latestOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Pass to view (we need a way to wrap it in the layout)
        // For simplicity, we can buffer the content or pass it to a layout renderer method in BaseController if we had one.
        // But since we are using simple views, let's buffer.
        
        ob_start();
        $data = $stats;
        extract($data);
        require_once '../app/Views/admin/dashboard.php';
        $content = ob_get_clean();
        
        require_once '../app/Views/admin/layout/main.php';
    }
}
