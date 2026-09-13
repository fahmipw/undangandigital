# 🏦 Panduan Upload Logo Bank

## Cara Menggunakan:

### 1. **Login Admin**
- Buka `http://localhost/invitation/admin.php`
- Login dengan username: `admin`, password: `admin123`

### 2. **Upload Logo Bank**
- Klik tab "Data Utama" di sidebar
- Scroll ke section "Gift & Alamat"
- Klik area "LOGO BANK" (icon bank)
- Pilih file logo bank (JPG, PNG, GIF, WebP)
- Maksimal ukuran: 2MB
- Preview akan muncul otomatis

### 3. **Simpan Perubahan**
- Klik tombol "Simpan Perubahan" di atas
- Logo akan tersimpan ke database

### 4. **Lihat Hasil**
- Buka `http://localhost/invitation/index.php`
- Scroll ke section "Gift"
- Logo bank akan muncul di samping info rekening

## File Testing:
- `test_bank_logo.html` - Untuk test upload dan preview
- Contoh logo bank tersedia di file testing

## Tips:
- **Format terbaik**: PNG dengan background transparan
- **Ukuran ideal**: 96×64px atau lebih
- **Aspect ratio**: 3:2 (landscape)
- **File size**: < 500KB untuk loading cepat

## Fallback:
- Jika tidak ada logo: Tampil icon bank default 🏦
- Sistem otomatis handle error dan fallback

**Selamat mencoba! 🎉**