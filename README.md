Website fan NCT Dream yang menampilkan artikel, galeri, dan jadwal dengan fitur **AI Article Generator** menggunakan Google Gemini API.

---

## ✨ Fitur Utama

### 👥 **User Side (Public)**
- 🏠 **Homepage** - Hero section dengan informasi utama
- 📰 **Article List** - Daftar artikel tentang NCT Dream
- 📖 **Article Detail** - Halaman detail artikel
- 🖼️ **Gallery Carousel** - Galeri foto member NCT Dream
- 📅 **Schedule** - Jadwal kuliah mingguan
- 👤 **Profile Team** - Informasi tim developer

### 🔐 **Admin Panel**
- 📊 **Dashboard** - Statistik artikel dan galeri
- ✍️ **Article Management** - CRUD artikel
  - ✨ **AI Generator** - Generate artikel otomatis dengan Gemini AI
  - 📝 Edit & Delete artikel
  - 🖼️ Upload gambar artikel
- 🖼️ **Gallery Management** - Upload dan kelola galeri foto
- 🔒 **Authentication** - Login/Logout system

### 🤖 **AI Integration**
- **Google Gemini 2.0 Flash** untuk generate artikel
- Automatic content generation berdasarkan judul
- Error handling dan debugging

---

## 🛠️ Tech Stack

| Technology | Purpose |
|------------|---------|
| **PHP 8.x** | Backend Logic |
| **MySQL** | Database |
| **Bootstrap 5.3** | UI Framework |
| **jQuery** | DOM Manipulation |
| **Google Gemini API** | AI Content Generation |
| **Environment Variables** | Secure Configuration |

---

## 📁 Struktur Folder

```
Creating-AI/
├── 📁 img/                      # Folder untuk gambar (artikel & galeri)
├── 📄 index.php                 # Homepage (public)
├── 📄 article.php               # Admin - halaman manajemen artikel
├── 📄 article_data.php          # Admin - CRUD operations artikel
├── 📄 article_detail.php        # Public - detail artikel
├── 📄 ai_article_generator.php  # AI generator endpoint
├── 📄 gallery.php               # Admin - halaman gallery
├── 📄 gallery_data.php          # Admin - CRUD operations gallery
├── 📄 admin.php                 # Admin panel utama
├── 📄 dashboard.php             # Admin dashboard
├── 📄 login.php                 # Login page
├── 📄 logout.php                # Logout handler
├── 📄 koneksi.php               # Database connection
├── 📄 config.php                # Environment loader
├── 📄 upload_foto.php           # Upload handler
├── 📄 .env                      # Environment variables (JANGAN DI-PUSH!)
├── 📄 .gitignore                # Git ignore rules
├── 📄 uaspbw.sql                # Database schema & sample data
└── 📄 README.md                 # Dokumentasi ini
```

---

## ⚙️ Instalasi

### 1️⃣ **Prasyarat**
- PHP 7.4 atau lebih tinggi
- MySQL / MariaDB
- XAMPP / WAMP / LAMP (untuk localhost)
- Composer (optional)

### 2️⃣ **Clone Repository**
```bash
git clone https://github.com/naiasyafina/Creating-AI.git
cd Creating-AI
```

### 3️⃣ **Setup Database**

1. Buat database baru di phpMyAdmin:
```sql
CREATE DATABASE uaspbw;
```

2. Import file SQL:
```bash
# Via phpMyAdmin: Import file uaspbw.sql
# Atau via command line:
mysql -u root -p uaspbw < uaspbw.sql
```

### 4️⃣ **Konfigurasi Environment**

Buat file `.env` di root folder:

```env
# Database Configuration
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=uaspbw

# API Configuration
GEMINI_API_KEY=your_gemini_api_key_here
```

