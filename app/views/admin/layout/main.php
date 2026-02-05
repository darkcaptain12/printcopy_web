<?php
use App\Core\Session;
$current_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Notification Counts (Direct Query)
$db = (new Database())->getConnection();

// 1. Pending Orders (Last 24h)
$stmtPending = $db->query("SELECT COUNT(*) FROM orders WHERE status = 'pending' AND created_at >= NOW() - INTERVAL 24 HOUR");
$pending_count = $stmtPending->fetchColumn();

// 2. Recent Paid Orders (Last 1h)
$stmtPaid = $db->query("SELECT COUNT(*) FROM orders WHERE status = 'paid' AND updated_at >= NOW() - INTERVAL 1 HOUR");
$recent_paid_count = $stmtPaid->fetchColumn();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= t('common.admin_panel') ?> - PrintCopy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden" x-data="{ sidebarOpen: true }">

    <!-- Sidebar -->
    <aside class="bg-gray-900 text-white flex-shrink-0 transition-all duration-300" :class="sidebarOpen ? 'w-64' : 'w-20'">
        <div class="h-16 flex items-center justify-center border-b border-gray-700">
            <span class="text-xl font-bold" x-show="sidebarOpen">PrintCopy</span>
            <span class="text-xl font-bold" x-show="!sidebarOpen">PC</span>
        </div>
        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="space-y-1">
                <li>
                    <a href="/admin/dashboard" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= $current_uri == '/admin/dashboard' ? 'bg-gray-800 border-l-4 border-blue-500' : '' ?>">
                        <i class="fas fa-tachometer-alt w-6"></i>
                        <span x-show="sidebarOpen"><?= t('common.dashboard') ?></span>
                    </a>
                </li>
                <li>
                    <a href="/admin/products" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= strpos($current_uri, '/admin/products') === 0 ? 'bg-gray-800 border-l-4 border-blue-500' : '' ?>">
                        <i class="fas fa-box w-6"></i>
                        <span x-show="sidebarOpen"><?= t('common.products') ?></span>
                    </a>
                </li>
                <li>
                    <a href="/admin/categories" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= strpos($current_uri, '/admin/categories') === 0 ? 'bg-gray-800 border-l-4 border-blue-500' : '' ?>">
                        <i class="fas fa-tags w-6"></i>
                        <span x-show="sidebarOpen"><?= t('common.categories') ?></span>
                    </a>
                </li>
                <li>
                    <a href="/admin/orders" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= strpos($current_uri, '/admin/orders') === 0 ? 'bg-gray-800 border-l-4 border-blue-500' : '' ?>">
                        <i class="fas fa-shopping-cart w-6"></i>
                        <span x-show="sidebarOpen"><?= t('common.orders') ?></span>
                    </a>
                </li>
                <li>
                    <a href="/admin/content" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= strpos($current_uri, '/admin/content') === 0 ? 'bg-gray-800 border-l-4 border-blue-500' : '' ?>">
                        <i class="fas fa-file-alt w-6"></i>
                        <span x-show="sidebarOpen"><?= t('common.content') ?></span>
                    </a>
                </li>
                <li>
                    <a href="/admin/pages" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= strpos($current_uri, '/admin/pages') === 0 ? 'bg-gray-800 border-l-4 border-blue-500' : '' ?>">
                        <i class="fas fa-copy w-6"></i>
                        <span x-show="sidebarOpen">Sayfalar</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/banners" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= strpos($current_uri, '/admin/banners') === 0 ? 'bg-gray-800 border-l-4 border-blue-500' : '' ?>">
                        <i class="fas fa-image w-6"></i>
                        <span x-show="sidebarOpen">Banners</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/settings" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= strpos($current_uri, '/admin/settings') === 0 ? 'bg-gray-800 border-l-4 border-blue-500' : '' ?>">
                        <i class="fas fa-cogs w-6"></i>
                        <span x-show="sidebarOpen"><?= t('common.settings') ?></span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="p-4 border-t border-gray-700">
            <a href="/admin/logout" class="flex items-center text-gray-400 hover:text-white">
                <i class="fas fa-sign-out-alt w-6"></i>
                <span x-show="sidebarOpen"><?= t('common.logout') ?></span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-white shadow flex items-center justify-between px-6">
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <div class="flex items-center gap-6">
                <!-- Notifications -->
                <div class="flex items-center gap-4">
                    <!-- Pending Orders (Last 24h) -->
                    <?php if ($pending_count > 0): ?>
                    <a href="/admin/orders?status=pending" class="relative group" title="<?= t('notif.pending_orders') ?>">
                        <i class="fas fa-clock text-yellow-600 text-lg"></i>
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                            <?= $pending_count ?>
                        </span>
                    </a>
                    <?php endif; ?>

                    <!-- Recent Paid (Last 1h) -->
                    <?php if ($recent_paid_count > 0): ?>
                    <a href="/admin/orders?status=paid" class="relative group" title="<?= t('notif.new_paid') ?>">
                        <i class="fas fa-check-circle text-green-600 text-lg"></i>
                        <span class="absolute -top-2 -right-2 bg-green-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full animate-pulse">
                            <?= $recent_paid_count ?>
                        </span>
                    </a>
                    <?php endif; ?>
                </div>

                <div class="h-8 w-px bg-gray-300"></div>

                <span class="text-sm text-gray-600">Admin: <strong><?= Session::get('admin_username') ?></strong></span>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
            <?php if ($msg = Session::getFlash('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?= t('msg.success') . ' ' . $msg ?></span>
                </div>
            <?php endif; ?>
            <?php if ($msg = Session::getFlash('error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?= t('msg.error') . ' ' . $msg ?></span>
                </div>
            <?php endif; ?>

            <?= $content ?>
        </main>
    </div>

</body>
</html>
