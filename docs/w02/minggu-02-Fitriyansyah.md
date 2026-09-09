# Catatan Minggu 2
Nama : Fitriyansyah Wicaksonoaji\
Nim : 10241033\
Kelas : A

## 2.3 Read → Break → Fix → Build

### READ — Bedah instalasi Anda sendiri (45 menit)
1. Baris mana di routes/web.php yang menangkapnya?
2. Kalau ditangani controller, berkas dan method mana?
3. View mana yang dikembalikan? Di path apa persisnya?
4. Layout apa yang membungkusnya?
5. Jalankan php artisan route:list --path=tentang. Cocok dengan analisis Anda?

jawaban:

1. Baris di `routes/web.php` yang menangkap `./about` adalah bagian: 
```php
Route::get('/about', function () {
    return view('about');
});
```

2. Controller dan method: tidak ada, route ini pakai hardcore url `/about`, tidak lewat controller terpisah. Dan kalau ada, method akan ditaruh di Controller (logika request) & Model (ke database), lalu berkas aplikasi ada di  `config/` & berkas data ada di `database/`.

3. View yang dikembalikan: `about.blade.php`, berada di `resources/views/about.blade.php`.

4. Dibungkus `<x-layout>` yang ada di `resources/views/components/layout.blade.php`.

5. ![alt text](path-about.png)
ya, ini cocok dengan path yang berada di folder. Di mana, jalur `./about` diambil oleh `routes/web.php`

---
### Break - Delapan kerusakan (40 menit)
| # | Yang dirusak | Yang Anda pelajari |
|---|--------------|--------------------|
| 1 | Ubah `Route::get` menjadi `Route::post` pada route daftar mata kuliah | - |
| 2 | Ubah nama view di `return view(...)` menjadi yang tidak ada | Error karena Laravel mencari file yang tidak ada |
| 3 | Hapus `->name('courses.show')`, lalu muat halaman yang memakai `route('courses.show')` | Halaman `/courses` langsung error duluan, karena `route()` dipanggil saat render, bukan saat link diklik |
| 4 | Pindahkan `/courses/{course}` ke ATAS `/courses/create`, lalu buka `/courses/create` | Karena Laravel menggunakan sistem membaca baris mulai dari atas, maka ditangkap duluan oleh `{course}`, karena "create" dianggap sebagai nilai `$course` |
| 5 | Ganti `{{ $nama }}` menjadi `{!! $nama !!}`, isi `$nama` dengan `<script>alert('XSS')</script>` | - |
| 6 | Hapus `@vite(...)` dari layout | belum memakai vite |
| 7 | Hentikan `npm run dev` lalu muat ulang halaman | belum memakai vite |
| 8 | Panggil `route('courses.show')` tanpa mengirim parameter | Error karena Laravel butuh tahu nilai parameter {course} untuk membuat URL |

---
### FIX
https://github.com/wiranata-orion/LMS-Broken/blob/W02/README.md

### Temuan ke-1
`@vite(['resources/css/app.css', 'resources/js/app.js'])` menyembabkan error karena `npm` belum di install di directory, setelah install `npm`

### Temuan ke-2

Pada bagian tambah mata kuliah terjadi eror 404 di karenakan laravel membaca dari atas kebawah, karena laravel menemukan route yang sama dengan yang di minta maka dia akan langsung menjalankan route yang paling atas itu terjadi karena routenya mirip, padahal route yang di minta ada di paling bawah.
ubah urutanya, maka errornya hilang.

### Temuan ke-3
Route `get` pada bagian delete harusnya di ubah menjadi `POST`, Route::POST('/courses/{id}/delete', [CourseController::class, 'destroy'])->name('courses.destroy.broken');

### Temuan ke-4
Pada bagian `show.blade.php` pada kode `{!! $course['description'] !!}` sehaursnya diubah menjadi `{{$course['description'] }}`, karena `{!! $course['description'] !!}` bisa membuat browser mengkesekusi kode program yang di sisipkan, sementara `{{$course['description'] }}` hanya menampilkan teks saja.

### Temuan ke-5
Pada bagian `index.blade.php` pada kode. Tertulis bahwa baris kode menggunakan `href` yang seharusnya digunakan untuk berpindah-pindah halaman, sedangkan `form` digunakan untuk mengirim data ke server.

### Temuan ke-6

Pada bagian `index.blade.php` ditemukan kode logika. karena kode logika ini seharusnya berada di `Controller` yang bertanggung jawab untuk melakukan logika yang menampilkan mata kuliah sesuai kodisi statusnya. Jadi dari `index.blade.php` dipindah ke bagian `Controller`

---
### BUILD - Kerangka KampusLMS

- [x] Layout `x-layout` dengan navbar berisi Dashboard, Mata Kuliah, Tentang (`resources/views/components/layout.blade.php`)
- [x] `CourseController` dengan method `index` dan `show`, masih memakai data statis (array)
- [x] `courses/index.blade.php`, daftar mata kuliah,
- [x] `courses/show.blade.php`, detail satu mata kuliah.
- [x] semua tautan memakai `route()`
- [x] Halaman 404 kustom (`resources/views/errors/404.blade.php`), belum dibuat

---
### Checkpoint second week
1. #### Kenapa menghapus data lewat GET berbahaya? Beri satu skenario konkret.
    Berbahaya karena GET seharusnya tidak mengubah state dan mudah dipicu melalui URL/link
2. #### Apa yang terjadi kalau /courses/{course} ditulis sebelum /courses/create? Kenapa?
    create cocok sebagai nilai {course}, sehingga route dinamis menangkapnya
3. #### Tunjukkan di kode Anda satu tempat yang memakai route(). Apa untungnya dibanding URL hardcode?
    Membuat URL berdasarkan nama route; lebih mudah dirawat daripada hardcode
4. #### Apa beda {{ }} dan {!! !!}? Peragakan XSS yang Anda buat di bagian BREAK.
    Menampilkan HTML mentah; berbahaya jika input user tidak terpercaya

    XSS bisa di Input attacker, dieksekusi sebagai script oleh browser
5. #### Apa fungsi @vite? Apa beda npm run dev dan npm run build?
Menghubungkan Blade dengan asset yang dikelola Vite

npm run dev = development
npm run build = Build asset untuk production

6. #### Jelaskan mengapa data dari Request tidak boleh dipercaya.
Client bisa memanipulasi request; data harus divalidasi dan tindakan harus diotorisasi
