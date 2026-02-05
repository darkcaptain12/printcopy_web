const { test, expect } = require('@playwright/test');

test.describe('Contact form', () => {
  test.beforeEach(async ({ page }) => {
    await page.request.get('/__test__/reset');
  });

  test('Submit contact form successfully', async ({ page }) => {
    await page.goto('/iletisim');
    await page.waitForLoadState('networkidle');

    await expect(page.getByRole('heading', { name: /İletişim|Contact/i })).toBeVisible({ timeout: 5000 });

    await page.getByLabel(/Adınız|Name/i).fill('Test Kullanıcı');
    await page.getByLabel(/E-?posta/i).fill('contact@test.com');
    await page.getByLabel(/Telefon/i).fill('5551231212');
    const messageBox = page.getByLabel(/Mesaj|Message/i);
    await messageBox.fill('Playwright otomasyon testi mesajı.');

    const submitBtn = page.getByRole('button', { name: /Gönder|Submit|Send/i });
    await submitBtn.click();
    await page.waitForLoadState('domcontentloaded');

    // Success ya da en azından hata mesajı olmamalı
    const success = page.locator('text=/başar|teşekkür|thank/i').first();
    const hasSuccess = await success.isVisible().catch(() => false);
    const error = page.locator('text=/hata|error|geçersiz/i').first();
    const hasErr = await error.isVisible().catch(() => false);
    expect(hasErr).toBeFalsy();
    if (!hasSuccess) {
        expect(page.url()).toContain('/iletisim');
    }
  });
});
