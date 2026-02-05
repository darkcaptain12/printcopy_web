<?php use App\Core\CSRF; ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Site Settings</h1>
</div>

<div class="bg-white rounded shadow p-6 max-w-4xl">
    <form action="/admin/settings/update" method="POST" enctype="multipart/form-data">
        <?= CSRF::field() ?>
        
        <h3 class="text-lg font-bold mb-4 border-b pb-2"><?= t('settings.general') ?></h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-700 font-bold mb-2"><?= t('settings.site_name') ?></label>
                <input type="text" name="site_name" value="<?= $settings['site_name'] ?? '' ?>" class="w-full p-2 border rounded">
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2"><?= t('settings.email') ?></label>
                <input type="email" name="site_email" value="<?= $settings['site_email'] ?? '' ?>" class="w-full p-2 border rounded">
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2"><?= t('settings.phone') ?></label>
                <input type="text" name="site_phone" value="<?= $settings['site_phone'] ?? '' ?>" class="w-full p-2 border rounded">
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2"><?= t('settings.address') ?></label>
                <input type="text" name="site_address" value="<?= $settings['site_address'] ?? '' ?>" class="w-full p-2 border rounded">
            </div>
        </div>

        <h3 class="text-lg font-bold mb-4 border-b pb-2"><?= t('settings.social') ?></h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-700 font-bold mb-2"><?= t('settings.facebook') ?></label>
                <input type="text" name="facebook_url" value="<?= $settings['facebook_url'] ?? '' ?>" class="w-full p-2 border rounded">
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2"><?= t('settings.instagram') ?></label>
                <input type="text" name="instagram_url" value="<?= $settings['instagram_url'] ?? '' ?>" class="w-full p-2 border rounded">
            </div>
        </div>

        <h3 class="text-lg font-bold mb-4 border-b pb-2"><?= t('settings.legal') ?></h3>
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2"><?= t('settings.privacy') ?></label>
            <textarea name="privacy_policy" rows="5" class="w-full p-2 border rounded"><?= $settings['privacy_policy'] ?? '' ?></textarea>
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2"><?= t('settings.terms') ?></label>
            <textarea name="terms_conditions" rows="5" class="w-full p-2 border rounded"><?= $settings['terms_conditions'] ?? '' ?></textarea>
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2"><?= t('settings.return_policy') ?></label>
            <textarea name="return_policy" rows="5" class="w-full p-2 border rounded"><?= $settings['return_policy'] ?? '' ?></textarea>
        </div>

        <h3 class="text-lg font-bold mb-4 border-b pb-2">Branding</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Site Adı</label>
                <input type="text" name="site_name" value="<?= $settings['site_name'] ?? '' ?>" class="w-full p-2 border rounded">
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Site Tagline</label>
                <input type="text" name="site_tagline" value="<?= $settings['site_tagline'] ?? '' ?>" class="w-full p-2 border rounded" placeholder="Kısa slogan">
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Logo (PNG/SVG)</label>
                <?php if(!empty($settings['site_logo'])): ?>
                    <img src="<?= $settings['site_logo'] ?>" class="h-12 mb-2">
                    <input type="hidden" name="site_logo" value="<?= $settings['site_logo'] ?>">
                <?php endif; ?>
                <input type="file" name="site_logo" accept="image/*" class="w-full">
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Favicon</label>
                <?php if(!empty($settings['site_favicon'])): ?>
                    <img src="<?= $settings['site_favicon'] ?>" class="h-8 mb-2">
                    <input type="hidden" name="site_favicon" value="<?= $settings['site_favicon'] ?>">
                <?php endif; ?>
                <input type="file" name="site_favicon" accept="image/*" class="w-full">
            </div>
        </div>

        <h3 class="text-lg font-bold mb-4 border-b pb-2">PayTR</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Merchant ID</label>
                <input type="text" name="paytr_merchant_id" value="<?= $settings['paytr_merchant_id'] ?? '' ?>" class="w-full p-2 border rounded">
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Merchant Key</label>
                <input type="text" name="paytr_merchant_key" value="<?= $settings['paytr_merchant_key'] ?? '' ?>" class="w-full p-2 border rounded">
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Merchant Salt</label>
                <input type="text" name="paytr_merchant_salt" value="<?= $settings['paytr_merchant_salt'] ?? '' ?>" class="w-full p-2 border rounded">
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Test Modu (0/1)</label>
                <input type="number" name="paytr_test_mode" value="<?= $settings['paytr_test_mode'] ?? 0 ?>" class="w-full p-2 border rounded">
            </div>
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Başarılı URL</label>
                <input type="text" name="paytr_success_url" value="<?= $settings['paytr_success_url'] ?? '' ?>" class="w-full p-2 border rounded" placeholder="https://site.com/thank-you">
            </div>
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Hata URL</label>
                <input type="text" name="paytr_fail_url" value="<?= $settings['paytr_fail_url'] ?? '' ?>" class="w-full p-2 border rounded" placeholder="https://site.com/payment/fail">
            </div>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700"><?= t('settings.save') ?></button>
    </form>
</div>
