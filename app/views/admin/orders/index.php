<?php use App\Core\CSRF; ?>
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <h1 class="text-2xl font-bold"><?= t('orders.title') ?></h1>
    
    <form class="flex flex-wrap gap-2" method="GET" action="/admin/orders">
        <!-- Status Filter -->
        <select name="status" class="border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value=""><?= t('status.all') ?></option>
            <option value="pending" <?= isset($_GET['status']) && $_GET['status'] == 'pending' ? 'selected' : '' ?>><?= t('status.pending') ?></option>
            <option value="paid" <?= isset($_GET['status']) && $_GET['status'] == 'paid' ? 'selected' : '' ?>><?= t('status.paid') ?></option>
            <option value="shipped" <?= isset($_GET['status']) && $_GET['status'] == 'shipped' ? 'selected' : '' ?>><?= t('status.shipped') ?></option>
            <option value="cancelled" <?= isset($_GET['status']) && $_GET['status'] == 'cancelled' ? 'selected' : '' ?>><?= t('status.cancelled') ?></option>
        </select>
        
        <!-- Search Input -->
        <div class="relative">
            <input type="text" name="search" placeholder="<?= t('common.search') ?>..." 
                   value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                   class="border rounded px-3 py-2 pl-8 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <svg class="w-4 h-4 absolute left-2.5 top-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700"><?= t('common.filter') ?></button>
        <?php if(!empty($_GET['search']) || !empty($_GET['status'])): ?>
            <a href="/admin/orders" class="bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-300"><?= t('common.clear') ?></a>
        <?php endif; ?>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= t('orders.order_no') ?></th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= t('orders.customer') ?></th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= t('orders.email') ?></th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= t('orders.phone') ?></th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= t('orders.amount') ?></th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"><?= t('orders.status') ?></th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"><?= t('orders.date') ?></th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"><?= t('common.actions') ?></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            <?= t('msg.no_records') ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($orders as $order): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            <?= htmlspecialchars($order['order_number']) ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($order['customer_name']) ?></div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <?= htmlspecialchars($order['customer_email']) ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <?= htmlspecialchars($order['customer_phone']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ₺<?= number_format($order['total_amount'], 2) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?= $order['status'] == 'paid' ? 'bg-green-100 text-green-800' : 
                                  ($order['status'] == 'shipped' ? 'bg-blue-100 text-blue-800' : 
                                  ($order['status'] == 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) ?>">
                                <?= t('status.' . $order['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <?= date('d M Y H:i', strtotime($order['created_at'])) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="/admin/orders/show?id=<?= $order['id'] ?>" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded"><?= t('common.view') ?></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
