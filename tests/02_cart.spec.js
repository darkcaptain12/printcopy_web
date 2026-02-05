const { test, expect } = require('@playwright/test');

test.describe('Cart flow', () => {
  test.beforeEach(async ({ page }) => {
    await page.request.get('/__test__/reset');
  });

  test('Add product and see it in cart; adding again increments quantity', async ({ page }) => {
    // Go to products and open first product
    await page.goto('/products');
    await page.waitForLoadState('domcontentloaded');
    await page.locator('a[href^="/product/"]').first().click();
    await page.waitForLoadState('domcontentloaded');

    // Add to cart twice on detail page to increment quantity
    const addBtn = page.getByRole('button', { name: /Sepete Ekle/i });
    await addBtn.click();
    await page.waitForLoadState('domcontentloaded');
    await addBtn.click();
    await page.waitForLoadState('domcontentloaded');

    // Go to cart
    await page.goto('/cart');
    await page.waitForLoadState('domcontentloaded');
    await expect(page.getByRole('heading', { name: /Sepetim/i })).toBeVisible({ timeout: 5000 });
    const qtyInput = page.locator('input[name="quantity"]').first();
    await expect(qtyInput).toBeVisible();
    const qtyVal = parseInt(await qtyInput.inputValue(), 10);
    expect(qtyVal).toBeGreaterThanOrEqual(2);
  });
});
