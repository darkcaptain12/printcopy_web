<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="bg-body py-12">
    <div class="container">
        <h1 class="text-3xl font-bold mb-8">Sepetim</h1>

        <?php if(empty($items)): ?>
            <div class="bg-white p-12 rounded-xl shadow-sm border text-center max-w-2xl mx-auto">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shopping-basket text-gray-400 text-3xl"></i>
                </div>
                <h2 class="text-xl font-bold mb-2">Sepetiniz Boş</h2>
                <p class="text-muted mb-8">Henüz sepetinize ürün eklemediniz. İhtiyacınız olan ürünleri hemen keşfedin.</p>
                <a href="/products" class="btn btn-primary btn-lg px-8 shadow-lg">Alışverişe Devam Et</a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
                
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4" style="grid-column: span 2;">
                    <?php foreach($items as $item): ?>
                        <div class="bg-white p-4 rounded-lg shadow-sm border flex flex-col sm:flex-row items-center gap-6">
                            <!-- Image -->
                            <div class="w-24 h-24 bg-gray-50 rounded flex items-center justify-center flex-shrink-0">
                                <img src="<?= !empty($item['image']) ? htmlspecialchars($item['image']) : 'https://placehold.co/100x100' ?>" 
                                     class="max-w-full max-h-full object-contain"
                                     onerror="this.src='https://placehold.co/100x100?text=Resim'">
                            </div>

                            <!-- Details -->
                            <div class="flex-1 text-center sm:text-left">
                                <h3 class="font-bold text-lg mb-1">
                                    <a href="/product/<?= $item['slug'] ?? '#' ?>" class="hover:text-primary">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </a>
                                </h3>
                                <p class="text-sm text-muted mb-2">Birim Fiyat: <?= number_format($item['price'], 2, ',', '.') ?> ₺</p>
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-col items-center gap-2">
                                <form action="/cart/update" method="POST" class="flex items-center border rounded overflow-hidden">
                                     <?= \App\Core\CSRF::field() ?>
                                    <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" 
                                           class="w-16 p-2 text-center text-sm font-bold border-none focus:ring-0">
                                    <button type="submit" class="bg-gray-100 hover:bg-gray-200 p-2 text-xs border-l transition" title="Güncelle">
                                        <i class="fas fa-sync"></i>
                                    </button>
                                </form>
                                <form action="/cart/remove" method="POST">
                                    <?= \App\Core\CSRF::field() ?>
                                    <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="text-xs text-red-500 hover:underline hover:text-red-700">
                                        Kaldır
                                    </button>
                                </form>
                            </div>

                            <!-- Total -->
                            <div class="text-right min-w-[100px]">
                                <span class="block text-sm text-muted">Toplam</span>
                                <span class="font-bold text-lg text-primary">
                                    <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?> ₺
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Summary Sidebar -->
                <div class="lg:col-span-1" style="grid-column: span 1;">
                    <div class="bg-white p-6 rounded-lg shadow-sm border sticky top-24">
                        <h3 class="font-bold text-lg mb-4 border-b pb-2">Sipariş Özeti</h3>
                        
                        <div class="flex justify-between mb-2 text-sm text-muted">
                            <span>Ara Toplam</span>
                            <span><?= number_format($total, 2, ',', '.') ?> ₺</span>
                        </div>
                        <div class="flex justify-between mb-2 text-sm text-muted">
                            <span>Kargo</span>
                            <span class="text-success font-medium">Ücretsiz</span>
                        </div>
                        <div class="flex justify-between mb-4 text-sm text-muted">
                            <span>KDV (%20)</span>
                            <span>Dahil</span>
                        </div>
                        
                        <div class="border-t pt-4 mt-4 flex justify-between items-center mb-6">
                            <span class="font-bold text-lg text-gray-800">Genel Toplam</span>
                            <span class="font-bold text-2xl text-primary"><?= number_format($total, 2, ',', '.') ?> ₺</span>
                        </div>
                        
                        <a href="/checkout" class="btn btn-primary btn-block btn-lg justify-center shadow-lg shadow-blue-900/10">
                            Ödemeye Geç <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                        
                        <div class="mt-4 text-center">
                            <a href="/products" class="text-xs text-gray-500 hover:underline">Alışverişe Devam Et</a>
                        </div>
                    </div>
                </div>
                
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
