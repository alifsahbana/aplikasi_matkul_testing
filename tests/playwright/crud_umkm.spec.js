import { test, expect } from '@playwright/test';

test.describe('Automated E2E: Operasi CRUD Data UMKM (Admin)', () => {
  test.beforeEach(async ({ page }) => {
    // Login sebagai Admin terlebih dahulu
    await page.goto('/login');
    await page.click('button:has-text("Akun Admin")');
    await page.click('button[type="submit"]');
    await expect(page).toHaveURL(/.*admin/);
  });

  test('Admin dapat membuka formulir penambahan UMKM dengan mini-map koordinat', async ({ page }) => {
    await page.goto('/admin/umkm/create');
    
    await expect(page.locator('h2')).toContainText('Formulir Pendataan UMKM Baru');
    await expect(page.locator('#miniMapPicker')).toBeVisible();
    await expect(page.locator('#latInput')).toBeVisible();
    await expect(page.locator('#lngInput')).toBeVisible();
  });

  test('Validasi formulir mencegah koordinat di luar batas Pamekasan', async ({ page }) => {
    await page.goto('/admin/umkm/create');

    await page.fill('input[name="code"]', 'E2E-INVALID-' + Date.now());
    await page.fill('input[name="name"]', 'Usaha Uji Validasi Koordinat');
    await page.selectOption('select[name="category_id"]', { index: 1 });
    await page.selectOption('select[name="district_id"]', { index: 1 });
    await page.fill('textarea[name="address"]', 'Jl. Uji Coba Luar Wilayah');
    
    // Masukkan koordinat di luar Pamekasan
    await page.fill('#latInput', '-6.2000000'); // Jakarta
    await page.fill('#lngInput', '106.8166660');

    await page.click('button[type="submit"]');

    // Pastikan muncul notifikasi error validasi
    await expect(page.locator('body')).toContainText('Titik lintang harus berada dalam batas geografis Kabupaten Pamekasan');
  });
});
