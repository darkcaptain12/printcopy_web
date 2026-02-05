<h1 class="text-2xl font-bold mb-6"><?= t('common.dashboard') ?></h1>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded shadow border-t-4 border-blue-500">
        <div class="text-gray-500 text-sm font-bold uppercase"><?= t('dashboard.total_products') ?></div>
        <div class="text-3xl font-bold mt-2"><?= $products ?></div>
    </div>
    <div class="bg-white p-6 rounded shadow border-t-4 border-green-500">
        <div class="text-gray-500 text-sm font-bold uppercase"><?= t('dashboard.total_orders') ?></div>
        <div class="text-3xl font-bold mt-2"><?= $orders ?></div>
    </div>
    <div class="bg-white p-6 rounded shadow border-t-4 border-purple-500">
        <div class="text-gray-500 text-sm font-bold uppercase"><?= t('dashboard.revenue') ?></div>
        <div class="text-3xl font-bold text-gray-800 mt-2">₺<?= number_format($revenue, 2) ?></div>
    </div>
    <div class="bg-white p-6 rounded shadow border-t-4 border-yellow-500">
        <div class="text-gray-500 text-sm font-bold uppercase"><?= t('dashboard.pending_orders') ?></div>
        <div class="text-3xl font-bold text-gray-800 mt-2"><?= $pending_orders ?></div>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b flex justify-between items-center bg-gray-50">
        <h3 class="font-bold text-gray-800"><?= t('dashboard.latest_orders') ?></h3>
        <a href="/admin/orders" class="text-sm text-blue-600 hover:text-blue-800 font-medium"><?= t('dashboard.view_all') ?> &rarr;</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"><?= t('orders.order_no') ?></th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"><?= t('orders.customer') ?></th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"><?= t('orders.amount') ?></th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase"><?= t('orders.status') ?></th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase"><?= t('orders.date') ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if(empty($latestOrders)): ?>
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500"><?= t('dashboard.no_orders') ?></td></tr>
                <?php else: ?>
                    <?php foreach($latestOrders as $o): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-mono font-medium"><?= htmlspecialchars($o['order_number']) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-700"><?= htmlspecialchars($o['customer_name'] ?? '-') ?></td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-800">₺<?= number_format($o['total_amount'], 2) ?></td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 text-xs font-bold rounded-full 
                                <?= $o['status'] == 'paid' ? 'bg-green-100 text-green-800' : 
                                  ($o['status'] == 'shipped' ? 'bg-blue-100 text-blue-800' : 
                                  ($o['status'] == 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) ?>">
                                <?= t('status.' . $o['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-gray-500"><?= date('d.m.Y', strtotime($o['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
