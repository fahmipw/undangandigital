<?php
session_start();
require_once 'db.php';

// Check auth
if (!isset($_SESSION['admin_logged_in'])) {
    jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
}

$action = $_GET['action'] ?? '';
$inv_id = $_GET['inv_id'] ?? 1; // Default to first invitation if not provided

try {
    $db = getDB();

    switch ($action) {
        case 'get_invitations':
            $stmt = $db->query("SELECT * FROM invitations ORDER BY created_at DESC");
            jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
            break;

        case 'add_invitation':
            $postData = json_decode(file_get_contents('php://input'), true);
            $slug = $postData['slug'];
            $title = $postData['title'];
            $stmt = $db->prepare("INSERT INTO invitations (slug, title) VALUES (?, ?)");
            $stmt->execute([$slug, $title]);
            jsonResponse(['success' => true, 'id' => $db->lastInsertId()]);
            break;

        case 'delete_invitation':
            $id = $_GET['id'];
            if ($id == 1) {
                jsonResponse(['success' => false, 'message' => 'Undangan utama tidak dapat dihapus'], 403);
                break;
            }
            $stmt = $db->prepare("DELETE FROM invitations WHERE id = ?");
            $stmt->execute([$id]);
            jsonResponse(['success' => true]);
            break;

        case 'get_settings':
            $stmt = $db->prepare("SELECT * FROM settings WHERE invitation_id = ?");
            $stmt->execute([$inv_id]);
            $settings = $stmt->fetchAll();
            $data = [];
            foreach ($settings as $s) {
                $data[$s['key_name']] = $s['key_value'];
            }
            jsonResponse(['success' => true, 'data' => $data]);
            break;

        case 'update_settings':
            $postData = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("INSERT INTO settings (invitation_id, key_name, key_value) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE key_value = VALUES(key_value)");
            foreach ($postData as $key => $value) {
                $stmt->execute([$inv_id, $key, $value]);
            }
            jsonResponse(['success' => true]);
            break;

        case 'get_guests':
            $stmt = $db->prepare("SELECT * FROM guests WHERE invitation_id = ? ORDER BY nama ASC");
            $stmt->execute([$inv_id]);
            jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
            break;

        case 'add_guest':
            $postData = json_decode(file_get_contents('php://input'), true);
            $nama = $postData['nama'];
            $no_hp = $postData['no_hp'] ?? null;
            $slug = urlencode($nama);
            $stmt = $db->prepare("INSERT INTO guests (invitation_id, nama, no_hp, slug) VALUES (?, ?, ?, ?)");
            $stmt->execute([$inv_id, $nama, $no_hp, $slug]);
            jsonResponse(['success' => true]);
            break;

        case 'bulk_add_guests':
            $postData = json_decode(file_get_contents('php://input'), true);
            $names = $postData['names'];
            $stmt = $db->prepare("INSERT INTO guests (invitation_id, nama, no_hp, slug) VALUES (?, ?, ?, ?)");
            foreach ($names as $line) {
                if (trim($line) === '') continue;
                
                // Check if line contains comma for phone number
                $parts = explode(',', $line);
                $nama = trim($parts[0]);
                $no_hp = isset($parts[1]) ? trim($parts[1]) : null;
                
                $slug = urlencode($nama);
                $stmt->execute([$inv_id, $nama, $no_hp, $slug]);
            }
            jsonResponse(['success' => true]);
            break;

        case 'delete_guest':
            $id = $_GET['id'];
            $stmt = $db->prepare("DELETE FROM guests WHERE id = ?");
            $stmt->execute([$id]);
            jsonResponse(['success' => true]);
            break;

        case 'get_rsvp':
            $stmt = $db->prepare("SELECT * FROM rsvp WHERE invitation_id = ? ORDER BY created_at DESC");
            $stmt->execute([$inv_id]);
            jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
            break;

        case 'get_ucapan':
            $stmt = $db->prepare("SELECT * FROM ucapan WHERE invitation_id = ? ORDER BY created_at DESC");
            $stmt->execute([$inv_id]);
            jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
            break;

        // ... delete cases similar to before but with inv_id check if needed ...
        case 'delete_rsvp':
            $id = $_GET['id'];
            $stmt = $db->prepare("DELETE FROM rsvp WHERE id = ?");
            $stmt->execute([$id]);
            jsonResponse(['success' => true]);
            break;

        case 'update_rsvp':
            $postData = json_decode(file_get_contents('php://input'), true);
            $id = $postData['id'];
            $nama = $postData['nama'];
            $jumlah = $postData['jumlah_tamu'];
            $status = $postData['status'];
            $alasan = $postData['alasan'];
            $stmt = $db->prepare("UPDATE rsvp SET nama = ?, jumlah_tamu = ?, status = ?, alasan = ? WHERE id = ?");
            $stmt->execute([$nama, $jumlah, $status, $alasan, $id]);
            jsonResponse(['success' => true]);
            break;

        case 'update_ucapan':
            $postData = json_decode(file_get_contents('php://input'), true);
            $id = $postData['id'];
            $nama = $postData['nama'];
            $pesan = $postData['pesan'];
            $stmt = $db->prepare("UPDATE ucapan SET nama = ?, pesan = ? WHERE id = ?");
            $stmt->execute([$nama, $pesan, $id]);
            jsonResponse(['success' => true]);
            break;

        case 'update_guest':
            $postData = json_decode(file_get_contents('php://input'), true);
            $id = $postData['id'];
            $nama = $postData['nama'];
            $no_hp = $postData['no_hp'] ?? null;
            $slug = urlencode($nama);
            $stmt = $db->prepare("UPDATE guests SET nama = ?, no_hp = ?, slug = ? WHERE id = ?");
            $stmt->execute([$nama, $no_hp, $slug, $id]);
            jsonResponse(['success' => true]);
            break;

        case 'get_gallery':
            // Changed to ASC so newest photos appear at the bottom
            $stmt = $db->prepare("SELECT * FROM gallery WHERE invitation_id = ? ORDER BY created_at ASC");
            $stmt->execute([$inv_id]);
            jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
            break;

        case 'upload_gallery':
            if (!isset($_FILES['image'])) {
                jsonResponse(['success' => false, 'message' => 'No file uploaded'], 400);
            }
            $file = $_FILES['image'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('img_') . '.' . $ext;
            $uploadPath = '../uploads/' . $filename;

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $stmt = $db->prepare("INSERT INTO gallery (invitation_id, image_path) VALUES (?, ?)");
                $stmt->execute([$inv_id, 'uploads/' . $filename]);
                jsonResponse(['success' => true, 'path' => 'uploads/' . $filename]);
            } else {
                jsonResponse(['success' => false, 'message' => 'Failed to move uploaded file'], 500);
            }
            break;

        case 'delete_gallery':
            $id = $_GET['id'];
            // Get path first to delete file
            $stmt = $db->prepare("SELECT image_path FROM gallery WHERE id = ?");
            $stmt->execute([$id]);
            $img = $stmt->fetch();
            if ($img) {
                $filePath = '../' . $img['image_path'];
                if (file_exists($filePath)) unlink($filePath);
            }
            $stmt = $db->prepare("DELETE FROM gallery WHERE id = ?");
            $stmt->execute([$id]);
            jsonResponse(['success' => true]);
            break;

        // Music Management
        case 'get_music':
            $stmt = $db->prepare("SELECT * FROM music WHERE invitation_id = ? ORDER BY created_at DESC");
            $stmt->execute([$inv_id]);
            jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
            break;

        case 'upload_music':
            if (!isset($_FILES['music'])) {
                jsonResponse(['success' => false, 'message' => 'No file uploaded'], 400);
            }
            
            $file = $_FILES['music'];
            
            // Validate file type
            $allowedTypes = ['audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/ogg', 'audio/m4a'];
            if (!in_array($file['type'], $allowedTypes)) {
                jsonResponse(['success' => false, 'message' => 'Tipe file tidak didukung. Gunakan MP3, WAV, OGG, atau M4A'], 400);
            }
            
            // Validate file size (max 10MB)
            if ($file['size'] > 10 * 1024 * 1024) {
                jsonResponse(['success' => false, 'message' => 'Ukuran file maksimal 10MB'], 400);
            }
            
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('music_') . '.' . $ext;
            $uploadPath = '../uploads/' . $filename;
            
            if (!is_dir('../uploads')) {
                mkdir('../uploads', 0755, true);
            }

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                // Deactivate other music files for this invitation
                $stmt = $db->prepare("UPDATE music SET is_active = 0 WHERE invitation_id = ?");
                $stmt->execute([$inv_id]);
                
                // Insert new music file as active
                $stmt = $db->prepare("INSERT INTO music (invitation_id, file_name, file_path, file_size, is_active) VALUES (?, ?, ?, ?, 1)");
                $stmt->execute([$inv_id, $file['name'], 'uploads/' . $filename, $file['size']]);
                
                jsonResponse(['success' => true, 'path' => 'uploads/' . $filename]);
            } else {
                jsonResponse(['success' => false, 'message' => 'Gagal mengupload file'], 500);
            }
            break;

        case 'toggle_active_music':
            $id = $_GET['id'] ?? $_POST['id'] ?? null;
            if (!$id) {
                jsonResponse(['success' => false, 'message' => 'ID musik tidak ditemukan'], 400);
            }
            
            // Get current music info
            $stmt = $db->prepare("SELECT * FROM music WHERE id = ?");
            $stmt->execute([$id]);
            $music = $stmt->fetch();
            
            if (!$music) {
                jsonResponse(['success' => false, 'message' => 'Musik tidak ditemukan'], 404);
            }
            
            if ($music['is_active']) {
                // Deactivate this music
                $stmt = $db->prepare("UPDATE music SET is_active = 0 WHERE id = ?");
                $stmt->execute([$id]);
            } else {
                // Deactivate all music for this invitation first
                $stmt = $db->prepare("UPDATE music SET is_active = 0 WHERE invitation_id = ?");
                $stmt->execute([$music['invitation_id']]);
                
                // Activate this music
                $stmt = $db->prepare("UPDATE music SET is_active = 1 WHERE id = ?");
                $stmt->execute([$id]);
            }
            
            jsonResponse(['success' => true]);
            break;

        case 'delete_music':
            $id = $_GET['id'] ?? $_POST['id'] ?? null;
            if (!$id) {
                jsonResponse(['success' => false, 'message' => 'ID musik tidak ditemukan'], 400);
            }
            
            // Get path first to delete file
            $stmt = $db->prepare("SELECT file_path FROM music WHERE id = ?");
            $stmt->execute([$id]);
            $music = $stmt->fetch();
            
            if ($music) {
                $filePath = '../' . $music['file_path'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            $stmt = $db->prepare("DELETE FROM music WHERE id = ?");
            $stmt->execute([$id]);
            jsonResponse(['success' => true]);
            break;

        // Bank Logo Upload
        case 'upload_bank_logo':
            if (!isset($_FILES['image'])) {
                jsonResponse(['success' => false, 'message' => 'No file uploaded'], 400);
            }
            
            $file = $_FILES['image'];
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp','image/svg+xml' ];
            if (!in_array($file['type'], $allowedTypes)) {
                jsonResponse(['success' => false, 'message' => 'Tipe file tidak didukung. Gunakan JPG, PNG, GIF, atau WebP'], 400);
            }
            
            // Validate file size (max 2MB)
            if ($file['size'] > 2 * 1024 * 1024) {
                jsonResponse(['success' => false, 'message' => 'Ukuran file maksimal 2MB'], 400);
            }
            
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('bank_logo_') . '.' . $ext;
            $uploadPath = '../uploads/' . $filename;
            
            if (!is_dir('../uploads')) {
                mkdir('../uploads', 0755, true);
            }

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                jsonResponse(['success' => true, 'path' => 'uploads/' . $filename]);
            } else {
                jsonResponse(['success' => false, 'message' => 'Gagal mengupload file'], 500);
            }
            break;

        // Upload Profile Photo (saves to settings, not to gallery)
        case 'upload_profile_photo':
            if (!isset($_FILES['image'])) {
                jsonResponse(['success' => false, 'message' => 'No file uploaded'], 400);
            }
            
            $file = $_FILES['image'];
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file['type'], $allowedTypes)) {
                jsonResponse(['success' => false, 'message' => 'Tipe file tidak didukung. Gunakan JPG, PNG, GIF, atau WebP'], 400);
            }
            
            // Validate file size (max 5MB)
            if ($file['size'] > 5 * 1024 * 1024) {
                jsonResponse(['success' => false, 'message' => 'Ukuran file maksimal 5MB'], 400);
            }
            
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('profile_') . '.' . $ext;
            $uploadPath = '../uploads/' . $filename;
            
            if (!is_dir('../uploads')) {
                mkdir('../uploads', 0755, true);
            }

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                // Just return the path, DON'T save to gallery
                jsonResponse(['success' => true, 'path' => 'uploads/' . $filename]);
            } else {
                jsonResponse(['success' => false, 'message' => 'Gagal mengupload file'], 500);
            }
            break;

        // Stories Management (Kisah Kasih)
        case 'get_stories':
            $stmt = $db->prepare("SELECT * FROM stories WHERE invitation_id = ? ORDER BY id ASC");
            $stmt->execute([$inv_id]);
            jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
            break;

        case 'add_story':
            $postData = json_decode(file_get_contents('php://input'), true);
            $tahun = $postData['tahun'];
            $judul = $postData['judul'];
            $isi = $postData['isi'];
            $stmt = $db->prepare("INSERT INTO stories (invitation_id, tahun, judul, isi) VALUES (?, ?, ?, ?)");
            $stmt->execute([$inv_id, $tahun, $judul, $isi]);
            jsonResponse(['success' => true, 'id' => $db->lastInsertId()]);
            break;

        case 'update_story':
            $postData = json_decode(file_get_contents('php://input'), true);
            $id = $postData['id'];
            $tahun = $postData['tahun'];
            $judul = $postData['judul'];
            $isi = $postData['isi'];
            $stmt = $db->prepare("UPDATE stories SET tahun = ?, judul = ?, isi = ? WHERE id = ?");
            $stmt->execute([$tahun, $judul, $isi, $id]);
            jsonResponse(['success' => true]);
            break;

        case 'delete_story':
            $id = $_GET['id'] ?? $_POST['id'] ?? null;
            $stmt = $db->prepare("DELETE FROM stories WHERE id = ?");
            $stmt->execute([$id]);
            jsonResponse(['success' => true]);
            break;

        default:
            jsonResponse(['success' => false, 'message' => 'Action not found'], 404);
    }
} catch (PDOException $e) {
    jsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
}
