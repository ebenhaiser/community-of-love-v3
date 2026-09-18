# PRD — Sistem Manajemen COOL GBI Salemba

## 1. Informasi Produk

**Nama Produk:** Sistem Manajemen COOL GBI Salemba  
**Jenis:** Web Application  
**Status:** Product Requirement Document (PRD) — Draft v1.0  
**Target Pengguna:** Master/Admin Aplikasi, Gembala COOL, dan anggota COOL (akses terbatas tanpa akun pada fase awal)

---

## 2. Background

GBI Salemba memiliki kegiatan **COOL (Community of Love)** sebagai kelompok komunitas jemaat. Setiap COOL terdiri dari beberapa anggota dan memiliki satu orang **Gembala COOL** yang bertanggung jawab terhadap kelompok tersebut.

Saat ini data COOL, anggota, kegiatan, absensi, serta perkembangan kehadiran perlu dikelola secara terstruktur agar Gembala COOL dan Master Aplikasi dapat memantau kondisi setiap kelompok dan anggotanya.

Sistem ini dibuat sebagai aplikasi terpusat untuk mengelola:

- Data COOL.
- Data Gembala COOL.
- Data anggota COOL.
- Kegiatan COOL.
- Absensi kegiatan.
- Statistik kehadiran dan progress anggota.
- Distribusi materi/dokumen kegiatan.
- Pesan dari anggota kepada Gembala COOL.
- Akses halaman COOL menggunakan QR Code dan PIN.
- Manajemen data dengan mekanisme soft delete.
- Data event gereja yang melibatkan beberapa atau seluruh COOL.

Pada fase awal, anggota **belum memiliki akun**. Anggota dapat mengakses halaman COOL melalui QR Code yang telah digenerate oleh sistem, kemudian memasukkan PIN. PIN disimpan dalam bentuk hash sehingga sistem tidak menyimpan PIN dalam bentuk plaintext.

Pada fase pengembangan berikutnya, setiap anggota dapat memiliki akun yang dibuat oleh Gembala COOL atau Master Aplikasi.

---

# 3. Tujuan Produk

Sistem bertujuan untuk:

1. Memusatkan seluruh data COOL GBI Salemba dalam satu aplikasi.
2. Memudahkan Master Aplikasi dalam mengelola COOL, Gembala, anggota, dan kegiatan.
3. Memudahkan Gembala COOL dalam mengelola kegiatan dan absensi COOL-nya.
4. Menyediakan statistik kehadiran anggota secara periodik.
5. Membantu mengidentifikasi anggota yang jarang hadir atau tidak hadir dalam periode tertentu.
6. Memudahkan penyampaian materi atau dokumen kegiatan kepada anggota.
7. Menyediakan kanal komunikasi anggota dengan Gembala COOL.
8. Menyediakan fondasi untuk pengembangan akun anggota dan event gereja pada fase berikutnya.
9. Memastikan data yang dihapus tetap tersedia untuk kebutuhan histori/audit melalui mekanisme soft delete.

---

# 4. Scope

## 4.1 Phase 1 — MVP

Fitur utama:

- Authentication dan authorization untuk Master dan Gembala COOL.
- Master dapat mengelola seluruh data.
- Gembala hanya dapat mengakses COOL yang menjadi tanggung jawabnya.
- CRUD COOL.
- CRUD anggota.
- CRUD Gembala COOL.
- CRUD kegiatan COOL.
- Absensi kegiatan.
- Statistik kehadiran.
- Statistik ketidakhadiran dalam periode tertentu.
- Progress/status anggota berdasarkan histori kehadiran.
- Generate QR Code untuk halaman COOL.
- Akses halaman COOL menggunakan QR Code + PIN.
- Pengiriman pesan dari anggota ke Gembala COOL.
- Notifikasi kepada Gembala COOL ketika menerima pesan.
- Upload atau penyimpanan link dokumen/materi kegiatan.
- Soft delete pada seluruh entitas yang relevan.

## 4.2 Phase 2

Pengembangan lanjutan:

- Setiap anggota memiliki akun.
- Akun anggota hanya dapat dibuat oleh Master atau Gembala COOL.
- Login anggota.
- Profil anggota.
- Event gereja lintas COOL.
- Absensi anggota pada event gereja.
- Event seperti ibadah COOL gabungan.
- Rekap absensi berdasarkan COOL maupun event.
- Pengembangan statistik anggota dan kelompok.

---

# 5. User Role

