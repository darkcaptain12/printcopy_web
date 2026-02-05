<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Cart;
use Database;
use PDO;

class CartController extends Controller {
    
    public function index() {
        $items = Cart::getItems();
        $total = Cart::getTotal();
        $this->view('cart/index', ['items' => $items, 'total' => $total]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!\App\Core\CSRF::check($_POST['csrf_token'] ?? '')) {
                header('Location: /products');
                exit;
            }
            $product_id = $_POST['product_id'];
            $quantity = max(1, (int)($_POST['quantity'] ?? 1));
            
            // Get product details from DB to be safe
            $db = (new Database())->getConnection();
            $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                Cart::add(
                    $product['id'], 
                    $quantity, 
                    $product['discount_price'] ?: $product['price'], 
                    $product['name'], 
                    $product['image']
                );
            }
            
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                 echo json_encode(['status' => 'success', 'count' => count(Cart::getItems())]);
                 exit;
            }
            
            header('Location: /cart');
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!\App\Core\CSRF::check($_POST['csrf_token'] ?? '')) {
                header('Location: /cart');
                exit;
            }
            $product_id = $_POST['product_id'];
            $quantity = (int)$_POST['quantity'];
            Cart::update($product_id, $quantity);
            header('Location: /cart');
        }
    }

    public function remove() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!\App\Core\CSRF::check($_POST['csrf_token'] ?? '')) {
                header('Location: /cart');
                exit;
            }
            $product_id = $_POST['product_id'];
            Cart::remove($product_id);
            header('Location: /cart');
        }
    }

    public function clear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!\App\Core\CSRF::check($_POST['csrf_token'] ?? '')) {
                header('Location: /cart');
                exit;
            }
            Cart::clear();
            header('Location: /cart');
        }
    }

    public function direct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!\App\Core\CSRF::check($_POST['csrf_token'] ?? '')) {
                header('Location: /products');
                exit;
            }
            $product_id = $_POST['product_id'];
            $db = (new Database())->getConnection();
            $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($product) {
                Cart::clear();
                Cart::add(
                    $product['id'],
                    1,
                    $product['discount_price'] ?: $product['price'],
                    $product['name'],
                    $product['image']
                );
            }
            header('Location: /checkout');
        }
    }
}