> **💡 Cara mendapatkan Gemini API Key:**
> 1. Kunjungi [Google AI Studio](https://makersuite.google.com/app/apikey)
> 2. Login dengan akun Google
> 3. Klik "Create API Key"
> 4. Copy API key ke file `.env`

### 5️⃣ **Jalankan Aplikasi**

```bash
# Jika menggunakan XAMPP:
# 1. Start Apache & MySQL di XAMPP Control Panel
# 2. Buka browser:
http://localhost/Creating-AI/

# Admin login:
http://localhost/Creating-AI/login.php
```

**Default Admin Credentials:**
- Username: `admin`
- Password: `123456`

---

## 🚀 Deploy ke Hosting

### 📌 **Deploy ke InfinityFree / Hosting Gratis**

1. **Upload Files**
   - Upload semua file via FTP (gunakan FileZilla)
   - FTP Host: `ftpupload.net`

2. **Setup Database**
   - Login ke cPanel → MySQL Databases
   - Buat database baru
   - Import `uaspbw.sql` via phpMyAdmin

3. **Konfigurasi `.env`** (PENTING!)
   ```env
   # KHUSUS InfinityFree - Format berbeda!
   DB_HOST=sql###.infinityfree.com
   DB_USER=epiz_#######
   DB_PASS=password_anda
   DB_NAME=epiz_#######_uaspbw
   
   GEMINI_API_KEY=your_api_key
   ```

4. **Test Website**
   ```
   https://yourdomain.infinityfreeapp.com/
   ```

> ⚠️ **Catatan Penting untuk InfinityFree:**
> - AI Generator **mungkin tidak berfungsi** (API external sering diblokir)
> - Upload gambar work normal
> - Database connection work dengan hostname khusus

📖 **Lihat panduan lengkap**: [troubleshooting_hosting.md](troubleshooting_hosting.md)

---

## 📖 Cara Menggunakan

### 🌐 **Public Side**

1. Buka `http://localhost/Creating-AI/`
2. Browse artikel, gallery, schedule
3. Klik artikel untuk detail
4. Toggle dark/light mode dengan tombol di navbar

### 🔐 **Admin Panel**

1. **Login**
   ```
   http://localhost/Creating-AI/login.php
   Username: admin
   Password: 123456
   ```

2. **Create Artikel Manual**
   - Klik "Article" di navbar
   - Klik "Tambah"
   - Isi judul, isi, upload gambar
   - Submit

3. **Generate Artikel dengan AI**
   - Di halaman Article
   - Klik "Generate with AI" button
   - Masukkan judul artikel
   - Klik "Generate Article"
   - Edit hasil generate jika perlu
   - Submit

4. **Manage Gallery**
   - Klik "Gallery"
   - Upload foto member NCT Dream
   - Delete foto yang tidak diperlukan

---

## 🗄️ Database Schema

### Table: `article`
| Column | Type | Description |
|--------|------|-------------|
| id | INT(11) | Primary Key |
| judul | TEXT | Judul artikel |
| isi | TEXT | Isi artikel |
| gambar | TEXT | Nama file gambar |
| tanggal | DATETIME | Tanggal publish |
| username | VARCHAR(50) | Author |

### Table: `gallery`
| Column | Type | Description |
|--------|------|-------------|
| id | INT(11) | Primary Key |
| gambar | TEXT | Nama file gambar |
| tanggal | DATETIME | Upload date |

### Table: `user`
| Column | Type | Description |
|--------|------|-------------|
| id | INT(11) | Primary Key |
| username | VARCHAR(50) | Username login |
| password | TEXT | Password (MD5 hash) |
| foto | TEXT | Profile photo |

---

## 🔒 Keamanan

### ✅ **Best Practices yang Diterapkan:**

1. **Environment Variables**
   - Kredensial database di `.env`
   - `.env` masuk `.gitignore`
   - No hardcoded passwords

2. **Session Management**
   - Login authentication dengan PHP session
   - Auto redirect jika belum login

3. **File Upload Security**
   - Validasi tipe file gambar
   - Rename file dengan timestamp

### ⚠️ **TODO - Security Improvements:**
- [ ] Ganti MD5 dengan password_hash()
- [ ] Implement CSRF protection
- [ ] Input validation & sanitization
- [ ] SQL injection prevention (use prepared statements)

---

## 🤝 Tim Developer

| Nama | NIM | Role |
|------|-----|------|
| **Naia Syafina H** | A11.2024.15554 | Lead Developer |
| **Nadjwa Salsabila W** | A11.2024.15670 | Developer |
| **Timothy Giovanny** | A11.2024.15646 | Developer |
| **Raffael Ezra N** | A11.2024.15667 | Developer |

---

## 🐛 Troubleshooting

### ❌ **Error: HTTP 500**
**Solusi**: Cek syntax error di PHP files. Pastikan semua statement diakhiri dengan semicolon (`;`)

### ❌ **Error: Connection Failed**
**Solusi**: 
- Cek kredensial di `.env`
- Pastikan MySQL running
- Test koneksi database

### ❌ **Error: .env file not found**
**Solusi**: Buat file `.env` di root folder dengan format yang benar

### ❌ **AI Generator tidak berfungsi**
**Solusi**:
- Cek API key di `.env`
- Pastikan ada koneksi internet
- Cek quota API di Google Console
- Jika di hosting gratis, fitur ini mungkin diblokir

### ❌ **Error: Git push rejected**
**Solusi**:
```bash
# Pull dulu dari remote
git pull origin naia

# Resolve conflicts jika ada
# Lalu push lagi
git push origin naia
```

📖 **Lihat panduan lengkap**: [Troubleshooting Hosting](troubleshooting_hosting.md)

---

## 📝 License

Project ini dibuat untuk tugas **UAS Pemrograman Berbasis Web** - Universitas Dian Nuswantoro 2024.

---

## 🙏 Credits

- **NCT Dream** - Inspirasi & konten
- **Bootstrap** - UI Framework
- **Google Gemini** - AI Integration
- **Bootstrap Icons** - Icon library

---

## 📞 Support

Jika ada pertanyaan atau issue:
- 📧 Email: syafinanaia@gmail.com
- 💬 WhatsApp: +62 822-4154-9915
- 🐛 Issues: [GitHub Issues](https://github.com/naiasyafina/Creating-AI/issues)

---

<div align="center">

**Made with ❤️ by UGduapunya Team**

*Dream Together, Grow Together, Forever* 🌟

</div>
