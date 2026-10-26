import { test, expect } from '@playwright/test';

test.describe('Automated E2E: Autentikasi & Keamanan Sesi', () => {
  test('Halaman login dapat dimuat dengan tombol bantuan SQA', async ({ page }) => {
    await page.goto('/login');
    await expect(page).toHaveTitle(/Masuk Petugas/);
    await expect(page.locator('h2')).toContainText('Portal Masuk Petugas');
    await expect(page.locator('button:has-text("Akun Admin")')).toBeVisible();
  });

  test('Login berhasil menggunakan akun Administrator dan diarahkan ke Dashboard Admin', async ({ page }) => {
    await page.goto('/login');
    
    // Klik tombol quick-fill admin
    await page.click('button:has-text("Akun Admin")');
    await expect(page.locator('#email')).toHaveValue('admin@pamekasan-gis.id');
    
    // Submit form login
    await page.click('button[type="submit"]');

    // Pastikan terarah ke dashboard admin
    await expect(page).toHaveURL(/.*admin/);
    await expect(page.locator('h1')).toContainText('Panel Kontrol Administrator');
    await expect(page.locator('body')).toContainText('Halo, Administrator WebGIS Pamekasan!');
  });

  test('Percobaan login dengan kata sandi salah memicu pesan kesalahan validasi', async ({ page }) => {
    await page.goto('/login');
    await page.fill('#email', 'admin@pamekasan-gis.id');
    await page.fill('#password', 'katasandisalah123');
    await page.click('button[type="submit"]');

    await expect(page.locator('body')).toContainText('Kombinasi email dan kata sandi yang Anda masukkan tidak sesuai');
  });

  test('Akses tanpa login ke /admin otomatis dialihkan ke /login', async ({ page }) => {
    await page.goto('/admin');
    await expect(page).toHaveURL(/.*login/);
  });
});
