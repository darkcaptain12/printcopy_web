<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) : 'PrintCopy' ?></title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Header -->
    <header>
        <div class="container navbar">
            <a href="/" class="logo">PrintCopy</a>
            <button class="mobile-menu-btn" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="nav-links" id="navLinks">
                <li><a href="/">Anasayfa</a></li>
                <li><a href="/products">Ürünler</a></li>
                <li><a href="/blog">Blog</a></li>
                <li><a href="/contact">İletişim</a></li>
                <!-- Sepet & Hesap -->
                <li><a href="/cart"><i class="fas fa-shopping-cart"></i> Sepet</a></li>
                <li><a href="/admin/login"><i class="fas fa-user-lock"></i> Yönetim</a></li>
            </ul>
        </div>
    </header>
