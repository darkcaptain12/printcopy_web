<?php $editing = !empty($banner); ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold"><?= $editing ? 'Banner Düzenle' : 'Yeni Banner' ?></h1>
    <a href="/admin/banners" class="text-blue-600 hover:underline">Listeye Dön</a>
</div>

<div class="bg-white p-6 rounded shadow">
    <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label class="block font-semibold mb-1">Başlık</label>
            <input type="text" name="title" value="<?= $banner['title'] ?? '' ?>" class="w-full border rounded p-2" required>
        </div>
        <div>
            <label class="block font-semibold mb-1">Alt Metin</label>
            <input type="text" name="subtitle" value="<?= $banner['subtitle'] ?? '' ?>" class="w-full border rounded p-2">
        </div>
        <div>
            <label class="block font-semibold mb-1">Görsel</label>
            <?php if(!empty($banner['image'])): ?>
                <img src="<?= $banner['image'] ?>" class="h-20 mb-2 rounded border">
                <input type="hidden" name="current_image" value="<?= $banner['image'] ?>">
            <?php endif; ?>
            <input type="file" name="image" accept="image/*" class="w-full">
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">CTA Metni</label>
                <input type="text" name="cta_text" value="<?= $banner['cta_text'] ?? '' ?>" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">CTA Linki</label>
                <input type="text" name="cta_link" value="<?= $banner['cta_link'] ?? '' ?>" class="w-full border rounded p-2" placeholder="/products">
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold mb-1">Sıra</label>
                <input type="number" name="sort_order" value="<?= $banner['sort_order'] ?? 0 ?>" class="w-full border rounded p-2">
            </div>
            <div class="flex items-center gap-2 mt-6 md:mt-8">
                <input type="checkbox" name="is_active" id="is_active" <?= !isset($banner) || ($banner['is_active'] ?? 1) ? 'checked' : '' ?>>
                <label for="is_active">Aktif</label>
            </div>
        </div>
        <div class="flex justify-end gap-3">
            <a href="/admin/banners" class="px-4 py-2 rounded border">İptal</a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700"><?= $editing ? 'Güncelle' : 'Kaydet' ?></button>
        </div>
    </form>
</div>
