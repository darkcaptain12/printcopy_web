<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold"><?= t('categories.title') ?></h1>
    <a href="/admin/categories/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        <i class="fas fa-plus mr-2"></i><?= t('categories.add_new') ?>
    </a>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="text-left py-3 px-4 font-semibold text-sm"><?= t('categories.id') ?></th>
                <th class="text-left py-3 px-4 font-semibold text-sm"><?= t('categories.name') ?></th>
                <th class="text-left py-3 px-4 font-semibold text-sm"><?= t('categories.slug') ?></th>
                <th class="text-left py-3 px-4 font-semibold text-sm"><?= t('categories.parent') ?></th>
                <th class="text-center py-3 px-4 font-semibold text-sm"><?= t('categories.status') ?></th>
                <th class="text-right py-3 px-4 font-semibold text-sm"><?= t('categories.actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($categories as $cat): ?>
            <tr class="border-b hover:bg-gray-50">
                <td class="py-3 px-4"><?= $cat['id'] ?></td>
                <td class="py-3 px-4 font-medium"><?= htmlspecialchars($cat['name']) ?></td>
                <td class="py-3 px-4 text-gray-500"><?= $cat['slug'] ?></td>
                <td class="py-3 px-4 text-gray-500"><?= $cat['parent_id'] ?: '-' ?></td>
                <td class="py-3 px-4 text-center">
                    <?php if($cat['is_active']): ?>
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded"><?= t('categories.active') ?></span>
                    <?php else: ?>
                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Inactive</span>
                    <?php endif; ?>
                </td>
                <td class="py-3 px-4 text-right">
                    <a href="/admin/categories/edit?id=<?= $cat['id'] ?>" class="text-blue-500 hover:underline mr-3"><?= t('common.edit') ?></a>
                    <a href="/admin/categories/delete?id=<?= $cat['id'] ?>" class="text-red-500 hover:underline" onclick="return confirm('<?= t('msg.confirm_delete') ?>')"><?= t('common.delete') ?></a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($categories)): ?>
                <tr><td colspan="6" class="p-4 text-center text-gray-500"><?= t('msg.no_records') ?></td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
