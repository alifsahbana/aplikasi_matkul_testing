# DRAF NASKAH PAPER JURNAL ILMIAH STANDAR NASIONAL (SINTA)
## Berdasarkan Format Rujukan Jurnal SOLUSI (ISSN: 1412-5331 / E-ISSN: 2716-2532)

---

**SOLUSI : Jurnal Ilmiah Bidang Ilmu Sistem Informasi dan Manajemen Bisnis**  
**Volume 22, No. 2, November 2026, Hal 1-16**  
**Fakultas Teknik - Program Studi S1 Sistem Informasi, Universitas Islam Madura (UIM)**  
**P-ISSN : 1412-5331, E-ISSN : 2716-2532**

---

### **Penerapan Sistem Rantai Pasok, Inventori, dan Pemetaan Spasial Berbasis Enterprise Resource Planning (ERP) & WebGIS pada UMKM Tembakau Kabupaten Pamekasan**

**Penulis:**  
[Nama Mahasiswa / Tim Pengembang]¹⁾, Aang Kisnu Darmawan²⁾, Anwari³⁾  
*¹⁾²⁾³⁾Program Studi S1 Sistem Informasi, Fakultas Teknik, Universitas Islam Madura, Pamekasan, Indonesia*  
*Korespondensi Penulis: [email_mahasiswa@student.uim.ac.id]¹⁾, aangkisnu@uim.ac.id²⁾*

---

#### **INFO ARTIKEL**
- **Proses Artikel**:
  - Dikirim: 10 November 2026
  - Direvisi: 18 November 2026
  - Diterima: 22 November 2026
  - Dipublikasikan: 30 November 2026

---

#### **ABSTRAK**
Perkembangan teknologi informasi spasial dan sistem informasi eksekutif sangat memengaruhi efisiensi operasional dan daya saing rantai pasok agribisnis komoditas lokal. Kabupaten Pamekasan merupakan salah satu sentra perkebunan dan industri tembakau terbesar di Madura dengan persebaran yang tersebar di 13 kecamatan. Namun, pengelolaan data operasional UMKM tembakau mitra binaan selama ini menghadapi hambatan mendasar, yaitu: (1) pendataan profil usaha, luas lahan tanam, dan kapasitas gudang tembakau yang masih dilakukan secara manual menggunakan pembukuan kertas atau *spreadsheet* terfragmentasi; (2) belum adanya visibilitas spasial posisi geografis gudang dan sentra olahan, sehingga sering terjadi kesalahan pencatatan koordinat di luar wilayah administratif; serta (3) ketiadaan sistem kalkulasi otomatis untuk mengukur rasio utilitas kapasitas produksi dan indeks potensi ekonomi secara objektif. 

Tujuan dari penelitian ini adalah merancang, membangun, dan mengimplementasikan sistem *Enterprise Resource Planning* (ERP) yang terintegrasi dengan modul *Web Geographic Information System* (WebGIS) guna mengotomatisasi alur inventori bahan baku, pemantauan kapasitas produksi, validasi integritas geospasial (*Boundary Value Analysis*), serta penyajian *Executive Information System* (EIS) bagi pemangku kepentingan. Metode pengembangan mengadopsi kerangka kerja *Software Quality Assurance* (SQA) berbasis ISO/IEC/IEEE 29119 dan V-Model, dengan pengujian fungsional unit, integrasi REST API GeoJSON, dan evaluasi *User Acceptance Testing* (UAT). Analisis data dilakukan dengan membandingkan prosedur *As-Is* dan *To-Be* melalui Analisis Kesenjangan (*GAP Analysis*) metode NPF (*No Fit, Partial, Full*). 

Hasil penelitian membuktikan bahwa sistem ERP-WebGIS berhasil menggantikan pencatatan manual menjadi platform terpusat berbasis web. Seluruh proses inventori bahan baku tembakau, pemetaan titik koordinat dengan proteksi batas wilayah Pamekasan (Lintang -7.35 s/d -6.95 dan Bujur 103.35 s/d 103.65), pelaporan otomatis *Purchase Order* bahan baku, dan pengujian otomatis (*automated testing*) 24 *test suites* dinyatakan lulus 100%. Dengan demikian, implementasi ERP-WebGIS ini memberikan kepastian data rantai pasok yang kredibel, *real-time*, dan akuntabel bagi ekosistem UMKM tembakau di Madura.

**Kata Kunci**: *Enterprise Resource Planning (ERP); WebGIS; UMKM Tembakau; Manajemen Rantai Pasok; Inventori; Pamekasan; SQA ISO 29119.*

---

