<?php 
use App\Core\ImageHelper;
require __DIR__ . '/../partials/header.php'; 
?>

<!-- Hero Section -->
<?php 
// Load banners if any
try {
    $db = (new Database())->getConnection();
    $stmt = $db->query("SELECT * FROM banners WHERE is_active = 1 ORDER BY sort_order ASC, id DESC");
    $banners = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(Exception $e) {
    $banners = [];
}
$hasBanner = !empty($banners);
?>
<section class="relative bg-primary overflow-hidden">
    <div class="absolute inset-0 hero-overlay"></div>
    <div class="container relative z-10 py-16 md:py-24">
        <?php if($hasBanner): ?>
            <div class="hero-grid">
                <?php foreach($banners as $banner): ?>
                    <div>
                        <div class="hero-badge mb-4">
                            <span class="w-2 h-2 rounded-full bg-accent"></span>
                            <?= htmlspecialchars($banner['subtitle'] ?? 'Print & Copy Çözümleri') ?>
                        </div>
                        <div class="hero-eyebrow">Kurumsal B2B Çözümler</div>
                        <h1 class="hero-title"><?= htmlspecialchars($banner['title']) ?></h1>
                        <p class="hero-subtitle">
                            <?= htmlspecialchars($banner['subtitle'] ?? '') ?>
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <?php if(!empty($banner['cta_text'])): ?>
                                <a href="<?= $banner['cta_link'] ?: '/products' ?>" class="btn btn-accent btn-lg">
                                    <?= htmlspecialchars($banner['cta_text']) ?>
                                </a>
                            <?php endif; ?>
                            <a href="/contact" class="btn btn-outline btn-lg" style="color:#fff;border-color:rgba(255,255,255,0.35);">Teklif Al</a>
                        </div>
                        <div class="mt-4 text-slate-200 text-sm font-medium">
                            Kurulum + Eğitim Dahil • 7/24 Teknik Destek
                        </div>
                    </div>
                    <div>
                        <div class="hero-img p-1">
                            <?php if(!empty($banner['image'])): ?>
                                <img src="<?= htmlspecialchars($banner['image']) ?>" alt="<?= htmlspecialchars($banner['title']) ?>" class="hero-image">
                            <?php else: ?>
                                <div class="hero-placeholder"></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php break; endforeach; // show first active banner ?>
            </div>
        <?php else: ?>
            <div class="hero-grid">
                <div>
                    <div class="hero-badge mb-4">
                        <span class="w-2 h-2 rounded-full bg-accent"></span>
                        Endüstriyel Baskı Teknolojileri 2026
                    </div>
                    <div class="hero-eyebrow">Kurumsal B2B Çözümler</div>
                    <h1 class="hero-title">
                        İşletmeniz için <span style="color:#93c5fd;">profesyonel</span> baskı ve üretim sistemleri
                    </h1>
                    <p class="hero-subtitle">
                        UV DTF, Eco Solvent ve DTF makinelerinde güvenilir tedarik, kurulum, eğitim ve 7/24 teknik destek ile üretim gücünüzü artırın.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="/products" class="btn btn-accent btn-lg">Modelleri İncele</a>
                        <a href="/contact" class="btn btn-outline btn-lg" style="color:#fff;border-color:rgba(255,255,255,0.35);">Teklif Al</a>
                    </div>
                    <div class="mt-4 text-slate-200 text-sm font-medium">
                        Kurulum + Eğitim Dahil • 7/24 Teknik Destek
                    </div>
                </div>
                <div>
                    <div class="hero-img p-1">
                        <div class="hero-placeholder"></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Trust Metrics Strip -->
<div class="bg-white border-b border-gray-200 section" style="padding:40px 0;">
    <div class="container">
        <div class="trust-grid">
            <?php 
            $trust = [
                ['icon'=>'fa-shield-alt','title'=>'2 Yıl Garanti','text'=>'Resmi distribütör güvencesi'],
                ['icon'=>'fa-truck-loading','title'=>'Ücretsiz Kurulum','text'=>'Yerinde kurulum ve eğitim'],
                ['icon'=>'fa-headset','title'=>'7/24 Destek','text'=>'Uzman teknik ekip'],
                ['icon'=>'fa-credit-card','title'=>'PayTR Güvenli','text'=>'12 taksit ödeme'],
            ];
            foreach($trust as $item): ?>
            <div class="trust-item">
                <div class="trust-icon"><i class="fas <?= $item['icon'] ?>"></i></div>
                <div class="trust-text">
                    <h4><?= $item['title'] ?></h4>
                    <p><?= $item['text'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Featured Products -->
<section class="section bg-body">
    <div class="container">
        <div class="flex justify-between items-end mb-10">
            <div>
                <span class="text-accent font-bold uppercase tracking-wider text-sm">Öne Çıkanlar</span>
                <h2 class="text-3xl font-bold mt-2">En Çok Tercih Edilen Sistemler</h2>
            </div>
            <a href="/products" class="hidden md:inline-flex items-center gap-2 text-primary font-bold hover:text-accent transition">
                Tümünü Gör <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <?php if (!empty($products)): ?>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
            <div class="product-card group">
                <img src="<?= htmlspecialchars(ImageHelper::url($product['image'] ?? '', 'https://placehold.co/600x338')) ?>" 
                     alt="<?= htmlspecialchars($product['name']) ?>"
                     class="product-img">
                <div class="product-body">
                    <span class="product-meta"><?= htmlspecialchars($product['category_name'] ?? 'Endüstriyel') ?></span>
                    <h3 class="product-title"><?= htmlspecialchars($product['name']) ?></h3>
                    <?php if(!empty($product['short_description'])): ?>
                        <p class="product-desc"><?= htmlspecialchars(mb_strimwidth($product['short_description'],0,120,'…')) ?></p>
                    <?php endif; ?>
                    <div class="product-chips">
                        <span class="product-chip">Kurulum Dahil</span>
                        <span class="product-chip">2 Yıl Garanti</span>
                        <span class="product-chip">Teknik Destek</span>
                    </div>
                    <div class="flex items-center justify-between mt-2">
                        <div class="product-price"><?= number_format($product['price'], 2, ',', '.') ?> ₺ <span class="text-xs text-muted font-medium">+KDV</span></div>
                        <div class="product-actions">
                            <a href="/product/<?= $product['slug'] ?>" class="btn btn-outline btn-sm">Detay & Teklif</a>
                        </div>
                    </div>
                </div>
            </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-12 text-muted bg-white rounded border">
                Henüz öne çıkan ürün eklenmedi.
            </div>
        <?php endif; ?>
        
        <div class="mt-8 text-center md:hidden">
            <a href="/products" class="btn btn-outline w-full justify-center">Tüm Modelleri Gör</a>
        </div>
    </div>
</section>

<!-- Kategori Blokları -->
<section class="section bg-white">
    <div class="container">
        <div class="flex justify-between items-center mb-8">
            <div>
                <span class="text-accent font-bold uppercase tracking-wider text-sm">Kategoriler</span>
                <h2 class="text-3xl font-bold mt-2">Ürün Grupları</h2>
            </div>
        </div>
        <div class="grid grid-1 md:grid-3 gap-6">
            <?php 
            $cats = [
                ['title'=>'DTF Baskı Sistemleri','desc'=>'Yüksek kaplama, parlak sonuçlar ve hızlı üretim.','icon'=>'fa-spray-can'],
                ['title'=>'UV DTF & Flatbed','desc'=>'Endüstriyel malzemelere premium baskı çözümleri.','icon'=>'fa-layer-group'],
                ['title'=>'Eco Solvent','desc'=>'Dayanıklı, canlı renkler; iç-dış mekan baskılar.','icon'=>'fa-leaf'],
            ];
            foreach($cats as $cat): ?>
            <div class="card p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-11 h-11 rounded-lg bg-blue-50 text-accent grid place-items-center text-lg"><i class="fas <?= $cat['icon']?>"></i></div>
                    <h3 class="m-0 text-lg font-bold"><?= $cat['title'] ?></h3>
                </div>
                <p class="text-sm text-muted mb-4"><?= $cat['desc'] ?></p>
                <a href="/products" class="text-accent font-semibold text-sm flex items-center gap-2">Ürünleri Gör <i class="fas fa-arrow-right text-xs"></i></a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Referans / İstatistik -->
<section class="section bg-white border-y border-gray-200">
    <div class="container">
        <div class="flex justify-between items-center mb-6">
            <div>
                <span class="text-accent font-bold uppercase tracking-wider text-sm">Referanslar</span>
                <h2 class="text-3xl font-bold mt-2">Güvenilir İş Ortaklığı</h2>
            </div>
        </div>
        <div class="ref-grid">
            <div class="ref-card">
                <div class="text-accent text-3xl font-extrabold">+120</div>
                <h3>Kurulum</h3>
                <p>Türkiye genelinde aktif çalışan sistem</p>
            </div>
            <div class="ref-card">
                <div class="text-accent text-3xl font-extrabold">%98</div>
                <h3>Memnuniyet</h3>
                <p>Satış sonrası destek ve eğitim</p>
            </div>
            <div class="ref-card">
                <div class="text-accent text-3xl font-extrabold">7/24</div>
                <h3>Servis</h3>
                <p>Yerinde ve uzaktan teknik ekip</p>
            </div>
        </div>
    </div>
</section>

<!-- Footer CTA -->
<section class="section bg-slate-900 border-t border-slate-800 relative overflow-hidden">
    <div class="absolute inset-0 bg-accent opacity-10" style="background-image: radial-gradient(circle at center, #0284c7 1px, transparent 1px); background-size: 24px 24px;"></div>
    <div class="container relative z-10 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Projeniz için Hazır mısınız?</h2>
        <p class="text-slate-400 text-lg mb-8 max-w-2xl mx-auto">
            Hemen iletişime geçin, uzman ekibimiz ihtiyaçlarınıza en uygun makine ve kurulum planını hazırlasın.
        </p>
        <a href="/contact" class="btn btn-accent btn-lg text-lg px-8 shadow-xl shadow-blue-500/20">
            Hemen Teklif Al
        </a>
    </div>
</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>
