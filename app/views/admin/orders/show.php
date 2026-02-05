<?php use App\Core\CSRF; ?>
<div class="mb-6 flex justify-between items-center">
    <div class="flex items-center gap-4">
        <a href="/admin/orders" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold"><?= t('orders.order_no') ?> #<?= htmlspecialchars($order['order_number']) ?></h1>
        <span class="px-3 py-1 rounded-full text-sm font-bold 
            <?= $order['status'] == 'paid' ? 'bg-green-100 text-green-800' : 
              ($order['status'] == 'shipped' ? 'bg-blue-100 text-blue-800' : 
              ($order['status'] == 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) ?>">
            <?= mb_strtoupper(t('status.' . $order['status'])) ?>
        </span>
    </div>
    
    <div class="text-sm text-gray-500">
        <?= t('orders.date') ?>: <?= date('d M Y H:i', strtotime($order['created_at'])) ?>
    </div>
</div>

<?php if (isset($_SESSION['flash_success'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline"><?= t('msg.success') . ' ' . $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></span>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['flash_error'])): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline"><?= t('msg.error') . ' ' . $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></span>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="md:col-span-2 space-y-6">
        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800"><?= t('orders.items') ?></h3>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"><?= t('common.products') ?></th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase"><?= t('orders.qty') ?></th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase"><?= t('orders.price') ?></th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase"><?= t('orders.total') ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($items)): ?>
                        <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500"><?= t('msg.no_records') ?></td></tr>
                    <?php else: ?>
                        <?php foreach($items as $item): ?>
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($item['product_name'] ?? 'Unknown Product') ?></div>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-900">
                                <?= $item['quantity'] ?? 1 ?>
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-gray-900">
                                ₺<?= number_format($item['price'] ?? 0, 2) ?>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                ₺<?= number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-700"><?= t('orders.total') ?>:</td>
                        <td class="px-6 py-4 text-right font-bold text-blue-600 text-lg">₺<?= number_format($order['total_amount'], 2) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="md:col-span-1 space-y-6">
        <!-- Customer Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2"><?= t('orders.customer_details') ?></h3>
            <div class="space-y-3 text-sm">
                <div>
                    <label class="text-gray-500 block text-xs uppercase"><?= t('orders.name') ?></label>
                    <div class="font-medium text-gray-900"><?= htmlspecialchars($order['customer_name']) ?></div>
                </div>
                <div>
                    <label class="text-gray-500 block text-xs uppercase"><?= t('orders.email') ?></label>
                    <div class="Break-all text-gray-900"><?= htmlspecialchars($order['customer_email']) ?></div>
                </div>
                <div>
                    <label class="text-gray-500 block text-xs uppercase"><?= t('orders.phone') ?></label>
                    <div class="font-medium text-gray-900"><?= htmlspecialchars($order['customer_phone']) ?></div>
                </div>
                <div>
                    <label class="text-gray-500 block text-xs uppercase"><?= t('orders.address') ?></label>
                    <div class="text-gray-900"><?= nl2br(htmlspecialchars($order['address'])) ?></div>
                </div>
            </div>
        </div>

        <!-- Status Management -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2"><?= t('orders.update_status') ?></h3>
            <form action="/admin/orders/status" method="POST">
                <?= CSRF::field() ?>
                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2"><?= t('orders.status_label') ?></label>
                    <select name="status" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>><?= t('status.pending') ?></option>
                        <option value="paid" <?= $order['status'] == 'paid' ? 'selected' : '' ?>><?= t('status.paid') ?></option>
                        <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : '' ?>><?= t('status.shipped') ?></option>
                        <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>><?= t('status.cancelled') ?></option>
                    </select>
                </div>

                <!-- Admin Note -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2"><?= t('orders.admin_note') ?></label>
                    <textarea name="admin_note" rows="4" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="<?= t('orders.admin_note_placeholder') ?>"><?= htmlspecialchars($order['admin_note'] ?? '') ?></textarea>
                </div>
                
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition duration-150">
                    <?= t('common.save') ?>
                </button>
            </form>
        </div>
    </div>
</div>
