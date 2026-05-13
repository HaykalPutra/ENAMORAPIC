BAB I – PROJECT OVERVIEW

1.1 Latar Belakang Proyek
Enamorapic bergerak di bidang jasa foto/video dengan variasi paket dan termin pembayaran. Sebelum sistem dibuat, data booking, pembayaran, dan follow up tersebar di chat serta catatan manual. Hal ini membuat rekap laporan lambat, status pembayaran tidak sinkron, dan potensi kehilangan detail transaksi. Karena itu dibangun aplikasi web terpadu untuk mengelola booking, pembayaran bertahap, laporan, serta monitoring oleh CEO/Admin dan Sekretaris.

1.2 Problem Statement
- Data customer dan booking belum terdokumentasi rapi sehingga menyulitkan pencarian.
- Follow up pembayaran dilakukan manual dan sering terlambat.
- Laporan pendapatan tidak real-time dan rawan salah hitung.
- Tidak ada notifikasi terpusat untuk admin/sekretaris saat ada booking atau pembayaran baru.
- Proses validasi booking dan pembaruan status pembayaran belum terstandar.

1.3 Tujuan Proyek
- Menyediakan alur booking dan pembayaran yang terstruktur dan terdokumentasi.
- Mempermudah follow up pembayaran termin melalui dashboard.
- Menyediakan laporan keuangan real-time dengan filter dan export.
- Menyediakan dashboard role-based (CEO, Admin, Sekretaris).
- Menjaga konsistensi data customer, booking, dan pembayaran.

1.4 Ruang Lingkup (Scope)
- Booking paket (pengisian biodata, jadwal acara, lokasi).
- Pembayaran DP dan pelunasan (multi-termin).
- Dashboard monitoring dan follow up pembayaran.
- Laporan keuangan dengan filter dan export PDF/Excel.
- Manajemen data paket, customer, pegawai, freelance.
- Notifikasi internal (dashboard) dan WA follow up.
Di luar scope:
- Integrasi CRM pihak ketiga.
- Aplikasi mobile penuh (native) di versi ini.
- Modul akuntansi lanjutan (pajak, invoice vendor).

1.5 Stakeholder Proyek
- CEO/Owner: keputusan bisnis dan validasi booking.
- Admin/Staff: monitoring transaksi, validasi, dan operasional.
- Sekretaris: follow up pembayaran, akses data read-only.
- Customer: pemesan layanan dan pembayaran termin.
- Tim Developer dan QA: implementasi dan pengujian.

1.6 Struktur Tim (Role-Based)
- Project Manager: mengelola timeline, scope, koordinasi lintas tim.
- Scrum Master: menjaga proses agile berjalan dan mengatasi blockers.
- Product Owner: menentukan prioritas fitur dan kebutuhan bisnis.
- Developer: implementasi backend dan frontend.
- QA Engineer: pengujian fungsional dan kualitas.

1.7 Metodologi Pengembangan
Agile – Scrum Framework
- Sprint: iterasi 1-2 minggu.
- Backlog: daftar kebutuhan prioritas.
- Sprint Planning: menentukan scope sprint.
- Daily Standup: update progres dan kendala.
- Sprint Review: demo hasil increment.
- Sprint Retrospective: evaluasi proses.


BAB II – SOFTWARE REQUIREMENT SPECIFICATION (SRS)

2.1 Deskripsi Umum Sistem
Sistem Enamorapic adalah aplikasi web manajemen booking dan pembayaran paket foto/video. Sistem memiliki portal customer untuk booking dan pembayaran, serta dashboard admin/CEO dan sekretaris untuk monitoring dan laporan.

2.2 Kebutuhan Fungsional
- F01: Customer mengisi form booking dan memilih paket.
- F02: Sistem menyimpan data customer dan booking ke database.
- F03: Sistem membuat jadwal pembayaran termin otomatis.
- F04: Customer melakukan pembayaran DP/pelunasan via gateway.
- F05: Sistem memperbarui status pembayaran otomatis.
- F06: Admin/CEO memvalidasi booking dan melihat dashboard.
- F07: Sekretaris melakukan follow up via WhatsApp dari detail customer.
- F08: Laporan keuangan dapat difilter per tanggal/bulan/status.
- F09: Laporan dapat diexport ke PDF dan Excel.
- F10: Notifikasi dashboard dan WA untuk event penting.