#### **ABSTRACT**
*Rapid developments in spatial information technology and executive information systems significantly influence the operational efficiency and competitiveness of local commodity agribusiness supply chains. Pamekasan Regency is one of the largest tobacco farming and processing centers in Madura, spread across 13 administrative districts. However, the operational data management of partner tobacco MSMEs has encountered persistent challenges: (1) manual and fragmented record-keeping of business profiles, cultivated farmland acreage, and tobacco warehouse inventory using paper ledgers or standalone spreadsheets; (2) lack of spatial visibility regarding geographic facility locations, leading to frequent out-of-boundary coordinate entry errors; and (3) the absence of an automated computational model to measure capacity utilization ratios and economic potential indices objectively.*

*The objective of this research is to design, construct, and implement an integrated Enterprise Resource Planning (ERP) and Web Geographic Information System (WebGIS) platform to automate raw tobacco inventory workflows, production capacity monitoring, spatial boundary integrity validation (Boundary Value Analysis), and Executive Information System (EIS) dashboards for regional stakeholders. The development methodology adopts the Software Quality Assurance (SQA) framework based on ISO/IEC/IEEE 29119 and the V-Model lifecycle, incorporating functional unit testing, GeoJSON REST API integration testing, and User Acceptance Testing (UAT). Business process transformation was evaluated using Gap Analysis with the NPF (No Fit, Partial, Full) approach comparing As-Is and To-Be workflows.*

*The experimental and deployment results demonstrate that the integrated ERP-WebGIS successfully transitioned manual record-keeping into a centralized, real-time web platform. Raw tobacco inventory tracking, geographic boundary enforcement within Pamekasan limits (Latitude -7.35 to -6.95 and Longitude 103.35 to 103.65), automated purchase order generation, and automated testing suites (24 tests, 107 assertions) achieved a 100% pass rate. Consequently, the ERP-WebGIS implementation delivers credible, timely, and auditable supply chain visibility across the tobacco enterprise ecosystem in Madura.*

**Keywords**: *Enterprise Resource Planning (ERP); WebGIS; Tobacco MSMEs; Supply Chain Management; Inventory; Pamekasan Regency; SQA ISO 29119.*

---

### **1. PENDAHULUAN**

Usaha Mikro, Kecil, dan Menengah (UMKM) merupakan tulang punggung perekonomian nasional yang menyerap mayoritas tenaga kerja dan menggerakkan roda ekonomi daerah (Nugroho, 2021). Di Pulau Madura, khususnya Kabupaten Pamekasan, sektor agribisnis dan pengolahan tembakau memegang peranan vital yang strategis. Kabupaten Pamekasan memiliki bentang geografis seluas 792,30 km² yang terbagi ke dalam 13 kecamatan, di mana wilayah perbukitan di bagian tengah dan utara seperti Kecamatan Kadur, Pakong, Pegantenan, dan Waru dikenal sebagai penghasil tembakau Madura berkualitas tinggi dengan aroma yang khas bagi industri kretek nasional (Darmawan & Fanani, 2022).

Dalam rantai pasok (*supply chain*) agribisnis tembakau, proses bisnis internal melibatkan tahapan multi-pihak: mulai dari pengadaan bahan baku daun tembakau rajangan dari petani mitra, pengelolaan gudang penyimpanan (*warehouse inventory*), pengendalian mutu (*grading* dan kadar air), hingga penjualan dan distribusi ke pabrikan rokok maupun konsumen akhir. Rangkaian aktivitas operasional ini menuntut sinkronisasi yang presisi agar terhindar dari *lead time* yang berkepanjangan dan kerugian akibat kerusakan bahan baku di gudang.

Namun, berdasarkan observasi lapangan dan wawancara pada asosiasi petani tembakau serta UMKM olahan tembakau di Kabupaten Pamekasan, ditemukan sejumlah permasalahan kritis pada tata kelola operasional eksisting:
1. **Pencatatan Persediaan dan Pembelian Manual**: Pendataan volume stok tembakau kering, bibit, pupuk, dan bahan penunjang masih menggunakan buku besar fisik atau *file* Microsoft Excel yang terisolasi pada masing-masing admin gudang. Hal ini menyebabkan seringnya terjadi selisih antara data fisik di gudang (*on-hand stock*) dengan catatan pembukuan.
2. **Ketiadaan Visibilitas Spasial dan Kesalahan Data Geografis**: Mitra UMKM dan dinas terkait tidak memiliki peta digital terpadu untuk memantau sebaran gudang dan klaster produksi. Seringkali petugas pendata salah mencatat titik koordinat GPS sehingga lokasi usaha terlempar ke laut (Selat Madura/Laut Jawa) atau masuk ke batas kabupaten tetangga (Sampang dan Sumenep).
3. **Ketiadaan Sistem Pendukung Keputusan Eksekutif (EIS)**: Penentuan kapasitas produksi dan analisis potensi ekonomi daerah masih bersifat perkiraan subjektif (*rules of thumb*), tanpa adanya formula baku seperti rasio utilitas kapasitas terpasang (*Capacity Utilization Index*) yang teruji secara komputasi.

