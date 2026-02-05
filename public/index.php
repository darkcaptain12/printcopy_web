<?php

// Start session for frontend
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

// Load Helpers
if (file_exists(__DIR__ . '/../app/Helpers/translate.php')) {
    require_once __DIR__ . '/../app/Helpers/translate.php';
}

// Autoloader function
spl_autoload_register(function ($class) {
    if (strpos($class, 'App\\') === 0) {
        $class = substr($class, 4); // Remove App\
    }
    
    $file = __DIR__ . '/../app/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    } else {
        // Fallback for global classes if any (like Database)
        $file = __DIR__ . '/../config/' . $class . '.php'; 
        if (file_exists($file)) require $file;
    }
});

use App\Core\Router;

$router = new Router();

// Test-only reset route (only active when APP_ENV=test or TEST_MODE=1)
if (
    getenv('APP_ENV') === 'test' ||
    getenv('TEST_MODE') === '1' ||
    in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'])
) {
    $router->add('GET', '/__test__/reset', function() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        unset($_SESSION['cart']);
        unset($_SESSION['admin_logged_in'], $_SESSION['admin_id'], $_SESSION['admin_username']);
        session_regenerate_id(true);
        header('Content-Type: application/json');
        echo json_encode(['ok' => true]);
        exit;
    });
}

// Frontend
$router->add('GET', '/', 'HomeController', 'index');

// Product Routes
$router->add('GET', '/products', 'ProductController', 'index');
$router->add('GET', '/product/([^/]+)', 'ProductController', 'detail');

// Cart Routes
$router->add('GET', '/cart', 'CartController', 'index');
$router->add('POST', '/cart/add', 'CartController', 'add');
$router->add('POST', '/cart/update', 'CartController', 'update');
$router->add('POST', '/cart/remove', 'CartController', 'remove');
$router->add('POST', '/cart/clear', 'CartController', 'clear');
$router->add('POST', '/cart/direct', 'CartController', 'direct');

// Contact
$router->add('GET', '/contact', 'ContactController', 'index');
$router->add('POST', '/contact/submit', 'ContactController', 'submit');
$router->add('GET', '/iletisim', 'ContactController', 'index'); // Turkish alias
$router->add('POST', '/iletisim/submit', 'ContactController', 'submit');

// Blog
$router->add('GET', '/blog', 'BlogController', 'index');
$router->add('GET', '/blog/([^/]+)', 'BlogController', 'detail');

// Static pages
$router->add('GET', '/page/([^/]+)', 'PageController', 'detail');

// Checkout Routes
$router->add('GET', '/checkout', 'CheckoutController', 'index');
$router->add('POST', '/checkout/process', 'CheckoutController', 'process');

// Payment Routes
$router->add('GET', '/payment/paytr', 'PaymentController', 'paytr');
$router->add('POST', '/payment/success', function() { require __DIR__ . '/../app/Views/payment/success.php'; });
$router->add('POST', '/payment/fail', function() { require __DIR__ . '/../app/Views/payment/fail.php'; });
// Get method support for success/fail as PayTR might redirect via GET or POST depending on config, usually POST but good to have both or check docs.
$router->add('GET', '/payment/success', function() { require __DIR__ . '/../app/Views/payment/success.php'; });
$router->add('GET', '/payment/fail', function() { require __DIR__ . '/../app/Views/payment/fail.php'; });
$router->add('GET', '/thank-you', function() { require __DIR__ . '/../app/Views/payment/thankyou.php'; });

// Dynamic sitemap
$router->add('GET', '/sitemap.xml', function() {
    header('Content-Type: application/xml; charset=utf-8');
    $base = 'http://' . $_SERVER['HTTP_HOST'];
    $urls = [
        '/',
        '/products',
        '/blog',
        '/iletisim',
    ];
    try {
        $db = (new Database())->getConnection();
        foreach ($db->query("SELECT slug FROM products WHERE is_active = 1") as $row) {
            $urls[] = '/product/' . $row['slug'];
        }
        foreach ($db->query("SELECT slug FROM categories WHERE is_active = 1") as $row) {
            $urls[] = '/products?category=' . $row['slug'];
        }
        foreach ($db->query("SELECT slug FROM blog_posts WHERE is_published = 1") as $row) {
            $urls[] = '/blog/' . $row['slug'];
        }
        foreach ($db->query("SELECT slug FROM pages WHERE is_active = 1") as $row) {
            $urls[] = '/page/' . $row['slug'];
        }
    } catch (\Exception $e) {}

    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
    foreach (array_unique($urls) as $u) {
        echo "  <url><loc>{$base}{$u}</loc></url>\n";
    }
    echo "</urlset>";
    exit;
});