## 5.1 Master / Admin Aplikasi

Master memiliki akses penuh terhadap seluruh data aplikasi.

Hak akses:

- Mengelola data COOL.
- Mengelola Gembala COOL.
- Mengelola anggota.
- Mengelola kegiatan.
- Melihat seluruh absensi.
- Melihat seluruh statistik.
- Melihat histori data.
- Membuat/mengelola akses QR Code.
- Mengelola event gereja.
- Mengelola akun pengguna.
- Melakukan restore data yang di-soft-delete jika fitur restore tersedia.

## 5.2 Gembala COOL

Gembala COOL hanya dapat melihat dan mengelola data COOL yang menjadi tanggung jawabnya.

Hak akses:

- Melihat profil COOL sendiri.
- Melihat anggota COOL sendiri.
- Mengelola kegiatan COOL sendiri.
- Membuat/mengelola absensi kegiatan COOL sendiri.
- Melihat statistik kehadiran COOL sendiri.
- Melihat anggota yang jarang hadir.
- Melihat anggota yang tidak hadir dalam periode tertentu.
- Mengirim materi/dokumen/link kepada anggota COOL.
- Menerima pesan dari anggota.
- Mendapatkan notifikasi pesan masuk.

Gembala **tidak boleh** dapat mengakses data COOL lain.

## 5.3 Anggota — Phase 1

Anggota belum memiliki akun.

Akses dilakukan melalui:

**QR Code → Halaman COOL → Input PIN → Halaman COOL**

Anggota dapat:

- Melihat informasi COOL.
- Melihat informasi kegiatan yang tersedia.
- Mengakses materi/link yang dibagikan.
- Mengirim pesan kepada Gembala COOL melalui aplikasi.

## 5.4 Anggota — Phase 2

Anggota memiliki akun.

Akun hanya dapat dibuat oleh:

- Master.
- Gembala COOL.

Anggota dapat login dan mengakses fitur yang diberikan kepadanya.

---

# 6. Konsep Utama Sistem

## 6.1 COOL

COOL adalah kelompok komunitas yang memiliki:

- Nama COOL.
- Gembala COOL.
- Daftar anggota.
- Daftar kegiatan.
- Histori absensi.
- Statistik kehadiran.
- Materi/dokumen.
- Informasi tambahan lainnya.

Contoh:

> COOL Salemba 01  
> Gembala: Budi  
> Anggota: 10 orang

Satu COOL dapat memiliki banyak kegiatan dan banyak anggota.

---

# 7. Functional Requirements

## FR-01 — Manajemen COOL

Master dapat:

- Membuat COOL.
- Mengubah data COOL.
- Melihat detail COOL.
- Melihat daftar anggota.
- Melihat Gembala COOL.
- Melihat histori kegiatan.
- Melihat statistik kehadiran.
- Menonaktifkan/menghapus COOL menggunakan soft delete.

Data minimal:

- COOL ID.
- Nama COOL.
- Gembala COOL.
- Deskripsi.
- Status aktif/nonaktif.
- Created By.
- Created Date.
- Modified By.
- Modified Date.
- Deleted By.
- Deleted Date.
- IsDeleted.

---

## FR-02 — Manajemen Gembala COOL

Master dapat:

- Membuat Gembala COOL.
- Mengubah data Gembala.
- Menentukan Gembala untuk suatu COOL.
- Melihat COOL yang dibina.
- Menonaktifkan Gembala menggunakan soft delete.

Data minimal:

- Gembala ID.
- Nama.
- Nomor HP.
- Email.
- Status.
- Created/Modified information.
- Soft delete information.

---

## FR-03 — Manajemen Anggota

Master dan Gembala memiliki akses sesuai role.

Master:

- Dapat mengelola seluruh anggota.

Gembala:

- Hanya dapat mengelola anggota COOL-nya sendiri.

Data minimal anggota:

- Member ID.
- Nama.
- Nomor HP.
- Email jika tersedia.
- COOL.
- Tanggal bergabung.
- Status anggota.
- Informasi tambahan yang diperlukan gereja.
- Created/Modified information.
- Soft delete information.

Satu anggota pada fase awal diasumsikan terdaftar pada satu COOL aktif. Struktur database sebaiknya tetap memungkinkan pengembangan histori perpindahan COOL pada fase berikutnya.

---

# 8. Kegiatan COOL

Setiap COOL dapat memiliki banyak kegiatan.

