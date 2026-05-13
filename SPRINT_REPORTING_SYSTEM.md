# Sprint Reporting System - Enamorapic

## 1. Ringkasan
Dokumen ini mengidentifikasi seluruh jenis laporan yang ada di web Enamorapic, fitur tiap laporan, alur proses bisnis, alur implementasi kode, serta teknologi export PDF yang digunakan.

## 2. Daftar Jenis Laporan yang Ada

### A. Laporan Keuangan CEO
- Modul: Admin CEO
- Route halaman: /admin/laporan
- Route export PDF: /admin/laporan/pdf
- Controller: App/Http/Controllers/Admin/LaporanController.php
- View layar: resources/views/admin/laporan/index.blade.php
- View PDF: resources/views/admin/laporan/pdf.blade.php

Fungsi utama:
- Rekap transaksi booking berdasarkan rentang tanggal.
- Filter status pembayaran: Semua, Lunas, DP.
- Menampilkan total pendapatan bersih (booking dengan status acara selain Batal).
- Export laporan ke PDF.

Data yang ditampilkan:
- Tanggal transaksi booking.
- Nama client.
- Tanggal acara.
- Status pembayaran.
- Status acara.
- Total transaksi.
- Total pendapatan bersih di footer.

### B. Laporan Keuangan Sekretaris (Admin2)
- Modul: Admin2
- Route halaman: /admin2/laporan
- Route export PDF: /admin2/laporan/export-pdf
- Controller: App/Http/Controllers/Admin2/LaporanAdmin2Controller.php
- View layar: resources/views/admin2/laporan/index.blade.php
- View PDF: resources/views/admin2/laporan/pdf.blade.php

Fungsi utama:
- Rekap booking berdasarkan bulan dan tahun.
- Ringkasan 3 metrik:
  - Total pendapatan dari booking status Selesai.
  - Total pendapatan DP (akumulasi termin yang sudah paid).
  - Total estimasi semua booking non-Batal.
- Menampilkan tabel detail booking + paket.
- Export PDF landscape.

Data yang ditampilkan:
- Tanggal booking.
- Nama client.
- Nama paket.
- Tanggal acara.
- Status pembayaran.
- Status acara.
- Total transaksi.
- 3 total ringkasan di bagian bawah.

### C. Laporan Operasional Dashboard CEO
- Route: /admin
- Controller: App/Http/Controllers/Admin/DashboardController.php
- View: resources/views/admin/dashboard/index.blade.php

Catatan: Ini bukan laporan cetak formal, tetapi laporan visual operasional berbasis kartu dan grafik.

Isi laporan operasional:
- Booking bulan ini.
- Total pending.
- Total selesai.
- Revenue bulan ini.
- Trend revenue 6 bulan (line chart).
- Performa pendapatan tahunan per bulan (bar chart).
- Komposisi status pembayaran Lunas vs DP (doughnut chart).
- Daftar paket terpopuler.
- Daftar booking terbaru.

### D. Laporan Operasional Dashboard Sekretaris
- Route: /admin2
- Controller: App/Http/Controllers/Admin2/DashboardAdmin2Controller.php
- View: resources/views/admin2/dashboard.blade.php

Isi laporan operasional:
- Total freelance.
- Total pegawai.
- Jumlah pesanan website pending.
- Booking bulan ini.
- Tabel booking terbaru.

## 3. Fitur Laporan per Modul

### Fitur Laporan Keuangan CEO
1. Filter tanggal dari dan sampai.
2. Filter status bayar.
3. Tabel rekap transaksi.
4. Penandaan data Batal dengan visual redup/coret.
5. Hitung total pendapatan bersih.
6. Export PDF dan stream di browser.

### Fitur Laporan Keuangan Admin2
1. Filter bulan.
2. Filter tahun.
3. Ringkasan 3 jenis total pendapatan.
4. Tabel detail termasuk paket.
5. Export PDF dan download file.
6. Format PDF landscape untuk tabel lebih lebar.

### Fitur Laporan Dashboard
1. KPI cards real-time.
2. Grafik tren dan distribusi.
3. Daftar data terbaru untuk monitoring cepat.

## 4. Alur Bisnis Laporan