2.3 Kebutuhan Non-Fungsional
- Performa: waktu respon halaman utama < 3 detik.
- Keamanan: role-based access, CSRF protection, input validation.
- Usability: UI konsisten, mudah dipahami, responsif.
- Availability: sistem stabil dan dapat diakses jam operasional.
- Maintainability: struktur MVC dan service layer terpisah.

2.4 Use Case Diagram
[TARUH SS DIAGRAM USE CASE DI SINI]

2.5 Use Case Description
- UC01 Booking Paket: customer memilih paket, isi biodata, submit booking.
- UC02 Pembayaran DP: customer memilih termin pertama dan bayar.
- UC03 Pelunasan: customer melunasi sisa tagihan di termin berikutnya.
- UC04 Validasi Booking: admin/CEO memeriksa dan menyetujui booking.
- UC05 Follow Up: sekretaris mengirim pesan WA follow up dari detail customer.
- UC06 Export Laporan: admin export laporan PDF/Excel sesuai filter.

2.6 Activity Diagram
[TARUH SS ACTIVITY DI SINI]

2.6.1 Alur Proses Utama 
Alur ini menjelaskan dari booking hingga pelunasan secara step-by-step:

A. Booking Baru oleh Customer
1) Customer memilih paket di halaman publik.
2) Customer mengisi form biodata (nama, WA, tanggal acara, lokasi, status bayar).
3) Sistem memvalidasi input.
4) Sistem membuat data Customer (jika belum ada) dan Booking baru.
5) Sistem membuat Payment Terms (termin pembayaran) sesuai paket.
6) Status pembayaran awal diset DP (atau Lunas bila dipilih).
7) Sistem membuat notifikasi dashboard untuk CEO/Admin/Sekretaris.
8) Customer diarahkan ke halaman pembayaran (payment.show).

B. Pembayaran DP (Termin Pertama)
1) Customer memilih termin pertama dan metode pembayaran.
2) Sistem membuat transaksi ke gateway (Midtrans).
3) Setelah bayar sukses, callback/verify menandai termin 1 sebagai paid.
4) Sistem menghitung ulang total paid dan sisa tagihan.
5) Status pembayaran Booking tetap DP jika belum lunas.

C. Follow Up oleh Sekretaris
1) Sekretaris membuka detail customer di dashboard.
2) Sistem menampilkan: status pembayaran, sisa tagihan, termin yang belum dibayar.
3) Sekretaris klik tombol Follow Up WA.
4) Sistem mengisi template pesan berisi link pembayaran termin berikutnya.
5) Pesan WA dikirim ke customer untuk pelunasan.

D. Pelunasan (Termin Berikutnya)
1) Customer membuka link pembayaran dari WA.
2) Customer melakukan pembayaran termin berikutnya.
3) Sistem menandai termin sebagai paid setelah callback/verify.
4) Jika semua termin paid, status pembayaran Booking diubah menjadi Lunas.
5) Sistem membuat notifikasi bahwa pembayaran lunas.

E. Kondisi Akhir
1) Jika status Lunas, booking dianggap selesai secara pembayaran.
2) Data laporan otomatis menampilkan transaksi dengan status Lunas.

2.7 User Stories (Scrum-based)
- Sebagai customer, saya ingin booking paket agar bisa memesan layanan.
- Sebagai customer, saya ingin bayar DP agar booking diproses.
- Sebagai customer, saya ingin link pembayaran termin agar bisa melunasi.
- Sebagai sekretaris, saya ingin melihat status DP agar bisa follow up.
- Sebagai admin, saya ingin laporan agar bisa evaluasi pendapatan.
- Sebagai CEO, saya ingin validasi booking agar kontrol kualitas terjaga.

