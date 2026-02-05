<?php require_once '../partials/header.php'; ?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Orders</h1>
</div>

<div class="bg-white rounded shadow">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="py-3 px-4 text-left">Order #</th>
                <th class="py-3 px-4 text-left">Customer</th>
                <th class="py-3 px-4 text-left">Total</th>
                <th class="py-3 px-4 text-center">Status</th>
                <th class="py-3 px-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM orders ORDER BY id DESC");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
            ?>
            <tr class="border-b hover:bg-gray-50">
                <td class="py-3 px-4 font-bold"><?= $row['order_number'] ?></td>
                <td class="py-3 px-4">
                    <div class="font-medium"><?= htmlspecialchars($row['customer_name']) ?></div>
                    <div class="text-xs text-gray-500"><?= $row['customer_email'] ?></div>
                </td>
                <td class="py-3 px-4">₺<?= number_format($row['total_amount'], 2) ?></td>
                <td class="py-3 px-4 text-center">
                    <span class="px-2 py-1 rounded text-xs uppercase font-bold
                        <?= $row['status'] == 'paid' ? 'bg-green-100 text-green-800' : 
                          ($row['status'] == 'shipped' ? 'bg-blue-100 text-blue-800' : 
                          ($row['status'] == 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) ?>">
                        <?= $row['status'] ?>
                    </span>
                </td>
                <td class="py-3 px-4 text-right">
                    <a href="detail.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline">View</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once '../partials/footer.php'; ?>