Jenis kegiatan dapat berupa:

- Ibadah COOL.
- Fellowship.
- Sharing.
- Doa bersama.
- Kegiatan sosial.
- Kegiatan lainnya.

Data kegiatan minimal:

- Activity ID.
- COOL ID.
- Nama kegiatan.
- Jenis kegiatan.
- Tanggal.
- Waktu.
- Lokasi.
- Deskripsi.
- Status kegiatan.
- Materi/dokumen.
- Link tambahan.
- Created/Modified information.
- Soft delete information.

Status kegiatan dapat berupa:

- Draft.
- Scheduled.
- Completed.
- Cancelled.

---

# 9. Absensi

Gembala dapat membuat absensi untuk setiap kegiatan COOL.

Absensi harus menyimpan histori, sehingga kehadiran anggota dapat dianalisis berdasarkan waktu.

Data minimal:

- Attendance ID.
- Activity ID.
- Member ID.
- Status kehadiran.
- Waktu absensi.
- Keterangan.
- Created By.
- Created Date.
- Modified By.
- Modified Date.
- Soft delete information.

Status kehadiran minimal:

- Hadir.
- Tidak Hadir.
- Izin.
- Sakit.

Sistem harus mencegah satu anggota memiliki lebih dari satu record absensi aktif pada kegiatan yang sama.

---

# 10. Statistik Kehadiran

Sistem harus menyediakan statistik kehadiran yang dapat difilter berdasarkan periode.

Filter minimal:

- COOL.
- Anggota.
- Tanggal mulai.
- Tanggal akhir.
- Jenis kegiatan.
- Status kehadiran.

Contoh statistik:

- Total kegiatan.
- Total hadir.
- Total tidak hadir.
- Total izin.
- Total sakit.
- Persentase kehadiran.
- Jumlah kegiatan yang diikuti berturut-turut.
- Jumlah ketidakhadiran berturut-turut.

Contoh:

> Periode: 1 September 2026 — 30 September 2026  
> Anggota: Andi  
> Total kegiatan: 4  
> Hadir: 2  
> Tidak hadir: 2  
> Persentase kehadiran: 50%

---

# 11. Statistik Anggota yang Jarang Hadir

Sistem harus dapat mengidentifikasi anggota yang memiliki tingkat kehadiran rendah.

Contoh aturan:

- Hadir kurang dari X% dalam periode tertentu.
- Tidak hadir sebanyak X kali dalam periode tertentu.
- Tidak hadir dalam X kegiatan berturut-turut.

Nilai X sebaiknya dibuat configurable oleh Master, bukan hard-code.

Contoh:

> Periode: 3 bulan  
> Minimum kehadiran: 60%

Sistem menampilkan daftar anggota yang berada di bawah threshold tersebut.

Tujuan fitur ini adalah memberikan informasi kepada Gembala agar dapat melakukan follow-up kepada anggota yang membutuhkan perhatian.

---

# 12. Anggota Tidak Hadir Dalam Periode Tertentu

Gembala dapat memilih periode tertentu.

Contoh:

> 1 Agustus 2026 — 31 Agustus 2026

Sistem dapat menampilkan:

- Anggota yang tidak pernah hadir.
- Anggota yang hanya hadir 1 kali.
- Anggota dengan ketidakhadiran berturut-turut.
- Total ketidakhadiran setiap anggota.

Contoh output:

| Anggota | Total Kegiatan | Hadir | Tidak Hadir | Status |
|---|---:|---:|---:|---|
| Andi | 4 | 4 | 0 | Aktif |
| Budi | 4 | 2 | 2 | Perlu perhatian |
| Citra | 4 | 0 | 4 | Perlu follow-up |

Status seperti "Perlu perhatian" merupakan indikator sistem berdasarkan konfigurasi threshold, bukan diagnosis atau kesimpulan mengenai kondisi pribadi anggota.

---

# 13. Progress Anggota

Sistem menyediakan ringkasan progress berbasis data kehadiran.

Progress dapat mencakup:

- Tren kehadiran.
- Konsistensi kehadiran.
- Frekuensi mengikuti kegiatan.
- Riwayat ketidakhadiran.
- Perbandingan periode sebelumnya.

Contoh:

> Bulan sebelumnya: 50% kehadiran  
> Bulan berjalan: 75% kehadiran  
> Perubahan: +25 percentage points

Sistem tidak boleh menyimpulkan alasan seseorang tidak hadir tanpa data yang diberikan pengguna.