2.8 Product Backlog (Ringkas)
- Booking & Payment Flow (High)
- Dashboard Statistik (High)
- Laporan + Export (High)
- Follow Up WA (Medium)
- Manajemen pegawai/freelance (Medium)
- Notifikasi real-time (Low)


BAB III – SYSTEM DESIGN & ARCHITECTURE

3.1 Arsitektur Sistem
Client-Server berbasis Laravel MVC. Client mengakses UI (Blade) dan mengirim request ke server. Server memproses logic bisnis, lalu menyimpan data ke database MySQL.

3.2 Desain Database (ERD)
Entitas utama: Customer, Booking, BookingDetail, PaymentTerms, PaymentLog, Paket, User, Notification.
[TARUH SS ERD DI SINI]

3.3 Class Diagram
Kelas utama: BookingController, PaymentController, LaporanController, CustomerController, WhatsAppService, Notification.
[TARUH SS CLASS DI SINI]

3.4 Technology Stack
- Backend: Laravel 10 (PHP 8.x)
- Frontend: Blade + Bootstrap 5
- Database: MySQL
- Payment: Midtrans
- Notification: WhatsApp Service (queue/cron)

3.5 Perancangan API (jika ada)
- Webhook Midtrans untuk callback pembayaran.
- Endpoint verifikasi pembayaran.
- Endpoint export laporan.


BAB IV – UI/UX DESIGN (BLUEPRINT)

4.1 Konsep Desain UI/UX
- Dashboard modern dengan kartu statistik.
- Warna utama navy dengan aksen gold/biru.
- Fokus pada keterbacaan data dan akses cepat.
- Navigasi sidebar untuk role-based menu.

4.2 Wireframe
[TARUH SS WIREFRAME DI SINI]


BAB V – IMPLEMENTATION (DEVELOPMENT)

5.1 Setup Environment
- PHP 8.x + Composer
- MySQL
- Node.js + NPM untuk asset build
- Konfigurasi .env (DB, Midtrans, WA)

5.2 Struktur Project (Folder & Architecture Code)
- app/Http/Controllers: logic request
- app/Models: ORM database
- app/Services: logic bisnis khusus (payment, WA)
- resources/views: tampilan UI
- routes/web.php: routing aplikasi

5.3 Implementasi Modul
- Authentication: login, session, role-based access.
- Dashboard: statistik, kalender, follow up pembayaran.
- Core Booking: create booking, payment terms, status pembayaran.
- Laporan: filter, export PDF/Excel.
- Customer Detail: info DP/Lunas, link payment follow up.

5.4 Integrasi Sistem
- Midtrans untuk pembayaran.
- Notifikasi WA untuk admin dan customer.
- Scheduler untuk kirim WA otomatis.

5.5 Source Code Management (Git)
- Branch utama: main
- Branch fitur: feature/booking, feature/laporan, feature/ui
- Commit terstruktur per modul.


BAB VI – TESTING & QUALITY ASSURANCE

6.1 Strategi Pengujian
- Blackbox testing untuk alur booking dan payment.
- Uji validasi input dan error handling.
- Uji export laporan dan filter data.

6.2 Test Plan
- Skenario booking baru.
- Pembayaran DP.
- Pelunasan termin.
- Export laporan.
- Follow up WA.

6.3 Black Box Testing
[TARUH SS HASIL BLACKBOX DI SINI]

6.4 Test Case & Result
- TC01: Booking baru (Lulus)
- TC02: Pembayaran DP (Lulus)
- TC03: Pelunasan (Lulus)
- TC04: Export PDF/Excel (Lulus)
- TC05: Follow up WA (Lulus)


BAB IX – CONCLUSION & RECOMMENDATION

9.1 Kesimpulan
Sistem Enamorapic berhasil mengelola booking, pembayaran bertahap, laporan, dan follow up secara terpusat dengan role-based dashboard. Alur DP dan pelunasan sudah otomatis memperbarui status pembayaran.

9.2 Rekomendasi Pengembangan Lanjutan
- Pengembangan aplikasi Android/iOS.
- Integrasi CRM/marketing automation.
- Notifikasi real-time via push notification.
- Analitik laporan keuangan lebih detail.