Perkembangan teknologi *Enterprise Resource Planning* (ERP) berbasis web menawarkan solusi terintegrasi untuk menyatukan departemen pembelian (*purchasing*), pergudangan (*inventory*), manufaktur (*manufacturing*), dan penjualan (*sales*) ke dalam satu basis data terpusat (Aditya & Hadi Santuso, 2022; Nugroho, Ardiana, et al., 2023). Integrasi modul ERP dengan *Web Geographic Information System* (WebGIS) menjadi inovasi krusial bagi komoditas berbasis wilayah, karena memungkinkan pemangku kepentingan melihat data operasional transaksional sekaligus keterhubungannya dengan posisi spasial di lapangan.

Penelitian ini bertujuan untuk merancang, membangun, dan menguji implementasi sistem **Enterprise Resource Planning (ERP) terintegrasi WebGIS** khusus untuk sektor UMKM tembakau di Kabupaten Pamekasan. Kebaruan (*novelty*) dari penelitian ini terletak pada integrasi modul rantai pasok ERP (pembelian, pergudangan, manufaktur) dengan modul geospasial yang dilengkapi validasi batas wilayah (*Boundary Value Analysis*) dan pengujian mutu perangkat lunak otomatis (*Software Quality Assurance*) berstandar ISO/IEC/IEEE 29119. Diharapkan sistem ini dapat meningkatkan akurasi data inventori gudang, mempercepat rantai pengadaan, dan memberikan visualisasi data yang akuntabel bagi pembinaan UMKM di Madura.

---

### **2. KAJIAN PUSTAKA**

#### **2.1. Enterprise Resource Planning (ERP)**
*Enterprise Resource Planning* (ERP) adalah arsitektur perangkat lunak modular yang mengintegrasikan berbagai area fungsional bisnis dalam suatu organisasi ke dalam satu sistem basis data terpadu (Marchewka, 2015). ERP memfasilitasi arus informasi yang mulus antar departemen, seperti *Procurement/Purchasing*, *Warehouse & Inventory Management*, *Manufacturing/Production Planning*, *Financial Accounting*, dan *Sales Management*. 

Penerapan ERP pada sektor UMKM terbukti mampu mereduksi redundansi data, memangkas biaya operasional administrasi, meningkatkan akurasi persediaan barang, serta memperpendek *cycle time* pengadaan barang (Nugroho, Firdaus, et al., 2023). Jika pada perusahaan besar ERP sering kali diimplementasikan melalui perangkat lunak berbayar bernilai tinggi, pada tataran UMKM arsitektur ERP berbasis *open-source web framework* (seperti Laravel, Python, atau Odoo) menjadi alternatif paling efektif karena fleksibel untuk dikustomisasi sesuai proses bisnis lokal yang spesifik (Aditya & Hadi Santuso, 2022).

#### **2.2. Web Geographic Information System (WebGIS)**
*Geographic Information System* (GIS) merupakan sistem berbasis komputer yang dirancang untuk menangkap, menyimpan, memanipulasi, menganalisis, mengelola, dan menyajikan seluruh jenis data spasial atau geografis. Dalam konteks agribisnis, WebGIS menyediakan antarmuka peta interaktif yang dapat diakses oleh publik maupun administrator melalui peramban web standar tanpa memerlukan instalasi aplikasi SIG desktop yang rumit. 

Teknologi WebGIS memanfaatkan pustaka *client-side mapping* seperti Leaflet JS dengan dukungan format pertukaran data standar terbuka GeoJSON (*RFC 7946*). Integrasi WebGIS ke dalam arsitektur ERP memungkinkan pemetaan posisi gudang penampungan tembakau, persebaran kelompok tani, visualisasi poligon batas 13 kecamatan di Pamekasan, serta analisis klaster spasial potensi komoditas (Darmawan & Fanani, 2022).

#### **2.3. Manajemen Rantai Pasok dan Inventori UMKM Tembakau**
Manajemen rantai pasok (*Supply Chain Management*) tembakau mencakup koordinasi dari pemasok bahan baku (petani mitra), pengolah daun tembakau (pemeraman, perajangan, penjemuran), pengemasan dalam keranjang (*packaging*), pergudangan (*inventory*), hingga distribusi ke pedagang besar atau pabrik rokok. Komponen kunci inventori tembakau meliputi:
1. **Bill of Materials (BOM)**: Daftar rincian bahan baku (daun tembakau basah/kering, tali rami, anyaman keranjang, etiket merek).
2. **Kapasitas Gudang & Utilitas**: Rasio antara kuantitas stok yang tersimpan riil (*actual inventory*) dengan kapasitas tampung maksimum gudang (*installed capacity*).
3. **Traceability**: Kemampuan sistem melacak asal-usul tembakau berdasarkan kecamatan panen untuk menjaga keaslian mutu tembakau Madura.

