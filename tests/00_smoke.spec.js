const { test, expect } = require('@playwright/test');

test.describe('Smoke routes', () => {
  test.beforeEach(async ({ page }) => {
    await page.request.get('/__test__/reset');
  });

  const pages = ['/', '/products', '/blog', '/iletisim'];

  for (const path of pages) {
    test(`GET ${path} responds and renders`, async ({ page }) => {
      const resp = await page.goto(path);
      expect(resp?.ok()).toBeTruthy();
      await page.waitForLoadState('domcontentloaded');
      // basic content check
      await expect(page.locator('body')).toBeVisible({ timeout: 5000 });
    });
  }

  test('Navbar links navigate correctly', async ({ page }) => {
    await page.goto('/');
    const navLinks = [
      { name: 'Anasayfa', url: '/' },
      { name: 'Ürünler', url: '/products' },
      { name: 'Blog', url: '/blog' },
      { name: 'İletişim', url: '/contact' },
    ];
    for (const link of navLinks) {
      await page.getByRole('link', { name: link.name }).first().click();
      await page.waitForLoadState('domcontentloaded');
      await expect(page).toHaveURL(new RegExp(link.url));
    }
  });
});
