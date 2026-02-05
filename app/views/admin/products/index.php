<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold"><?= t('products.title') ?></h1>
    <a href="/admin/products/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        <i class="fas fa-plus mr-2"></i><?= t('products.add_new') ?>
    </a>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="py-3 px-4 text-left"><?= t('products.image') ?></th>
                <th class="py-3 px-4 text-left"><?= t('products.name') ?></th>
                <th class="py-3 px-4 text-left"><?= t('products.category') ?></th>
                <th class="py-3 px-4 text-left"><?= t('products.price') ?></th>
                <th class="py-3 px-4 text-center"><?= t('products.stock') ?></th>
                <th class="py-3 px-4 text-center"><?= t('products.status') ?></th>
                <th class="py-3 px-4 text-right"><?= t('products.actions') ?></th>
            </tr>
        </thead>
        <tbody>
<?php foreach($products as $p): ?>
            <tr class="border-b hover:bg-gray-50">
                <td class="py-3 px-4">
                    <?php 
                        $img = \App\Core\ImageHelper::url($p['image'] ?? '', 'https://placehold.co/80');
                    ?>
                    <img src="<?= htmlspecialchars($img) ?>" class="w-10 h-10 object-cover rounded" onerror="this.src='https://placehold.co/80';">
                </td>
                <td class="py-3 px-4 font-medium"><?= htmlspecialchars($p['name']) ?></td>
                <td class="py-3 px-4 text-gray-500"><?= htmlspecialchars($p['category_name']) ?></td>
                <td class="py-3 px-4">₺<?= number_format($p['price'], 2) ?></td>
                <td class="py-3 px-4 text-center text-sm">
                    <?= $p['stock_status'] == 'out_of_stock' ? t('products.out_of_stock') : t('products.in_stock') ?>
                </td>
                <td class="py-3 px-4 text-center">
                    <span class="<?= $p['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?> text-xs px-2 py-1 rounded">
                        <?= $p['is_active'] ? t('products.active') : t('products.inactive') ?>
                    </span>
                </td>
                <td class="py-3 px-4 text-right">
                    <a href="/admin/products/edit?id=<?= $p['id'] ?>" class="text-blue-500 hover:underline mr-3"><?= t('common.edit') ?></a>
                    <a href="/admin/products/delete?id=<?= $p['id'] ?>" class="text-red-500 hover:underline" onclick="return confirm('<?= t('msg.confirm_delete') ?>')"><?= t('common.delete') ?></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
