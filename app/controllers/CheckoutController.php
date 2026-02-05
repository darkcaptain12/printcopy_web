<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Cart;
use PDO;

class CheckoutController extends Controller {
    
    public function index() {
        if (empty(Cart::getItems())) {
            header('Location: /cart');
            exit;
        }
        $this->view('checkout/index', ['items' => Cart::getItems(), 'total' => Cart::getTotal()]);
    }

    public function process() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!\App\Core\CSRF::check($_POST['csrf_token'] ?? '')) {
                die('Güvenlik Hatası: CSRF Token geçersiz.');
            }
            if (empty(Cart::getItems())) {
                header('Location: /cart');
                exit;
            }

            $data = [
                'name' => trim($_POST['full_name']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'address' => trim($_POST['address']),
                'city' => trim($_POST['city']),
                'district' => trim($_POST['district']),
                'notes' => trim($_POST['notes'] ?? ''),
            ];

            // Basic Validation
            if (empty($data['name']) || empty($data['email']) || empty($data['phone']) || empty($data['address'])) {
                die('Lütfen tüm zorunlu alanları doldurunuz.');
            }

            // Create Order
            $order = $this->createOrder($data);
            if ($order) {
                // Clear Cart
                Cart::clear();
                
                // Redirect to Payment Page
                header("Location: /payment/paytr?oid=" . $order['order_number']);
                exit;
            } else {
                die('Sipariş oluşturulurken bir hata oluştu.');
            }
        }
    }

    private function createOrder($data) {
        $db = (new \Database())->getConnection();
        
        try {
            $db->beginTransaction();

            $total = Cart::getTotal();
            // We use order_number as merchant_oid mostly, but let's keep consistency
            // Pattern: PC-YYYYMMDDHHIISS-XXXX
            $order_number = 'PC-' . date('YmdHis') . '-' . rand(1000, 9999);
            
            $full_address = $data['address'] . ' ' . $data['district'] . '/' . $data['city'];

            $stmt = $db->prepare("INSERT INTO orders (order_number, merchant_oid, customer_name, customer_email, customer_phone, address, total_amount, notes, status, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'pending')");
            
            $stmt->execute([
                $order_number,
                $order_number, // merchant_oid same as order_number for simplicity
                $data['name'],
                $data['email'],
                $data['phone'],
                $full_address,
                $total,
                $data['notes']
            ]);
            
            $order_id = $db->lastInsertId();

            // Insert Items
            $itemStmt = $db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            foreach (Cart::getItems() as $item) {
                $itemStmt->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
            }

            $db->commit();
            
            return [
                'id' => $order_id,
                'order_number' => $order_number
            ];

        } catch (\Exception $e) {
            $db->rollBack();
            return false;
        }
    }
}
