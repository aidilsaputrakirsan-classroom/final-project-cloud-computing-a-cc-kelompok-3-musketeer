# ChatterBox - Aplikasi Komunitas Diskusi Publik

![Laravel](https://img.shields.io/badge/Laravel-12.36.1-FF2D20?style=flat&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-blue?style=flat&logo=mysql)
![License](https://img.shields.io/badge/License-MIT-green.svg)


ChatterBox adalah aplikasi komunitas berbasis web yang dirancang untuk
memfasilitasi pengguna dalam membuat diskusi dan berdiskusi berbagai topik menarik secara open public. Pengguna dapat membuat postingan topik baru, saling berinteraksi dengan memberikan komentar pada postingan orang lain, serta adanya fitur like dan dislike di setiap postingan.


## Daftar Isi
- [Fitur Utama](#fitur-utama)
- [Tech Stack](#tech-stack)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Konfigurasi](#konfigurasi)
- [Role & Permissions](#role--permissions)
- [Fitur Berdasarkan Role](#fitur-berdasarkan-role)
- [Screenshot](#screenshot)
- [Struktur Database](#struktur-database)
- [Penggunaan](#penggunaan)
- [Deployment](#deployment)
- [Kontribusi](#kontribusi)
- [Lisensi](#lisensi)
---



---
## Fitur Utama

### 1. Manajemen Postingan
✅ **CRUD Postingan**
Pengguna dapat membuat, mengedit, menghapus, dan melihat postingan. 

✅ **Kategori Postingan**
Setiap postingan memiliki satu kategori, dan pengguna dapat mencari kategori postingan orang lain melalui menu jelajahi topik.

### 2. Interaksi Pengguna
✅ **Voting (Like / Dislike)**
Pengguna dapat memberikan voting berupa like atau dislike pada postingan pengguna. Adapun pengguna dapat melihat daftar like dan dislike pada postingan pribadi melalui menu daftar suka.

✅ **Komentar**
Pengguna dapat memberikan komentar pada postingan pribadi dan postingan orang lain, serta dapat membalas komentar orang lain.  

✅ **Notifikasi**
Pengguna mendapatkan notifikasi untuk komentar baru, balasan komentar, like, dislike, dan laporan postingan.  

### 3. Moderasi Konten
✅ **Report Postingan**
Pengguna dapat melaporkan postingan sesuai pilihan kategori laporan dan memberikan alasan tambahan. Laporan ini akan dikelola oleh admin.  

### 4. Personalisasi dan Pengalaman Pengguna
✅ **Daftar Postingan**
Pengguna dapat melihat daftar postingan yang telah dibuat 
melalui menu postingan saya.

✅ **Profil Pengguna**
Pengguna dapat mengedit foto profil melalui menu postingan saya ataupun dengan mengklik foto profil.

### 5. Halaman Informasi
✅ **FAQ & Bantuan**
Pengguna yang memiliki kendala dapat memilih menu Lihat Bantuan dan FAQ untuk melihat pertanyaan dan solusi dari masalah yang umumnya terjadi di pengguna.

---

---
## Tech Stack

### Backend
- **Laravel 12.36.1** - - PHP Framework
- **MySQL** - Database

### Frontend
- **HTML**
- **CSS**

### Tools & Libraries
- **Node.js & NPM** - pengelola dependency frontend dan menjalankan Vite
- **Composer** - dependency manager untuk PHP
- **Laravel Artisan CLI** - command line tool (`php artisan`)
---



---
## Persyaratan Sistem
- PHP >= 8.2  
- MySQL >= 5.7
- Composer  
- Node.js dan NPM
- Web Server (Apache/Nginx)  
- Ekstensi PHP:
  - PDO
  - Mbstring
  - Tokenizer
  - OpenSSL
  - Ctype
  - JSON
  - GD
---



---
## Instalasi
### 1. Clone Repository
```bash
git clone https://github.com/aidilsaputrakirsan-classroom/final-project-cloud-computing-a-cc-kelompok-3-musketeer.git
```
### 2. Install Dependencies
```bash
composer install
```
### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```
### 4. Konfigurasi Database
Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chatterbox
DB_USERNAME=root
DB_PASSWORD=
```
### 5. Migrasi Database
```bash
php artisan migrate --seed
```
### 6. Build Assets
```bash
# Development
npm run dev
# Production
npm run build
```
### 7. Jalankan Aplikasi
```bash
php artisan serve
```
Aplikasi akan berjalan di: `http://localhost:8000`
```



```
## Konfigurasi
### Email
Saat ini ChatterBox belum menggunakan fitur pengiriman email seperti verifikasi akun, reset password, maupun notifikasi ke email pengguna.  
```



```
## Role & Permissions
Sistem menggunakan 2 role utama dengan permission berbeda:

| Role | Deskripsi |
|------|-----------|
| **User** | Pengguna yang dapat membuat postingan dan berinteraksi dengan postingan |
| **Admin** | Admin sebagai pengelola laporan postingan dari User |
---



---
## Fitur Berdasarkan Role
### User
- Membuat / mengedit / menghapus postingan  
- Membuat Komentar
- Voting Postingan (Like/Dislike)  
- Melaporkan Postingan  
- Edit profile  
- Mengatur tema tampilan  
### Admin
- Melihat daftar laporan postingan dari User
- Memberikan aksi terhadap laporan (Menerima/Menolak)
- Melihat riwayat laporan postingan dari User  
---



---
## Screenshot
### 1. Halaman Login
![Halaman Login](public/images/screenshots/01-login.png)
*Halaman login dengan form email dan password*
### 2. Halaman Register
![Halaman Register](public/images/screenshots/02-register.png)
*Halaman Register dengan form nama lengkap, email, password, dan konfirmasi password*
### 3. Halaman Diskusi
![Halaman Diskusi](public/images/screenshots/03-diskusi.png)
*Halaman yang menampilkan daftar postingan*
### 4. Halaman Membuat Postingan
![Halaman Membuat Postingan](public/images/screenshots/04-membuat-postingan.png)
*Halaman Membuat Postingan dengan form judul, isi konten, dan pilihan kategori*
### 5. Halaman Edit Postingan
![Halaman Mengedit Postingan](public/images/screenshots/05-mengedit-postingan.png)
*Halaman membuat postingan dengan form judul, isi konten, dan pilihan kategori*
### 6. Halaman Detail Postingan
![Halaman Detail Postingan](public/images/screenshots/06-detail-postingan.png)
*Halaman yang menampilkan detail postingan pengguna dan isi komentar*
### 7. Halaman Jelajahi Topik
![Halaman Jelajahi Topik](public/images/screenshots/07-jelajahi-topik.png)
*Halaman yang menampilkan beberapa kategori postingan yang dapat dipilih pengguna untuk melihat daftar postingan dengan kategori yang dipilih*
### 8. Halaman Postingan Saya
![Halaman Postingan Saya](public/images/screenshots/08-postingan-saya.png)
*Halaman yang menampilkan daftar postingan serta profil pengguna*
### 9. Halaman Daftar Suka
![Halaman Daftar Suka](public/images/screenshots/09-daftar-suka.png)
*Halaman yang menampilkan daftar pengguna yang melakukan aksi like atau dislike pada postingan pribadi pengguna*
*Halaman pengaturan dengan pilihan mode gelap atau mode terang*
### 10. Tampilan Melaporkan Postingan
![Tampilan Melaporkan Postingan](public/images/screenshots/10-melaporkan-postingan.png)
*Tampilan pelaporan terhadap postingan orang lain*
### 11. Halaman Laporan Postingan
![Halaman Laporan Postingan](public/images/screenshots/11-laporan-postingan.png)
*Halaman yang menampilkan daftar laporan postingan dari pengguna*
### 12. Halaman Detail Laporan
![Halaman Detail Laporan](public/images/screenshots/12-detail-laporan.jpeg)
*Halaman yang menampilkan detail laporan dari pengguna*
### 13. Tampilan Notifikasi Postingan dihapus karena dilaporkan
![Tampilan Notifikasi 1](public/images/screenshots/13-tampilan-notifikasi.png)
*Tampilan notifikasi saat laporan diterima Admin*
### 14. Tampilan Notifikasi
![Tampilan Notifikasi 2](public/images/screenshots/14-tampilan-notifikasi.png)
*Tampilan notifikasi saat pengguna mengomentari postingan pribadi*
### 15. Tampilan Notifikasi
![Tampilan Notifikasi 3](public/images/screenshots/15-tampilan-notifikasi.png)
*Tampilan notifikasi saat terdapat pengguna membalas komentar pribadi*
### 16. Tampilan Notifikasi
![Tampilan Notifikasi 4](public/images/screenshots/16-tampilan-notifikasi.png)
*Tampilan notifikasi saat pengguna melakukan like/dislike postingan pribadi*
### 17. Halaman History Laporan
![Halaman History Laporan](public/images/screenshots/17-history-laporan.png)
*Halaman yang menampilkan daftar riwayat laporan pengguna*
### 18. Halaman Lihat Bantuan & FAQ
![Halaman Bantuan](public/images/screenshots/18-bantuan.png)
*Halaman yang menampilkan daftar pertanyaan yang dapat membantu permasalahan umum yang dihadapi pengguna*
### 19. Halaman Detail Bantuan & FAQ
![Halaman Detail Bantuan](public/images/screenshots/19-detail-bantuan.png)
*Halaman yang menampilkan jawaban yang membantu pengguna sesuai dengan pertanyaan yang dipilih*

---


---
## Struktur Database
```
### Tabel Utama
- **users**  
- **posts**  
- **comments**    
- **categories**    
- **reports**  
- **notifications**  
- **reactions**  
```



### Relasi Database
```
users (1) --- (n) posts
users (1) --- (n) comments
posts (1) --- (n) comments
posts (n) --- (1) categories
posts (1) --- (n) reports
posts (1) --- (n) reactions
users (1) --- (n) notifications
```



---
## Penggunaan
### Workflow Umum Pengguna
1. Login / Register  
2. Membuat postingan baru  
3. Memberikan komentar dan voting (like/dislike)
4. Melaporkan postingan jika diperlukan  
5. Mengatur tema tampilan  
6. Mengelola profil  



---
## Deployment
### Requirements
- PHP 8.2+
- MySQL / MariaDB
- Composer
- Web server (Apache/Nginx)
- SSL Certificate (jika production)
### Production Setup
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```
Pastikan `.env` menggunakan setting production.
---



---
## Kontribusi
1. Fork repository ini  
2. Buat branch fitur baru (`git checkout -b feature/FiturBaru`)  
3. Commit perubahan (`git commit -m "Tambah fitur baru"`)  
4. Push ke branch (`git push origin feature/FiturBaru`)  
5. Buat Pull Request  
---



---
## Lisensi
Project ini dilisensikan di bawah **MIT License**.



---
**Developed with ❤️ by Tim Musketeer — Cloud Computing**


