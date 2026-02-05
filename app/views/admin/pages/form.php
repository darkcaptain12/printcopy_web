<?php $editing = !empty($page); ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold"><?= $editing ? 'Sayfa Düzenle' : 'Yeni Sayfa' ?></h1>
    <a href="/admin/pages" class="text-blue-600 hover:underline">Listeye Dön</a>
</div>

<div class="bg-white p-6 rounded shadow">
    <form action="" method="POST" class="space-y-4">
        <div>
            <label class="block font-semibold mb-1">Başlık</label>
            <input type="text" name="title" value="<?= $page['title'] ?? '' ?>" class="w-full border rounded p-2" required>
        </div>
        <div>
            <label class="block font-semibold mb-1">Slug</label>
            <input type="text" name="slug" value="<?= $page['slug'] ?? '' ?>" class="w-full border rounded p-2" placeholder="kvkk, gizlilik-politikasi">
        </div>
        <div>
            <label class="block font-semibold mb-1">İçerik</label>
            <textarea name="content" rows="10" class="w-full border rounded p-2"><?= $page['content'] ?? '' ?></textarea>
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" id="is_active" <?= !isset($page) || ($page['is_active'] ?? 1) ? 'checked' : '' ?>>
            <label for="is_active">Aktif</label>
        </div>

        <div class="flex justify-end gap-3">
            <a href="/admin/pages" class="px-4 py-2 rounded border">İptal</a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700"><?= $editing ? 'Güncelle' : 'Kaydet' ?></button>
        </div>
    </form>
</div>
