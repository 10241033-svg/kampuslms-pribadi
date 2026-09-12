Nama : Hamzah Wiranata\
Nim : 10241035\
Kelas : A\

### READ — Telusuri satu request penuh (30 menit)

Ambil route `/tentang` yang Anda buat minggu lalu. Tanpa AI, tulis di catatan Anda:

1. Baris mana di `routes/web.php` yang menangkapnya?
2. Kalau ditangani controller, berkas dan method mana?
3. View mana yang dikembalikan? Di path apa persisnya?
4. Layout apa yang membungkusnya?
5. Jalankan `php artisan route:list --path=tentang`. Cocok dengan analisis Anda?

Jawaban

1. Pada Baris
    ```php
    Route::get('/tentang', function () {
        return view('tentang');
    });
    ```
2. Jika ditangani controller, berkasnya ada di `app/Http/Controllers/AboutController.php` dan memanggil controller misal `Route::get('/tentang', [HomeController::class, 'tentang'])->name('tentang.index');
` dengan method misal `tentang()`.
3. View yang dikembalikan adalah `tentang.blade.php` yang berada di path `resources/views/tentang.blade.php`.
4. Layout yang membungkusnya adalah `<x-layout>` yang berada di path `resources/views/components/layout.blade.php`.
5. `php artisan route:list --path=tentang` menghasilkan output yang sama dengan analisis saya yaitu satu route karena tentang hanya saya buat 1 mungkin jika saya buat group pada route tentang maka hasilnya akan lebih dari satu

---

### Break - Delapan kerusakan (40 menit)
| # | Yang dirusak | Yang Anda pelajari |
|---|--------------|--------------------|
| 1 | Ubah `Route::get` menjadi `Route::post` pada route daftar mata kuliah | Route harus sesuai method HTTP yang dipakai form. |
| 2 | Ubah nama view di `return view(...)` menjadi yang tidak ada | Nama view harus sesuai file Blade yang benar. |
| 3 | Hapus `->name('courses.show')`, lalu muat halaman yang memakai `route('courses.show')` | `route()` bergantung pada nama route yang sudah dibuat. |
| 4 | Pindahkan `/courses/{course}` ke ATAS `/courses/create`, lalu buka `/courses/create` | Route dengan parameter harus ditempatkan setelah route yang lebih spesifik. |
| 5 | Ganti `{{ $nama }}` menjadi `{!! $nama !!}`, isi `$nama` dengan `<script>alert('XSS')</script>` | Blade `{{ }}` otomatis escape HTML, sedangkan `{!! !!}` akan menampilkan HTML mentah. |
| 6 | Hapus `@vite(...)` dari layout | Asset CSS/JS dari Vite tidak akan dimuat jika `@vite(...)` dihapus. |
| 7 | Hentikan `npm run dev` lalu muat ulang halaman | Frontend dev server harus aktif agar asset dan tampilan bisa dimuat dengan benar. |
| 8 | Panggil `route('courses.show')` tanpa mengirim parameter | Route yang membutuhkan parameter harus diberi nilai, kalau tidak Laravel akan error. |