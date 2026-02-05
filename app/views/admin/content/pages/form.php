<?php use App\Core\CSRF; ?>
<div class="mb-6"><a href="/admin/content/pages" class="text-gray-600">&larr; <?= t('common.back') ?></a></div>
<div class="bg-white p-6 rounded shadow">
    <form action="/admin/content/pages/update" method="POST">
        <?= CSRF::field() ?>
        <input type="hidden" name="id" value="<?= $page['id'] ?>">
        
        <div class="mb-4">
            <label class="block font-bold mb-2"><?= t('content.page_title') ?></label>
            <input type="text" name="title" value="<?= $page['title'] ?? '' ?>" class="w-full p-2 border rounded">
        </div>
        
        <div class="mb-4">
            <label class="block font-bold mb-2"><?= t('content.content') ?> (JSON/HTML)</label>
            <div class="text-sm text-gray-500 mb-2">For 'hero' and 'footer', this must be valid JSON.</div>
            <textarea name="content" rows="15" class="w-full p-2 border rounded font-mono text-sm"><?= $page['content'] ?? '' ?></textarea>
        </div>
        
        <button class="bg-blue-600 text-white px-6 py-2 rounded"><?= t('common.save') ?></button>
    </form>
</div>