---

# 14. QR Code Access

Pada Phase 1, anggota tidak memiliki akun.

Setiap COOL memiliki QR Code yang mengarah ke halaman COOL tertentu.

Flow:

1. Master/Gembala membuat atau generate QR Code.
2. Sistem menghasilkan URL khusus untuk COOL.
3. QR Code dicetak/dibagikan.
4. Anggota melakukan scan.
5. Anggota diarahkan ke halaman COOL.
6. Sistem meminta PIN.
7. PIN diverifikasi terhadap hash yang tersimpan.
8. Jika valid, anggota dapat mengakses halaman COOL.
9. Jika invalid, akses ditolak.

PIN **tidak boleh disimpan dalam plaintext**.

Database hanya menyimpan:

- PIN hash.
- Salt/parameter hashing sesuai algoritma yang digunakan.
- Informasi perubahan PIN jika diperlukan.

Sistem harus menyediakan mekanisme:

- Regenerate QR Code.
- Mengubah PIN.
- Menonaktifkan QR Code.
- Membatasi percobaan PIN jika diperlukan.
- Expire/revoke access token/session.

QR Code tidak boleh dianggap sebagai satu-satunya faktor keamanan karena QR dapat disalin atau dibagikan.

---

# 15. Halaman COOL untuk Anggota

Setelah berhasil melakukan autentikasi dengan PIN, anggota dapat melihat:

- Nama COOL.
- Gembala COOL.
- Informasi singkat COOL.
- Daftar kegiatan.
- Jadwal kegiatan.
- Materi/dokumen yang dibagikan.
- Link yang diberikan Gembala.
- Fitur kirim pesan.

Data sensitif yang tidak diperlukan anggota tidak boleh ditampilkan.

---

# 16. Distribusi PDF / Materi

Gembala dapat membagikan materi kegiatan.

Materi dapat berupa:

- PDF.
- Dokumen lain yang diizinkan sistem.
- Link eksternal.

Contoh flow:

1. Gembala membuat kegiatan.
2. Gembala upload PDF atau memasukkan link.
3. Sistem menyimpan metadata file/link.
4. Sistem menampilkan materi pada halaman kegiatan.
5. Anggota membuka halaman kegiatan melalui QR Code + PIN.
6. Anggota dapat mengakses materi tersebut.

Sistem sebaiknya menyimpan file di object/file storage terpisah dari database dan database hanya menyimpan metadata/path/reference file.

Metadata minimal:

- File ID.
- Activity ID.
- Nama file.
- File type.
- File size.
- Storage path/reference.
- Uploaded By.
- Uploaded Date.
- Soft delete information.

---

# 17. Pesan Anggota ke Gembala

Anggota dapat mengirim pesan melalui halaman COOL.

Data pesan:

- Message ID.
- COOL ID.
- Member ID jika identitas diketahui.
- Nama pengirim jika identitas diperlukan.
- Isi pesan.
- Status pesan.
- Created Date.
- Read Date.
- Soft delete information.

Flow:

1. Anggota membuka halaman COOL.
2. Anggota memilih "Kirim Pesan".
3. Anggota mengisi pesan.
4. Sistem menyimpan pesan.
5. Sistem mengirim notifikasi kepada Gembala COOL.
6. Gembala melihat pesan pada dashboard/inbox.

Jika pada Phase 1 anggota belum login, sistem harus menentukan bagaimana identitas pengirim dikaitkan dengan anggota. Opsi yang disarankan:

- Anggota memilih nama dari daftar anggota setelah autentikasi PIN; atau
- Sistem menggunakan access session yang terkait dengan member apabila QR/access flow memang dibuat khusus per anggota.

Pemilihan mekanisme perlu ditentukan sebelum development karena berpengaruh terhadap privasi dan desain autentikasi.

---

# 18. Notification

Gembala mendapatkan notifikasi ketika:

- Ada pesan baru dari anggota.
- Terdapat kebutuhan notifikasi kegiatan jika fitur tersebut diaktifkan.
- Sistem memiliki notifikasi administratif lain.

Phase 1 dapat menggunakan in-app notification terlebih dahulu.

Arsitektur harus dibuat agar nantinya dapat ditambahkan:

- Email.
- WhatsApp.
- Push notification.

---

# 19. Soft Delete

Seluruh data bisnis yang memiliki histori harus menggunakan soft delete.

Tidak diperbolehkan melakukan hard delete melalui UI normal.

