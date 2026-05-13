# Enamora Code Structure (Ringkasan Lengkap)

Dokumen ini menjelaskan struktur coding utama project Enamora Laravel: fungsi controller, model/database, service, route, dan view.

## 1. Gambaran Arsitektur

Aplikasi dibagi menjadi 3 area utama:

1. Public/Customer: booking dari website + pembayaran termin.
2. Admin CEO (`/admin`): otoritas penuh (validasi status pesanan, data customer, paket, laporan).
3. Admin2 Sekretaris (`/admin2`): operasional harian (lihat data, laporan, customer follow-up, read-only untuk status pesanan).

Alur inti:

1. Customer isi form booking di homepage.
2. Sistem membuat data `customer`, `booking`, `booking_detail`, `payment_terms`, dan notifikasi `pesanan_website`.
3. Customer melakukan pembayaran termin (QRIS/transfer) melalui modul payment.
4. Admin/CEO memvalidasi dan mengelola status booking.
5. Admin2 membantu monitoring termin dan kirim link pembayaran/follow-up ke customer.

## 2. Struktur Folder Penting

- `app/Http/Controllers/`: semua logic request HTTP.
- `app/Models/`: representasi tabel database + relasi Eloquent.
- `app/Services/`: business/service layer (payment gateway, QRIS, payment term schedule, WhatsApp).
- `resources/views/`: Blade template untuk UI.
- `routes/web.php`: peta endpoint web.
- `database/migrations/`: definisi skema tabel.

## 3. Routing & Akses Role

File: `routes/web.php`

### Public
- `/` -> `HomeController@index`
- `/booking` -> `BookingController@store`
- `/payment/*` -> `PaymentController` (show, initiate, checkout, verify, callback)

### Auth
- `/login`, `/logout` -> `AuthController`

### Admin CEO (`prefix: admin`, middleware `auth + role:CEO`)
- Dashboard, Paket (CRUD), Data Pesanan, Pesanan Website, Customer, Laporan, Import.

### Admin2 (`prefix: admin2`, middleware `auth + role:ADMIN,CEO`)
- Dashboard, Pegawai (CRUD), Freelance (CRUD), Pesanan (read-only), Customer (monitor termin + follow-up), Laporan.

Catatan:
- Middleware role sudah mendukung multi-role (contoh `role:ADMIN,CEO`).

## 4. Controller Map (Siapa melakukan apa)

## Core/Public Controllers

### `HomeController`
- Menampilkan halaman utama customer (form booking).

### `BookingController`
- Menerima submit booking dari website.
- Validasi input berdasarkan tipe paket.
- Menyimpan:
  - `customer`
  - `booking`
  - `booking_detail`
  - `payment_terms` (via `PaymentTermService`)
  - `pesanan_website` (antrian validasi admin)
- Mengirim notifikasi ke role CEO/ADMIN.
- Redirect customer ke halaman pembayaran booking.

### `PaymentController`
- `show()`: tampilkan progress pembayaran + jadwal termin + tombol bayar termin aktif.
- `initiate()`: inisiasi transaksi payment per termin.
- `checkout()`: tampilkan QRIS/transfer untuk transaksi terpilih.
- `verify()` / `callback()`: sinkron status pembayaran ke term/booking.
- `testMarkSuccess()`: helper sandbox/testing.
- Build template pesan WhatsApp konfirmasi pembayaran.

### `AuthController`
- Login/logout user dashboard admin.

## Admin CEO Controllers (`app/Http/Controllers/Admin`)

### `DashboardController`
- Ringkasan statistik utama panel CEO.

### `PaketController`
- CRUD paket foto/video.

### `BookingAdminController`
- Daftar/Detail data pesanan utama.
- Filter status/nama/bulan.
- Update status acara + status pembayaran.
- Hapus booking.
- Kirim notifikasi ke customer saat status berubah.

### `DataPesananController` (Pesanan Website)
- Menampilkan antrian pesanan dari form website.
- Validasi pesanan: booked/ditolak.
- Sinkron status ke tabel `booking`.
- Menandai item `pesanan_website` selesai diproses.

### `CustomerController`
- Daftar customer + pencarian.
- Detail customer + histori booking.
- Menampilkan paket yang diambil, progress termin, detail termin, tombol follow-up termin 2.

### `LaporanController`
- Laporan keuangan CEO (filter periodik + export PDF).

### `ImportController`
- Import data dari sumber eksternal/file.

## Admin2 Controllers (`app/Http/Controllers/Admin2`)

### `DashboardAdmin2Controller`
- Ringkasan operasional sekretaris.

### `PegawaiController`
- CRUD data pegawai internal.

### `FreelanceController`
- CRUD data freelance.

### `PesananAdmin2Controller`
- Menampilkan data pesanan untuk monitoring.
- Endpoint proses status sekarang dikunci (read-only) agar admin2 tidak bisa ubah status booking.

### `CustomerAdmin2Controller`
- List & detail customer versi admin2.
- Menampilkan progress termin, paket, follow-up termin.
- `terms()`: halaman khusus lihat termin booking (read-only) + kirim link pembayaran ke customer.

### `LaporanAdmin2Controller`
- Laporan admin2 + export PDF.
- Menyediakan metrik:
  - Total pendapatan status selesai
  - Total pendapatan DP
  - Total semua estimasi

