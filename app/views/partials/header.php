<?php
use App\Core\Session;
$cart_count = \App\Core\Cart::count();
$current_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$settings = [];
try {
    $db = (new Database())->getConnection();
    $settings = $db->query("SELECT `key`,`value` FROM settings")->fetchAll(PDO::FETCH_KEY_PAIR);
} catch (\Exception $e) {}
$siteLogo = $settings['site_logo'] ?? '';
$siteName = $settings['site_name'] ?? 'PrintCopy';
$siteTagline = $settings['site_tagline'] ?? '';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
      $currentUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
      $pageTitle = $siteName . ' | Profesyonel Baskı Çözümleri';
      $pageDesc  = $settings['default_meta_desc'] ?? 'PrintCopy kurumsal dijital baskı çözümleri, UV DTF, Eco Solvent ve teknik servis desteği.';
      echo \App\Core\Seo::meta($pageTitle, $pageDesc, $currentUrl);
    ?>
    <?php if(!empty($settings['site_favicon'])): ?>
    <link rel="icon" href="<?= htmlspecialchars($settings['site_favicon']) ?>" type="image/png">
    <?php endif; ?>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CDN (for utility classes used in templates) -->
    <script>
        tailwind = {
            config: {
                theme: {
                    container: {
                        center: true,
                        padding: {
                            DEFAULT: '1rem',
                            sm: '1.5rem',
                            lg: '2rem',
                        },
                        screens: {
                            '2xl': '1280px',
                        }
                    },
                    extend: {
                        colors: {
                            primary: '#0b1b4d',
                            accent: '#0a6bc2',
                            muted: '#64748b',
                            body: '#f8fafc',
                        },
                        boxShadow: {
                            card: '0 14px 30px -16px rgba(15,23,42,0.28)',
                        }
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Main CSS -->
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="bg-gray-50 text-slate-900 font-sans antialiased">

    <!-- Header -->
    <header class="site-header">
        <div class="container nav-container">
            <!-- Brand -->
            <a href="/" class="brand group">
                <?php if($siteLogo): ?>
                    <img src="<?= htmlspecialchars($siteLogo) ?>" alt="<?= htmlspecialchars($siteName) ?>" class="h-10 object-contain">
                <?php else: ?>
                    <div class="mark">P</div>
                <?php endif; ?>
                <span class="text-2xl font-extrabold tracking-tight text-primary">
                    <?= htmlspecialchars($siteName) ?><?php if($siteTagline): ?><span class="text-accent"> | <?= htmlspecialchars($siteTagline) ?></span><?php endif; ?>
                </span>
            </a>

            <!-- Desktop Navigation -->
            <nav class="main-nav hidden md:flex">
                <a href="/" class="<?= $current_uri == '/' ? 'active nav-active' : '' ?>">
                    Anasayfa
                </a>
                <a href="/products" class="<?= strpos($current_uri, '/products') === 0 || strpos($current_uri, '/product/') === 0 ? 'active nav-active' : '' ?>">
                    Ürünler
                </a>
                <a href="/blog" class="<?= strpos($current_uri, '/blog') === 0 ? 'active nav-active' : '' ?>">
                    Blog
                </a>
                <a href="/contact" class="<?= strpos($current_uri, '/contact') === 0 ? 'active nav-active' : '' ?>">
                    İletişim
                </a>
            </nav>

            <!-- Actions -->
            <div class="nav-actions">
                 <!-- Search (Optional Icon) -->
                 <button class="hidden md:flex icon-btn text-slate-500">
                    <i class="fas fa-search text-lg"></i>
                 </button>

                <!-- Cart -->
                <a href="/cart" class="relative icon-btn">
                    <i class="fas fa-shopping-cart text-lg"></i>
                    <?php if($cart_count > 0): ?>
                        <span class="cart-badge">
                            <?= $cart_count ?>
                        </span>
                    <?php endif; ?>
                </a>

                <!-- Admin Icon (Always visible but subtle) -->
                <a href="/admin" class="icon-btn text-slate-400" title="Yönetim Paneli">
                    <i class="fas fa-user-lock text-lg"></i>
                </a>

                <!-- CTA -->
                <div class="cta-quote hidden md:flex">
                    <a href="/contact" class="btn btn-primary btn-sm rounded-full px-6 shadow-md shadow-blue-900/10">
                        Teklif AI
                    </a>
                </div>

                <!-- Mobile Toggle -->
            <button id="mobileMenuBtn" class="mobile-toggle w-10 h-10 flex items-center justify-center text-slate-700 hover:bg-slate-100 rounded-lg transition-colors" style="z-index:60;">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </div>
    </header>

    <!-- Mobile Menu Overlay -->
    <div id="mobileMenu" class="hidden fixed inset-0 z-40 bg-white pt-[80px] px-6 pb-6 overflow-y-auto">
        <nav class="flex flex-col gap-4 text-lg font-medium">
            <a href="/" class="py-3 border-b border-gray-100">Anasayfa</a>
            <a href="/products" class="py-3 border-b border-gray-100">Ürünler</a>
            <a href="/blog" class="py-3 border-b border-gray-100">Blog</a>
            <a href="/contact" class="py-3 border-b border-gray-100">İletişim</a>
            <a href="/cart" class="py-3 border-b border-gray-100 flex items-center justify-between">
                Sepetim 
                <span class="bg-accent text-white px-2 py-1 rounded text-xs"><?= $cart_count ?></span>
            </a>
            <a href="/admin/login" class="py-3 mt-4 text-center btn btn-outline w-full justify-center">
                Yönetim Paneli
            </a>
        </nav>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('mobileMenuBtn');
        const menu = document.getElementById('mobileMenu');
        const body = document.body;
        const header = document.querySelector('header.site-header');
        if (!btn || !menu) return;

        function openMenu() {
            menu.classList.remove('hidden');
            menu.classList.add('open');
            body.classList.add('menu-open');
            btn.innerHTML = '<i class="fas fa-times text-xl"></i>';
        }
        function closeMenu() {
            menu.classList.add('hidden');
            menu.classList.remove('open');
            body.classList.remove('menu-open');
            btn.innerHTML = '<i class="fas fa-bars text-xl"></i>';
        }
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            menu.classList.contains('hidden') ? openMenu() : closeMenu();
        });
        // Dış tıklama
        document.addEventListener('click', (e) => {
            if (!menu.classList.contains('hidden') && !menu.contains(e.target) && !btn.contains(e.target)) {
                closeMenu();
            }
        });
        // ESC kapama
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !menu.classList.contains('hidden')) {
                closeMenu();
            }
        });

        // Compact header on scroll
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                header?.classList.add('compact');
            } else {
                header?.classList.remove('compact');
            }
        });
    });
    </script>

    <main class="min-h-screen">
        <?php require_once __DIR__ . '/flash.php'; ?>
