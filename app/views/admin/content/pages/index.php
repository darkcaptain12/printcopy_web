<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold"><?= t('content.pages') ?></h1>
</div>
<div class="bg-white rounded shadow">
    <table class="w-full">
        <thead>
            <tr class="text-left border-b bg-gray-100">
                <th class="p-4">Page Key</th>
                <th class="p-4"><?= t('content.page_title') ?></th>
                <th class="p-4 text-right"><?= t('common.actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($pages as $p): ?>
            <tr class="border-b">
                <td class="p-4 font-mono text-sm"><?= $p['page_key'] ?></td>
                <td class="p-4"><?= htmlspecialchars($p['title']) ?></td>
                <td class="p-4 text-right">
                    <a href="/admin/content/pages/edit?id=<?= $p['id'] ?>" class="text-blue-600"><?= t('common.edit') ?></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
