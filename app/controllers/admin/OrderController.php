<?php
namespace App\Controllers\Admin;

use App\Core\Session;
use App\Core\CSRF;
use Database;
use PDO;

class OrderController extends BaseAdminController {
    private $db;

    public function __construct() {
        parent::__construct();
        $this->db = (new Database())->getConnection();
    }

    public function index() {
        // Filter & Search Logic
        $whereCliuses = [];
        $params = [];

        // Search
        $search = $_GET['search'] ?? '';
        if (!empty($search)) {
            $whereCliuses[] = "(order_number LIKE ? OR customer_name LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        // Filter by Status
        $status = $_GET['status'] ?? '';
        $validStatuses = ['pending', 'paid', 'shipped', 'cancelled'];
        if (in_array($status, $validStatuses)) {
            $whereCliuses[] = "status = ?";
            $params[] = $status;
        }

        $sql = "SELECT * FROM orders";
        if (!empty($whereCliuses)) {
            $sql .= " WHERE " . implode(" AND ", $whereCliuses);
        }
        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        ob_start();
        require_once '../app/Views/admin/orders/index.php';
        $content = ob_get_clean();
        require_once '../app/Views/admin/layout/main.php';
    }

    public function show() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /admin/orders');
            exit();
        }

        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            Session::setFlash('error', t('msg.order_not_found'));
            header('Location: /admin/orders');
            exit();
        }

        // Fetch Order Items (verify table existence or if items are stored)
        // Assuming table `order_items` exists based on typical structure
        $items = [];
        try {
            $stmtItems = $this->db->prepare("SELECT * FROM order_items WHERE order_id = ?");
            $stmtItems->execute([$id]);
            $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            // Table might not exist or other error, handle gracefully
            $items = []; 
        }

        ob_start();
        require_once '../app/Views/admin/orders/show.php';
        $content = ob_get_clean();
        require_once '../app/Views/admin/layout/main.php';
    }

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!CSRF::check($_POST['csrf_token'])) die(t('msg.csrf_error'));

            $id = $_POST['order_id'];
            $newStatus = $_POST['status'];
            $adminNote = trim($_POST['admin_note'] ?? '');

            // 1. Fetch Current Order
            $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = ?");
            $stmt->execute([$id]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$order) {
                Session::setFlash('error', t('msg.order_not_found'));
                header("Location: /admin/orders");
                exit();
            }

            $currentStatus = $order['status'];
            $validStatuses = ['pending', 'paid', 'shipped', 'cancelled'];

            // 2. Validate Status Existence
            if (!in_array($newStatus, $validStatuses)) {
                Session::setFlash('error', t('msg.invalid_status'));
                header("Location: /admin/orders/show?id=$id");
                exit();
            }

            // 3. Status Transition Rules (Business Logic)
            $errorMsg = null;

            // Rule: cancelled -> any (No return from cancelled)
            if ($currentStatus === 'cancelled' && $newStatus !== 'cancelled') {
                $errorMsg = t('error.transition_cancelled'); 
            }
            // Rule: shipped -> paid (No return)
            elseif ($currentStatus === 'shipped' && $newStatus === 'paid') {
                $errorMsg = t('error.transition_shipped_to_paid');
            }
            // Rule: shipped -> pending (No return)
            elseif ($currentStatus === 'shipped' && $newStatus === 'pending') {
                $errorMsg = t('error.transition_shipped_to_pending');
            }
            // Rule: paid -> cancelled (Explicitly blocked by user request)
            elseif ($currentStatus === 'paid' && $newStatus === 'cancelled') {
                $errorMsg = t('error.transition_paid_to_cancelled');
            }

            if ($errorMsg) {
                Session::setFlash('error', $errorMsg);
                header("Location: /admin/orders/show?id=$id");
                exit();
            }

            try {
                // 4. Update Database (Status + Admin Note)
                $stmt = $this->db->prepare("UPDATE orders SET status = ?, admin_note = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$newStatus, $adminNote, $id]);
                
                // 5. Logging
                if ($currentStatus !== $newStatus) {
                    $logFile = __DIR__ . '/../../../storage/logs/order_admin.log';
                    $logDir = dirname($logFile);
                    if (!is_dir($logDir)) mkdir($logDir, 0775, true);

                    // Try to get admin info safely
                    $adminId = $_SESSION['admin_id'] ?? ($_SESSION['user_id'] ?? 'Bilinmiyor');
                    $adminUser = $_SESSION['admin_username'] ?? ($_SESSION['username'] ?? 'Admin');
                    
                    $logEntry = sprintf(
                        "[%s] Admin: %s (ID: %s) | IP: %s | Sipariş: %s | Durum: %s -> %s\n",
                        date('Y-m-d H:i:s'),
                        $adminUser,
                        $adminId,
                        $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
                        $order['order_number'],
                        $currentStatus,
                        $newStatus
                    );
                    
                    file_put_contents($logFile, $logEntry, FILE_APPEND);
                }

                Session::setFlash('success', 'Sipariş durumu güncellendi.');
            } catch (\Exception $e) {
                Session::setFlash('error', 'Hata: ' . $e->getMessage());
            }

            header("Location: /admin/orders/show?id=$id");
            exit();
        }
    }
}