## 5. Model & Tabel Database (fungsi per entitas)

File: `app/Models/*`

### `User` (tabel `users`)
- Akun login dashboard.
- Menyimpan role (`CEO`, `ADMIN`, dst).

### `Customer` (tabel `customer`)
- Data klien (nama, no WA, alamat).
- Relasi: `hasMany(Booking)`.

### `Booking` (tabel `booking`)
- Entitas utama transaksi booking.
- Menyimpan tanggal acara, total, status pembayaran, status job, status booking.
- Relasi:
  - `belongsTo(Customer)`
  - `hasMany(BookingDetail)`
  - `hasMany(PaymentTerm)`
  - `hasMany(Notification)`
  - `hasMany(PaymentLog)`
- Helper penting:
  - cek full paid / sisa bayar
  - next due term
  - status badge

### `BookingDetail` (tabel `booking_detail`)
- Pivot booking ke paket.
- Menyimpan snapshot harga paket saat booking.

### `Paket` (tabel `paket`)
- Master produk paket.
- Harga, nama paket, tipe paket (`wedding_only`, `all_in`, dll).

### `PaymentTerm` (tabel `payment_terms`)
- Jadwal pembayaran per termin (T1/T2/T3).
- Menyimpan persen termin, nominal, due date, payment_status.
- Method helper: `isPaid`, `isOverdue`, `markAsPaid`.

### `PaymentLog` (tabel `payment_logs`)
- Log transaksi payment gateway (transaction_id, amount, method, status).

### `PesananWebsite` (tabel `pesanan_website`)
- Queue awal booking dari form publik untuk validasi CEO.
- Menyimpan `booking_id` agar sinkron ke data booking utama.

### `Notification` (tabel `notifications`)
- Notifikasi sistem (dashboard/WA) ke role/admin/customer.

### `Pegawai` (tabel `pegawai`)
- Data staff internal.

### `Freelance` (tabel `freelance`)
- Data crew freelance.

## 6. Service Layer (logic reusable)

File: `app/Services/*`

### `PaymentGatewayService`
- Abstraksi integrasi pembayaran.
- Inisiasi transaksi + verifikasi status.

### `PaymentTermService`
- Generate jadwal termin otomatis saat booking dibuat.
- Logika pembagian persentase termin berdasarkan tipe paket.

### `QRISService`
- Generate/resolve QRIS image sesuai nominal termin.
- Prioritas pakai real QRIS file (misal `public/assets/qris/qris_{amount}.png`) jika tersedia.

### `WhatsAppService`
- Utility pengiriman/format pesan WhatsApp (jika dipakai untuk channel WA).

## 7. View Layer (UI utama)

### Customer Views (`resources/views/customer`)
- `home.blade.php`: landing + form booking.
- `payments/index.blade.php`: status pembayaran, jadwal termin, aksi bayar, konfirmasi WA.
- `payments/checkout.blade.php`: halaman pembayaran QRIS/transfer.
- `payments/done.blade.php`: status lunas + tombol konfirmasi WA.

### Admin CEO Views (`resources/views/admin`)
- `dashboard/`: ringkasan panel CEO.
- `paket/`: CRUD paket.
- `pesanan/`: data pesanan utama + detail status.
- `pesanan-website/`: inbox pesanan website untuk validasi.
- `customer/`: list/detail customer + termin monitoring.
- `laporan/`: laporan keuangan + export.

### Admin2 Views (`resources/views/admin2`)
- `dashboard.blade.php`: ringkasan sekretaris.
- `pegawai/*`, `freelance/*`: operasional SDM.
- `pesanan/index.blade.php`: monitoring pesanan read-only.
- `customer/index.blade.php`: list customer + progress termin.
- `customer/show.blade.php`: detail customer + paket + follow-up.
- `customer/terms.blade.php`: lihat termin read-only + kirim link pembayaran.
- `laporan/*`: ringkasan keuangan admin2 + PDF.

## 8. Relasi Data Utama (mindset cepat)

1. `customer` 1..N `booking`
2. `booking` 1..N `booking_detail` -> N..1 `paket`
3. `booking` 1..N `payment_terms`
4. `booking` 1..N `payment_logs`
5. `pesanan_website` -> refer ke `booking_id` saat validasi

## 9. Use Case Penting (end-to-end)

### Booking baru dari customer
1. Customer isi form -> `BookingController@store`
2. Sistem generate booking + term schedule
3. Masuk `pesanan_website` untuk approval
4. Admin/CEO validasi -> status booking update

### Pembayaran bertahap termin
1. Customer buka `payment.show/{booking_id}`
2. Bayar termin aktif -> `payment.initiate`
3. Checkout QRIS/transfer -> verify/callback
4. `payment_terms` update `paid`
5. Jika semua lunas -> tampil halaman done

### Follow-up termin oleh admin2
1. Admin2 buka customer detail
2. Cek termin 2 belum paid
3. Klik follow-up WA atau kirim link pembayaran
4. Customer lanjut bayar via link payment show

## 10. Catatan Pengembangan

- Semua endpoint status booking strategis tetap di CEO.
- Admin2 difokuskan untuk monitoring + operasional follow-up.
- Jika mau audit lebih kuat, tambahkan log aktivitas perubahan status per user.
- Jika mau UX lebih cepat, bisa tambah tombol `Copy Payment Link` di halaman `admin2.customer.terms`.
