# Desa Digital - Starter Scaffold

Ini adalah **starter scaffold** Laravel untuk project **desa-digital**.
Tujuan: mempercepat setup (views, controllers, routes, migrations, seeder, CSS pastel).
Ini **bukan** distribusi vendor lengkap. Setelah ekstrak, jalankan langkah berikut di Laragon/VS Code:

1. Tempatkan folder `desa-digital` di `C:\laragon\www\` (atau folder webserver kamu).
2. Salin file `.env.example` menjadi `.env` dan sesuaikan DB:
   - DB_DATABASE=desa_digital
   - DB_USERNAME=root
   - DB_PASSWORD=

3. Jalankan di terminal project:
   - `composer install` (butuh koneksi & composer)
   - `cp .env.example .env` (Windows: copy)
   - `php artisan key:generate`
   - `php artisan migrate --seed`
   - `npm install`
   - `npm run build`
   - `php artisan serve` (atau jalankan via Laragon)

Default akun admin dibuat di seeder:
- Email: admin@desa.com
- Password: admin123

Fitur yang termasuk:
- Role user (admin/warga)
- Routing dasar untuk Admin & Warga
- CRUD model Surat (upload PDF)
- Tampilan sederhana menggunakan Bootstrap + custom CSS pastel (pink & biru)
- Contoh view Blade untuk admin & warga
- Upload file diarahkan ke `public/uploads/surat`

Catatan:
- Jika kamu mau aku lengkapi jadi ZIP "siap jalan" (termasuk vendor), aku tidak bisa menjalankan composer/npm di server ini. Namun scaffold ini sudah cukup agar kamu dapat menjalankan `composer install` & `npm install` di PC kamu.
