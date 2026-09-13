# 🏦 Fitur Upload Logo Bank

## ✅ Fitur yang Ditambahkan

### 1. **Upload Logo Bank di Admin Panel**
- Form upload logo bank di section "Gift & Alamat"
- Preview logo setelah upload
- Validasi file (gambar, maksimal 2MB)
- Support format: JPG, PNG, GIF, WebP

### 2. **Tampilan Logo di Frontend**
- Logo bank ditampilkan di section Gift
- Fallback icon jika tidak ada logo
- Responsive design untuk semua device
- Terintegrasi dengan informasi bank existing

### 3. **Database Integration**
- Setting `gift_bank_logo` untuk menyimpan path logo
- Auto-save ke database saat upload
- Kompatibel dengan sistem multi-invitation

## 🗄️ **Perubahan Database**

### **Settings Baru:**
```sql
-- Ditambahkan ke tabel settings
(1, 'gift_bank_logo', '')  -- Path ke file logo bank
```

## 📁 **File yang Dimodifikasi**

### **1. Database & API**
- `api/schema.sql` - Tambah default setting gift_bank_logo
- `api/admin_api.php` - Endpoint upload_bank_logo

### **2. Admin Panel**
- `admin.php` - Form upload logo dan preview

### **3. Frontend**
- `index.php` - Tampilan logo di section Gift

### **4. File Testing**
- `test_bank_logo.html` - Testing upload dan preview

## 🎨 **Implementasi UI**

### **Admin Panel:**
```html
<!-- Upload Area -->
<label class="block aspect-[3/2] bg-secondary/5 rounded-xl border border-dashed border-secondary/30 flex flex-col items-center justify-center cursor-pointer overflow-hidden group max-w-[120px] mx-auto">
    <img id="preview-gift_bank_logo" src="" class="w-full h-full object-contain hidden">
    <div id="placeholder-gift_bank_logo" class="text-secondary opacity-40 text-center">
        <span class="material-symbols-outlined text-2xl">account_balance</span>
        <p class="text-[8px] font-bold mt-1">LOGO BANK</p>
    </div>
    <input type="file" class="hidden" accept="image/*" onchange="uploadBankLogo()">
    <input type="hidden" id="set-gift_bank_logo">
</label>
```

### **Frontend Display:**
```html
<!-- Bank Logo -->
<?php if (getSet('gift_bank_logo')): ?>
  <div class="w-12 h-8 flex items-center justify-center bg-white rounded border border-outline-variant/20 overflow-hidden flex-shrink-0">
    <img src="<?php echo getSet('gift_bank_logo'); ?>" alt="<?php echo getSet('gift_bank', 'Bank'); ?> Logo" class="w-full h-full object-contain">
  </div>
<?php else: ?>
  <div class="w-12 h-8 flex items-center justify-center bg-secondary/10 rounded border border-secondary/20 flex-shrink-0">
    <span class="material-symbols-outlined text-secondary text-sm">account_balance</span>
  </div>
<?php endif; ?>
```

## 🔧 **API Endpoints**

### **Upload Logo Bank:**
```
POST api/admin_api.php?action=upload_bank_logo&inv_id={id}
Content-Type: multipart/form-data

Body:
- image: File (JPG, PNG, GIF, WebP, max 2MB)

Response:
{
  "success": true,
  "path": "uploads/bank_logo_xxxxx.jpg"
}
```

### **Get Settings (Updated):**
```
GET api/admin_api.php?action=get_settings&inv_id={id}

Response includes:
{
  "success": true,
  "data": {
    "gift_bank": "BANK BRI",
    "gift_account": "00550 11527 30502",
    "gift_owner": "Elvy Nur Fauziyah",
    "gift_bank_logo": "uploads/bank_logo_xxxxx.jpg"
  }
}
```

## 🚀 **Cara Menggunakan**

### **1. Upload Logo di Admin:**
1. Login ke admin panel (`admin.php`)
2. Klik tab "Data Utama"
3. Scroll ke section "Gift & Alamat"
4. Klik area "LOGO BANK" untuk upload
5. Pilih file logo bank (JPG/PNG/GIF/WebP, max 2MB)
6. Preview akan muncul setelah upload
7. Klik "Simpan Perubahan" untuk menyimpan

### **2. Lihat Hasil di Frontend:**
1. Buka `index.php`
2. Scroll ke section "Gift"
3. Logo bank akan muncul di samping informasi bank

## 🎯 **Fitur Unggulan**

### **1. Smart Fallback**
- Jika tidak ada logo: Tampilkan icon bank default
- Jika ada logo: Tampilkan logo dengan proper sizing
- Responsive di semua device

### **2. File Validation**
- Tipe file: Hanya gambar (JPG, PNG, GIF, WebP)
- Ukuran: Maksimal 2MB
- Error handling yang user-friendly

### **3. Professional Display**
- Logo dalam container dengan border
- Aspect ratio 3:2 untuk konsistensi
- Object-fit contain untuk menjaga proporsi
- Background putih untuk kontras

### **4. Admin UX**
- Drag & drop area yang intuitif
- Preview real-time setelah upload
- Placeholder yang jelas
- Feedback sukses/error

## 📱 **Responsive Design**

### **Desktop:**
- Logo 48px × 32px
- Layout horizontal dengan flex

### **Mobile:**
- Logo tetap proporsional
- Layout tetap readable
- Touch-friendly untuk admin

## 🧪 **Testing**

### **File Testing:**
- `test_bank_logo.html` - Comprehensive testing
- Upload functionality test
- Preview display test
- API integration test

### **Test Cases:**
1. **Upload Valid Image** ✅
   - JPG, PNG, GIF, WebP
   - Size < 2MB
   - Preview muncul

2. **Upload Invalid File** ✅
   - Non-image file → Error message
   - Size > 2MB → Error message
   - Proper error handling

3. **Frontend Display** ✅
   - Logo muncul dengan benar
   - Fallback icon jika tidak ada logo
   - Responsive di berbagai device

4. **Admin Integration** ✅
   - Save settings dengan logo
   - Load settings dengan logo
   - Preview update real-time

## 🎨 **Contoh Logo Bank**

Untuk testing, bisa menggunakan logo dari:
- BRI: Logo merah dengan teks putih
- BCA: Logo biru dengan teks putih  
- Mandiri: Logo kuning-orange
- BNI: Logo hijau dengan teks putih

## 📋 **Best Practices**

### **Logo Requirements:**
- **Format**: PNG dengan background transparan (recommended)
- **Size**: Minimal 96×64px untuk kualitas baik
- **Aspect Ratio**: 3:2 atau mendekati
- **File Size**: < 500KB untuk loading cepat

### **Design Guidelines:**
- Logo harus readable dalam ukuran kecil
- Kontras yang baik dengan background putih
- Hindari logo dengan detail terlalu kecil

---

**Fitur Upload Logo Bank berhasil diimplementasi! 🎉**

Sekarang admin dapat mengupload logo bank untuk membuat tampilan gift section lebih profesional dan menarik.