<?php use App\Core\CSRF; ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold"><?= $product ? t('products.edit_title') : t('products.create_title') ?></h1>
    <a href="/admin/products" class="text-gray-600 hover:underline">&larr; <?= t('products.back_list') ?></a>
</div>

<div class="bg-white rounded shadow p-6">
    <form action="<?= $product ? '/admin/products/update' : '/admin/products/store' ?>" method="POST" enctype="multipart/form-data">
        <?= CSRF::field() ?>
        <?php if($product): ?>
            <input type="hidden" name="id" value="<?= $product['id'] ?>">
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="col-span-1">
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2"><?= t('products.name') ?></label>
                    <input type="text" name="name" value="<?= $product['name'] ?? '' ?>" class="w-full p-2 border rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2"><?= t('products.slug') ?></label>
                    <input type="text" name="slug" value="<?= $product['slug'] ?? '' ?>" class="w-full p-2 border rounded">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2"><?= t('products.category') ?></label>
                    <select name="category_id" class="w-full p-2 border rounded" required>
                        <option value=""><?= t('products.select_category') ?></option>
                        <?php foreach($categories as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= ($product && $product['category_id'] == $c['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2"><?= t('products.price') ?> (₺)</label>
                    <input type="number" step="0.01" name="price" value="<?= $product['price'] ?? '' ?>" class="w-full p-2 border rounded" required>
                </div>
            </div>

            <div class="col-span-1">
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2"><?= t('products.stock_status') ?></label>
                    <select name="stock_status" class="w-full p-2 border rounded">
                        <option value="in_stock" <?= ($product && $product['stock_status'] == 'in_stock') ? 'selected' : '' ?>><?= t('products.in_stock') ?></option>
                        <option value="out_of_stock" <?= ($product && $product['stock_status'] == 'out_of_stock') ? 'selected' : '' ?>><?= t('products.out_of_stock') ?></option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2"><?= t('products.main_image') ?></label>
                    <?php if(isset($product['image']) && $product['image']): ?>
                        <div class="mb-2">
                            <?php $normalized = \App\Core\ImageHelper::url($product['image'], 'https://placehold.co/80'); ?>
                            <img src="<?= htmlspecialchars($normalized) ?>" class="h-20 w-20 object-cover rounded border" onerror="this.src='https://placehold.co/80';">
                            <p class="text-xs text-gray-500 mt-1 truncate"><?= htmlspecialchars($product['image']) ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="flex flex-col gap-2">
                        <input type="text" name="image_url" placeholder="Görsel URL (https://...)" class="w-full p-2 border rounded" value="<?= ($product && filter_var($product['image'], FILTER_VALIDATE_URL)) ? $product['image'] : '' ?>">
                        <span class="text-xs text-center text-gray-500">- VEYA -</span>
                        <input type="file" name="image" class="w-full p-2 border rounded">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2"><?= t('products.active_product') ?></label>
                    <input type="checkbox" name="is_active" class="mr-2" <?= (!$product || $product['is_active']) ? 'checked' : '' ?>>
                </div>
            </div>
            
            <div class="col-span-1 md:col-span-2">
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Kısa Açıklama (Listede görünür)</label>
                    <textarea name="short_description" rows="2" class="w-full p-2 border rounded"><?= $product['short_description'] ?? '' ?></textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2"><?= t('products.description') ?> (Teknik Özellikler)</label>
                    <textarea name="description" rows="5" class="w-full p-2 border rounded"><?= $product['description'] ?? '' ?></textarea>
                </div>
                
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 w-full md:w-auto">
                    <?= t('products.save') ?>
                </button>
            </div>
        </div>
    </form>
</div>
