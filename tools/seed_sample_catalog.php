<?php
// tools/seed_sample_catalog.php

require_once __DIR__ . '/../config/database.php';

$db = (new Database())->getConnection();

echo "Seeding Sample Catalog...\n";

// 0. Schema Migration (Auto-fix columns)
try {
    // Categories: description, image
    $db->exec("ALTER TABLE categories ADD COLUMN description TEXT NULL");
} catch (Exception $e) {}
try {
    $db->exec("ALTER TABLE categories ADD COLUMN image VARCHAR(255) NULL");
} catch (Exception $e) {}

// Products: short_description, image (if missing)
try {
    $db->exec("ALTER TABLE products ADD COLUMN short_description TEXT NULL AFTER price");
} catch (Exception $e) {}
try {
    $db->exec("ALTER TABLE products ADD COLUMN image VARCHAR(255) NULL");
} catch (Exception $e) {}

// 1. Categories
$categories = [
    [
        'name' => 'UV DTF Yazıcılar',
        'slug' => 'uv-dtf-yazicilar',
        'description' => 'Yüksek kaliteli UV DTF baskı makineleri.',
        'image' => 'https://ui-avatars.com/api/?name=UV&background=0D8ABC&color=fff&size=200'
    ],
    [
        'name' => 'Eco Solvent Yazıcılar',
        'slug' => 'eco-solvent-yazicilar',
        'description' => 'Dış mekan dayanıklı eco solvent yazıcılar.',
        'image' => 'https://ui-avatars.com/api/?name=ES&background=28a745&color=fff&size=200'
    ],
    [
        'name' => 'Laminasyon / Kesim',
        'slug' => 'laminasyon-kesim',
        'description' => 'Profesyonel laminasyon ve kesim çözümleri.',
        'image' => 'https://ui-avatars.com/api/?name=LK&background=6c757d&color=fff&size=200'
    ]
];

$catCount = 0;
foreach ($categories as $cat) {
    // Check if exists
    $stmt = $db->prepare("SELECT id FROM categories WHERE slug = ?");
    $stmt->execute([$cat['slug']]);
    if (!$stmt->fetch()) {
        $insert = $db->prepare("INSERT INTO categories (name, slug, description, image, is_active, created_at) VALUES (?, ?, ?, ?, 1, NOW())");
        $insert->execute([$cat['name'], $cat['slug'], $cat['description'], $cat['image']]);
        $catCount++;
        echo "Created Category: {$cat['name']}\n";
    } else {
        echo "Category exists: {$cat['name']}\n";
    }
}

