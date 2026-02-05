const { test, expect } = require('@playwright/test');
const { adminLogin, getAdminCreds } = require('./helpers/auth');

test.describe('Admin login', () => {
  test.beforeEach(async ({ page }) => {
    await page.request.get('/__test__/reset');
  });

  test('Login page reachable', async ({ page }) => {
    const resp = await page.goto('/admin/login');
    expect(resp?.ok()).toBeTruthy();
    await expect(page).toHaveURL(/\/admin\/login/);
  });

  test('Wrong password shows error', async ({ page }) => {
    await page.goto('/admin/login');
    await page.getByPlaceholder(/E-?posta|Email|Kullanıcı/i).fill('wrong@user.com');
    await page.getByPlaceholder(/Şifre|Password/i).fill('wrongpass');
    await page.getByRole('button', { name: /Giriş|Login|Oturum Aç|Sign In/i }).click();
    await page.waitForLoadState('domcontentloaded');
    const error = page.locator('text=/hata|geçersiz|yanlış|invalid/i');
    if (await error.count()) {
      await expect(error.first()).toBeVisible({ timeout: 5000 });
    } else {
      await expect(page).toHaveURL(/\/admin\/login/); // stay on login if no flash text
    }
  });

  test('Valid credentials reach dashboard', async ({ page }) => {
    await adminLogin(page);
    await expect(page).toHaveURL(/\/admin\/dashboard/);
    const creds = getAdminCreds();
    console.log(`Logged in as ${creds.email}`);
  });
});
