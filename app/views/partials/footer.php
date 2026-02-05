    </main>

    <footer class="bg-primary text-gray-400 py-12 border-t border-gray-800 mt-auto">
        <div class="container grid grid-1 md:grid-4 gap-8" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem;">
            <!-- Brand -->
            <div>
                <?php if(!empty($settings['site_logo'])): ?>
                    <img src="<?= $settings['site_logo'] ?>" alt="<?= htmlspecialchars($settings['site_name'] ?? 'PrintCopy') ?>" class="h-10 mb-4">
                <?php else: ?>
                    <h3 class="text-white mb-4">PRINTCOPY</h3>
                <?php endif; ?>
                <p class="text-sm">
                    <?= htmlspecialchars($settings['site_tagline'] ?? 'Dijital baskı makineleri, teknik servis ve yedek parça çözümlerinde güvenilir iş ortağınız.') ?>
                </p>
                <div class="flex gap-4 mt-4">
                    <?php if(!empty($settings['facebook_url'])): ?><a href="<?= $settings['facebook_url'] ?>" class="hover:text-white"><i class="fab fa-facebook"></i></a><?php endif; ?>
                    <?php if(!empty($settings['instagram_url'])): ?><a href="<?= $settings['instagram_url'] ?>" class="hover:text-white"><i class="fab fa-instagram"></i></a><?php endif; ?>
                    <?php if(!empty($settings['linkedin_url'])): ?><a href="<?= $settings['linkedin_url'] ?>" class="hover:text-white"><i class="fab fa-linkedin"></i></a><?php endif; ?>
                </div>
            </div>

            <!-- Links -->
            <div>
                <h4 class="text-white text-lg font-bold mb-4">Hızlı Erişim</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/" class="hover:text-white">Anasayfa</a></li>
                    <li><a href="/products" class="hover:text-white">Modeller</a></li>
                    <li><a href="/blog" class="hover:text-white">Blog & Haberler</a></li>
                    <li><a href="/contact" class="hover:text-white">İletişim</a></li>
                </ul>
            </div>

            <!-- Corporate -->
            <div>
                <h4 class="text-white text-lg font-bold mb-4">Kurumsal</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white">Hakkımızda</a></li>
                    <li><a href="#" class="hover:text-white">Garanti Şartları</a></li>
                    <li><a href="#" class="hover:text-white">Gizlilik Politikası</a></li>
                    <li><a href="#" class="hover:text-white">KVKK Aydınlatma</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="text-white text-lg font-bold mb-4">Bize Ulaşın</h4>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-center gap-2">
                        <i class="fas fa-phone text-accent"></i> +90 212 555 00 00
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-envelope text-accent"></i> info@printcopy.com.tr
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-map-marker-alt text-accent mt-1"></i> 
                        İkitelli OSB, Yazıcılar San. Sit.<br>İstanbul / Türkiye
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="container text-center text-sm border-t border-gray-800 mt-12 pt-8">
            &copy; <?= date('Y') ?> PrintCopy Dijital Baskı Sistemleri. Tüm hakları saklıdır.
        </div>
    </footer>
</body>
</html>
