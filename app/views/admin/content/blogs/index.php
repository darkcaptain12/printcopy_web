<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold"><?= t('content.blogs') ?></h1>
    <a href="/admin/content/blogs/create" class="bg-blue-600 text-white px-4 py-2 rounded"><?= t('content.add_new_blog') ?></a>
</div>
<div class="bg-white rounded shadow">
    <table class="w-full">
        <thead>
            <tr class="text-left border-b bg-gray-100">
                <th class="p-4"><?= t('content.blog_title') ?></th>
                <th class="p-4 text-right"><?= t('common.actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($blogs as $b): ?>
            <tr class="border-b">
                <td class="p-4"><?= htmlspecialchars($b['title']) ?></td>
                <td class="p-4 text-right">
                    <a href="/admin/content/blogs/edit?id=<?= $b['id'] ?>" class="text-blue-600 mr-2"><?= t('common.edit') ?></a>
                    <a href="/admin/content/blogs/delete?id=<?= $b['id'] ?>" class="text-red-600" onclick="return confirm('<?= t('msg.confirm_delete') ?>')"><?= t('common.delete') ?></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
