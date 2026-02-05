const { test, expect } = require('@playwright/test');
const { adminLogin } = require('./helpers/auth');

test.describe('Admin CRUD surfaces', () => {
  test.beforeEach(async ({ page }) => {
    await page.request.get('/__test__/reset');
  });
  async function ensureAuthenticated(page) {
    await adminLogin(page);
  }

  test('Products list and create form open', async ({ page }) => {
    await ensureAuthenticated(page);
    await page.goto('/admin/products');
    await page.waitForLoadState('domcontentloaded');
    if (page.url().includes('/admin/login')) throw new Error('Not authenticated (redirected to login)');
    await expect(page.locator('text=/Ürünler|Products/i')).toBeVisible();

    // Açılış butonu
    await page.getByRole('link', { name: /Ekle|Add|Create/i }).first().click();
    await page.waitForLoadState('networkidle');
    await expect(page).toHaveURL(/products\/create/);
    await expect(page.getByRole('textbox').first()).toBeVisible();
  });

  test('Categories page opens', async ({ page }) => {
    await ensureAuthenticated(page);
    await page.goto('/admin/categories');
    await page.waitForLoadState('domcontentloaded');
    if (page.url().includes('/admin/login')) throw new Error('Not authenticated (redirected to login)');
    await expect(page.locator('body')).toBeVisible();
  });

  test('Orders page opens', async ({ page }) => {
    await ensureAuthenticated(page);
    await page.goto('/admin/orders');
    await page.waitForLoadState('domcontentloaded');
    if (page.url().includes('/admin/login')) throw new Error('Not authenticated (redirected to login)');
    await expect(page.locator('body')).toBeVisible();
  });

  test('Settings page opens without fatal', async ({ page }) => {
    await ensureAuthenticated(page);
    await page.goto('/admin/settings');
    await page.waitForLoadState('domcontentloaded');
    if (page.url().includes('/admin/login')) throw new Error('Not authenticated (redirected to login)');
    await expect(page.locator('text=/Settings|Site Settings/i')).toBeVisible();
  });

  test.skip('Delete product (skipped to avoid destructive action)', async () => {});
});
