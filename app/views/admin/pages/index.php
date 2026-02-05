<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Sayfalar</h1>
    <a href="/admin/pages/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Yeni Sayfa</a>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="py-3 px-4 text-left">Başlık</th>
                <th class="py-3 px-4 text-left">Slug</th>
                <th class="py-3 px-4 text-center">Aktif</th>
                <th class="py-3 px-4 text-right">İşlem</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($pages as $p): ?>
            <tr class="border-b hover:bg-gray-50">
                <td class="py-3 px-4 font-semibold"><?= htmlspecialchars($p['title']) ?></td>
                <td class="py-3 px-4 text-sm text-gray-500"><?= htmlspecialchars($p['slug']) ?></td>
                <td class="py-3 px-4 text-center"><?= $p['is_active'] ? '✔' : '—' ?></td>
                <td class="py-3 px-4 text-right">
                    <a href="/admin/pages/edit?id=<?= $p['id'] ?>" class="text-blue-600 hover:underline mr-3">Düzenle</a>
                    <a href="/admin/pages/delete?id=<?= $p['id'] ?>" class="text-red-500 hover:underline" onclick="return confirm('Silinsin mi?')">Sil</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
