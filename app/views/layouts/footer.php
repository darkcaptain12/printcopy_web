    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>PrintCopy</h3>
                    <p>Profesyonel baskı çözümleri ve ofis ihtiyaçlarınız için güvenilir adres.</p>
                </div>
                <div class="footer-column">
                    <h3>Hızlı Bağlantılar</h3>
                    <ul>
                        <li><a href="/">Anasayfa</a></li>
                        <li><a href="/products">Ürünler</a></li>
                        <li><a href="/blog">Blog</a></li>
                        <li><a href="/contact">İletişim</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>İletişim</h3>
                    <ul>
                        <?php 
                        // Try to find specific settings if available, otherwise default
                        $phone = '+90 212 123 45 67';
                        $email = 'info@printcopy.com.tr';
                        $address = 'İstanbul, Türkiye';
                        
                        if (!empty($settings)) {
                            foreach ($settings as $setting) {
                                if ($setting['key'] === 'phone') $phone = $setting['value'];
                                if ($setting['key'] === 'email') $email = $setting['value'];
                                if ($setting['key'] === 'address') $address = $setting['value'];
                            }
                        }
                        ?>
                        <li><i class="fas fa-phone"></i> <?= $phone ?></li>
                        <li><i class="fas fa-envelope"></i> <?= $email ?></li>
                        <li><i class="fas fa-map-marker-alt"></i> <?= $address ?></li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                &copy; <?= date('Y') ?> PrintCopy. Tüm hakları saklıdır.
            </div>
        </div>
    </footer>

    <script>
        function toggleMenu() {
            const nav = document.getElementById('navLinks');
            nav.classList.toggle('active');
        }
    </script>
</body>
</html>