### Alur Laporan Keuangan CEO
1. User CEO membuka halaman laporan.
2. Sistem membaca parameter filter dari query string.
3. Sistem query tabel booking sesuai rentang tanggal.
4. Jika status bayar dipilih, query ditambah filter status_pembayaran.
5. Data diurutkan berdasarkan tanggal booking.
6. Sistem menghitung totalOmzet dengan mengecualikan job_status Batal.
7. Data ditampilkan ke halaman.
8. Jika klik PDF, sistem render view PDF dan stream hasil PDF.

### Alur Laporan Keuangan Admin2
1. User Admin2 membuka halaman laporan.
2. Sistem mengambil data booking beserta relasi paket dan payment terms.
3. Sistem filter berdasarkan bulan dan tahun acara.
4. Sistem hitung:
   - totalPendapatan: sum total_transaksi untuk status acara Selesai.
   - totalPendapatanDp: sum paid_amount dari payment_terms untuk booking status DP.
   - totalEstimasi: sum total_transaksi untuk status acara non-Batal.
5. Data ditampilkan pada ringkasan dan tabel.
6. Jika klik Export PDF, sistem render PDF landscape dan download.

## 5. Alur Kode (Coding Flow)

### Laporan CEO
Sumber utama:
- App/Http/Controllers/Admin/LaporanController.php

Flow method index:
1. Ambil request tgl_dari, tgl_sampai, status_bayar.
2. Bangun query DB::table(booking) dengan whereBetween tgl_booking.
3. Tambah where status_pembayaran jika filter bukan All.
4. Jalankan query orderBy tgl_booking.
5. Hitung totalOmzet dari collection hasil query dengan filter job_status != Batal.
6. Return ke view admin/laporan/index.

Flow method exportPdf:
1. Ulangi proses filter yang sama agar konsisten dengan layar.
2. Render view PDF admin/laporan/pdf menggunakan facade Pdf.
3. Set kertas A4 portrait.
4. Return stream PDF.

### Laporan Admin2
Sumber utama:
- App/Http/Controllers/Admin2/LaporanAdmin2Controller.php

Flow method index:
1. Query model Booking dengan eager load details.paket dan paymentTerms.
2. Filter month/year dari tgl_acara.
3. Hitung totalPendapatan, totalPendapatanDp, totalEstimasi.
4. Return ke view admin2/laporan/index.

Flow method exportPdf:
1. Ulangi query + filter agar hasil PDF sama dengan layar.
2. Hitung tiga metrik ringkasan.
3. Render view PDF admin2/laporan/pdf.
4. Set kertas A4 landscape.
5. Return download PDF.

## 6. Teknologi Export PDF dan Vendor

### Engine PDF yang dipakai
- Package Laravel facade: barryvdh/laravel-dompdf
- Tercatat di composer: require barryvdh/laravel-dompdf ^3.1
- Namespace dipakai di controller laporan:
  - Barryvdh/DomPDF/Facade/Pdf

Kesimpulan:
- Ya, sistem laporan PDF menggunakan DOMPDF melalui wrapper Laravel dari Barryvdh.
- Tidak ditemukan penggunaan vendor PDF lain seperti Snappy atau TCPDF untuk modul laporan ini.

## 7. Dependensi Lain yang Relevan (Bukan Engine Laporan PDF)
- simplesoftwareio/simple-qrcode: untuk QR pembayaran.
- midtrans/midtrans-php: untuk integrasi payment gateway.

Dependensi ini bukan engine laporan, tetapi terkait modul transaksi yang datanya masuk ke laporan.

## 8. Catatan Implementasi dan Rekomendasi Sprint

### Kekuatan saat ini
1. Laporan CEO dan Admin2 sudah terpisah sesuai kebutuhan peran.
2. Kedua modul sudah mendukung export PDF.
3. Laporan Admin2 sudah memperhitungkan pendapatan DP real dari termin yang dibayar.

### Rekomendasi sprint berikutnya
1. Tambah export Excel/CSV untuk analisis lanjutan.
2. Tambah summary per paket, per fotografer, dan per sumber order.
3. Tambah grafik tren langsung di halaman laporan (bukan hanya dashboard).
4. Samakan definisi pendapatan antara laporan CEO dan Admin2 agar konsisten.
5. Tambah nomor dokumen laporan dan metadata filter di header PDF.

