<?php 
use App\Core\ImageHelper;
require __DIR__ . '/../partials/header.php'; 
?>

<div class="bg-body py-8 md:py-12">
    <div class="container">
        
        <!-- Breadcrumb -->
        <nav class="flex text-sm text-muted mb-8 overflow-x-auto whitespace-nowrap">
            <a href="/" class="hover:text-primary transition">Anasayfa</a>
            <span class="mx-3 text-gray-300">/</span>
            <a href="/products" class="hover:text-primary transition">Ürünler</a>
            <?php if(!empty($product['category_name'])): ?>
                <span class="mx-3 text-gray-300">/</span>
                <span class="text-gray-600"><?= htmlspecialchars($product['category_name']) ?></span>
            <?php endif; ?>
            <span class="mx-3 text-gray-300">/</span>
            <span class="text-primary font-medium"><?= htmlspecialchars($product['name']) ?></span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 3rem;">
            
            <!-- Gallery / Image -->
            <div>
                <div class="bg-white rounded-xl border p-8 flex items-center justify-center mb-6 relative overflow-hidden group">
                    <img src="<?= htmlspecialchars(ImageHelper::url($product['image'] ?? '', 'https://placehold.co/600x600?text=No+Image')) ?>" 
                         alt="<?= htmlspecialchars($product['name']) ?>" 
                         class="max-w-full max-h-[500px] object-contain transition-transform duration-500 group-hover:scale-105">
                    
                    <div class="absolute top-4 left-4">
                        <span class="badge badge-success px-3 py-1 shadow-sm">
                            <i class="fas fa-check-circle mr-1"></i> Orijinal Ürün
                        </span>
                    </div>
                </div>
                
                <!-- Thumbnails (Placeholder for future) -->
                <div class="grid grid-cols-4 gap-4">
                   <!-- Only showing main image repeatedly for demo visual structure if needed, or leave empty -->
                </div>
            </div>

            <!-- Product Info & Actions -->
            <div class="flex flex-col">
                <div class="mb-2">
                    <span class="text-accent font-bold text-sm tracking-wide uppercase">
                        <?= htmlspecialchars($product['category_name'] ?? 'Kategori Yok') ?>
                    </span>
                </div>
                
                <h1 class="text-3xl md:text-4xl font-extrabold text-primary mb-4 leading-tight">
                    <?= htmlspecialchars($product['name']) ?>
                </h1>

                <div class="flex items-center gap-4 mb-6">
                    <div class="flex text-yellow-500 text-sm">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <span class="text-muted text-sm">(Yeni Ürün)</span>
                    <span class="text-gray-300">|</span>
                    <span class="text-sm text-green-600 font-bold flex items-center gap-1">
                        <i class="fas fa-box-open"></i> Stokta Var
                    </span>
                </div>

                <div class="bg-blue-50/50 rounded-lg p-6 border border-blue-100 mb-8">
                    <div class="flex items-end gap-2 mb-2">
                        <span class="text-4xl font-bold text-primary">
                            <?= number_format($product['price'], 2, ',', '.') ?>
                        </span>
                        <span class="text-xl font-medium text-gray-500 mb-1">TL</span>
                    </div>
                    <p class="text-sm text-muted flex items-center gap-2">
                        <i class="fas fa-info-circle text-accent"></i> KDV ve Kargo Dahil Fiyatıdır.
                    </p>
                </div>

                <div class="mb-8 text-secondary leading-relaxed">
                    <?= nl2br(htmlspecialchars($product['short_description'] ?? 'Bu ürün için kısa açıklama bulunmuyor.')) ?>
                </div>

                <!-- Add to Cart Form -->
                <form action="/cart/add" method="POST" class="mt-auto">
                    <?= \App\Core\CSRF::field() ?>
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    
                    <div class="flex flex-col sm:flex-row gap-4 mb-6">
                        <div class="w-full sm:w-32">
                            <label class="block text-xs font-bold text-gray-500 mb-1">Adet</label>
                            <div class="relative">
                                <input type="number" name="quantity" value="1" min="1" max="100" 
                                       class="form-input text-center text-lg font-bold">
                            </div>
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-transparent mb-1">.</label> <!-- Spacer -->
                            <button type="submit" class="btn btn-primary btn-lg w-full text-lg shadow-lg shadow-blue-900/10">
                                <i class="fas fa-shopping-cart"></i> Sepete Ekle
                            </button>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="https://wa.me/902120000000?text=<?= urlencode("Ürün hakkında bilgi almak istiyorum: " . $product['name']) ?>" 
                           target="_blank" 
                           class="btn btn-outline w-full justify-center border-gray-300 text-gray-600 hover:border-green-500 hover:text-green-600 hover:bg-green-50">
                            <i class="fab fa-whatsapp text-lg"></i> Teklif Al (WhatsApp)
                        </a>
                        <button form="direct-buy" type="submit" class="btn btn-accent w-full justify-center">
                            <i class="fas fa-bolt"></i> Direkt Satın Al
                        </button>
                    </div>
                </form>
                <form id="direct-buy" action="/cart/direct" method="POST" class="hidden">
                    <?= \App\Core\CSRF::field() ?>
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                </form>

                <!-- Trust Box -->
                <div class="grid grid-cols-2 gap-4 mt-8 pt-8 border-t border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-primary">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <span class="text-sm font-medium">2 Yıl Garanti</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-primary">
                            <i class="fas fa-truck"></i>
                        </div>
                        <span class="text-sm font-medium">Hızlı Teslimat</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Tabs / Content -->
        <div class="bg-white rounded-xl border shadow-sm mt-12">
            <div class="border-b px-8 py-5 bg-gray-50/50 rounded-t-xl flex items-center gap-4">
                <span class="px-4 py-2 bg-white border rounded-lg shadow-sm font-bold text-primary text-sm">
                    Ürün Açıklaması
                </span>
                <span class="text-slate-400 text-sm">Teknik Özellikler</span>
            </div>
            
            <div class="p-8 md:p-12 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-2 prose max-w-none text-slate-600 leading-relaxed">
                    <?php 
                    $desc = htmlspecialchars($product['description']);
                    $desc = preg_replace('/([A-ZİĞÜŞÖÇ ]{3,}):/', '<h3 class="text-lg font-bold text-primary mt-8 mb-4">$1</h3>', $desc);
                    if (strpos($desc, '- ') !== false) {
                        $desc = preg_replace('/^-\s+(.+)$/m', '<li class="flex items-start gap-2 mb-2"><span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-accent flex-shrink-0"></span><span>$1</span></li>', $desc);
                        $desc = preg_replace('/((<li.*<\/li>\s*)+)/s', '<ul class="bg-gray-50 rounded-xl p-6 border border-gray-100">$1</ul>', $desc);
                    }
                    echo nl2br($desc); 
                    ?>
                </div>
                <div class="bg-gray-50 border border-gray-100 rounded-xl p-6">
                    <h4 class="font-bold text-primary mb-4">Teknik Özellikler</h4>
                    <div class="space-y-3 text-sm text-slate-600">
                        <div class="flex justify-between"><span>Model</span><strong><?= htmlspecialchars($product['name']) ?></strong></div>
                        <div class="flex justify-between"><span>Kategori</span><strong><?= htmlspecialchars($product['category_name'] ?? '—') ?></strong></div>
                        <div class="flex justify-between"><span>Durum</span><strong><?= ($product['stock_status'] ?? '') == 'out_of_stock' ? 'Stokta yok' : 'Stokta' ?></strong></div>
                        <div class="flex justify-between"><span>Fiyat</span><strong><?= number_format($product['price'], 2, ',', '.') ?> ₺</strong></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
