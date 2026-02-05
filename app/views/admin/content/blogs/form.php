<?php use App\Core\CSRF; ?>
<div class="mb-6"><a href="/admin/content/blogs" class="text-gray-600">&larr; <?= t('common.back') ?></a></div>
<div class="bg-white p-6 rounded shadow">
    <form action="<?= $blog ? '/admin/content/blogs/update' : '/admin/content/blogs/store' ?>" method="POST" enctype="multipart/form-data">
        <?= CSRF::field() ?>
        <?php if($blog): ?> <input type="hidden" name="id" value="<?= $blog['id'] ?>"> <?php endif; ?>
        
        <div class="mb-4">
            <label class="block font-bold mb-2"><?= t('content.blog_title') ?></label>
            <input type="text" name="title" value="<?= $blog['title'] ?? '' ?>" class="w-full p-2 border rounded" required>
        </div>
        <div class="mb-4">
            <label class="block font-bold mb-2"><?= t('products.slug') ?></label>
            <input type="text" name="slug" value="<?= $blog['slug'] ?? '' ?>" class="w-full p-2 border rounded">
        </div>
        <div class="mb-4">
            <label class="block font-bold mb-2"><?= t('content.content') ?></label>
            <textarea name="content" rows="10" class="w-full p-2 border rounded"><?= $blog['content'] ?? '' ?></textarea>
        </div>
        <div class="mb-4">
            <label class="block font-bold mb-2"><?= t('products.image') ?></label>
            <input type="file" name="image" class="w-full border p-2 rounded">
        </div>
        
        <button class="bg-blue-600 text-white px-6 py-2 rounded"><?= t('common.save') ?></button>
    </form>
</div>
