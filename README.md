# 🎓 Sistem Informasi Pembayaran SPP

<div align="center">

![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.x-EE4623?style=for-the-badge&logo=codeigniter&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)

**Sistem Manajemen Pembayaran SPP Modern & Efisien**

[Fitur](#-fitur-utama) • [Instalasi](#-instalasi) • [Dokumentasi](#-dokumentasi) • [Screenshot](#-screenshot) • [Kontribusi](#-kontribusi)

</div>

---

## 📋 Deskripsi

Sistem Informasi Pembayaran SPP adalah aplikasi web berbasis CodeIgniter 4 yang dirancang untuk memudahkan pengelolaan pembayaran SPP (Sumbangan Pembinaan Pendidikan) dan pembayaran lainnya di institusi pendidikan. Sistem ini dilengkapi dengan fitur manajemen siswa, kelas, pembayaran, pelaporan, dan pengajuan tunggakan.

## ✨ Fitur Utama

### 👨‍💼 Panel Admin
- ✅ **Dashboard Interaktif** - Monitoring real-time transaksi pembayaran
- 📊 **Manajemen Data Master**
  - Data Siswa & Kelas
  - Data Guru
  - Jenis Pembayaran
  - Pengaturan SPP
- 💰 **Manajemen Pembayaran**
  - Verifikasi Pembayaran SPP
  - Verifikasi Pembayaran Lain
  - Riwayat Transaksi
- 📑 **Laporan Lengkap**
  - Laporan Pembayaran SPP (CSV, Excel, PDF)
  - Laporan Pembayaran Lain (CSV, Excel, PDF)
  - Laporan Tunggakan (CSV, Excel, PDF)
  - Filter berdasarkan periode, kelas, status
- 🔄 **Transaksi Tunggakan**
  - Approve/Reject pengajuan tunggakan
  - Update jatuh tempo pembayaran
  - Tracking status tunggakan

### 👨‍🎓 Panel Siswa
- 📱 **Dashboard Siswa** - Info tagihan & status pembayaran
- 💳 **Pembayaran**
  - Upload bukti pembayaran SPP
  - Upload bukti pembayaran lain
  - Riwayat pembayaran
- 📝 **Pengajuan Tunggakan**
  - Form pengajuan tunggakan
  - Upload bukti pendukung
  - Tracking status pengajuan
  - Cancel pengajuan (pending only)

### 🔐 Keamanan
- Authentication & Authorization
- Role-based Access Control (Admin & Siswa)
- Session Management
- CSRF Protection
- XSS Protection

## 🛠️ Teknologi

| Teknologi | Versi | Keterangan |
|-----------|-------|------------|
| **CodeIgniter** | 4.x | PHP Framework |
| **PHP** | 8.1+ | Backend Language |
| **MySQL** | 8.0+ | Database |
| **Bootstrap** | 5.3 | CSS Framework |
| **jQuery** | 3.x | JavaScript Library |
| **PhpSpreadsheet** | 1.10+ | Excel Export |
| **Dompdf** | 2.0+ | PDF Export |

## 📦 Instalasi

### Prasyarat

Pastikan sistem Anda memiliki:
- PHP >= 8.1
- MySQL >= 8.0
- Composer
- Apache/Nginx Web Server

### Extension PHP yang Diperlukan:
```
- intl
- mbstring
- json
- mysqlnd
- libcurl
- gd (untuk manipulasi gambar)
- zip (untuk PhpSpreadsheet)
```

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/username/pendataan-spp.git
   cd pendataan-spp
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**
   ```bash
   cp env .env
   ```

4. **Edit file `.env`**
   ```env
   CI_ENVIRONMENT = production
   
   app.baseURL = 'http://localhost/pendataan-spp/public/'
   
   database.default.hostname = localhost
   database.default.database = data_spp
   database.default.username = root
   database.default.password = 
   database.default.DBDriver = MySQLi
   ```

5. **Import Database**
   - Buat database baru: `data_spp`
   - Import file: `database/data_spp.sql`
   ```bash
   mysql -u root -p data_spp < database/data_spp.sql
   ```

6. **Set Permissions**
   ```bash
   chmod -R 777 writable/
   chmod -R 777 public/uploads/
   ```

7. **Jalankan Aplikasi**
   
   **Development Server:**
   ```bash
   php spark serve
   ```
   Akses: `http://localhost:8080`
   
   **Production (Apache/Nginx):**
   - Point document root ke folder `public/`
   - Akses sesuai virtual host

## 👤 Akun Default

### Admin
```
Username: admin
Password: admin123
```

### Siswa
```
NIS: 2024001
Password: siswa123
```

> ⚠️ **Penting:** Segera ubah password default setelah login pertama!

## 📁 Struktur Folder

```
pendataan-spp/
├── app/
│   ├── Controllers/        # Controller files
│   ├── Models/             # Model files
│   ├── Views/              # View files
│   └── Config/             # Configuration files
├── public/
│   ├── uploads/            # User uploaded files
│   │   ├── bukti_pembayaran/
│   │   └── bukti_tunggakan/
│   ├── css/                # CSS files
│   ├── js/                 # JavaScript files
│   └── index.php           # Entry point
├── writable/
│   ├── cache/              # Cache files
│   ├── logs/               # Log files
│   └── session/            # Session files
├── database/
│   └── data_spp.sql        # Database schema
└── vendor/                 # Composer dependencies
```

## 🎨 Screenshot

### Dashboard Admin
![Dashboard Admin](screenshot/dashboard-admin.png)

### Laporan Pembayaran
![Laporan](screenshot/laporan.png)

### Dashboard Siswa
![Dashboard Siswa](screenshot/dashboard-siswa.png)

## 📖 Dokumentasi

### Routing Utama

| Route | Method | Keterangan |
|-------|--------|------------|
| `/login` | GET/POST | Halaman login |
| `/dashboard` | GET | Dashboard (Admin/Siswa) |
| `/siswa/pembayaran-spp` | GET | Upload bukti SPP |
| `/siswa/tunggakan` | GET | List pengajuan tunggakan |
| `/laporan-pembayaran-spp` | GET | Laporan SPP |
| `/laporan-pembayaran-lain` | GET | Laporan Pembayaran Lain |
| `/laporan-tunggakan` | GET | Laporan Tunggakan |
| `/tunggakan-transaksi` | GET | Kelola tunggakan (Admin) |

### Export Data

Sistem mendukung export data dalam 3 format:

1. **CSV** - Compatible dengan Excel/Google Sheets
   ```
   /laporan-pembayaran-spp/export-csv
   ```

2. **Excel (.xlsx)** - Format native Excel
   ```
   /laporan-pembayaran-spp/export-excel
   ```

3. **PDF** - Untuk keperluan cetak
   ```
   /laporan-pembayaran-spp/export-pdf
   ```

### Filter Laporan

Semua laporan mendukung filter:
- Status pembayaran (Pending/Verified/Rejected)
- Periode tanggal
- Kelas
- Bulan & Tahun (khusus SPP)
- Kategori pembayaran (khusus Pembayaran Lain)

## 🔧 Konfigurasi

### Upload File

Edit `app/Config/Constants.php`:
```php
define('MAX_UPLOAD_SIZE', 2048); // 2MB
define('ALLOWED_FILE_TYPES', 'jpg|jpeg|png|pdf');
```

### Session

Edit `app/Config/App.php`:
```php
public $sessionDriver = 'CodeIgniter\Session\Handlers\FileHandler';
public $sessionExpiration = 7200; // 2 jam
```

## 🐛 Troubleshooting

### Error: "Class PhpOffice\PhpSpreadsheet not found"
```bash
composer require phpoffice/phpspreadsheet:1.10.0 --ignore-platform-reqs
```

### Error: Upload file gagal
- Pastikan folder `public/uploads/` memiliki permission 777
- Check `upload_max_filesize` di `php.ini`

### Error: Session tidak tersimpan
- Pastikan folder `writable/session/` exists dan writable
- Check `session.save_path` di `php.ini`

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan:

1. Fork repository ini
2. Buat branch fitur (`git checkout -b fitur-baru`)
3. Commit perubahan (`git commit -m 'Tambah fitur baru'`)
4. Push ke branch (`git push origin fitur-baru`)
5. Buat Pull Request

## 📝 Changelog

### Version 1.0.0 (2026-01-06)
- ✅ Initial release
- ✅ Manajemen data master lengkap
- ✅ Pembayaran SPP & Pembayaran Lain
- ✅ Pengajuan tunggakan
- ✅ Laporan dengan export CSV/Excel/PDF
- ✅ Dashboard interaktif

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Developer

Developed with ❤️ by **Fadia Nurcholifah**

- GitHub: [@Fadia0504](https://github.com/Fadia0504)
- Email: fadianurkholifah@gmail.com.com

## 🙏 Acknowledgments

- [CodeIgniter](https://codeigniter.com/) - The PHP Framework
- [Bootstrap](https://getbootstrap.com/) - CSS Framework
- [PhpSpreadsheet](https://phpspreadsheet.readthedocs.io/) - Excel Library
- [Dompdf](https://github.com/dompdf/dompdf) - PDF Library
- [Bootstrap Icons](https://icons.getbootstrap.com/) - Icon Library

---

<div align="center">

**⭐ Star this repo if you find it helpful!**

Made with 💙 using CodeIgniter 4

</div>