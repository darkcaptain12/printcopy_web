<?php
namespace App\Controllers;

use App\Core\Controller;
use Database;
use PDO;

class PaymentController extends Controller {
    
    public function paytr() {
        $oid = $_GET['oid'] ?? '';
        
        if (!$oid) {
            die("Sipariş numarası bulunamadı.");
        }

        $db = (new Database())->getConnection();
        $stmt = $db->prepare("SELECT * FROM orders WHERE order_number = ? LIMIT 1");
        $stmt->execute([$oid]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            die("Geçersiz sipariş numarası.");
        }

        if ($order['status'] === 'paid') {
            die("Bu sipariş zaten ödenmiş.");
        }

        // Prepare PayTR Token
        $tokenData = $this->getPaytrToken($order);
        
        $this->view('payment/paytr', [
            'order' => $order,
            'token_data' => $tokenData
        ]);
    }

    private function getPaytrToken($order) {
        // PayTR Configuration from settings
        $db = (new Database())->getConnection();
        $settings = $db->query("SELECT `key`,`value` FROM settings")->fetchAll(PDO::FETCH_KEY_PAIR);
        $merchant_id    = $settings['paytr_merchant_id'] ?? '';
        $merchant_key   = $settings['paytr_merchant_key'] ?? '';
        $merchant_salt  = $settings['paytr_merchant_salt'] ?? '';
        if (!$merchant_id || !$merchant_key || !$merchant_salt) {
            throw new \Exception('PayTR ayarları eksik');
        }
        
        $email = $order['customer_email'];
        $payment_amount = $order['total_amount'] * 100; // In kurus
        $merchant_oid = $order['order_number']; // Use order_number as merchant_oid for simplicity in matching
        $user_name = $order['customer_name'];
        $user_address = $order['address'];
        $user_phone = $order['customer_phone'];
        
        // Return URLs
        // Adjust domain if needed, for local testing use localhost or ngrok if configured
        // Ideally utilize an environment variable for BASE_URL
        $base_url = 'http://' . $_SERVER['HTTP_HOST']; 
        $merchant_ok_url = $settings['paytr_success_url'] ?? ($base_url . "/thank-you?order=" . urlencode($order['order_number']));
        $merchant_fail_url = $settings['paytr_fail_url'] ?? ($base_url . "/payment/fail");
        
        // Fetch items for basket
        $db = (new Database())->getConnection();
        $stmtItems = $db->prepare("SELECT p.name, oi.price, oi.quantity FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
        $stmtItems->execute([$order['id']]);
        $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

        $user_basket_array = array_map(function($item) {
            return [$item['name'], $item['price'], $item['quantity']];
        }, $items);

        $user_basket = base64_encode(json_encode($user_basket_array));

        if(isset($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif(isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        $timeout_limit = "30";
        $debug_on = 0;
        $test_mode = (int)($settings['paytr_test_mode'] ?? 0);
        $no_installment = 0;
        $max_installment = 0;
        $currency = "TL";
        
        $hash_str = $merchant_id . $ip . $merchant_oid . $email . $payment_amount . $user_basket . $no_installment . $max_installment . $currency . $test_mode;
        $paytr_token = base64_encode(hash_hmac('sha256', $hash_str . $merchant_salt, $merchant_key, true));
        
        return [
            'merchant_id' => $merchant_id,
            'user_ip' => $ip,
            'merchant_oid' => $merchant_oid,
            'email' => $email,
            'payment_amount' => $payment_amount,
            'paytr_token' => $paytr_token,
            'user_basket' => $user_basket,
            'debug_on' => $debug_on,
            'no_installment' => $no_installment,
            'max_installment' => $max_installment,
            'user_name' => $user_name,
            'user_address' => $user_address,
            'user_phone' => $user_phone,
            'merchant_ok_url' => $merchant_ok_url,
            'merchant_fail_url' => $merchant_fail_url,
            'timeout_limit' => $timeout_limit,
            'currency' => $currency,
            'test_mode' => $test_mode
        ];
    }
}