#### **2.4. Penjaminan Mutu Perangkat Lunak (ISO/IEC/IEEE 29119 & V-Model)**
Penjaminan mutu perangkat lunak (*Software Quality Assurance* / SQA) adalah pendekatan sistematis untuk mengevaluasi kualitas dan kepatuhan proses rekayasa perangkat lunak terhadap standar internasional ISO/IEC/IEEE 29119. Standar ini mencakup konsep pengujian, proses pengujian organisasi, pengelolaan pengujian dinamis, serta dokumentasi pengujian terstruktur. Model pengembangan V-Model menghubungkan setiap tahapan perancangan dengan tingkat pengujian yang setara, mulai dari *Unit Testing* menggunakan metode *Boundary Value Analysis* (BVA) dan *Equivalence Partitioning* (EP), *Integration Testing* antarmuka REST API, hingga *User Acceptance Testing* (UAT).

---

### **3. METODE PENELITIAN**

Metode penelitian dilaksanakan secara bertahap melalui pendekatan *Action Research* dan *Project-Based Learning* yang melibatkan kemitraan bersama sentra UMKM tembakau di Kabupaten Pamekasan. Tahapan penelitian dirancang sistematis sebagai berikut:

```
+-------------------------------------------------------------------------+
|                  TAHAPAN METODOLOGI PENELITIAN                          |
+-------------------------------------------------------------------------+
|  1. Identifikasi Lapangan & Observasi Mitra UMKM Tembakau Pamekasan      |
|  2. Analisis Kebutuhan Sistem & Perancangan Arsitektur 3-Tier MVC       |
|  3. Pengembangan Modul ERP-WebGIS (Spasial, Inventori, Purchasing, EIS) |
|  4. Pengujian Sistem Mutu SQA (ISO 29119: BVA, EP, White-Box, E2E, UAT)  |
|  5. Evaluasi Kesenjangan Proses Bisnis (Fit/Gap Analysis Metode NPF)    |
+-------------------------------------------------------------------------+
```

#### **3.1. Identifikasi Objek Penelitian & Mitra UMKM**
Penelitian berfokus pada ekosistem UMKM pengolahan dan perdagangan tembakau di Kabupaten Pamekasan, Jawa Timur. Kriteria pemilihan mitra mencakup:
1. UMKM yang memiliki tempat operasional/gudang penyimpanan tembakau di salah satu dari 13 kecamatan di Kabupaten Pamekasan.
2. Proses pencatatan inventori dan pemesanan bahan baku masih dilakukan secara konvensional (manual atau spreadsheet sederhana).
3. Bersedia memberikan data sampel operasional kapasitas gudang, transaksi pembelian, dan titik koordinat lahan untuk keperluan digitalisasi sistem.

#### **3.2. Perancangan Arsitektur Sistem**
Sistem dibangun menggunakan pendekatan arsitektur *3-Tier Model-View-Controller* (MVC):
1. **Presentation Layer**: Antarmuka web responsif menggunakan Blade Template, Tailwind CSS, Leaflet JS untuk rendering peta spasial, dan Alpine.js untuk interaktivitas reaktif.
2. **Application & Business Logic Layer**: Ditenagai oleh *Laravel Framework*, mengelola aturan bisnis (*Business Rules*), *Geographic Coordinate Validator*, *CsvImportService* untuk migrasi data massal, dan *EconomicAnalysisService* untuk perhitungan indeks kapasitas.
3. **Data Layer**: Basis data relasional (*Relational Database*) dengan dukungan ekstensi geospasial yang mencakup tabel master pengguna, tabel data master UMKM tembakau, kategori produk, log transaksi inventori, dan poligon batas kecamatan.

#### **3.3. Perancangan Modul ERP Terintegrasi**
Modul sistem yang dirancang meliputi:
- **Modul Warehouse & Inventory**: Pencatatan gudang, kapasitas terpasang (ton/musim), status persediaan (*on-hand inventory*), dan klasifikasi jenis tembakau (Kait/Campuran, Prancak 95, Bojonegoroan).
- **Modul Procurement & Purchase Order (PO)**: Form pemesanan bahan baku ke petani mitra, pencatatan harga beli per kilogram, dan pembuatan dokumen faktur pemesanan digital.
- **Modul Spasial WebGIS & Boundary Validator**: Integrasi peta Leaflet dengan mekanisme pencegahan input titik koordinat di luar wilayah administratif Pamekasan.
- **Modul Executive Information System (EIS)**: Dasbor analitik yang menyajikan ringkasan total UMKM aktif, total serapan tenaga kerja, kalkulasi rasio utilitas kapasitas, dan tombol cepat komunikasi melalui WhatsApp dan navigasi Google Maps.