Field standar:

- IsDeleted.
- DeletedBy.
- DeletedDate.

Query default harus hanya mengambil data:

`IsDeleted = false`

Data yang sudah di-soft-delete tetap dapat dipertahankan untuk:

- Histori.
- Audit.
- Pelacakan.
- Recovery/restore.

Relasi antar data yang sudah dihapus harus diperhatikan agar histori absensi dan kegiatan tidak hilang hanya karena parent record di-soft-delete.

Contoh:

Jika COOL di-soft-delete, histori kegiatan dan absensi tidak boleh otomatis dihapus secara permanen.

---

# 20. Dashboard Master

Dashboard Master menampilkan ringkasan seluruh gereja/COOL.

Contoh informasi:

- Total COOL aktif.
- Total Gembala aktif.
- Total anggota aktif.
- Total kegiatan.
- Statistik kehadiran.
- COOL dengan tingkat kehadiran tertentu.
- Jumlah anggota yang tidak hadir dalam periode tertentu.
- Aktivitas terbaru.

Dashboard harus menyediakan filter periode.

---

# 21. Dashboard Gembala COOL

Dashboard Gembala hanya menampilkan data COOL yang menjadi tanggung jawabnya.

Contoh:

- Nama COOL.
- Jumlah anggota.
- Jumlah kegiatan.
- Kegiatan terdekat.
- Kehadiran kegiatan terbaru.
- Statistik kehadiran.
- Daftar anggota dengan ketidakhadiran tinggi.
- Pesan masuk.
- Materi terbaru.

Gembala tidak boleh dapat mengganti COOL ID pada request untuk mengakses data COOL lain.

Authorization harus dilakukan di server-side, bukan hanya menyembunyikan menu di frontend.

---

# 22. Event Gereja — Phase 2

Sistem akan dikembangkan untuk menangani event yang tidak hanya dimiliki oleh satu COOL.

Contoh:

- Ibadah COOL gabungan.
- Event seluruh COOL.
- Kegiatan gereja tertentu.

Event memiliki:

- Event ID.
- Nama event.
- Tanggal.
- Waktu.
- Lokasi.
- Deskripsi.
- Status.
- Daftar COOL peserta.
- Daftar anggota peserta.
- Absensi.

Konsep event harus dipisahkan dari kegiatan COOL karena satu event dapat melibatkan banyak COOL.

---

# 23. Absensi Event Gereja — Phase 2

Pada event gereja, setiap anggota dapat melakukan absensi menggunakan aplikasi.

Flow yang direncanakan:

1. Master membuat event.
2. COOL/anggota yang terlibat ditentukan.
3. Sistem membuat mekanisme absensi.
4. Anggota login ke aplikasi.
5. Anggota melakukan absensi.
6. Sistem menyimpan waktu dan event.
7. Master/Gembala dapat melihat rekap.

Sistem harus mencegah:

- Double attendance.
- Absensi oleh anggota yang tidak terdaftar pada event.
- Manipulasi event/member ID melalui request.

Metode validasi absensi dapat dikembangkan kemudian, misalnya QR event, token sementara, atau mekanisme lain.

---

# 24. Data Model Konseptual

Entitas utama:

- User
- Role
- COOL
- Shepherd/Gembala
- Member
- COOLMember
- Activity
- Attendance
- ActivityMaterial
- QRAccess
- MemberMessage
- Notification
- ChurchEvent
- EventParticipant
- EventAttendance
- AuditLog

Relasi utama:

```text
User
 ├── Master
 └── Gembala

Gembala
 └── COOL

COOL
 ├── Members
 ├── Activities
 ├── QR Access
 └── Messages

Activity
 ├── Attendance
 └── Materials

Member
 └── Attendance

ChurchEvent
 ├── Event Participants
 └── Event Attendance
```

---

# 25. Authorization Rules

Authorization wajib dilakukan di backend.

Aturan utama:

| Action | Master | Gembala |
|---|---|---|
| Lihat semua COOL | Ya | Tidak |
| Kelola COOL | Ya | Sesuai kewenangan |
| Lihat anggota semua COOL | Ya | Tidak |
| Kelola anggota COOL sendiri | Ya | Ya |
| Kelola kegiatan COOL sendiri | Ya | Ya |
| Lihat absensi semua COOL | Ya | Tidak |
| Lihat statistik COOL sendiri | Ya | Ya |
| Lihat pesan COOL sendiri | Ya | Ya |
| Kelola User | Ya | Tidak |
| Kelola Event Gereja | Ya | Sesuai permission Phase 2 |

