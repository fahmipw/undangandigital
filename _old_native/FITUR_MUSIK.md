# 🎵 Fitur Upload Musik - Web Invitation

Fitur upload musik telah berhasil ditambahkan ke web invitation Anda! Berikut adalah penjelasan lengkap tentang fitur ini.

## ✨ Fitur yang Ditambahkan

### 1. **Upload Musik di Admin Panel**
- Tab "Musik" baru di admin panel
- Upload file audio (MP3, WAV, OGG, M4A)
- Maksimal ukuran file: 10MB
- Preview musik dengan audio player
- Aktivasi/deaktivasi musik
- Hapus file musik

### 2. **Pengaturan Musik**
- **Volume Musik**: Atur volume dari 0-100%
- **Auto Play**: Pilih apakah musik otomatis diputar atau menunggu klik user

### 3. **Integrasi Frontend**
- Musik otomatis dimuat berdasarkan pengaturan admin
- Fallback ke `lagu.mp3` jika tidak ada musik yang diupload
- Kontrol musik tetap menggunakan tombol yang sudah ada

## 🗄️ Perubahan Database

Tabel baru `music` telah ditambahkan dengan struktur:
```sql
CREATE TABLE music (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  invitation_id INT UNSIGNED NOT NULL,
  file_name     VARCHAR(255) NOT NULL,
  file_path     VARCHAR(255) NOT NULL,
  file_size     INT UNSIGNED NOT NULL,
  is_active     TINYINT(1) NOT NULL DEFAULT 0,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
```

Settings baru:
- `music_volume`: Volume musik (0-100)
- `music_autoplay`: Auto play musik (0/1)

## 📁 File yang Dimodifikasi

### 1. **Database & API**
- `api/schema.sql` - Tambah tabel musik dan settings
- `api/admin_api.php` - Endpoint untuk manajemen musik
- `api/music.php` - API untuk mendapatkan musik aktif

### 2. **Admin Panel**
- `admin.php` - Tab musik dan fungsi JavaScript

### 3. **Frontend**
- `nav.js` - Modifikasi sistem musik untuk menggunakan upload
- `index.php` - Tambah variabel global invId

### 4. **File Baru**
- `uploads/.htaccess` - Konfigurasi akses file upload
- `test_music.html` - File testing (opsional)

## 🚀 Cara Menggunakan

### 1. **Akses Admin Panel**
1. Login ke admin panel (`admin.php`)
2. Klik tab "Musik" di sidebar

### 2. **Upload Musik**
1. Klik tombol "+ Upload Lagu"
2. Pilih file audio (MP3, WAV, OGG, M4A)
3. File akan otomatis menjadi aktif dan menggantikan musik sebelumnya

### 3. **Pengaturan Musik**
1. Atur volume musik (0-100%)
2. Pilih auto play (Ya/Tidak)
3. Klik "Simpan Pengaturan"

### 4. **Manajemen Musik**
- **Preview**: Gunakan audio player untuk mendengar musik
- **Aktivasi**: Klik tombol "Aktif/Nonaktif" untuk mengubah status
- **Hapus**: Klik ikon delete untuk menghapus file

## 🔧 Fitur Teknis

### **Multi-Invitation Support**
- Setiap undangan bisa memiliki musik berbeda
- Pengaturan musik terpisah per undangan

### **Fallback System**
- Jika tidak ada musik yang diupload, sistem akan menggunakan `lagu.mp3`
- Jika file musik rusak/hilang, akan fallback ke default

### **Validasi Upload**
- Tipe file: Hanya audio yang diperbolehkan
- Ukuran: Maksimal 10MB
- Keamanan: File PHP tidak bisa dieksekusi di folder uploads

### **Performance**
- Hanya satu musik aktif per undangan
- File lama otomatis dihapus saat upload baru
- Lazy loading musik di frontend

## 🧪 Testing

Gunakan file `test_music.html` untuk testing:
1. Buka `http://localhost/invitation/test_music.html`
2. Test upload musik
3. Lihat daftar musik
4. Test API musik

## 📝 Catatan Penting

1. **Backup**: Selalu backup database sebelum menggunakan fitur baru
2. **Permissions**: Pastikan folder `uploads/` memiliki permission write
3. **File Size**: Sesuaikan `upload_max_filesize` di PHP jika perlu upload file lebih besar
4. **Browser Support**: Fitur audio HTML5 didukung semua browser modern

## 🎯 Penggunaan Praktis

### **Skenario 1: Ganti Musik Default**
1. Upload musik baru di admin
2. Musik otomatis aktif dan menggantikan `lagu.mp3`
3. Pengunjung akan mendengar musik baru

### **Skenario 2: Multiple Musik**
1. Upload beberapa file musik
2. Aktifkan salah satu yang diinginkan
3. Musik lain tetap tersimpan untuk digunakan nanti

### **Skenario 3: Disable Musik**
1. Set semua musik ke "Nonaktif"
2. Sistem akan fallback ke `lagu.mp3`
3. Atau set "Auto Play" ke "Tidak"

## 🔮 Pengembangan Selanjutnya

Fitur yang bisa ditambahkan di masa depan:
- Playlist musik (multiple active songs)
- Fade in/out antar lagu
- Musik berbeda per section
- Upload dari URL
- Kompres audio otomatis
- Visualizer musik

---

**Selamat menggunakan fitur upload musik! 🎵**

Jika ada pertanyaan atau masalah, silakan hubungi developer.