// 2. Products
$products = [
    [
        'name' => 'UV DTF Baskı Makinesi Pro X1',
        'slug' => 'uv-dtf-baski-makinesi-pro-x1',
        'category_slug' => 'uv-dtf-yazicilar',
        'price' => 189000.00,
        'short_desc' => 'Yüksek hızlı, çift kafa baskı teknolojisi. Bardak, termos, kalem gibi sert zeminler için mükemmel çözüm.',
        'description' => "Pro X1 UV DTF Yazıcı, endüstriyel üretim standartlarında geliştirilmiştir.\n\nTEKNİK ÖZELLİKLER:\n- Baskı Kafası: 3 x Epson i3200-U1\n- Baskı Genişliği: 60cm Rulo\n- Hız: 12 m2/saat\n- Mürekkep: UV Curable (CMYK + W + V)\n- RIP Yazılımı: Sai PhotoPrint\n- Garanti: 2 Yıl Yerinde Servis\n\nAVANTAJLAR:\n- Transfer filmi gerektirmez, direkt filme baskı.\n- Kristal etiket kalitesinde sonuç.\n- Otomatik temizleme sistemi.",
        'image' => 'https://picsum.photos/seed/uvdtf/900/600'
    ],
    [
        'name' => 'Eco Solvent Baskı Makinesi S800',
        'slug' => 'eco-solvent-baski-makinesi-s800',
        'category_slug' => 'eco-solvent-yazicilar',
        'price' => 245000.00,
        'short_desc' => 'Açık hava reklamcılığı için canlı renkler ve dayanıklı baskı. 160cm ve 320cm seçenekleriyle.',
        'description' => "S800 Eco Solvent, dış mekan dayanıklılığı yüksek baskılar için idealdir.\n\nTEKNİK ÖZELLİKLER:\n- Baskı Kafası: 2 x Epson i3200-E1\n- Baskı Genişliği: 180cm\n- Çözünürlük: 1440 dpi\n- Isıtma Sistemi: Ön, Orta, Arka 3 bölge\n- Kurutma: Fan + Infrared Isıtıcı\n\nUYGULAMA ALANLARI:\n- Araç Kaplama\n- Vinil, Branda, Oneway Vision\n- Roll-up ve Poster",
        'image' => 'https://picsum.photos/seed/ecosolvent/900/600'
    ],
    [
        'name' => 'DTF Fırın + Kurutma Ünitesi D600',
        'slug' => 'dtf-firin-kurutma-unitesi-d600',
        'category_slug' => 'laminasyon-kesim',
        'price' => 79500.00,
        'short_desc' => 'Otomatik tozlama ve kürleme sistemi ile kesintisiz üretim hattı oluşturun.',
        'description' => "D600, DTF baskı sürecini otomatize eden profesyonel bir finishing ünitesidir.\n\nÖZELLİKLER:\n- Genişlik: 60cm uyumlu\n- Isıtma: Akıllı termostat kontrolü\n- Tozlama: Otomatik sensörlü geri dönüşüm\n- Sarım: Tork kontrollü otomatik sarıcı\n- Güç: 220V / 3500W",
        'image' => 'https://picsum.photos/seed/shaking/900/600'
    ]
];

$prodCount = 0;
foreach ($products as $prod) {
    // Get Category ID
    $stmtCat = $db->prepare("SELECT id FROM categories WHERE slug = ?");
    $stmtCat->execute([$prod['category_slug']]);
    $catId = $stmtCat->fetchColumn();

    if ($catId) {
        // Check if product exists
        $stmtCheck = $db->prepare("SELECT id FROM products WHERE slug = ?");
        $stmtCheck->execute([$prod['slug']]);
        if (!$stmtCheck->fetch()) {
            $insert = $db->prepare("INSERT INTO products (category_id, name, slug, price, description, image, stock_status, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?, 'instock', 1, NOW())");
            // Note: We are putting short_desc in description temporarily or appending, 
            // but ideally we should update schema if short_description column exists.
            // Let's check schema or just append short desc to desc for now if column missing,
            // BUT prompt requested 'Kısa Açıklama' field in admin. 
            // Assuming table might NOT have short_description column, I will try to ADD it if missing or just put it in description.
            // To be safe and fast, I'll concatenate: "Short Desc\n\nFull Desc"
            // Wait, prompt said: "Alanlar: ... Kısa Açıklama, Detay Açıklama".
            // I'll assume usage of existing columns or create column if needed. 
            // Let's try to add the column if it doesn't exist.
            
            try {
                $db->exec("ALTER TABLE products ADD COLUMN short_description TEXT AFTER price");
            } catch (Exception $e) {
                // Column likely exists or error, ignore
            }

            $insert = $db->prepare("INSERT INTO products (category_id, name, slug, price, short_description, description, image, stock_status, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'in_stock', 1, NOW())");
            $insert->execute([$catId, $prod['name'], $prod['slug'], $prod['price'], $prod['short_desc'], $prod['description'], $prod['image']]);
            $prodCount++;
            echo "Created Product: {$prod['name']}\n";
        } else {
            echo "Product exists: {$prod['name']}\n";
        }
    } else {
        echo "Skipping Product {$prod['name']} (Category not found)\n";
    }
}

echo "--------------------------------------------------\n";
echo "Seed Completed. Added $catCount Categories and $prodCount Products.\n";
