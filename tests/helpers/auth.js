const { expect } = require('@playwright/test');

function getAdminCreds() {
  const email = process.env.ADMIN_EMAIL || 'admin';
  const password = process.env.ADMIN_PASSWORD || 'admin123';
  return { email, password };
}

/**
 * Logs into the admin panel.
 * Assumes baseURL is configured in playwright.config.js.
 */
async function adminLogin(page) {
  const { email, password } = getAdminCreds();
  const res = await page.goto('/admin/login');
  expect(res?.ok()).toBeTruthy();
  await expect(page).toHaveURL(/\/admin\/login/);

  // Form fields by id/label
  await page.locator('#username').fill(email);
  await page.locator('#password').fill(password);
  await page.getByRole('button', { name: /Sign In|Giriş|Login/i }).click();
  await page.waitForLoadState('domcontentloaded');
  await expect(page).toHaveURL(/\/admin\/dashboard/);
}

module.exports = { adminLogin, getAdminCreds };
