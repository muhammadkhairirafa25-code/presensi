# Aplikasi Presensi Siswa – SMK TI Airlangga Samarinda

Aplikasi web sederhana (PHP native, PDO, OOP, MariaDB) untuk merekap kehadiran siswa dari tap kartu RFID.

## Fitur
Login/Logout, CRUD Kelas, CRUD Siswa (validasi), Simulasi Tap Kartu, Rekap Presensi (INNER JOIN 3 tabel, filter tanggal/kelas, hapus log), Pencarian siswa & presensi.

## Cara Instalasi
1. Pindahkan folder ke `htdocs` (XAMPP) atau `/var/www/html`.
2. Jalankan MariaDB lalu import database: `mysql -u root < schema.sql`
3. Sesuaikan kredensial di `classes/Database.php` bila perlu.
4. Jalankan seeder: `php seed.php`
5. Buka `http://localhost/presensi/`

## Akun Demo
| Username | Password |
|----------|----------|
| admin    | admin123 |

## ERD
![ERD](erd.png)  <!-- ganti dengan gambar ERD notasi Chen buatanmu -->

## Hasil Pengujian
| No | Skenario Uji | Hasil Diharapkan | Hasil | Bukti |
|----|--------------|------------------|-------|-------|
| 1 | Login password salah | Gagal, muncul pesan kesalahan | Berhasil/Gagal | ss/01.png |
| 2 | Buka halaman tanpa login lewat URL | Diarahkan ke login | | ss/02.png |
| 3 | Tambah siswa NISN 9 digit | Validasi gagal, isian tidak hilang | | ss/03.png |
| 4 | Tambah siswa NISN sudah ada | Ditolak, "NISN sudah terdaftar" | | ss/04.png |
| 5 | Tap NISN tidak terdaftar | Ditolak, pesan jelas | | ss/05.png |
| 6 | Tap NISN valid | Muncul di rekap hari ini, waktu otomatis | | ss/06.png |
| 7 | Hapus kelas yang masih punya siswa | Ditolak, pesan mudah dipahami | | ss/07.png |
| 8 | Cari siswa berdasar sebagian nama | Hanya siswa sesuai yang tampil | | ss/08.png |

Uji juga tampilan di smartphone.

## Tangkapan Layar
(tambahkan screenshot login, kelas, siswa, tap kartu, rekap)
