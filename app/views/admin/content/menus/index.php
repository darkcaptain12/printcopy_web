<?php use App\Core\CSRF; ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold"><?= t('content.menus') ?></h1>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded shadow">
        <h3 class="font-bold mb-4"><?= t('content.menu_item_add') ?></h3>
        <form action="/admin/content/menus/store" method="POST">
            <?= CSRF::field() ?>
            <div class="mb-4">
                <label class="block text-sm font-bold mb-1"><?= t('content.menu_title') ?></label>
                <input type="text" name="title" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-bold mb-1"><?= t('content.menu_link') ?></label>
                <input type="text" name="link" class="w-full p-2 border rounded" required>
            </div>
            <div class="flex gap-4 mb-4">
                <div class="w-1/2">
                    <label class="block text-sm font-bold mb-1"><?= t('content.menu_position') ?></label>
                    <select name="position" class="w-full p-2 border rounded">
                        <option value="header">Header</option>
                        <option value="footer">Footer</option>
                    </select>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-bold mb-1"><?= t('content.menu_sort_order') ?></label>
                    <input type="number" name="sort_order" value="0" class="w-full p-2 border rounded">
                </div>
            </div>
            <button class="bg-blue-600 text-white w-full py-2 rounded"><?= t('content.add_btn') ?></button>
        </form>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <h3 class="font-bold mb-4"><?= t('content.menu_order_title') ?></h3>
        <ul>
            <?php foreach($menus as $m): ?>
            <li class="flex justify-between items-center border-b py-2">
                <div>
                    <span class="font-bold"><?= htmlspecialchars($m['title']) ?></span>
                    <span class="text-xs text-gray-500 ml-2">(<?= $m['position'] ?>)</span>
                </div>
                <a href="/admin/content/menus/delete?id=<?= $m['id'] ?>" class="text-red-500 text-sm" onclick="return confirm('<?= t('msg.confirm_delete') ?>')"><?= t('common.delete') ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
