<?php 
use App\Core\ImageHelper;
require __DIR__ . '/../partials/header.php'; 
?>

<div class="bg-body py-12">
    <div class="container">
        
        <!-- Header & Breadcrumb -->
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 border-b pb-6">
            <div>
                <nav class="flex text-sm text-muted mb-2">
                    <a href="/" class="hover:text-primary transition">Anasayfa</a>
                    <span class="mx-2">/</span>
                    <span class="text-primary font-medium">Ürünler</span>
                </nav>
                <h1 class="text-3xl font-bold m-0 p-0 text-primary">Ürün Kataloğu</h1>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="text-muted text-sm">Toplam <strong><?= count($products) ?></strong> ürün listeleniyor</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem;">
            
            <!-- Sidebar (Left) -->
            <aside class="lg:col-span-1" style="grid-column: span 1;">
                <div class="bg-white rounded-lg shadow-sm border p-6 sticky top-24">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-lg m-0">Filtrele</h3>
                        <?php if(!empty($_GET)): ?>
                            <a href="/products" class="text-xs text-accent hover:underline">Temizle</a>
                        <?php endif; ?>
                    </div>

                    <!-- Search -->
                    <div class="mb-6">
                        <form action="/products" method="GET">
                            <?php if($selected_category): ?><input type="hidden" name="category" value="<?= $selected_category ?>"><?php endif; ?>
                            <div class="relative">
                                <input type="text" name="q" value="<?= htmlspecialchars($search_query ?? '') ?>" 
                                       placeholder="Model veya ürün ara..." 
                                       class="form-input text-sm pl-10 w-full rounded-md border-gray-300">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-sm"></i>
                            </div>
                        </form>
                    </div>

                    <!-- Categories -->
                    <div class="mb-6">
                        <h4 class="font-bold text-sm text-primary mb-3 uppercase tracking-wide">Kategoriler</h4>
                        <ul class="space-y-2">
                            <li>
                                <a href="/products" class="flex items-center justify-between group <?= empty($selected_category) ? 'text-accent font-bold' : 'text-gray-600' ?>">
                                    <span class="group-hover:text-accent transition">Tüm Ürünler</span>
                                </a>
                            </li>
                            <?php foreach($categories as $cat): ?>
                                <li>
                                    <a href="/products?category=<?= $cat['slug'] ?>" class="flex items-center justify-between group <?= ($selected_category == $cat['slug']) ? 'text-accent font-bold' : 'text-gray-600' ?>">
                                        <span class="group-hover:text-accent transition"><?= htmlspecialchars($cat['name']) ?></span>
                                        <?php if($selected_category == $cat['slug']): ?>
                                            <i class="fas fa-check text-xs"></i>
                                        <?php endif; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Filter by Price link -->
                    <!-- Simulating price range links for UI completeness -->
                    <div class="mb-6">
                         <h4 class="font-bold text-sm text-primary mb-3 uppercase tracking-wide">Fiyat Aralığı</h4>
                         <div class="space-y-1 text-sm text-gray-600">
                             <!-- Future implementation could involve JS sliders or form inputs -->
                             <p class="text-xs text-muted">Fiyat filtreleme yakında eklenecek.</p>
                         </div>
                    </div>
                </div>
            </aside>

            <!-- Product Grid (Right) -->
            <div class="lg:col-span-3" style="grid-column: span 3; @media(max-width: 1024px){ grid-column: span 4; }">
                
                <!-- Sorting & Top Bar -->
                <div class="bg-white p-4 rounded-lg shadow-sm border mb-6 flex flex-wrap gap-4 items-center justify-between">
                    <div class="text-sm text-muted hidden md:block">
                        Aradığınız kriterlere uygun sonuçlar.
                    </div>
                    
                    <form action="/products" method="GET" class="flex items-center gap-2 ml-auto" id="sortForm">
                        <?php if($selected_category): ?><input type="hidden" name="category" value="<?= $selected_category ?>"><?php endif; ?>
                        <?php if($search_query): ?><input type="hidden" name="q" value="<?= $search_query ?>"><?php endif; ?>
                        
                        <label for="sort" class="text-sm font-medium text-gray-700">Sırala:</label>
                        <select name="sort" id="sort" onchange="this.form.submit()" class="form-select text-sm py-1 pl-2 pr-8 border-gray-300 rounded-md focus:ring-accent focus:border-accent">
                            <option value="newest" <?= $selected_sort == 'newest' ? 'selected' : '' ?>>En Yeni</option>
                            <option value="price_asc" <?= $selected_sort == 'price_asc' ? 'selected' : '' ?>>Fiyat (Artan)</option>
                            <option value="price_desc" <?= $selected_sort == 'price_desc' ? 'selected' : '' ?>>Fiyat (Azalan)</option>
                        </select>
                    </form>
                </div>

                <?php if(empty($products)): ?>
                    <div class="bg-white rounded-lg shadow-sm border p-12 text-center">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-search text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Ürün Bulunamadı</h3>
                        <p class="text-gray-500 mb-6">Arama kriterlerinize uygun ürün bulunamadı. Lütfen filtreleri temizleyip tekrar deneyin.</p>
                        <a href="/products" class="btn btn-primary">Tüm Ürünleri Gör</a>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                        <?php foreach($products as $product): ?>
                            <div class="card h-full flex flex-col group hover:shadow-lg transition duration-200">
                                <div class="relative bg-white p-6 border-b flex items-center justify-center h-60">
                                    <img src="<?= htmlspecialchars(ImageHelper::url($product['image'] ?? '', 'https://placehold.co/400x400?text=No+Image')) ?>" 
                                         alt="<?= htmlspecialchars($product['name']) ?>" 
                                         class="max-h-full max-w-full object-contain group-hover:scale-105 transition duration-300">
                                    
                                    <?php if(($product['stock_status'] ?? 'in_stock') == 'in_stock'): ?>
                                        <span class="absolute top-3 right-3 badge badge-success text-[10px]">Stokta</span>
                                    <?php else: ?>
                                        <span class="absolute top-3 right-3 badge badge-danger text-[10px]">Tükendi</span>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body flex-1 flex flex-col p-5">
                                    <div class="text-xs text-muted font-bold uppercase tracking-wider mb-2">
                                        <?= htmlspecialchars($product['category_name'] ?? '') ?>
                                    </div>
                                    <h3 class="text-base font-bold mb-2 leading-snug">
                                        <a href="/product/<?= $product['slug'] ?>" class="text-primary hover:text-accent transition">
                                            <?= htmlspecialchars($product['name']) ?>
                                        </a>
                                    </h3>
                                    
                                    <div class="mt-auto pt-4 flex items-end justify-between border-t border-gray-50">
                                        <div>
                                            <span class="block text-xs text-muted mb-1">Nakit / Havale</span>
                                            <span class="text-lg font-bold text-primary">
                                                <?= number_format($product['price'], 2, ',', '.') ?> <span class="text-xs">₺</span>
                                            </span>
                                        </div>
                                        <a href="/product/<?= $product['slug'] ?>" class="btn btn-sm btn-outline hover:bg-accent hover:border-accent hover:text-white transition-colors">
                                            İncele
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