#### **3.4. Metode Pengujian Perangkat Lunak (SQA Framework)**
Pengujian dilakukan secara bertingkat mengacu standar ISO/IEC/IEEE 29119:
1. **Black-Box Testing (BVA & EP)**: Menguji batas nilai numerik koordinat Lintang `[-7.35 s/d -6.95]` dan Bujur `[103.35 s/d 103.65]` untuk memastikan tidak ada anomali titik di luar batas geospasial Pamekasan.
2. **Structural / White-Box Testing**: Menguji diagram alur kendali (*Control Flow Graph*) dan *Cyclomatic Complexity* pada modul kalkulasi formula ekonomi tembakau guna mencegah kesalahan *division-by-zero*.
3. **API & Integration Testing**: Menguji integritas transmisi muatan GeoJSON *FeatureCollection* pada endpoint REST API.
4. **User Acceptance Testing (UAT)**: Evaluasi penerimaan pengguna akhir berdasarkan 8 skenario pengujian bisnis riil.

---

### **4. HASIL DAN PEMBAHASAN**

#### **4.1. Hasil Pengembangan Sistem ERP-WebGIS**
Sistem yang dikembangkan berhasil mengintegrasikan seluruh alur rantai pasok tembakau ke dalam satu portal web terpadu. Antarmuka dan fungsi utama yang berhasil diwujudkan meliputi:

##### **a. Modul Peta Geospasial Interaktif WebGIS**
Modul WebGIS menjadi instrumen utama dalam memvisualisasikan persebaran UMKM dan gudang tembakau di 13 kecamatan Pamekasan. Sistem menyediakan fitur pengalih peta dasar (*basemap switcher*) antara **CartoDB Voyager (OSM)** untuk melihat peta jalan dan fasilitas umum, serta **Esri World Imagery (Satellite)** untuk melihat citra satelit kondisi fisik lahan perkebunan tembakau.
Setiap marker UMKM dilengkapi dengan fitur *clustering* otomatis untuk mencegah tumpang-tindih visual pada area padat seperti sentra Kecamatan Pakong dan Kadur. Ketika marker diklik, sistem menyajikan kartu informasi (*popup card*) yang memuat nama UMKM, kapasitas produksi tahunan, jumlah tenaga kerja, tombol *Petunjuk Rute Google Maps*, serta tombol tautan komunikasi instan *Chat WhatsApp* langsung kepada pemilik UMKM.

##### **b. Modul Warehouse & Manajemen Master UMKM**
Pada panel kontrol administrator, sistem menyediakan formulir terstruktur untuk mengelola data gudang dan profil UMKM. Formulir dilengkapi *mini-map* interaktif di mana penentuan titik lokasi dapat dilakukan dengan mengeklik peta atau mengetik koordinat numerik secara langsung. Setiap penambahan data secara otomatis melewati validasi geospasial *Boundary Value Analysis* pada sisi server (*server-side validation*).

##### **c. Modul Impor Data Massal (ETL Data Migration)**
Untuk mengatasi kendala data historis UMKM yang selama ini tersimpan dalam berkas *spreadsheet*, sistem menyediakan modul impor berkas CSV. Modul ini menerapkan prinsip ETL (*Extract, Transform, Load*) yang mencakup pratinjau data (*data preview*), pembersihan koordinat kotor (*data sanitization*), deteksi dan eliminasi kode duplikat, serta mekanisme *atomic transaction rollback* jika ditemukan kegagalan format data di tengah proses impor.

##### **d. Modul Analisis Potensi Ekonomi & Dasbor Eksekutif**
Dasbor eksekutif menyajikan ringkasan metrik statistik tingkat kabupaten: agregasi kapasitas produksi terpasang, persentase utilitas gudang, rasio serapan tenaga kerja, serta laporan siap cetak berformat PDF yang dapat diunduh oleh dinas pembina sebagai rujukan kebijakan penyerapan tembakau pada musim panen raya.

---

#### **4.2. Analisis Alur Proses Bisnis (Flowchart As-Is vs To-Be)**
Transformasi proses bisnis dari pencatatan konvensional manual (*As-Is*) menuju proses terintegrasi sistem ERP-WebGIS (*To-Be*) digambarkan dalam diagram alir berikut:

```
+------------------------------------------------------------------------------------+
|               FLOWCHART ANALISIS PROSES BISNIS SISTEM ERP-WEBGIS                   |
+------------------------------------------------------------------------------------+
  [PETANI / MITRA]               [ADMIN / GUDANG ERP]                 [SISTEM ERP-WEBGIS]
         |                                |                                    |
         |-- Registrasi/Setor Tembakau -->|                                    |
         |                                |-- Input Data Profil & Gudang ----->|
         |                                |                                    |-- Validasi Batas Wilayah (BVA)
         |                                |                                    |   (Lat: -7.35..-6.95, Lng: 103.35..103.65)
         |                                |                                    |   [Jika di luar: Ditolak Error]
         |                                |                                    |   [Jika valid: Simpan ke Database]
         |                                |                                    |
         |<-- Konfirmasi Penerimaan & PO -|<-- Buat Purchase Order (PO) -------|
         |                                |                                    |
         |                                |-- Update Stok Tembakau Gudang ---->|-- Hitung Utilitas Kapasitas
         |                                |                                    |-- Render Marker ke Peta WebGIS
         |                                |                                    |
  [KONSUMEN / DINAS]                      |                                    |
         |                                |                                    |
         |-- Akses Peta Interaktif ------------------------------------------->|-- Render 13 Poligon Kecamatan
         |-- Filter Sentra Wilayah (Kadur/Pakong) ---------------------------->|-- Tampilkan Marker Terfilter
         |-- Klik Navigasi Rute Google Maps ---------------------------------->|-- Buka Rute Perjalanan
         |-- Klik Hubungi WhatsApp ------------------------------------------->|-- Buka Chat Langsung Pemilik
+------------------------------------------------------------------------------------+
```

