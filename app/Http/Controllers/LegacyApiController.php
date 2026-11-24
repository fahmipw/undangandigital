<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invitation;
use App\Models\Guest;
use App\Models\Gallery;
use App\Models\Story;
use App\Models\Music;
use App\Models\Setting;
use App\Models\Rsvp;
use App\Models\Ucapan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class LegacyApiController extends Controller
{
    public function adminApi(Request $request)
    {
        if (!auth()->guard('admin')->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $json = json_decode($request->getContent(), true) ?? [];
        if (!empty($json)) {
            $request->merge($json);
        }

        $action = $request->query('action', '') ?: $request->input('action', '');
        $inv_id = $request->query('inv_id', 1) ?: $request->input('inv_id', 1);

        if (ob_get_length()) ob_clean(); 
        
        // Disable error reporting for JSON responses to avoid warnings/notices leaking
        error_reporting(0);
        ini_set('display_errors', 0);

        if (!$action) {
            return response()->json(['success' => false, 'message' => 'Action parameter required'], 400);
        }

        switch ($action) {
            case 'get_invitations':
                return response()->json([
                    'success' => true,
                    'data' => Invitation::orderBy('created_at', 'DESC')->get()
                ]);

            case 'get_stats':
                // Undangan "aktif" = tanggal acaranya (resepsi, fallback akad)
                // belum lewat; undangan tanpa tanggal dianggap masih aktif.
                $today = now()->toDateString();
                $dateRows = Setting::whereIn('key_name', ['wedding_date', 'reception_date'])
                    ->whereNotNull('key_value')->where('key_value', '!=', '')
                    ->get(['invitation_id', 'key_name', 'key_value']);
                $eventDates = [];
                foreach ($dateRows as $row) {
                    if ($row->key_name === 'reception_date') {
                        $eventDates[$row->invitation_id] = $row->key_value;
                    } elseif (!isset($eventDates[$row->invitation_id])) {
                        $eventDates[$row->invitation_id] = $row->key_value;
                    }
                }
                $total = Invitation::count();
                $past = 0;
                foreach ($eventDates as $date) {
                    if ($date < $today) $past++;
                }
                return response()->json([
                    'success' => true,
                    'data' => [
                        'total' => $total,
                        'active' => $total - $past,
                        'past' => $past,
                    ]
                ]);

            case 'add_invitation':
                $inv = Invitation::create([
                    'slug' => $request->input('slug'),
                    'title' => $request->input('title')
                ]);
                return response()->json(['success' => true, 'id' => $inv->id]);

            case 'delete_invitation':
                $id = $request->query('id');
                if ($id == 1) {
                    return response()->json(['success' => false, 'message' => 'Undangan utama tidak dapat dihapus'], 403);
                }
                Invitation::destroy($id);
                return response()->json(['success' => true]);

            case 'update_invitation':
                $id = $request->input('id');
                $title = trim((string) $request->input('title', ''));
                if (!$id || $title === '') {
                    return response()->json(['success' => false, 'message' => 'Nama undangan harus diisi'], 400);
                }
                Invitation::where('id', $id)->update(['title' => $title]);
                return response()->json(['success' => true]);

            case 'get_settings':
                $settings = Setting::where('invitation_id', $inv_id)->get();
                $data = [];
                foreach ($settings as $s) {
                    $data[$s->key_name] = $s->key_value;
                }
                return response()->json(['success' => true, 'data' => $data]);

            case 'get_landing_themes':
                // Katalog landing untuk tab "Landing Page" di admin: SEMUA tema
                // (termasuk yang disembunyikan) + pengaturan landing efektif.
                $themes = \App\Http\Controllers\FrontController::catalogThemes(false);
                $eff = \App\Http\Controllers\FrontController::landingSettingsEffective($themes);
                return response()->json(['success' => true, 'data' => [
                    'themes' => $themes,
                    'harga_basic' => (string) $eff['harga_basic'],
                    'harga_premium' => (string) $eff['harga_premium'],
                    'harga_eksklusif' => (string) $eff['harga_eksklusif'],
                    'hero_title' => $eff['hero_title'],
                    'hero_highlight' => $eff['hero_highlight'],
                    'hero_subtitle' => $eff['hero_subtitle'],
                    'wa' => $eff['wa'],
                ]]);

            case 'upload_landing_theme_photo':
                // Foto preview katalog landing: potong tengah jadi rasio 3:4
                // (seperti kartu katalog), maks 780x1040, timpa file lama
                // public/images/landing/themes/<slug>.jpg.
                // Bila POST kosong total, file melewati batas post_max_size
                // PHP — PHP membuang seluruh body tanpa pesan, jadi beri
                // tahu langsung (sebelum cek slug, karena slug ikut hilang).
                if (empty($_FILES) && empty($_POST)) {
                    return response()->json(['success' => false, 'message' => 'File tidak sampai ke server (kemungkinan melewati batas upload PHP). Coba foto di bawah 2MB, atau naikkan upload_max_filesize & post_max_size di php.ini Laragon lalu restart Apache.'], 400);
                }
                $slug = (string) $request->input('slug', '');
                $catalogSlugs = array_map(function ($t) { return $t['slug']; }, \App\Http\Controllers\FrontController::catalogThemes(false));
                if (!in_array($slug, $catalogSlugs, true)) {
                    return response()->json(['success' => false, 'message' => 'Tema tidak dikenal.'], 400);
                }
                // Kode error upload PHP (1 = melewati upload_max_filesize).
                // Laravel hasFile() false untuk file error begini, jadi cek
                // $_FILES langsung supaya pesannya tidak sekadar "No file".
                $upErr = $_FILES['photo']['error'] ?? null;
                if ($upErr !== null && $upErr !== UPLOAD_ERR_OK) {
                    $msg = 'Upload gagal (kode ' . $upErr . ').';
                    if ($upErr === UPLOAD_ERR_INI_SIZE || $upErr === UPLOAD_ERR_FORM_SIZE) {
                        $msg = 'Foto terlalu besar untuk batas upload PHP. Coba foto di bawah 2MB, atau naikkan upload_max_filesize di php.ini Laragon lalu restart Apache.';
                    } elseif ($upErr === UPLOAD_ERR_NO_FILE) {
                        $msg = 'Tidak ada file yang dipilih.';
                    }
                    return response()->json(['success' => false, 'message' => $msg], 400);
                }
                if (!$request->hasFile('photo')) {
                    return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
                }
                $file = $request->file('photo');
                if ($file->getSize() > 5 * 1024 * 1024) {
                    return response()->json(['success' => false, 'message' => 'Ukuran file maksimal 5MB'], 400);
                }
                $ext = strtolower($file->getClientOriginalExtension());
                if ($ext === 'heic' || $ext === 'heif') {
                    return response()->json(['success' => false, 'message' => 'Foto iPhone (HEIC) belum didukung. Ubah dulu ke JPG — di iPhone: Pengaturan > Kamera > Format > Paling Kompatibel, lalu foto ulang; atau konversi file ini ke JPG.'], 400);
                }
                if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    return response()->json(['success' => false, 'message' => 'Tipe file tidak didukung. Pakai JPG/PNG/GIF/WebP.'], 400);
                }
                // Tanpa GD, imagecreatefrom* tidak ada (fatal) — galeri
                // selamat karena convertToWebp punya fallback, handler ini
                // tidak (log: "Call to undefined function ...imagecreatefromjpeg()").
                // Samakan: JPG langsung disimpan tanpa crop (katalog pakai
                // object-cover, jadi tetap tampil benar); non-JPG ditolak
                // dengan pesan jelas.
                $dir = public_path('images/landing/themes');
                if (!file_exists($dir)) mkdir($dir, 0777, true);
                if (!function_exists('imagecreatefromjpeg')) {
                    if (in_array($ext, ['jpg', 'jpeg'])) {
                        $file->move($dir, $slug . '.jpg');
                        return response()->json(['success' => true, 'path' => 'images/landing/themes/' . $slug . '.jpg']);
                    }
                    return response()->json(['success' => false, 'message' => 'Ekstensi GD tidak aktif di PHP server, jadi foto ' . strtoupper($ext) . ' tidak bisa diubah ke JPG. Aktifkan GD di Laragon (PHP > Extensions > gd, lalu restart Apache), atau unggah file JPG.'], 400);
                }
                // GD yang mati / foto rusak / memori habis sebelumnya jadi
                // 500 HTML tanpa pesan — tangkap jadi JSON supaya popup
                // admin menampilkan penyebabnya.
                try {
                    $srcPath = $file->getRealPath();
                    $src = false;
                    if (in_array($ext, ['jpg', 'jpeg'])) $src = @imagecreatefromjpeg($srcPath);
                    elseif ($ext === 'png') $src = @imagecreatefrompng($srcPath);
                    elseif ($ext === 'gif') $src = @imagecreatefromgif($srcPath);
                    elseif ($ext === 'webp' && function_exists('imagecreatefromwebp')) $src = @imagecreatefromwebp($srcPath);
                    if ($src === false) {
                        return response()->json(['success' => false, 'message' => 'File gambar tidak dapat dibaca.'], 400);
                    }
                    $w = imagesx($src); $h = imagesy($src);
                    $scale = max(780 / $w, 1040 / $h);
                    if ($scale > 1) $scale = 1; // jangan perbesar foto kecil
                    $resized = imagescale($src, (int) round($w * $scale), (int) round($h * $scale));
                    imagedestroy($src);
                    if ($resized === false) {
                        return response()->json(['success' => false, 'message' => 'Gagal memproses gambar.'], 400);
                    }
                    $sw = imagesx($resized); $sh = imagesy($resized);
                    if ($sw / $sh > 3 / 4) { $ch = $sh; $cw = (int) round($sh * 3 / 4); }
                    else { $cw = $sw; $ch = (int) round($sw * 4 / 3); }
                    $crop = imagecreatetruecolor($cw, $ch);
                    imagecopy($crop, $resized, 0, 0, (int) (($sw - $cw) / 2), (int) (($sh - $ch) / 2), $cw, $ch);
                    imagedestroy($resized);
                    $dir = public_path('images/landing/themes');
                    if (!file_exists($dir)) mkdir($dir, 0777, true);
                    $ok = imagejpeg($crop, $dir . '/' . $slug . '.jpg', 82);
                    imagedestroy($crop);
                    if (!$ok) {
                        return response()->json(['success' => false, 'message' => 'Gagal menyimpan gambar.'], 400);
                    }
                } catch (\Throwable $e) {
                    return response()->json(['success' => false, 'message' => 'Gagal memproses gambar di server (' . $e->getMessage() . '). Coba foto JPG yang lebih kecil, dan pastikan ekstensi GD aktif di PHP.'], 400);
                }
                return response()->json(['success' => true, 'path' => 'images/landing/themes/' . $slug . '.jpg']);

            case 'upload_landing_theme_music':
                // Musik demo per tema: file audio disimpan di uploads/ dan
                // path-nya dicatat di landing_themes[slug].music oleh admin
                // (saveLanding). File musik tema lama dihapus saat diganti.
                $slug = (string) $request->input('slug', '');
                $catalogSlugs = array_map(function ($t) { return $t['slug']; }, \App\Http\Controllers\FrontController::catalogThemes(false));
                if (!in_array($slug, $catalogSlugs, true)) {
                    return response()->json(['success' => false, 'message' => 'Tema tidak dikenal.'], 400);
                }
                if (!$request->hasFile('music')) {
                    return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
                }
                $file = $request->file('music');
                $allowedTypes = ['audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/ogg', 'audio/m4a'];
                if (!in_array($file->getMimeType(), $allowedTypes) && !in_array($file->getClientMimeType(), $allowedTypes)) {
                    return response()->json(['success' => false, 'message' => 'Tipe file tidak didukung.'], 400);
                }
                if ($file->getSize() > 10 * 1024 * 1024) {
                    return response()->json(['success' => false, 'message' => 'Ukuran file maksimal 10MB'], 400);
                }
                if (!file_exists(public_path('uploads'))) {
                    mkdir(public_path('uploads'), 0777, true);
                }
                foreach (glob(public_path('uploads/music_theme_' . $slug . '_*')) as $old) {
                    @unlink($old);
                }
                $filename = 'music_theme_' . $slug . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $filename);
                return response()->json(['success' => true, 'path' => 'uploads/' . $filename]);

            case 'update_settings':
                $postData = $request->all();
                unset($postData['action'], $postData['inv_id']);
                
                $newSlug = null;
                if (!empty($postData['bride_nickname']) && !empty($postData['groom_nickname'])) {
                    $title = $postData['bride_nickname'] . ' & ' . $postData['groom_nickname'];
                    $slug = \Illuminate\Support\Str::slug($title);
                    
                    // pastikan unik
                    $count = \App\Models\Invitation::where('slug', $slug)->where('id', '!=', $inv_id)->count();
                    if ($count > 0) {
                        $slug = $slug . '-' . time();
                    }
                    
                    \App\Models\Invitation::where('id', $inv_id)->update([
                        'title' => $title,
                        'slug' => $slug
                    ]);
                    $newSlug = $slug;
                }
                
                foreach ($postData as $key => $value) {
                    \Illuminate\Support\Facades\DB::table('settings')->updateOrInsert(
                        ['invitation_id' => $inv_id, 'key_name' => $key],
                        ['key_value' => $value]
                    );
                }
                return response()->json(['success' => true, 'new_slug' => $newSlug]);

            case 'get_guests':
                return response()->json([
                    'success' => true,
                    'data' => Guest::where('invitation_id', $inv_id)->orderBy('nama', 'ASC')->get()
                ]);

            case 'add_guest':
                Guest::create([
                    'invitation_id' => $inv_id,
                    'nama' => $request->input('nama'),
                    'no_hp' => $request->input('no_hp'),
                    'slug' => urlencode($request->input('nama')),
                    'qr_code' => Guest::newQrCode($inv_id)
                ]);
                return response()->json(['success' => true]);

            case 'bulk_add_guests':
                $names = $request->input('names', []);
                foreach ($names as $line) {
                    if (trim($line) === '') continue;
                    $parts = explode(',', $line);
                    $nama = trim($parts[0]);
                    $no_hp = isset($parts[1]) ? trim($parts[1]) : null;
                    Guest::create([
                        'invitation_id' => $inv_id,
                        'nama' => $nama,
                        'no_hp' => $no_hp,
                        'slug' => urlencode($nama),
                        'qr_code' => Guest::newQrCode($inv_id)
                    ]);
                }
                return response()->json(['success' => true]);

            case 'delete_guest':
                Guest::destroy($request->query('id'));
                return response()->json(['success' => true]);

            case 'get_rsvp':
                return response()->json([
                    'success' => true,
                    'data' => Rsvp::where('invitation_id', $inv_id)->orderBy('created_at', 'DESC')->get()
                ]);

            case 'get_ucapan':
                return response()->json([
                    'success' => true,
                    'data' => Ucapan::where('invitation_id', $inv_id)->orderBy('created_at', 'DESC')->get()
                ]);

            case 'delete_rsvp':
                Rsvp::destroy($request->query('id'));
                return response()->json(['success' => true]);

            case 'delete_ucapan':
                Ucapan::destroy($request->query('id'));
                return response()->json(['success' => true]);

            case 'update_rsvp':
                $rsvp = Rsvp::find($request->input('id'));
                if ($rsvp) {
                    $rsvp->update([
                        'nama' => $request->input('nama'),
                        'jumlah_tamu' => $request->input('jumlah_tamu'),
                        'status' => $request->input('status'),
                        'alasan' => $request->input('alasan')
                    ]);
                }
                return response()->json(['success' => true]);

            case 'update_ucapan':
                $ucapan = Ucapan::find($request->input('id'));
                if ($ucapan) {
                    $ucapan->update([
                        'nama' => $request->input('nama'),
                        'pesan' => $request->input('pesan')
                    ]);
                }
                return response()->json(['success' => true]);

            case 'update_guest':
                $guest = Guest::find($request->input('id'));
                if ($guest) {
                    $guest->update([
                        'nama' => $request->input('nama'),
                        'no_hp' => $request->input('no_hp'),
                        'slug' => urlencode($request->input('nama'))
                    ]);
                }
                return response()->json(['success' => true]);

            case 'get_gallery':
                return response()->json([
                    'success' => true,
                    'data' => Gallery::where('invitation_id', $inv_id)->when(Schema::hasColumn('galleries', 'sort_order'), function ($q) { $q->orderBy('sort_order', 'ASC'); })->orderBy('created_at', 'ASC')->get()
                ]);

            case 'upload_gallery':
                if (!$request->hasFile('image')) {
                    return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
                }
                
                $galleryCount = Gallery::where('invitation_id', $inv_id)->count();
                if ($galleryCount >= 50) {
                    return response()->json(['success' => false, 'message' => 'Maksimal 50 foto per undangan. Hapus foto lama untuk menambahkan yang baru.'], 400);
                }
                
                $filename = $this->convertToWebp($request->file('image'), public_path('uploads'), 'img_');
                $galleryData = [
                    'invitation_id' => $inv_id,
                    'image_path' => 'uploads/' . $filename
                ];
                if (Schema::hasColumn('galleries', 'sort_order')) {
                    $maxSort = Gallery::where('invitation_id', $inv_id)->max('sort_order');
                    $galleryData['sort_order'] = $maxSort === null ? 0 : $maxSort + 1;
                }
                Gallery::create($galleryData);
                return response()->json(['success' => true, 'path' => 'uploads/' . $filename]);

            case 'delete_gallery':
                $id = $request->query('id');
                $gallery = Gallery::find($id);
                if ($gallery) {
                    $filePath = public_path($gallery->image_path);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    $gallery->delete();
                }
                return response()->json(['success' => true]);

            case 'reorder_gallery':
                if (!Schema::hasColumn('galleries', 'sort_order')) {
                    return response()->json(['success' => false, 'message' => 'Kolom urutan belum ada. Jalankan php artisan migrate terlebih dahulu.'], 400);
                }
                $ids = $request->input('ids', []);
                if (!is_array($ids)) {
                    return response()->json(['success' => false, 'message' => 'Format urutan tidak valid'], 400);
                }
                foreach (array_values($ids) as $i => $gid) {
                    Gallery::where('id', (int) $gid)
                        ->where('invitation_id', $inv_id)
                        ->update(['sort_order' => $i]);
                }
                return response()->json(['success' => true]);

            case 'get_music':
                return response()->json([
                    'success' => true,
                    'data' => Music::where('invitation_id', $inv_id)->orderBy('created_at', 'DESC')->get()
                ]);

            case 'upload_music':
                \Log::info('Upload music started for inv_id: ' . $inv_id);
                if (!$request->hasFile('music')) {
                    \Log::error('No file in request');
                    return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
                }
                $file = $request->file('music');
                \Log::info('File received: ' . $file->getClientOriginalName() . ' Type: ' . $file->getMimeType());

                $allowedTypes = ['audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/ogg', 'audio/m4a'];
                if (!in_array($file->getMimeType(), $allowedTypes) && !in_array($file->getClientMimeType(), $allowedTypes)) {
                    return response()->json(['success' => false, 'message' => 'Tipe file tidak didukung.'], 400);
                }
                
                $fileSize = $file->getSize();
                if ($fileSize > 10 * 1024 * 1024) {
                    return response()->json(['success' => false, 'message' => 'Ukuran file maksimal 10MB'], 400);
                }

                if (!file_exists(public_path('uploads'))) {
                    mkdir(public_path('uploads'), 0777, true);
                }

                $filename = uniqid('music_') . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $filename);

                Music::where('invitation_id', $inv_id)->update(['is_active' => 0]);
                
                $music = Music::create([
                    'invitation_id' => $inv_id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => 'uploads/' . $filename,
                    'file_size' => $fileSize,
                    'is_active' => 1
                ]);
                
                return response()->json([
                    'success' => true, 
                    'path' => 'uploads/' . $filename,
                    'data' => $music
                ]);

            case 'toggle_active_music':
                $id = $request->input('id') ?? $request->query('id');
                $music = Music::find($id);
                if (!$music) {
                    return response()->json(['success' => false, 'message' => 'Musik tidak ditemukan'], 404);
                }
                if ($music->is_active) {
                    $music->update(['is_active' => 0]);
                } else {
                    Music::where('invitation_id', $music->invitation_id)->update(['is_active' => 0]);
                    $music->update(['is_active' => 1]);
                }
                return response()->json(['success' => true]);

            case 'delete_music':
                $id = $request->input('id') ?? $request->query('id');
                $music = Music::find($id);
                if ($music) {
                    $filePath = public_path($music->file_path);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    $music->delete();
                }
                return response()->json(['success' => true]);

            case 'upload_prewedding_video':
                if (!$request->hasFile('video')) {
                    return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
                }
                $file = $request->file('video');
                $allowedTypes = ['video/mp4', 'video/webm', 'video/quicktime', 'video/x-msvideo', 'video/x-matroska'];
                if (!in_array($file->getMimeType(), $allowedTypes) && !in_array($file->getClientMimeType(), $allowedTypes)) {
                    return response()->json(['success' => false, 'message' => 'Tipe file tidak didukung. Gunakan MP4, WebM, atau MOV.'], 400);
                }
                $fileSize = $file->getSize();
                if ($fileSize > 100 * 1024 * 1024) {
                    return response()->json(['success' => false, 'message' => 'Ukuran file maksimal 100MB'], 400);
                }
                if (!file_exists(public_path('uploads'))) {
                    mkdir(public_path('uploads'), 0777, true);
                }
                $filename = uniqid('prewed_') . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $filename);
                return response()->json(['success' => true, 'path' => 'uploads/' . $filename, 'name' => $file->getClientOriginalName()]);

            case 'upload_bank_logo':
                if (!$request->hasFile('image')) {
                    return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
                }
                $file = $request->file('image');
                
                if ($file->getSize() > 2 * 1024 * 1024) {
                    return response()->json(['success' => false, 'message' => 'Ukuran file maksimal 2MB'], 400);
                }
                
                $filename = $this->convertToWebp($file, public_path('uploads'), 'bank_logo_');
                return response()->json(['success' => true, 'path' => 'uploads/' . $filename]);

            case 'upload_profile_photo':
                if (!$request->hasFile('image')) {
                    return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
                }
                $file = $request->file('image');
                if ($file->getSize() > 5 * 1024 * 1024) {
                    return response()->json(['success' => false, 'message' => 'Ukuran file maksimal 5MB'], 400);
                }
                $filename = $this->convertToWebp($file, public_path('uploads'), 'profile_');
                return response()->json(['success' => true, 'path' => 'uploads/' . $filename]);

            case 'upload_qris_image':
                if (!$request->hasFile('image')) {
                    return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
                }
                $file = $request->file('image');
                if ($file->getSize() > 5 * 1024 * 1024) {
                    return response()->json(['success' => false, 'message' => 'Ukuran file maksimal 5MB'], 400);
                }
                $filename = $this->convertToWebp($file, public_path('uploads'), 'qris_');
                return response()->json(['success' => true, 'path' => 'uploads/' . $filename]);

            case 'get_stories':
                return response()->json([
                    'success' => true,
                    'data' => Story::where('invitation_id', $inv_id)->orderBy('id', 'ASC')->get()
                ]);

            case 'add_story':
                $story = Story::create([
                    'invitation_id' => $inv_id,
                    'tahun' => $request->input('tahun'),
                    'icon' => $request->input('icon', 'favorite'),
                    'judul' => $request->input('judul'),
                    'isi' => $request->input('isi')
                ]);
                return response()->json(['success' => true, 'id' => $story->id]);

            case 'update_story':
                $story = Story::find($request->input('id'));
                if ($story) {
                    $story->update([
                        'tahun' => $request->input('tahun'),
                        'icon' => $request->input('icon', 'favorite'),
                        'judul' => $request->input('judul'),
                        'isi' => $request->input('isi')
                    ]);
                }
                return response()->json(['success' => true]);

            case 'delete_story':
                $id = $request->input('id') ?? $request->query('id');
                Story::destroy($id);
                return response()->json(['success' => true]);

            default:
                return response()->json(['success' => false, 'message' => 'Action not found'], 404);
        }
    }

    private function convertToWebp($file, $destinationPath, $prefix = 'img_')
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $path = $file->getRealPath();
        
        if (!function_exists('imagewebp') || !in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $filename = uniqid($prefix) . '.' . $extension;
            $file->move($destinationPath, $filename);
            return $filename;
        }

        if ($extension === 'webp') {
            $filename = uniqid($prefix) . '.webp';
            $file->move($destinationPath, $filename);
            return $filename;
        }

        $image = false;
        if (in_array($extension, ['jpg', 'jpeg'])) {
            $image = @imagecreatefromjpeg($path);
        } elseif ($extension === 'png') {
            $image = @imagecreatefrompng($path);
        } elseif ($extension === 'gif') {
            $image = @imagecreatefromgif($path);
        }

        if ($image !== false) {
            $maxSide = 1600;
            $width = imagesx($image);
            $height = imagesy($image);
            if ($width > $maxSide || $height > $maxSide) {
                $scale = $maxSide / max($width, $height);
                $resized = imagescale($image, (int) round($width * $scale), (int) round($height * $scale));
                if ($resized !== false) {
                    imagedestroy($image);
                    $image = $resized;
                }
            }
            if ($extension === 'png' || $extension === 'gif') {
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
            }
            $filename = uniqid($prefix) . '.webp';
            $destination = rtrim($destinationPath, '/') . '/' . $filename;
            $success = imagewebp($image, $destination, 80);
            imagedestroy($image);
            
            if ($success) {
                return $filename;
            }
        }

        $filename = uniqid($prefix) . '.' . $extension;
        $file->move($destinationPath, $filename);
        return $filename;
    }
}