// Admin Auth
$router->add('GET', '/admin/login', 'Admin\AuthController', 'login');
$router->add('POST', '/admin/login', 'Admin\AuthController', 'authenticate');
$router->add('GET', '/admin/logout', 'Admin\AuthController', 'logout');

// Admin Dashboard
$router->add('GET', '/admin', 'Admin\DashboardController', 'index');
$router->add('GET', '/admin/dashboard', 'Admin\DashboardController', 'index');

// Products
$router->add('GET', '/admin/products', 'Admin\ProductController', 'index');
$router->add('GET', '/admin/products/create', 'Admin\ProductController', 'create');
$router->add('POST', '/admin/products/store', 'Admin\ProductController', 'store');
$router->add('GET', '/admin/products/edit', 'Admin\ProductController', 'edit');
$router->add('POST', '/admin/products/update', 'Admin\ProductController', 'update');
$router->add('GET', '/admin/products/delete', 'Admin\ProductController', 'delete');

// Banners
$router->add('GET', '/admin/banners', 'Admin\BannerController', 'index');
$router->add('GET', '/admin/banners/create', 'Admin\BannerController', 'create');
$router->add('POST', '/admin/banners/create', 'Admin\BannerController', 'create');
$router->add('GET', '/admin/banners/edit', 'Admin\BannerController', 'edit');
$router->add('POST', '/admin/banners/edit', 'Admin\BannerController', 'edit');
$router->add('GET', '/admin/banners/delete', 'Admin\BannerController', 'delete');

// Categories
$router->add('GET', '/admin/categories', 'Admin\CategoryController', 'index');
$router->add('GET', '/admin/categories/create', 'Admin\CategoryController', 'create');
$router->add('POST', '/admin/categories/store', 'Admin\CategoryController', 'store');
$router->add('GET', '/admin/categories/edit', 'Admin\CategoryController', 'edit');
$router->add('POST', '/admin/categories/update', 'Admin\CategoryController', 'update');
$router->add('GET', '/admin/categories/delete', 'Admin\CategoryController', 'delete');

// Orders
$router->add('GET', '/admin/orders', 'Admin\OrderController', 'index');
$router->add('GET', '/admin/orders/show', 'Admin\OrderController', 'show');
$router->add('POST', '/admin/orders/status', 'Admin\OrderController', 'updateStatus');

// Settings
// $router->add('GET', '/admin/settings', 'Admin\SettingController', 'index');
// $router->add('POST', '/admin/settings/update', 'Admin\SettingController', 'update');

// Direct File Routing for Admin Settings (Quick Fix)
$router->add('GET', '/admin/settings', function() { require __DIR__ . '/../admin/settings/general.php'; });
$router->add('POST', '/admin/settings', function() { require __DIR__ . '/../admin/settings/general.php'; });
$router->add('GET', '/admin/settings/general.php', function() { require __DIR__ . '/../admin/settings/general.php'; });

// API Routes
$router->add('POST', '/admin/api/send_test_mail.php', function() { require __DIR__ . '/../admin/api/send_test_mail.php'; });

// Content - Blogs
$router->add('GET', '/admin/content', 'Admin\ContentController', 'index');
$router->add('GET', '/admin/content/blogs', 'Admin\ContentController', 'blogs');
$router->add('GET', '/admin/content/blogs/create', 'Admin\ContentController', 'createBlog');
$router->add('POST', '/admin/content/blogs/store', 'Admin\ContentController', 'storeBlog');
$router->add('GET', '/admin/content/blogs/edit', 'Admin\ContentController', 'editBlog');
$router->add('POST', '/admin/content/blogs/update', 'Admin\ContentController', 'updateBlog');
$router->add('GET', '/admin/content/blogs/delete', 'Admin\ContentController', 'deleteBlog');

// Content - Pages
$router->add('GET', '/admin/content/pages', 'Admin\ContentController', 'pages');
$router->add('GET', '/admin/content/pages/edit', 'Admin\ContentController', 'editPage');
$router->add('POST', '/admin/content/pages/update', 'Admin\ContentController', 'updatePage');

// Content - Menus
$router->add('GET', '/admin/content/menus', 'Admin\ContentController', 'menus');
$router->add('POST', '/admin/content/menus/store', 'Admin\ContentController', 'storeMenu');
$router->add('GET', '/admin/content/menus/delete', 'Admin\ContentController', 'deleteMenu');


$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = rtrim($path, '/');
if ($path === '') $path = '/';

$router->dispatch($path);
