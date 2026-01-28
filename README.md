# Dashboard Manajemen Inventaris - E-Commerce Web

Proyek ini adalah sistem manajemen gudang (back-end) sederhana untuk toko online. Fokus utama aplikasi ini adalah memudahkan admin dalam mengelola stok barang, memantau nilai aset, dan mencetak laporan secara instan.

---

## 🛠️ Penjelasan Proyek

Aplikasi ini dibangun untuk menangani siklus manajemen barang digital, mulai dari autentikasi keamanan hingga pelaporan data. Sistem ini menggunakan arsitektur **PHP Native** yang efisien untuk pengolahan data server-side dan **MySQL** sebagai penyimpanan basis datanya.

### Fitur Utama:
1. **Keamanan (Authentication)**: Memastikan hanya admin terdaftar yang bisa mengelola data melalui sistem login berbasis session.
2. **Manajemen Data (CRUD)**:
   - **Create**: Menambah barang baru lengkap dengan unggah foto produk.
   - **Read**: Menampilkan daftar barang dalam tabel yang rapi.
   - **Update**: Mengubah informasi barang (harga, nama, stok).
   - **Delete**: Menghapus barang (otomatis menghapus file foto di server untuk menghemat ruang).
3. **Pencarian Real-time**: Fitur filter untuk menemukan produk tertentu di tengah ratusan data.
4. **Analitik Inventaris**: Widget otomatis yang menghitung total seluruh stok dan total nilai uang dari aset yang tersimpan di gudang.
5. **Sistem Cetak**: Desain khusus yang memungkinkan admin mencetak laporan fisik atau simpan sebagai PDF dengan tampilan yang bersih (beberapa elemen web otomatis disembunyikan saat dicetak).

---

## 📂 Struktur File Utama

* `login.php` - Halaman masuk admin.
* `index.php` - Dashboard pusat yang menggabungkan fitur statistik, pencarian, dan tabel data.
* `logout.php` - Penghapusan session keamanan.
* `edit.php` - Logika pembaruan data produk.
* `/img` - Folder penyimpanan fisik untuk foto-foto produk yang diunggah.

---

## 💻 Cara Menjalankan

1. Pastikan **XAMPP** aktif (Apache & MySQL).
2. Buat database `toko_online` di phpMyAdmin.
3. Jalankan script SQL yang sesuai untuk tabel `users` dan `produk`.
4. Akses melalui `localhost/E-Commerce-Web/`.

---
*Proyek ini dikembangkan sebagai implementasi dasar sistem manajemen database berbasis web.*