Gembala harus selalu dibatasi berdasarkan relasi:

`CurrentUser → Gembala → COOL`

Bukan berdasarkan COOL ID yang dikirim dari frontend saja.

---

# 26. Non-Functional Requirements

## Security

- Password user disimpan menggunakan password hashing yang aman.
- PIN QR Access disimpan dalam bentuk hash.
- Authorization dilakukan server-side.
- Semua endpoint harus memvalidasi ownership/scope data.
- Session/token harus memiliki expiration.
- Rate limiting dapat diterapkan pada endpoint autentikasi/PIN.
- File upload harus divalidasi berdasarkan extension, MIME type, dan ukuran file.
- Nama file asli tidak boleh digunakan langsung sebagai path storage.
- Semua komunikasi production menggunakan HTTPS.
- Hindari menyimpan informasi sensitif dalam log.

## Auditability

Perubahan data penting harus dapat dilacak:

- Siapa yang membuat.
- Siapa yang mengubah.
- Siapa yang menghapus.
- Kapan perubahan dilakukan.

## Performance

Dashboard dan statistik harus menggunakan query yang efisien.

Untuk statistik dengan jumlah data besar, pertimbangkan:

- Index database.
- Aggregation query.
- Pagination.
- Caching jika diperlukan.
- Materialized/precomputed statistics jika skala data nantinya membutuhkan.

## Availability

Aplikasi harus dapat digunakan melalui browser desktop dan mobile karena anggota akan banyak mengakses halaman melalui smartphone.

## Responsive Design

UI harus responsive dan mengutamakan mobile experience untuk halaman yang diakses anggota melalui QR Code.

---

# 27. Business Rules

1. Satu COOL memiliki satu Gembala aktif pada satu waktu.
2. Satu Gembala dapat menangani satu atau beberapa COOL jika kebijakan gereja mengizinkan.
3. Anggota hanya dapat memiliki satu keanggotaan COOL aktif pada satu waktu pada Phase 1.
4. Satu kegiatan dimiliki oleh satu COOL pada Phase 1.
5. Satu anggota hanya memiliki satu absensi aktif untuk satu kegiatan.
6. Data yang dihapus menggunakan soft delete.
7. Record soft-deleted tidak muncul pada daftar aktif.
8. Histori tetap dipertahankan.
9. Gembala hanya dapat mengakses data COOL yang menjadi tanggung jawabnya.
10. Master dapat mengakses seluruh data.
11. Statistik hanya menghitung data yang valid dan tidak di-soft-delete.
12. Threshold statistik ketidakhadiran harus dapat dikonfigurasi.
13. PIN tidak boleh disimpan dalam plaintext.
14. QR access dapat dinonaktifkan atau direvoke.
15. Materi kegiatan hanya dapat diakses oleh pengguna yang memiliki akses ke COOL terkait.

---

# 28. Acceptance Criteria MVP

## COOL

- Master dapat membuat, melihat, mengubah, dan soft delete COOL.
- COOL memiliki Gembala dan anggota.
- Gembala hanya dapat melihat COOL yang menjadi tanggung jawabnya.

## Kegiatan

- Gembala dapat membuat kegiatan.
- Kegiatan memiliki tanggal, waktu, jenis, dan informasi lainnya.
- Gembala dapat mengubah atau membatalkan kegiatan.
- Kegiatan dapat memiliki materi/link.

## Absensi

- Gembala dapat membuka absensi kegiatan.
- Setiap anggota dapat memiliki satu status absensi per kegiatan.
- Histori absensi dapat dilihat.
- Statistik dapat dihitung berdasarkan periode.

## Statistik

- Sistem dapat menghitung persentase kehadiran.
- Sistem dapat menampilkan anggota dengan tingkat kehadiran rendah.
- Sistem dapat menampilkan anggota yang tidak hadir selama periode tertentu.
- Periode statistik dapat dipilih.

## QR Access

- Sistem dapat membuat QR Code untuk COOL.
- QR Code mengarah ke halaman aplikasi.
- Halaman meminta PIN.
- PIN diverifikasi menggunakan hash.
- PIN yang salah tidak dapat mengakses halaman.
- QR access dapat dinonaktifkan.

## Pesan

- Anggota dapat mengirim pesan.
- Gembala menerima notifikasi.
- Pesan tersimpan sebagai histori.

