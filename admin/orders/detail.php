<?php 
require_once '../partials/header.php'; 

$id = $_GET['id'] ?? null;
if (!$id) redirect('index.php');

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

$items = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE order_id = ?");
$items->execute([$id]);
$orderItems = $items->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $status = $_POST['status'];
    $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?")->execute([$status, $id]);
    set_flash('success', 'Order status updated');
    redirect("detail.php?id=$id");
}
?>

<div class="mb-6"><a href="index.php" class="text-gray-600 hover:underline">&larr; Back</a></div>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Order #<?= $order['order_number'] ?></h1>
    <span class="text-sm text-gray-500"><?= $order['created_at'] ?></span>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="col-span-2 space-y-6">
        <div class="bg-white rounded shadow p-6">
            <h3 class="font-bold border-b pb-2 mb-4">Items</h3>
            <table class="w-full">
                <thead>
                    <tr class="text-left bg-gray-50 border-b">
                        <th class="p-2">Product</th>
                        <th class="p-2 text-center">Qty</th>
                        <th class="p-2 text-right">Price</th>
                        <th class="p-2 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orderItems as $item): 
                        $total = $item['quantity'] * $item['price'];
                    ?>
                    <tr class="border-b">
                        <td class="p-2"><?= htmlspecialchars($item['name']) ?></td>
                        <td class="p-2 text-center"><?= $item['quantity'] ?></td>
                        <td class="p-2 text-right">₺<?= number_format($item['price'], 2) ?></td>
                        <td class="p-2 text-right">₺<?= number_format($total, 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="text-right mt-4 text-xl font-bold">
                Total: ₺<?= number_format($order['total_amount'], 2) ?>
            </div>
        </div>
    </div>
    
    <div class="col-span-1 space-y-6">
        <div class="bg-white rounded shadow p-6">
            <h3 class="font-bold border-b pb-2 mb-4">Customer Info</h3>
            <p><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($order['customer_phone']) ?></p>
            <div class="mt-4">
                <strong>Address:</strong><br>
                <?= nl2br(htmlspecialchars($order['address'])) ?>
            </div>
        </div>
        
        <div class="bg-white rounded shadow p-6">
            <h3 class="font-bold border-b pb-2 mb-4">Status</h3>
            <form action="" method="POST">
                <?= csrf_field() ?>
                <select name="status" class="w-full border rounded p-2 mb-4">
                    <?php foreach(['pending','paid','shipped','cancelled'] as $s): ?>
                        <option value="<?= $s ?>" <?= $order['status'] == $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="w-full bg-blue-600 text-white py-2 rounded">Update Status</button>
            </form>
        </div>
    </div>
</div>

<?php require_once '../partials/footer.php'; ?>
