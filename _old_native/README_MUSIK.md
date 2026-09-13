# 🎵 Panduan Singkat Fitur Upload Musik

## Cara Menggunakan:

### 1. **Login Admin**
- Buka `http://localhost/invitation/admin.php`
- Login dengan username: `admin`, password: `admin123`

### 2. **Upload Musik**
- Klik tab "Musik" di sidebar
- Klik "+ Upload Lagu"
- Pilih file audio (MP3, WAV, OGG, M4A) maksimal 10MB
- File akan otomatis aktif

### 3. **Pengaturan**
- Atur volume musik (0-100%)
- Pilih auto play (Ya/Tidak)
- Klik "Simpan Pengaturan"

### 4. **Test di Frontend**
- Buka `http://localhost/invitation/index.php`
- Musik baru akan otomatis dimuat
- Gunakan tombol musik di header untuk kontrol

## File Testing:
- `test_music.html` - Untuk test upload dan API
- File musik akan disimpan di folder `uploads/`

## Fallback:
- Jika tidak ada musik upload, akan menggunakan `lagu.mp3`
- Sistem otomatis handle error dan fallback

**Selamat mencoba! 🎉**