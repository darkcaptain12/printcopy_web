<?php require_once '../partials/header.php'; ?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Products</h1>
    <a href="create.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        <i class="fas fa-plus mr-2"></i>Add Product
    </a>
</div>

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="py-3 px-4 text-left">Image</th>
                <th class="py-3 px-4 text-left">Name</th>
                <th class="py-3 px-4 text-left">Category</th>
                <th class="py-3 px-4 text-left">Price</th>
                <th class="py-3 px-4 text-center">Status</th>
                <th class="py-3 px-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
            ?>
            <tr class="border-b hover:bg-gray-50">
                <td class="py-3 px-4">
                    <?php if($row['image']): ?>
                        <img src="/storage/<?= $row['image'] ?>" class="w-12 h-12 object-cover rounded">
                    <?php else: ?>
                        <div class="w-12 h-12 bg-gray-200 flex items-center justify-center rounded text-gray-400"><i class="fas fa-image"></i></div>
                    <?php endif; ?>
                </td>
                <td class="py-3 px-4 font-medium"><?= htmlspecialchars($row['name']) ?></td>
                <td class="py-3 px-4 text-gray-500"><?= htmlspecialchars($row['category_name']) ?></td>
                <td class="py-3 px-4">₺<?= number_format($row['price'], 2) ?></td>
                <td class="py-3 px-4 text-center">
                    <span class="<?= $row['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?> px-2 py-1 rounded text-xs">
                        <?= $row['is_active'] ? 'Active' : 'Inactive' ?>
                    </span>
                </td>
                <td class="py-3 px-4 text-right">
                    <a href="edit.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline mr-3">Edit</a>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once '../partials/footer.php'; ?>