## Soft Delete

- Data yang dihapus tidak hilang secara permanen.
- Data soft-deleted tidak muncul pada daftar aktif.
- Histori terkait tetap tersedia sesuai kebutuhan audit.

---

# 29. Suggested Main Navigation

## Master

```text
Dashboard
├── COOL
├── Gembala
├── Anggota
├── Kegiatan
├── Absensi
├── Statistik
├── Event Gereja
├── Pesan
├── Notifikasi
└── User Management
```

## Gembala

```text
Dashboard
├── COOL Saya
├── Anggota
├── Kegiatan
├── Absensi
├── Statistik Kehadiran
├── Materi
├── Pesan
└── Notifikasi
```

## Anggota Phase 1

```text
Halaman COOL
├── Informasi COOL
├── Kegiatan
├── Materi
└── Kirim Pesan
```

---

# 30. Recommended Development Architecture

Aplikasi sebaiknya dibuat dengan arsitektur yang memisahkan:

```text
Presentation / Frontend
        ↓
API / Application Layer
        ↓
Business Logic
        ↓
Data Access Layer
        ↓
Database
```

Untuk backend, teknologi dapat menggunakan stack yang sesuai dengan kemampuan tim, misalnya:

- ASP.NET Core Web API.
- Entity Framework Core.
- SQL Server.
- JWT/secure authentication untuk user yang memiliki akun.
- Object/File Storage untuk dokumen.
- Background service untuk proses notifikasi jika dibutuhkan.

Frontend dapat menggunakan framework yang sesuai kebutuhan proyek.

---

# 31. API Concept

Contoh endpoint:

```text
POST   /api/auth/login

GET    /api/cools
POST   /api/cools
GET    /api/cools/{id}
PUT    /api/cools/{id}
DELETE /api/cools/{id}

GET    /api/cools/{id}/members
POST   /api/cools/{id}/members

GET    /api/cools/{id}/activities
POST   /api/cools/{id}/activities

POST   /api/activities/{id}/attendance
GET    /api/activities/{id}/attendance

GET    /api/statistics/attendance
GET    /api/statistics/members

POST   /api/cools/{id}/qr-access
POST   /api/qr-access/{id}/verify

GET    /api/cools/{id}/messages
POST   /api/cools/{id}/messages

GET    /api/notifications
```

Endpoint final harus mengikuti standar naming dan authorization yang digunakan pada implementasi.

---

# 32. Statistik — Contoh Perhitungan

Persentase kehadiran:

```text
Persentase Kehadiran =
Jumlah Hadir / Total Kegiatan yang Diikuti dalam Periode × 100%
```

Contoh:

```text
Total kegiatan = 8
Hadir = 6

6 / 8 × 100% = 75%
```

Perlu ditentukan business rule apakah status **Izin** dan **Sakit** masuk denominator statistik atau dihitung terpisah. Rekomendasi desain adalah menyimpan seluruh status secara terpisah sehingga aturan perhitungan dapat dikonfigurasi tanpa mengubah data historis.

---

# 33. Reporting

Sistem dapat menyediakan laporan:

### Laporan COOL

- Daftar COOL.
- Gembala.
- Jumlah anggota.
- Jumlah kegiatan.

### Laporan Anggota

- Daftar anggota.
- COOL.
- Status keanggotaan.
- Histori kegiatan.

### Laporan Kehadiran

- Periode.
- COOL.
- Anggota.
- Total kegiatan.
- Hadir.
- Tidak hadir.
- Izin.
- Sakit.
- Persentase kehadiran.

### Laporan Event

Phase 2:

- Event.
- COOL peserta.
- Total anggota.
- Total hadir.
- Total tidak hadir.
- Persentase kehadiran.

Export dapat dikembangkan ke Excel/PDF pada fase berikutnya.

---

# 34. Privacy & Access Considerations

Karena sistem menyimpan data anggota gereja, akses data harus dibatasi berdasarkan kebutuhan.

Prinsip:

- Master memiliki akses administratif sesuai kebutuhan.
- Gembala hanya melihat data COOL yang menjadi tanggung jawabnya.
- Anggota hanya melihat informasi yang memang ditujukan untuk anggota.
- Data kontak anggota tidak perlu ditampilkan kepada seluruh anggota kecuali memang diperlukan.
- Pesan anggota hanya dapat diakses oleh pihak yang berwenang.
- File/materi tidak boleh dapat diakses hanya dengan mengetahui URL file apabila file tersebut bersifat privat.

