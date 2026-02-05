<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="bg-body py-12">
    <div class="container">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            
            <!-- Contact Info -->
            <div>
                <h1 class="text-3xl font-bold mb-6">Bize Ulaşın</h1>
                <p class="text-muted mb-8 text-lg">
                    Projeleriniz ve ihtiyaçlarınız için uzman ekibimizle iletişime geçin.
                </p>

                <div class="bg-white rounded-xl shadow-sm border p-8 space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-blue-50 text-accent rounded-full flex items-center justify-center text-xl flex-shrink-0">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg mb-1">Adres</h3>
                            <p class="text-secondary">
                                İkitelli OSB Mahallesi,<br>
                                Yazıcılar Sanayi Sitesi, No: 123<br>
                                Başakşehir / İstanbul
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-blue-50 text-accent rounded-full flex items-center justify-center text-xl flex-shrink-0">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg mb-1">Telefon</h3>
                            <p class="text-secondary font-bold text-lg">0212 555 00 00</p>
                            <p class="text-sm text-muted">Hafta içi 09:00 - 18:00</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-blue-50 text-accent rounded-full flex items-center justify-center text-xl flex-shrink-0">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg mb-1">E-Posta</h3>
                            <p class="text-secondary">info@printcopy.com.tr</p>
                            <p class="text-secondary">satis@printcopy.com.tr</p>
                        </div>
                    </div>
                </div>

                <!-- Google Maps (Placeholder) -->
                <div class="mt-8 bg-gray-200 rounded-xl h-64 overflow-hidden shadow-inner">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d192698.6007249339!2d28.8720968!3d41.0054958!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14caa7040068086b%3A0xe1ccfe98bc01b0d0!2zxLBzdGFuYnVs!5e0!3m2!1str!2str!4v1672345678901!5m2!1str!2str" 
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>

            <!-- Contact Form -->
            <div>
                <form action="/contact/submit" method="POST" class="bg-white p-8 rounded-xl shadow-lg border border-gray-100 sticky top-24">
                     <?= \App\Core\CSRF::field() ?>
                     <!-- Success Message Check -->
                     <?php if(isset($_GET['success'])): ?>
                        <div class="alert alert-success mb-6">
                            <i class="fas fa-check-circle"></i> Mesajınız başarıyla gönderildi.
                        </div>
                    <?php endif; ?>
                     <?php if(isset($_GET['error'])): ?>
                        <div class="alert alert-danger mb-6">
                            <i class="fas fa-exclamation-triangle"></i> Mail gönderilemedi. Lütfen daha sonra tekrar deneyin.
                        </div>
                     <?php endif; ?>

                    <h2 class="text-2xl font-bold mb-6">İletişim Formu</h2>
                    
                    <div class="space-y-4">
                        <div class="form-group">
                            <label class="form-label">Ad Soyad</label>
                            <input type="text" name="name" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">E-Posta</label>
                            <input type="email" name="email" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Telefon</label>
                            <input type="tel" name="phone" class="form-input">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Konu</label>
                            <select name="subject" class="form-select">
                                <option>Genel Bilgi</option>
                                <option>Teklif İsteği</option>
                                <option>Teknik Servis</option>
                                <option>Diğer</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Mesajınız</label>
                            <textarea name="message" rows="4" class="form-input" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            Gönder <i class="fas fa-paper-plane ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