---

#### **4.3. Analisis Kesenjangan Proses Bisnis (GAP Analysis Metode NPF)**
Untuk mengukur efektivitas dan tingkat pemenuhan sistem yang dibangun terhadap kebutuhan operasional mitra UMKM tembakau di Kabupaten Pamekasan, dilakukan analisis kesenjangan (*GAP Analysis*) dengan menggunakan metode NPF (*No fit, Partial, Full*) mengacu pada Nugroho (2023):
- **N (*No Fit*)**: Sistem belum mampu memenuhi kebutuhan operasional.
- **P (*Partial*)**: Sistem mampu memenuhi kebutuhan, namun masih membutuhkan intervensi manual sebagian.
- **F (*Full*)**: Sistem berhasil memenuhi seluruh kebutuhan operasional bisnis secara otomatis dan terintegrasi.

Berikut adalah hasil rekapitulasi Analisis Kesenjangan Proses Bisnis:

**Tabel 1. Analisis GAP Proses Bisnis Rantai Pasok dan Inventori UMKM Tembakau**

| No | Proses Bisnis | Kebutuhan Fungsional Bisnis | Pemenuhan (Fulfillment) | | | Keterangan Sistem Manual (*As-Is*) | Keterangan Sistem ERP-WebGIS (*To-Be*) |
|---|---|---|:---:|:---:|:---:|---|---|
| | | | **N** | **P** | **F** | | |
| **1** | **Pendataan Profil Mitra UMKM** | Memiliki basis data profil UMKM, pemilik usaha, nomor kontak legal, dan komoditas secara terstruktur. | | | **✓** | Pendataan dicatat manual dalam buku agenda atau lembaran kertas yang rentan hilang atau rusak fisik. | Data tersimpan terpusat di database PostgreSQL/SQLite dengan pencarian instan dan klasifikasi kategori. |
| **2** | **Validasi Koordinat Lokasi Spasial** | Memastikan titik koordinat lokasi gudang berada tepat di dalam wilayah administratif Kabupaten Pamekasan. | | | **✓** | Sering terjadi salah input koordinat (human error) sehingga lokasi usaha melompat ke luar kabupaten atau ke laut. | Sistem menerapkan algoritma BVA server-side yang otomatis menolak titik di luar koordinat Pamekasan. |
| **3** | **Manajemen Inventori & Gudang** | Mengetahui kapasitas terpasang, stok riil tembakau (*on-hand inventory*), dan ketersediaan ruang simpan gudang. | | | **✓** | Pengecekan stok dilakukan dengan menghitung fisik keranjang satu per satu di gudang, lambat dan rawan selisih. | Jumlah stok dan kapasitas gudang terbarui secara otomatis di dasbor sistem, lengkap dengan status utilitas. |
| **4** | **Pemesanan Bahan Baku (Purchase Order)** | Membuat format pesanan bahan baku daun tembakau ke petani mitra dengan rincian kuantitas dan estimasi harga. | | | **✓** | Pemesanan dilakukan lisan atau via SMS/catatan kasual tanpa ada arsip faktur digital yang terstandarisasi. | Formulir Purchase Order digital otomatis terbit, memuat rincian volume kilogram, mutu, dan estimasi biaya. |
| **5** | **Penerimaan & Konfirmasi Stok** | Mengonfirmasi kedatangan bahan baku tembakau yang masuk ke gudang penyimpanan setelah proses penjemuran. | | | **✓** | Surat tanda terima dibuat secara manual menggunakan kertas nota yang kerap tercecer. | Bukti penerimaan barang tercatat di sistem log inventori dan langsung meng-update stok *on-hand*. |
| **6** | **Kalkulasi Biaya & Formula Kapasitas** | Mengukur rasio utilitas kapasitas produksi tembakau per kecamatan secara otomatis tanpa risiko *division by zero*. | | | **✓** | Perhitungan dilakukan manual dengan kalkulator; jika kecamatan baru belum ada stok, terjadi ketidakpastian formula. | Dijalankan oleh modul *EconomicAnalysisService* dengan penanganan *zero-division fallback* dan kuorum statistik. |
| **7** | **Visualisasi Sebaran Spasial Sentra** | Memetakan klaster sentra tembakau per kecamatan secara interaktif dengan dukungan citra satelit resolusi tinggi. | | | **✓** | Peta hanya berupa gambar cetak statis di dinding kantor dinas yang tidak dapat diperbarui berkala. | WebGIS Leaflet dinamis dengan layer OSM dan Esri Satellite, pengelompokan marker, dan poligon 13 kecamatan. |
| **8** | **Konektivitas Rute & Hubungan Pelanggan** | Memfasilitasi pembeli/pedagang untuk mendapatkan petunjuk arah menuju gudang dan menghubungi pemilik UMKM. | | | **✓** | Pembeli harus bertanya arah rute secara manual atau menanyakan nomor telepon melalui perantara tengkulak. | Tersedia tombol satu-klik integrasi *Google Maps Navigation* dan tombol *Chat WhatsApp Direct Link*. |
| **9** | **Pelaporan Eksekutif & Migrasi Data** | Menyajikan ringkasan laporan cetak PDF bagi pimpinan dan mendukung migrasi data massal dari format Excel/CSV. | | | **✓** | Pembuatan laporan rekapitulasi membutuhkan waktu berhari-hari untuk merekap buku arsip dari 13 kecamatan. | Laporan eksekutif dapat diekspor ke format PDF siap cetak dalam hitungan detik, serta mendukung impor CSV massal. |

