<?php 
use App\Core\ImageHelper;
require __DIR__ . '/../partials/header.php'; 
?>

<div class="bg-body py-12">
    <div class="container">
        
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 border-b pb-6">
            <div>
                <h1 class="text-3xl font-bold text-primary">Ödeme ve Teslimat</h1>
                <p class="text-slate-500 text-sm mt-1">Siparişinizi güvenle tamamlayın.</p>
            </div>
            
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span class="flex items-center gap-1 text-primary font-bold"><i class="fas fa-shopping-cart"></i> Sepet</span>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="flex items-center gap-1 text-primary font-bold"><i class="fas fa-user"></i> Bilgiler</span>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="flex items-center gap-1"><i class="fas fa-credit-card"></i> Ödeme</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
            
            <!-- Information Form -->
            <div class="lg:col-span-2" style="grid-column: span 2;">
                <form action="/checkout/process" method="POST" id="checkout-form" class="bg-white p-8 rounded-xl shadow-sm border">
                     <?= \App\Core\CSRF::field() ?>
                    
                    <h2 class="text-xl font-bold mb-6 flex items-center gap-2 text-primary border-b pb-4">
                        <i class="fas fa-map-marker-alt text-accent"></i> Teslimat Bilgileri
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="form-group">
                            <label class="form-label">Ad Soyad</label>
                            <input type="text" name="full_name" class="form-input" required placeholder="Adınız Soyadınız">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Telefon</label>
                            <input type="tel" name="phone" class="form-input" required placeholder="0555 555 55 55">
                        </div>
                    </div>

                    <div class="form-group mb-6">
                        <label class="form-label">E-posta Adresi</label>
                        <input type="email" name="email" class="form-input" required placeholder="ornek@sirket.com">
                        <p class="text-xs text-muted mt-1">Sipariş durumu hakkında bildirim alacaksınız.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                         <div class="form-group">
                            <label class="form-label">İl</label>
                            <input type="text" name="city" class="form-input" required placeholder="İl">
                        </div>
                        <div class="form-group">
                            <label class="form-label">İlçe</label>
                            <input type="text" name="district" class="form-input" required placeholder="İlçe">
                        </div>
                    </div>

                    <div class="form-group mb-6">
                        <label class="form-label">Adres Detayı</label>
                        <textarea name="address" rows="3" class="form-input" required placeholder="Mahalle, Cadde, Sokak, Kapı No..."></textarea>
                    </div>

                    <div class="form-group mb-8">
                        <label class="form-label">Sipariş Notu (Opsiyonel)</label>
                        <textarea name="notes" rows="2" class="form-input" placeholder="Varsa teslimat ile ilgili ek notlarınız..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg justify-center shadow-lg shadow-blue-900/20 py-4 text-lg">
                        <i class="fas fa-lock"></i> Güvenli Ödemeye Geç
                    </button>
                    <p class="text-center text-xs text-muted mt-4">
                        Devam ederek <a href="#" class="underline">Mesafeli Satış Sözleşmesi</a>'ni onaylamış olursunuz.
                    </p>
                </form>
            </div>

            <!-- Summary Sidebar -->
            <div class="lg:col-span-1" style="grid-column: span 1;">
                <div class="bg-white p-6 rounded-xl shadow-sm border sticky top-24">
                    <h2 class="text-lg font-bold mb-4 border-b pb-2 flex items-center gap-2">
                        <i class="fas fa-receipt text-gray-400"></i> Sipariş Özeti
                    </h2>
                    
                    <ul class="space-y-4 mb-6 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
                        <?php foreach($items as $item): ?>
                        <li class="flex items-start gap-3 text-sm">
                            <div class="w-12 h-12 bg-gray-50 rounded border flex items-center justify-center flex-shrink-0">
                                <img src="<?= htmlspecialchars(ImageHelper::url($item['image'] ?? '', 'https://placehold.co/50')) ?>" 
                                     class="max-w-full max-h-full object-contain">
                            </div>
                            <div class="flex-1">
                                <span class="block font-medium text-gray-800 line-clamp-2"><?= htmlspecialchars($item['name']) ?></span>
                                <span class="text-xs text-muted"><?= $item['quantity'] ?> Adet x <?= number_format($item['price'], 2) ?> ₺</span>
                            </div>
                            <span class="font-bold text-gray-700">
                                <?= number_format($item['price'] * $item['quantity'], 2) ?> ₺
                            </span>
                        </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="border-t pt-4 space-y-2 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Ara Toplam</span>
                            <span><?= number_format($total, 2) ?> ₺</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Kargo</span>
                            <span class="text-success font-medium">Ücretsiz</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg text-primary pt-2 border-t mt-2">
                            <span>Toplam</span>
                            <span><?= number_format($total, 2) ?> ₺</span>
                        </div>
                    </div>

                    <!-- Trust Badges -->
                    <div class="mt-6 bg-blue-50 p-4 rounded-lg border border-blue-100">
                        <div class="flex items-center gap-3 mb-3">
                            <i class="fas fa-shield-alt text-2xl text-accent"></i>
                            <div>
                                <h4 class="font-bold text-sm text-primary">Güvenli Ödeme</h4>
                                <p class="text-xs text-muted">256-bit SSL ile korunmaktadır.</p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center opacity-70 grayscale">
                             <!-- Example Icons -->
                             <i class="fab fa-cc-visa text-2xl"></i>
                             <i class="fab fa-cc-mastercard text-2xl"></i>
                             <i class="fas fa-credit-card text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