---

# 35. Open Questions Before Development

Beberapa keputusan bisnis perlu dikonfirmasi sebelum development:

1. Apakah satu anggota boleh terdaftar pada lebih dari satu COOL?
2. Apakah anggota yang pindah COOL harus memiliki histori COOL sebelumnya?
3. Apakah satu Gembala boleh memiliki beberapa COOL?
4. Apakah Gembala boleh menghapus anggota atau hanya Master?
5. Apakah absensi dilakukan oleh Gembala atau anggota sendiri pada Phase 1?
6. Jika anggota belum memiliki akun, bagaimana sistem mengetahui identitas anggota ketika mengirim pesan?
7. Apakah satu QR Code digunakan oleh seluruh anggota COOL?
8. Apakah PIN satu untuk satu COOL atau berbeda per anggota?
9. Berapa lama session setelah PIN berhasil diverifikasi?
10. Apakah anggota dapat melihat nama anggota lain?
11. Status apa saja yang digunakan untuk absensi: Hadir, Tidak Hadir, Izin, Sakit, atau lainnya?
12. Apakah Izin/Sakit dihitung sebagai tidak hadir dalam statistik?
13. Berapa threshold untuk kategori "jarang hadir"?
14. Berapa jumlah ketidakhadiran berturut-turut yang dianggap perlu follow-up?
15. Apakah Gembala dapat mengubah threshold atau hanya Master?
16. Maksimal ukuran file PDF/dokumen yang boleh diupload?
17. File apa saja yang diperbolehkan selain PDF?
18. Apakah materi memiliki tanggal kedaluwarsa?
19. Notifikasi Phase 1 menggunakan in-app saja atau membutuhkan email/WhatsApp?
20. Pada Phase 2, apakah anggota dapat melakukan absensi event menggunakan QR Code event?
21. Apakah event gereja dapat diikuti oleh sebagian anggota dari suatu COOL?
22. Apakah Master membutuhkan audit log lengkap untuk setiap perubahan data?

---

# 36. Future Development

Setelah MVP stabil, sistem dapat dikembangkan menjadi platform manajemen komunitas GBI Salemba dengan fitur:

- Akun anggota.
- Mobile-first member portal.
- Event gereja.
- Absensi event.
- QR attendance.
- Push notification.
- Email/WhatsApp notification.
- Kalender kegiatan.
- Reminder kegiatan.
- Dashboard perkembangan COOL.
- Laporan periodik.
- Export Excel/PDF.
- Audit log.
- Histori perpindahan anggota antar COOL.
- Role/permission yang lebih granular.
- Multi-level organizational structure jika diperlukan.

---

# 37. Definition of Done — MVP

MVP dianggap selesai apabila:

1. Master dapat mengelola seluruh master data.
2. Gembala dapat mengelola COOL-nya sendiri.
3. Anggota dapat mengakses halaman COOL menggunakan QR Code + PIN.
4. Kegiatan dapat dibuat dan dikelola.
5. Absensi dapat dicatat.
6. Statistik kehadiran dapat dihitung berdasarkan periode.
7. Anggota dengan ketidakhadiran tinggi dapat diidentifikasi berdasarkan threshold.
8. Materi/link dapat dibagikan.
9. Anggota dapat mengirim pesan.
10. Gembala mendapatkan notifikasi pesan.
11. Seluruh operasi delete bisnis menggunakan soft delete.
12. Authorization mencegah Gembala mengakses COOL lain.
13. Data sensitif dan file privat terlindungi.
14. Aplikasi responsive untuk penggunaan smartphone.
15. Seluruh requirement kritikal memiliki test case dan telah melewati UAT.

---

# 38. Product Summary

Sistem Manajemen COOL GBI Salemba merupakan aplikasi untuk mengelola komunitas COOL secara terpusat, mulai dari data COOL, Gembala, anggota, kegiatan, absensi, statistik kehadiran, distribusi materi, hingga komunikasi anggota dengan Gembala.

Arsitektur sistem harus sejak awal dibuat extensible agar Phase 2 dapat menambahkan akun anggota dan event gereja tanpa melakukan perubahan besar terhadap struktur inti aplikasi.

Prioritas utama MVP adalah **data yang terstruktur, pembatasan akses berdasarkan role, histori absensi yang akurat, statistik berbasis periode, dan keamanan akses anggota melalui QR Code + PIN**.
