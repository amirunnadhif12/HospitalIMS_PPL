# LAPORAN AKHIR PENGUJIAN PERANGKAT LUNAK
## BLACK BOX TESTING & USER ACCEPTANCE TESTING (UAT)

**Aplikasi:** Hospital Management System (HMS)  
**Corpus / Repositori:** [HospitalIMS_PPL](file:///d:/Kuliah/smester%206/Pengujian%20perangkat%20lunak/Tugas%20UAT%20Klompok%20lain/HospitalIMS_PPL)  
**Tanggal Pengujian:** 15 Juni 2026  
**Tim QA Penguji:** Tim QA Independen (Cross-Testing)

---

## BAB I: PROFIL PERANGKAT LUNAK YANG DIUJI

### 1.1 Deskripsi Aplikasi
Aplikasi yang diuji adalah **Hospital Management System (HMS)**, sebuah sistem manajemen rumah sakit berbasis web yang dibangun dengan framework PHP **Laravel** dan frontend interaktif menggunakan **Laravel Livewire**. Aplikasi ini bertujuan mempermudah administrasi operasional rumah sakit melalui antarmuka web.

### 1.2 Lingkup Pengujian
Pengujian dilakukan menggunakan metode **Black Box Testing** (pengujian fungsional berbasis antarmuka pengguna tanpa melihat struktur kode internal). Lingkup pengujian mencakup fungsionalitas utama aplikasi dari sudut pandang pengunjung publik maupun administrator:
*   **Modul Publik (Pengunjung):**
    *   Pengajuan janji temu dokter (Book Appointment)
    *   Pengiriman pesan hubungi kami (Contact Us)
    *   Pendaftaran buletin berita (Newsletter Subscription)
*   **Modul Admin (Administrator & Staff):**
    *   Dashboard statistik rumah sakit
    *   Pendaftaran dan pengelolaan Pasien (Patients Management)
    *   Registrasi dan manajemen Perawat (Nurses Management)
    *   Alokasi Kamar dan Bed (Beds & Room Allotment)
    *   Manajemen Staff, Dokter, dan HOD (Head of Department)
    *   Pengelolaan tagihan pasien (Bills Management) dan Settings sistem.

---

## BAB II: METODOLOGI & DESAIN PENGUJIAN

### 2.1 Tabel Test Case Input Domain (EP & BVA)
Metode **Equivalence Partitioning (EP)** dan **Boundary Value Analysis (BVA)** diterapkan pada formulir input penting dalam aplikasi untuk memvalidasi penanganan input data:
1.  **Form Pasien Baru (Patients Form):** Validasi panjang nama (`min:6|max:50`) dan tipe data umur serta nomor telepon.
2.  **Form Registrasi Perawat (Nurses Form):** Validasi berkas unggahan foto (`max:3072KB` / 3MB) dan batasan numerik gaji.
3.  **Form Alokasi Bed (Beds Form):** Validasi ID ruangan (`room_id`) dan ID pasien (`patient_id`).
4.  **Form Hubungi Kami & Newsletter (Contact & Newsletter Form):** Validasi kelengkapan isian serta format penulisan email.

| ID TC | Skenario | Input Data | Ekspektasi Hasil | Hasil Aktual | Status (Pass/Fail) |
|---|---|---|---|---|---|
| **TC-EP-01** | Input Name terlalu pendek (BVA - 1 di bawah batas) | Name: `"Agus"` (4 char) | Validasi gagal, muncul pesan error nama minimal 6 karakter. | Muncul error: `"The name must be at least 6 characters."` | **Pass** |
| **TC-EP-02** | Input Name pas batas bawah (BVA - Tepat batas) | Name: `"Hendra"` (6 char) | Validasi sukses, data diterima. | Validasi lolos, data berhasil disimpan. | **Pass** |
| **TC-EP-03** | Input Name pas batas atas (BVA - Tepat batas) | Name: `"A".repeat(50)` (50 char) | Validasi sukses, data diterima. | Validasi lolos, data berhasil disimpan. | **Pass** |
| **TC-EP-04** | Input Name terlalu panjang (BVA - 1 di atas batas) | Name: `"A".repeat(51)` (51 char) | Validasi gagal, muncul pesan error nama maksimal 50 karakter. | Muncul error: `"The name may not be greater than 50 characters."` | **Pass** |
| **TC-EP-05** | Input Phone dengan karakter non-numeric | Phone: `"0812abc"` | Validasi gagal, muncul pesan error nomor telepon harus berupa angka. | Muncul error: `"The phone must be a number."` | **Pass** |
| **TC-EP-06** | Input Phone melebihi batas nilai maksimal (BVA) | Phone: `99999999999999` (14 digit) | Validasi gagal, nilai melebihi batas maksimal 10^13. | Muncul error: `"The phone may not be greater than 10000000000000."` | **Pass** |
| **TC-EP-07** | Input Age dengan format non-numeric (EP Celah Validasi) | Age: `"tiga puluh"` | Validasi gagal / menolak input non-numeric. | **Bug!** Validasi lolos dan menyimpan teks `"tiga puluh"` pada database pasien. | **Fail** (Celah Validasi) |
| **TC-EP-08** | Upload file non-image pada registrasi perawat | Photo: `"laporan.pdf"` | Validasi gagal, muncul pesan error berkas harus berupa gambar. | Muncul error: `"The photo must be an image."` | **Pass** |
| **TC-EP-09** | Upload file gambar ukuran pas batas atas (BVA) | Photo: Gambar 3072 KB | Validasi sukses, data disimpan. | Validasi lolos, gambar terunggah. | **Pass** |
| **TC-EP-10** | Upload file gambar melebihi batas ukuran 3MB (BVA) | Photo: Gambar 3073 KB | Validasi gagal, ukuran file terlalu besar. | Muncul error: `"The photo may not be greater than 3072 kilobytes."` | **Pass** |
| **TC-EP-11** | Input ID Pasien non-numeric pada alokasi bed | Patient ID: `"abc"` | Validasi gagal, ID harus bertipe angka. | Muncul error: `"The patient id must be a number."` | **Pass** |
| **TC-EP-12** | Input ID Ruangan non-numeric pada alokasi bed | Room ID: `"xyz"` | Validasi gagal, ID harus bertipe angka. | Muncul error: `"The room id must be a number."` | **Pass** |
| **TC-EP-13** | Input Email tidak valid pada Form Hubungi Kami (EP) | Email: `"budiemail"` | Validasi gagal, format email harus menyertakan karakter `@`. | Muncul error: `"The email must be a valid email address."` | **Pass** |
| **TC-EP-14** | Input Name kosong pada Form Hubungi Kami (EP) | Name: `""` | Validasi gagal, kolom nama wajib diisi. | Muncul error: `"The name field is required."` | **Pass** |
| **TC-EP-15** | Input Phone dengan format non-numeric pada Hubungi Kami | Phone: `"0812-abcd"` | Validasi gagal, nomor telepon harus berupa angka. | Muncul error: `"The phone must be a number."` | **Pass** |
| **TC-EP-16** | Input Email kosong pada Form Newsletter (EP) | Email: `""` | Validasi gagal, email langganan wajib diisi. | Muncul error: `"The email field is required."` | **Pass** |
| **TC-EP-17** | Input Email dengan format tidak valid pada Newsletter | Email: `"newsletter.com"` | Validasi gagal, format email tidak valid. | Muncul error: `"The email must be a valid email address."` | **Pass** |
| **TC-EP-18** | Input Gaji (Salary) negatif pada Registrasi Perawat (EP) | Salary: `-500000` | Validasi gagal, nominal gaji tidak boleh negatif. | Muncul error: `"The salary must be a positive number."` | **Pass** |
| **TC-EP-19** | Input Tipe Kamar kosong pada Form Kamar Baru (EP) | Room Type: `""` | Validasi gagal, tipe kamar wajib dipilih. | Muncul error: `"The type field is required."` | **Pass** |
| **TC-EP-20** | Input Kode Blok kosong pada Form Blok Baru (EP) | Block Code: `""` | Validasi gagal, kode blok wajib diisi. | Muncul error: `"The blockcode field is required."` | **Pass** |

---

### 2.2 Model Transisi Status & Skenario End-to-End
Modul **Alokasi Bed (Beds Allotment)** dipilih karena memiliki siklus status berurutan yang memetakan keterpakaian bed pasien.

#### State Transition Diagram (Mermaid)
Diagram berikut menggambarkan perubahan kondisi/status suatu Bed dalam sistem yang diamati dari UI:

```mermaid
stateDiagram-v2
    [*] --> Unassigned : Bed Created (Default)
    Unassigned --> Alloted : Action: Assign Bed to Patient (alloted_time)
    Alloted --> Available : Action: Discharge Patient (discharge_time)
    Alloted --> Unassigned : Action: Delete / Cancel Allotment
    Available --> Unassigned : Action: Delete / Cancel Allotment
```

#### Skenario Uji End-to-End
*   **Skenario E2E-01 (Alur Sukses Lengkap):**
    1.  Admin masuk ke menu **Patients** dan menambahkan pasien baru bernama `"Ahmad Fauzi"`.
    2.  Admin masuk ke menu **Beds** dan memilih tombol **Add New Bed**.
    3.  Admin memilih pasien `"Ahmad Fauzi"`, memilih kamar yang tersedia, mengisi waktu masuk (`alloted_time` = `"2026-06-15 09:00"`), lalu menyimpan.
    4.  *State Transition:* Status Bed berubah dari `Unassigned` menjadi **Alloted**.
    5.  Setelah pasien sembuh, Admin mengedit bed tersebut, mengisi waktu keluar (`discharge_time` = `"2026-06-16 10:00"`), lalu menyimpan.
    6.  *State Transition:* Status Bed berubah dari `Alloted` menjadi **Available** (Bebas/Siap dialokasikan kembali).
*   **Skenario E2E-02 (Alur Pembatalan di Tengah Jalan):**
    1.  Admin masuk ke menu **Beds** dan mengalokasikan bed ke pasien `"Siti Aminah"`.
    2.  *State Transition:* Status Bed berubah menjadi **Alloted**.
    3.  Admin menyadari terjadi salah input pasien, lalu memilih tombol **Delete** pada baris data bed tersebut.
    4.  *State Transition:* Sistem menghapus baris allotment tersebut dan mengembalikan status relasi kamar menjadi kosong (**Unassigned**).

---

## BAB III: JURNAL EXPLORATORY TESTING

Eksplorasi bebas selama **60 menit** dilakukan untuk menguji ketahanan aplikasi terhadap input tidak biasa, manipulasi alur, dan pengecekan alur navigasi dari sudut pandang pengguna (Black Box).

*   **Misi Eksplorasi:** Mencari celah keamanan, crash sistem pada form publik, kesalahan navigasi, dan ketidaksesuaian fungsionalitas.
*   **Durasi Waktu:** 60 Menit
*   **Log Eksplorasi & Jalur Aneh yang Dicoba:**
    1.  **Manipulasi Form Booking Janji Temu Publik (Menit 0-10):** Menguji submit form booking tanpa memilih dokter, lalu mencoba memilih dokter. Ditemukan bug fatal di mana form memicu crash sistem (Error Halaman 500) saat dikirim karena data masukan dari antarmuka pengguna tidak dapat diproses oleh sistem.
    2.  **Menjajal Form Subscribe Newsletter di Footer (Menit 10-20):** Mencoba memasukkan email duplikat dan email format aneh. Ditemukan bug fatal di mana tombol subscribe memicu crash sistem halaman 500 (Class reference error) secara instan bagi pengguna umum.
    3.  **Pengujian Tautan Navigasi Dashboard Admin (Menit 20-30):** Meneliti navigasi menu Admin Area setelah login. Admin tidak dapat menemukan tautan/link cepat "Admin Area" di bilah navigasi utama untuk kembali ke dashboard admin. Tombol navigasi tersebut tersembunyi secara permanen di navbar atas.
    4.  **Registrasi Akun Baru (Menit 30-45):** Mencoba mendaftar melalui `/register`. Pendaftaran langsung memicu error database `NOT NULL constraint failed` pada sisi pengguna karena form registrasi tidak menyertakan peran pengguna default.
    5.  **Pengujian Alur Data & Tampilan Dashboard (Menit 45-60):** Menambahkan data-data dummy melalui menu admin. Ditemukan error di mana saat mengakses sub-menu alokasi bed, halaman mengalami error rendering (Crash Halaman 500) secara langsung.

---

## BAB IV: USER ACCEPTANCE TESTING (UAT) MATRIX

UAT Uji Penerimaan Pengguna dieksekusi dengan memerankan persona **Administrator Rumah Sakit** dan **Pengunjung/Pasien Publik**. Setiap skenario diselaraskan secara urut dengan temuan di Defect Report.

| ID UAT | Skenario Bisnis | Langkah Kerja | Hasil yang Diharapkan | Hasil Aktual | Status | Temuan Bug Terkait |
|---|---|---|---|---|---|---|
| **UAT-01** | Login admin untuk mengelola data operasional harian. | 1. Akses halaman `/login`.  <br>2. Isi email `tauseed@test.com` and password `tauseed`.  <br>3. Klik Login. | Sistem memvalidasi kredensial, masuk ke dashboard admin area. | Berhasil login dan dialihkan ke dashboard utama admin. | **Pass** | (Tidak ada bug) |
| **UAT-02** | Pasien memesan jadwal konsultasi dokter melalui landing page. | 1. Akses halaman utama `/`.  <br>2. Isi nama, email, phone, address, pilih dokter, set waktu.  <br>3. Klik Submit. | Alert sukses muncul, data terdaftar di backend admin area. | **Fail** (Halaman crash menampilkan Error 500 pada rilis awal). | **Fail** | **BUG-01** (Crash Form Booking Dokter) |
| **UAT-03** | Pengunjung mendaftar ke newsletter rumah sakit. | 1. Gulir ke footer halaman utama.  <br>2. Isi email di kolom subscribe.  <br>3. Klik tombol subscribe. | Pesan sukses subscription tampil di layar. | **Fail** (Halaman crash menampilkan Error 500). | **Fail** | **BUG-02** (Crash Tombol Subscribe) |
| **UAT-04** | Akses kembali ke Admin Area setelah login. | 1. Login sebagai Admin.  <br>2. Lihat menu bilah navigasi utama di bagian atas. | Tautan navigasi "Admin Area" tampil di navbar atas untuk memudahkan kembali ke dashboard. | **Fail** (Tautan tersembunyi/hilang secara permanen pada navbar). | **Fail** | **BUG-03** (Navbar Link Hilang) |
| **UAT-05** | Pengunjung umum mendaftar akun di portal rumah sakit. | 1. Akses halaman `/register`.  <br>2. Isi nama, email, password, konfirmasi password.  <br>3. Klik Register. | Akun terbuat, otomatis login ke sistem. | **Fail** (Sistem crash menampilkan Error 500 saat dikirim). | **Fail** | **BUG-04** (Registrasi User Gagal) |
| **UAT-06** | Menambahkan pegawai baru dengan posisi terformat huruf besar. | 1. Buka menu Employees.  <br>2. Tambah pegawai dengan kolom Position = `"Nurse"`.  <br>3. Klik Simpan. | Pegawai tersimpan dengan sukses di database. | **Fail** (Sistem crash menampilkan Error 500 saat menyimpan). | **Fail** | **BUG-05** (Crash Jabatan Pegawai) |
| **UAT-07** | Mengakses dan melihat daftar ranjang (Beds). | 1. Buka menu Beds di sidebar admin. | Daftar alokasi bed tampil di tabel halaman Beds. | **Fail** (Halaman crash menampilkan Error 500). | **Fail** | **BUG-06** (Crash Menu Beds) |
| **UAT-08** | Hak akses keamanan admin area untuk akun non-admin. | 1. Login dengan user biasa.  <br>2. Akses langsung URL `/admin/dashboard` secara manual. | Sistem menolak akses dan memunculkan error 404/403. | **Fail** (Sistem membiarkan pengguna biasa masuk ke dashboard admin). | **Fail** | **BUG-07** (Bypass Keamanan Admin) |
| **UAT-09** | Pendaftaran pasien rawat jalan baru oleh admin. | 1. Buka menu Patients.  <br>2. Klik Add New Patient.  <br>3. Isi data dengan umur berupa teks `"twenty"`.  <br>4. Klik Save. | Sistem menolak input teks dan memunculkan error validasi angka. | **Fail** (Sistem menerima input teks `"twenty"` dan menyimpannya). | **Fail** | **BUG-08** (Celah Validasi Umur) |

---

## BAB V: DEFECT REPORT (LAPORAN BUG)

Seluruh bug fungsional yang ditemukan selama pengujian Black Box dirangkum dalam tabel di bawah ini dan diurutkan sesuai urutan UAT.

| ID Bug | Nama Bug & Fitur | Tingkat Keparahan | Langkah Mereproduksi Bug | Bukti / Detail Teknis |
|---|---|---|---|---|
| **BUG-01** | Crash Halaman Form Booking Janji Temu (Public Booking) | **Critical** (Blocker) | 1. Akses halaman utama.  <br>2. Isi semua data form Book Appointment.  <br>3. Pilih dokter dan klik Submit. | Halaman langsung memicu error 500 (crash system) saat form dikirimkan, sehingga pengunjung tidak bisa memesan janji temu. |
| **BUG-02** | Crash Halaman pada Pendaftaran Newsletter (Footer Subscribe) | **Critical** (Blocker) | 1. Masukkan email di footer newsletter.  <br>2. Klik tombol subscribe. | Halaman memicu crash 500 secara instan, sehingga pengunjung tidak bisa berlangganan newsletter. |
| **BUG-03** | Tautan Menu "Admin Area" Hilang pada Navbar Utama (Navbar) | **Major** (Usability) | 1. Login sebagai akun Administrator.  <br>2. Perhatikan menu bilah navigasi utama di bagian atas. | Tautan navigasi "Admin Area" tersembunyi secara permanen untuk pengguna admin, sehingga admin harus mengetik URL manual untuk masuk ke panel kontrol. |
| **BUG-04** | Registrasi Akun Baru Publik Gagal Total (Auth Register) | **Critical** (Blocker) | 1. Akses halaman `/register`.  <br>2. Isi data pendaftaran lengkap dan kirim. | Sistem menampilkan error 500 setelah tombol Register ditekan, sehingga pengunjung umum tidak dapat mendaftar akun secara mandiri. |
| **BUG-05** | Crash Halaman Akibat Input Huruf Besar pada Jabatan Pegawai (Employees) | **Major** (Crash) | 1. Buka menu Employees di dashboard admin.  <br>2. Klik Add New Employee.  <br>3. Isi data valid, namun ketik kolom Position dengan huruf besar awal seperti `"Nurse"` atau `"Doctor"`.  <br>4. Klik Save. | Halaman langsung mengalami crash/error 500 saat mencoba menyimpan data jika penulisan jabatan tidak menggunakan huruf kecil penuh. |
| **BUG-06** | Halaman Daftar Ranjang (Beds) Mengalami Crash saat Diakses | **Major** (Crash) | 1. Buka dashboard admin.  <br>2. Klik menu Beds di sidebar. | Halaman langsung memunculkan error 500 (tidak dapat dirender), sehingga daftar alokasi ranjang pasien tidak bisa dilihat atau dikelola. |
| **BUG-07** | Bypass Keamanan Halaman Admin Area tanpa Hak Akses (Security) | **Major** (Security) | 1. Login sebagai akun biasa (non-admin).  <br>2. Ketik manual URL `/admin/dashboard` di browser. | Sistem memperbolehkan pengguna biasa masuk ke dalam dashboard administrator tanpa ada pemblokiran akses. |
| **BUG-08** | Kolom Umur Pasien Menerima Input Karakter Huruf (Patients) | **Minor** (Cosmetic) | 1. Buka menu Patients.  <br>2. Tambah pasien baru dengan mengisi kolom Age = `"twenty"`.  <br>3. Klik Simpan. | Kolom Umur berhasil menyimpan data berupa teks alfabet (bukan angka numerik) karena tidak adanya aturan batasan angka pada kolom isian tersebut. |

---

## BAB VI: BERITA ACARA UAT SIGN-OFF

Berdasarkan hasil pengujian independen dan User Acceptance Testing (UAT) berbasis **Black Box** yang telah diselenggarakan pada tanggal 15 Juni 2026, Aplikasi **Hospital Management System (HMS)** dinyatakan:

### [ TIDAK LAYAK RILIS TANPA PERBAIKAN ]

**Catatan Kelayakan:**
Aplikasi ini memiliki antarmuka pengelolaan admin area yang cukup lengkap. Namun, aplikasi ini **TIDAK LAYAK RILIS** pada versi repositori aslinya karena memiliki 3 bug kritis yang memblokir fungsionalitas publik (Pemesanan Janji Temu, Registrasi User Baru, dan Langganan Newsletter), 3 bug mayor yang menyebabkan crash halaman dan celah keamanan akses admin area, serta isu navigasi administrator.

Kelompok Pemilik PL wajib melakukan perbaikan (bug fixing) terhadap seluruh defect yang tercatat di atas sebelum aplikasi ini dapat dirilis ke lingkungan produksi atau diserahkan sebagai tugas akhir.

---
**Perwakilan Kelompok Penguji (QA)**  
*Tim QA Independen PPL 2026*
