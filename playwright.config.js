// Playwright configuration for PrintCopy
const { defineConfig } = require('@playwright/test');

const baseURL = process.env.BASE_URL || 'http://127.0.0.1:8002';

module.exports = defineConfig({
  testDir: './tests',
  timeout: 30_000,
  expect: { timeout: 10_000 },
  retries: 1,
  reporter: [
    ['list'],
    ['html', { outputFolder: 'playwright-report', open: 'never' }],
  ],
  use: {
    baseURL,
    headless: true,
    screenshot: 'only-on-failure',
    video: 'retain-on-failure',
    trace: 'on-first-retry',
    actionTimeout: 15_000,
    navigationTimeout: 20_000,
  },
});
