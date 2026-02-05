const { test, expect } = require('@playwright/test');

test.describe('Products listing & detail', () => {
  test.beforeEach(async ({ page }) => {
    await page.request.get('/__test__/reset');
  });

  test('Products grid has items', async ({ page }) => {
    await page.goto('/products');
    await page.waitForLoadState('domcontentloaded');
    const cards = page.locator('.product-card');
    await expect(cards.first()).toBeVisible({ timeout: 5000 });
  });

  test('First product navigates to detail with slug', async ({ page }) => {
    await page.goto('/products');
    await page.waitForLoadState('domcontentloaded');
    const firstLink = page.locator('a[href^="/product/"]').first();
    const href = await firstLink.getAttribute('href');
    await firstLink.click();
    await page.waitForLoadState('domcontentloaded');
    await expect(page).toHaveURL(/\/product\//);
    await expect(page.locator('h1')).toBeVisible();
    expect(href).toMatch(/\/product\//);
  });
});