*Sumber: Hasil Olahan Analisis Data Penelitian, 2026.*

Berdasarkan Tabel 1 di atas, terlihat bahwa seluruh 9 parameter proses bisnis utama yang sebelumnya berjalan manual atau parsial berhasil mencapai pemenuhan penuh (**Full / F = 100%**) melalui penerapan sistem ERP-WebGIS.

---

#### **4.4. Hasil Pengujian Mutu Perangkat Lunak (SQA Testing Verification)**
Pengujian fungsional dan integritas sistem dieksekusi secara otomatis menggunakan *PHPUnit Test Runner*. Pengujian mencakup 24 kasus uji (*test cases*) dengan 107 parameter asersi (*assertions*):
- **Otentikasi & Keamanan (Role-Based Access Control)**: 7 tests, 21 assertions (**100% PASS**).
- **Validasi Spasial Boundary Value Analysis (BVA & EP)**: 2 tests, 12 assertions (**100% PASS**).
- **Struktur White-Box Formula Ekonomi**: 2 tests, 10 assertions (**100% PASS**).
- **REST API Filter GeoJSON Spasial**: 5 tests, 33 assertions (**100% PASS**).
- **Operasi CRUD UMKM Manajerial**: 5 tests, 19 assertions (**100% PASS**).
- **Alur Migrasi Data CSV (ETL & Rollback)**: 3 tests, 12 assertions (**100% PASS**).

Waktu eksekusi keseluruhan tes tercatat 21,62 detik tanpa ditemukan adanya kegagalan fungsi (*0 Failure, 0 Error*). Seluruh 9 cacat perangkat lunak yang sempat teridentifikasi selama proses pengembangan (*BUG-001 s/d BUG-009*) telah ditangani secara tuntas dan tercatat pada *Defect Log*.

---

### **5. PENUTUP**

#### **5.1. Kesimpulan**
Berdasarkan hasil perancangan, implementasi, dan pengujian sistem ERP terintegrasi WebGIS pada UMKM tembakau di Kabupaten Pamekasan, dapat ditarik beberapa kesimpulan utama:
1. Penerapan sistem ERP berbasis web berhasil mentransformasikan tata kelola rantai pasok tembakau yang sebelumnya manual dan terfragmentasi menjadi satu ekosistem digital terpadu, mencakup modul inventori gudang, pengadaan bahan baku (*purchasing*), serta pelaporan manajerial eksekutif.
2. Integrasi WebGIS interaktif dengan dukungan *Boundary Value Analysis* (BVA) terbukti efektif mengeliminasi kesalahan input data koordinat spasial di luar batas Kabupaten Pamekasan (Lintang -7.35 s/d -6.95 dan Bujur 103.35 s/d 103.65), sekaligus menyediakan fitur navigasi rute Google Maps dan komunikasi langsung WhatsApp ke pengelola UMKM.
3. Analisis Kesenjangan (*GAP Analysis*) dengan metode NPF membuktikan bahwa sistem ERP-WebGIS mencapai tingkat pemenuhan penuh (*Full Fulfillment*) sebesar 100% pada seluruh 9 proses bisnis utama rantai pasok agribisnis tembakau.
4. Kualitas perangkat lunak terverifikasi secara ilmiah melalui penerapan standar SQA ISO/IEC/IEEE 29119 dengan hasil 24 rangkaian *automated testing* lulus 100% dan seluruh 9 cacat (*defects*) berhasil diselesaikan (*100% Remediated*).

