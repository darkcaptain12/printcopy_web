<?php include 'partials/header.php'; ?>

<h1 class="text-3xl font-bold mb-8">Dashboard</h1>

<?php
$stats = [
    'products' => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
    'orders' => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
    'categories' => $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn(),
    'revenue' => $pdo->query("SELECT SUM(total_amount) FROM orders WHERE status = 'paid'")->fetchColumn() ?: 0,
];
?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                <i class="fas fa-box fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="mb-2 text-sm font-medium text-gray-600">Total Products</p>
                <p class="text-lg font-semibold text-gray-700"><?= number_format($stats['products']) ?></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-500">
                <i class="fas fa-shopping-cart fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="mb-2 text-sm font-medium text-gray-600">Total Orders</p>
                <p class="text-lg font-semibold text-gray-700"><?= number_format($stats['orders']) ?></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                <i class="fas fa-tags fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="mb-2 text-sm font-medium text-gray-600">Total Categories</p>
                <p class="text-lg font-semibold text-gray-700"><?= number_format($stats['categories']) ?></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                <i class="fas fa-lira-sign fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="mb-2 text-sm font-medium text-gray-600">Revenue</p>
                <p class="text-lg font-semibold text-gray-700">₺<?= number_format($stats['revenue'], 2) ?></p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white p-6 rounded shadow mb-6">
    <h2 class="text-xl font-bold mb-4">Quick Actions</h2>
    <div class="flex gap-4">
        <a href="/admin/products/create.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Product</a>
        <a href="/admin/blogs/create.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Add Blog Post</a>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
