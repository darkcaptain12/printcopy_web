const { test, expect } = require('@playwright/test');

test.describe('Checkout flow', () => {
  test.beforeEach(async ({ page }) => {
    await page.request.get('/__test__/reset');
  });

  test('Checkout form accessible and submittable', async ({ page }) => {
    // ensure cart has an item
    await page.goto('/products');
    await page.waitForLoadState('domcontentloaded');
    await page.locator('a[href^="/product/"]').first().click();
    await page.waitForLoadState('domcontentloaded');
    await page.getByRole('button', { name: /Sepete Ekle/i }).click();
    await page.waitForLoadState('domcontentloaded');

    await page.goto('/checkout');
    await page.waitForLoadState('domcontentloaded');
    await expect(page.getByRole('heading', { name: /Ödeme|Checkout|Sipariş/i })).toBeVisible({ timeout: 5000 });

    await page.getByLabel(/Ad Soyad|İsim/i).fill('Test Kullanıcı');
    await page.getByLabel(/E-?posta/i).fill('test@example.com');
    await page.getByLabel(/Telefon/i).fill('5551231212');
    await page.getByLabel(/Adres/i).fill('Test Mah. No:1 Istanbul');

    // Submit
    const submitBtn = page.getByRole('button', { name: /Güvenli Ödemeye Geç|Ödemeye|Ödemeye Geç/i });
    await submitBtn.click();
    await page.waitForLoadState('domcontentloaded');
    // Success: stay without validation error or redirect pattern
    const err = page.locator('text=/hata|error|geçersiz/i').first();
    const hasErr = await err.isVisible().catch(() => false);
    expect(hasErr).toBeFalsy();
    expect(page.url()).toMatch(/checkout|payment|success|paytr/i);
  });
});
