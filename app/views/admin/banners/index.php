<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Banners</h1>
    <a href="/admin/banners/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Yeni Banner</a>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="py-3 px-4 text-left">Görsel</th>
                <th class="py-3 px-4 text-left">Başlık</th>
                <th class="py-3 px-4 text-left">CTA</th>
                <th class="py-3 px-4 text-center">Aktif</th>
                <th class="py-3 px-4 text-right">İşlem</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($banners as $b): ?>
            <tr class="border-b hover:bg-gray-50">
                <td class="py-3 px-4">
                    <?php if(!empty($b['image'])): ?>
                        <img src="<?= $b['image'] ?>" class="h-12 object-cover rounded">
                    <?php else: ?>
                        <div class="w-12 h-12 bg-gray-200 rounded"></div>
                    <?php endif; ?>
                </td>
                <td class="py-3 px-4">
                    <div class="font-semibold"><?= htmlspecialchars($b['title']) ?></div>
                    <div class="text-xs text-gray-500"><?= htmlspecialchars($b['subtitle']) ?></div>
                </td>
                <td class="py-3 px-4 text-sm text-blue-600"><?= htmlspecialchars($b['cta_text']) ?></td>
                <td class="py-3 px-4 text-center"><?= $b['is_active'] ? '✔' : '—' ?></td>
                <td class="py-3 px-4 text-right">
                    <a href="/admin/banners/edit?id=<?= $b['id'] ?>" class="text-blue-600 hover:underline mr-3">Düzenle</a>
                    <a href="/admin/banners/delete?id=<?= $b['id'] ?>" class="text-red-500 hover:underline" onclick="return confirm('Silinsin mi?')">Sil</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
