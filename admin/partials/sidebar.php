<?php
$current_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>
<aside class="bg-gray-900 text-white flex-shrink-0 transition-all duration-300 w-64">
    <div class="h-16 flex items-center justify-center border-b border-gray-700">
        <span class="text-xl font-bold">PrintCopy</span>
    </div>
    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1">
            <li>
                <a href="/admin/dashboard" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= $current_uri == '/admin/dashboard' || $current_uri == '/admin/dashboard.php' ? 'bg-gray-800 border-l-4 border-blue-500' : '' ?>">
                    <i class="fas fa-tachometer-alt w-6"></i>
                    <span><?= t('common.dashboard') ?></span>
                </a>
            </li>
            <li>
                <a href="/admin/products" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= strpos($current_uri, '/admin/products') === 0 ? 'bg-gray-800 border-l-4 border-blue-500' : '' ?>">
                    <i class="fas fa-box w-6"></i>
                    <span><?= t('common.products') ?></span>
                </a>
            </li>
            <li>
                <a href="/admin/categories" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= strpos($current_uri, '/admin/categories') === 0 ? 'bg-gray-800 border-l-4 border-blue-500' : '' ?>">
                    <i class="fas fa-tags w-6"></i>
                    <span><?= t('common.categories') ?></span>
                </a>
            </li>
            <li>
                <a href="/admin/orders" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= strpos($current_uri, '/admin/orders') === 0 ? 'bg-gray-800 border-l-4 border-blue-500' : '' ?>">
                    <i class="fas fa-shopping-cart w-6"></i>
                    <span><?= t('common.orders') ?></span>
                </a>
            </li>
            <li>
                <a href="/admin/pages" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= strpos($current_uri, '/admin/pages') === 0 ? 'bg-gray-800 border-l-4 border-blue-500' : '' ?>">
                    <i class="fas fa-file-alt w-6"></i>
                    <span><?= t('common.content') ?></span>
                </a>
            </li>
            <li>
                <a href="/admin/settings/general.php" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= strpos($current_uri, '/admin/settings') === 0 ? 'bg-gray-800 border-l-4 border-blue-500' : '' ?>">
                    <i class="fas fa-cogs w-6"></i>
                    <span><?= t('common.settings') ?></span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="p-4 border-t border-gray-700">
        <a href="/admin/logout" class="flex items-center text-gray-400 hover:text-white">
            <i class="fas fa-sign-out-alt w-6"></i>
            <span><?= t('common.logout') ?></span>
        </a>
    </div>
</aside>
