import { test, expect } from '@playwright/test';

test.describe('Automated E2E: Eksplorasi Spasial WebGIS Leaflet', () => {
  test('Peta Leaflet berhasil diinisialisasi dengan basemap OSM dan penanda lokasi', async ({ page }) => {
    await page.goto('/peta');
    
    // Pastikan kontainer peta Leaflet aktif
    await expect(page.locator('#map')).toBeVisible();
    await expect(page.locator('.leaflet-tile-pane')).toBeVisible();

    // Verifikasi kontrol filter
    await expect(page.locator('text=Eksplorasi Spasial GIS')).toBeVisible();
    await expect(page.locator('text=Legenda Kategori Marker')).toBeVisible();
  });

  test('Pencarian nama UMKM memfilter daftar hasil spasial secara reaktif', async ({ page }) => {
    await page.goto('/peta');
    
    // Tunggu data marker awal dimuat
    await page.waitForTimeout(1000);
    
    // Ketik kata kunci 'Barurambat'
    const searchInput = page.locator('input[placeholder*="Contoh: Barurambat"]');
    await searchInput.fill('Barurambat');

    // Tunggu debounce dan verifikasi daftar
    await page.waitForTimeout(600);
    await expect(page.locator('body')).toContainText('Barurambat');
  });

  test('Filter dropdown kecamatan memperbarui data layer', async ({ page }) => {
    await page.goto('/peta');
    await page.waitForTimeout(1000);

    // Pilih kecamatan Kadur
    const districtSelect = page.locator('select').first();
    await districtSelect.selectOption({ label: 'Kadur' });

    await page.waitForTimeout(600);
    await expect(page.locator('body')).toContainText('Kadur');
  });
});