#### **5.2. Saran dan Rekomendasi Penelitian Lanjutan**
Untuk pengembangan sistem pada fase berikutnya, disarankan:
1. **Integrasi Sensor IoT Gudang**: Menambahkan integrasi sensor nirkabel berbasis *Internet of Things* (IoT) pada gudang penyimpanan tembakau untuk memantau suhu dan kelembaban udara (*humidity*) secara *real-time* guna mencegah jamur pada daun tembakau kering.
2. **Pelatihan Literasi Digital Operator Desa**: Menyelenggarakan program pendampingan teknis secara luring bagi kelompok tani dan operator BUMDes di wilayah pedesaan Pamekasan bagian utara terkait penggunaan fitur penandaan lokasi GPS melalui gawai pintar.
3. **Penerapan Model Pentahelix**: Memperkuat kemitraan rantai pasok melalui kolaborasi *Pentahelix* yang melibatkan akademisi, asosiasi petani tembakau, industri rokok nasional, perbankan/fintech, dan Dinas Koperasi & UMKM Kabupaten Pamekasan demi keberlanjutan hilirisasi produk tembakau Madura.

---

### **DAFTAR PUSTAKA**

Aditya, A., & Hadi Santuso, Y. E. (2022). Business process analysis and implementation of Odoo ERP in sales, purchasing and Accounting Systems (case study: CV Mitra Perkasa). *Procedia of Social Sciences and Humanities*, 3, 358–365. https://doi.org/10.21070/pssh.v3i.151

Amaliyah, E. D. E., & Nugroho, B. S. (2022). Improving Personal Financial Management through Financial Technology, Financial Capability, and Spiritual Intelligence as Intervening Variable. *Admisi Dan Bisnis*, 23(1), 57–70. https://doi.org/10.32497/ab.v23i1.3477

Darmawan, A. K., & Fanani, Z. (2022). Pengembangan Sistem Informasi Berbasis Tata Kelola dan Manajemen Proyek Terstruktur untuk Optimalisasi Potensi Daerah. *Jurnal RESTI (Rekayasa Sistem dan Teknologi Informasi)*, 6(3), 415–423. https://doi.org/10.29207/resti.v6i3.4088

Marchewka, J. T. (2015). *Information Technology Project Management: Providing Measurable Organizational Value* (5th ed.). John Wiley & Sons.

Nugroho, B. S. (2021). Human Resource Performance Improvement Model Design (Case Study in the Biggest Central Java Automotive Company). *Admisi Dan Bisnis*, 22(1), 47–60. https://doi.org/10.32497/ab.v22i1.2489

Nugroho, B. S., Ardiana, G. N., Ramadhani, G. N., Pandugo, K. I., Gintings, M. J. B., & Akbar, R. M. F. (2023). Reengineering Proses Bisnis UD. Multi Snack Menggunakan Enterprise Resource Planning Odoo. *Bisman (Bisnis Dan Manajemen): The Journal of Business and Management*, 6(1), 190–203. https://doi.org/10.37112/bisman.v6i1.2494

Nugroho, B. S., Firdaus, A. S., Pamade, N. W., Varma, N. V., Amiyati, N. R., & Rohman, T. (2023). Business Process Reengineering of CV Dext Jaya Sentosa to Support the Supply Chain Management. *Admisi Dan Bisnis*, 24(1), 23–42. https://jurnal.polines.ac.id/index.php/admisi

Nugroho, B. S., Mafaza, A. P., Mohammad, Dzalfajri, M. A., Rizkiyanto, R., & Setianingrum, S. W. (2023). Implementation of Supply Chain Management Using ERP Odoo (Case Study of PT Mas Arya Indonesia). *International Journal of Economics, Vocational, and Social Sciences (IJEVSS)*, 2(2), 261–274.

Nugroho, B. S., Purnamasari, E., Prahara, T., & Ayuwardani, M. (2022). Penerapan Strategi Kaizen Production dan Integrated Digital Marketing Untuk Meningkatkan Produktivitas dan Penjualan Tanaman Hias. *Surya Abdimas*, 6(2), 361–370. https://doi.org/10.37729/abdimas.v6i2.1736

Nugroho, B. S., Utami, H., Ayuwardani, M., & Adie Setyawan, N. (2022). Knowledge Sharing and Employee Performance: the mediating role of Organizational Learning. *Admisi Dan Bisnis*, 23(2), 155–164. https://doi.org/10.32497/ab.v23i2.3786

Project Management Institute. (2021). *A Guide to the Project Management Body of Knowledge (PMBOK Guide)* (7th ed.). Project Management Institute.

Schwalbe, K. (2019). *Information Technology Project Management* (9th ed.). Cengage Learning.

Suroija, N., Asrori, M., Nugroho, B. S., & Sulistiyani, E. (2022). Pentahelix Model For The Development Of Ecotourism Karang Ginger Beach Punjulharjo Village, Rembang Regency. *International Journal of Education, Business and Economics Research (IJEBER)*, 2(6), 134–145.