## 9. Referensi Kode
- routes/web.php
- app/Http/Controllers/Admin/LaporanController.php
- app/Http/Controllers/Admin2/LaporanAdmin2Controller.php
- app/Http/Controllers/Admin/DashboardController.php
- app/Http/Controllers/Admin2/DashboardAdmin2Controller.php
- resources/views/admin/laporan/index.blade.php
- resources/views/admin/laporan/pdf.blade.php
- resources/views/admin2/laporan/index.blade.php
- resources/views/admin2/laporan/pdf.blade.php
- resources/views/admin/dashboard/index.blade.php
- resources/views/admin2/dashboard.blade.php
- composer.json

## 10. Pembagian Tugas 3 Orang (Sprint Reporting System)

Tujuan pembagian ini:
- Tiap anggota terlihat mengerjakan bagian berbeda.
- File ownership jelas (siapa pegang file apa).
- Integrasi tetap aman karena minim konflik edit file.

### Anggota Haykal - Modul Laporan CEO
Fokus kerja:
- Menangani laporan keuangan untuk role CEO (filter tanggal, status bayar, dan PDF).

File yang dikerjakan:
- app/Http/Controllers/Admin/LaporanController.php
- resources/views/admin/laporan/index.blade.php
- resources/views/admin/laporan/pdf.blade.php
- routes/web.php (hanya bagian route admin laporan)

Output sprint Anggota Haykal:
1. Filter laporan CEO berjalan benar.
2. Total pendapatan bersih sesuai aturan non-Batal.
3. Export PDF CEO portrait berjalan.

### Anggota Dipam - Modul Laporan Admin2 (Sekretaris)
Fokus kerja:
- Menangani laporan keuangan operasional Admin2 (bulan/tahun, 3 ringkasan, PDF).

File yang dikerjakan:
- app/Http/Controllers/Admin2/LaporanAdmin2Controller.php
- resources/views/admin2/laporan/index.blade.php
- resources/views/admin2/laporan/pdf.blade.php
- routes/web.php (hanya bagian route admin2 laporan)

Output sprint Anggota Dipam:
1. Filter bulan/tahun berjalan benar.
2. Total pendapatan selesai, DP, dan estimasi valid.
3. Export PDF Admin2 landscape berjalan.

### Anggota Imel - Dashboard Reporting dan Dokumentasi Sprint
Fokus kerja:
- Menangani laporan visual (dashboard) dan dokumentasi final sprint.

File yang dikerjakan:
- app/Http/Controllers/Admin/DashboardController.php
- app/Http/Controllers/Admin2/DashboardAdmin2Controller.php
- resources/views/admin/dashboard/index.blade.php
- resources/views/admin2/dashboard.blade.php
- SPRINT_REPORTING_SYSTEM.md

Output sprint Anggota Imel:
1. KPI card dan grafik dashboard valid.
2. Data ringkasan dashboard sinkron dengan booking.
3. Dokumentasi sprint selesai dan siap presentasi.

## 11. Rencana Kerja Sprint (Contoh Pembagian Hari)

### Hari 1
1. Anggota A: rapikan laporan CEO + validasi query filter.
2. Anggota B: rapikan laporan Admin2 + validasi perhitungan DP.
3. Anggota C: validasi dashboard KPI + sinkron data.

### Hari 2
1. Anggota A: finalisasi PDF CEO.
2. Anggota B: finalisasi PDF Admin2.
3. Anggota C: finalisasi chart dashboard + dokumentasi.

### Hari 3
1. Integrasi semua branch.
2. Uji end-to-end laporan (halaman + PDF).
3. Final revisi dan persiapan presentasi.

## 12. Format Pengakuan Kontribusi (Untuk Laporan Kelompok)

Contoh penulisan di laporan kampus:
1. Anggota A bertanggung jawab pada modul Laporan CEO (controller, view, pdf).
2. Anggota B bertanggung jawab pada modul Laporan Admin2 (controller, view, pdf).
3. Anggota C bertanggung jawab pada Dashboard Reporting dan dokumentasi sprint.

Dengan format ini, pembagian tugas terlihat jelas dan realistis karena setiap anggota memegang file inti yang berbeda.
