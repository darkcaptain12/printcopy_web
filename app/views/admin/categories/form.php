<?php use App\Core\CSRF; ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold"><?= $category ? t('categories.edit_title') : t('categories.create_title') ?></h1>
    <a href="/admin/categories" class="text-gray-600 hover:underline">&larr; <?= t('categories.back_list') ?></a>
</div>

<div class="bg-white rounded shadow p-6 max-w-2xl">
    <form action="<?= $category ? '/admin/categories/update' : '/admin/categories/store' ?>" method="POST">
        <?= CSRF::field() ?>
        <?php if($category): ?>
            <input type="hidden" name="id" value="<?= $category['id'] ?>">
        <?php endif; ?>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2"><?= t('categories.name') ?></label>
            <input type="text" name="name" value="<?= $category['name'] ?? '' ?>" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2"><?= t('categories.slug') ?></label>
            <input type="text" name="slug" value="<?= $category['slug'] ?? '' ?>" class="w-full p-2 border rounded" placeholder="Auto-generated if empty">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2"><?= t('categories.parent') ?></label>
            <select name="parent_id" class="w-full p-2 border rounded">
                <option value=""><?= t('categories.none') ?></option>
                <?php foreach($parents as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= ($category && $category['parent_id'] == $p['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" class="mr-2" <?= (!$category || $category['is_active']) ? 'checked' : '' ?>>
                <span class="text-gray-700"><?= t('categories.active') ?></span>
            </label>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700"><?= t('categories.save') ?></button>
    </form>
</div>
