# Catatan Minggu 3
Nama : Fitriyansyah Wicaksonoaji\
Nim : 10241033\
Kelas : A

## 3.3 Read → Break → Fix → Build

### READ — Bedah instalasi Anda sendiri (45 menit)
1. Gambar ulang ERD dari spesifikasi di papan/kertas, tanpa melihat dokumen.
2. Untuk setiap foreign key, tentukan perilaku onDelete-nya dan tuliskan alasannya.

    `courses.lecturer_id` → `users.id`, **restrictOnDelete**.

    Mencegah penghapusan data induk (parent) jika data tersebut masih digunakan oleh data anak (child). Karena jika seorang dosen (`users.id`) dihapus dari sistem, namun akunnya masih tercatat sebagai pengajar di beberapa mata kuliah (`courses.lecturer_id`), sistem akan menolak penghapusan tersebut.

    `materials.course_id` → `courses.id`, **cascadeOnDelete**.

    karena jika sebuah kelas (`course`) dihapus, maka semua materi (`materials`) yang ada di dalam kelas tersebut secara logis sudah tidak memiliki fungsi atau acuan lagi. cascadeOnDelete memastikan bahwa saat Anda menghapus satu course, semua materials yang terhubung dengannya akan ikut terhapus secara otomatis oleh database.
    
    `assignments.course_id` → `courses.id`, **cascadeOnDelete**.

    Sama seperti sebelumnya, assignment juga akan terhapus karena tidak memiliki fungsi lagi jika kelasnya saja tidak digunakan.

    `subsmission.assignment_id` → `assignment.course.id`, **cascadeOnDelete**.

    Sama seperti assignment, penyerahan tugas dari mahasiswa sudah tidak relevan lagi kalau bahkan tugasnya saja dihapus.

    `graded.subsmission_id` → `subsmission.assignment.id`, **cascadeOnDelete**.

    Karena 'nilai' itu punya relasi one-to-one ke submission, maka kita tambahkan onDelete di subsmission juga (sebagai induk) yang kalau dihapus maka nilai juga ikut terhapus

3. Jawab: kalau seorang dosen dihapus, apa yang terjadi pada mata kuliahnya? Kenapa dirancang begitu?

Mata kuliah adalah child dari sebuah induk bernama dosen. Jadi ketika dosen dihapus maka mata kuliahnya ikut terhapus, karena berarti mata kuliah yang dulu pernah diajarnya sudah tidak relevan lagi.

4. Jawab: kenapa grades.submission_id bersifat unique, bukan sekadar index biasa?

Karena sebagai aturan relasi one-to-one yang mengharuskan satu subsmission hanya bisa memiliki satu grade. Satu tugas mahasiswa hanya bisa punya nilai satu.


---
### Break - Delapan kerusakan (40 menit)
| # | Yang dirusak | Yang anda pelajari |
|---|-------------|------------------------|
| 1 | Hapus `unique(['course_id','user_id'])` dari `course_user`, lalu daftarkan mahasiswa yang sama dua kali | mahasiswa bisa terdaftar dua kali dalam satu mata kuliah yang sama, akibatnya bisa terjadi data ganda. |
| 2 | Tambahkan `role` ke `$fillable` model `User`, lalu kirim request pembuatan user dengan `role=admin` lewat form yang **tidak punya field role** | request dari browser bakal bisa mengubah role dari sang request yang misalnya dia seorang mahasiswa |
| 3 | Ganti seluruh `$fillable` dengan `protected $guarded = [];` lalu ulangi nomor 2 | role yang selain admin bisa berubah menjadi admin |
| 4 | Kosongkan isi `down()` di satu migrasi, lalu jalankan `php artisan migrate:refresh` | semua tabel jadinya kehapus |
| 5 | Ubah `restrictOnDelete` pada `lecturer_id` menjadi `cascadeOnDelete`, lalu hapus satu dosen | Child (courses) dari 'dosen' akan terhapus semua |

---
### FIX

---
### BUILD - Kerangka KampusLMS

- [x] Seluruh migrasi sesuai Bagian 4 spesifikasi, termasuk semua constraint dan index. Reversible.
- [x] Seluruh model dengan relasi lengkap sesuai Bagian 4.3, $fillable yang ketat, casts() sebagai method.
- [x] Factory + seeder yang memenuhi Bagian 4.4, termasuk 3 akun demo.
- [x] CRUD Mata Kuliah berfungsi penuh (index, create, store, show, edit, update, destroy).
- [x] CRUD Pengguna berfungsi penuh, dengan role tidak di $fillable melainkan diisi eksplisit di controller.
- [x] CI GitHub Actions aktif dan hijau: migrate:fresh --seed sukses, migrate:refresh sukses, .env tidak ter-commit.
- [x] Setiap anggota punya commit atas namanya sendiri, dan setiap fitur masuk lewat PR yang direview anggota lain.

---
### Checkpoint third week
1. Tunjukkan migrasi yang Anda tulis. Jelaskan setiap constraint di dalamnya.

2. Kenapa course_user punya unique composite? Peragakan apa yang terjadi kalau dihapus.

3. Apa itu mass assignment? Tunjukkan di kode Anda apa yang mencegahnya, lalu peragakan serangannya dengan curl.

4. Kenapa role tidak boleh ada di `$fillable`? Di mana ia diisi sebagai gantinya?

5. Kenapa `lecturer_id` memakai `restrictOnDelete` sementara materials.`course_id` memakai `cascadeOnDelete`?

6. Jalankan `php artisan migrate:refresh` di depan penguji. Harus berhasil tanpa error.

7. Tunjukkan satu bagian kode yang Anda tulis dengan bantuan AI. Apa yang Anda ubah dari keluaran aslinya, dan kenapa?
